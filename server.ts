import express from 'express';
import dotenv from 'dotenv';
import { GoogleGenAI } from '@google/genai';

dotenv.config();

const app = express();
app.use(express.json());

const DEFAULT_SYSTEM_PROMPT =
  'Eres un asistente experto en programación en Lenguaje Ensamblador x86. Respondes de forma clara, técnica y precisa, usando ejemplos en NASM o MASM cuando sea útil. Ayudas a resolver errores, optimizar rutinas y explicar conceptos complejos de bajo nivel de forma sencilla.';

function getLlmConfig() {
  return {
    provider: (process.env.LLM_PROVIDER || 'gemini').toLowerCase(),
    systemPrompt: process.env.LLM_SYSTEM_PROMPT || DEFAULT_SYSTEM_PROMPT,
    timeout: parseInt(process.env.LLM_TIMEOUT || '30', 10) * 1000,
    maxMessageLength: parseInt(process.env.LLM_MAX_MESSAGE_LENGTH || '8000', 10),
    temperature: parseFloat(process.env.LLM_TEMPERATURE || '0.2'),
    maxTokens: parseInt(process.env.LLM_MAX_TOKENS || '2048', 10),
  };
}

interface ChatRequestBody {
  message?: string;
  provider?: string;
  apiKey?: string;
  customModel?: string;
  customBaseUrl?: string;
}

async function generateWithGemini(
  message: string,
  systemPrompt: string,
  config: ReturnType<typeof getLlmConfig>,
  overrideApiKey?: string,
  overrideModel?: string,
  overrideBaseUrl?: string
): Promise<string> {
  const apiKey = overrideApiKey?.trim() || process.env.GEMINI_API_KEY;
  const customBaseUrl = overrideBaseUrl?.trim() || process.env.GEMINI_API_BASE;
  const model = overrideModel?.trim() || process.env.GEMINI_MODEL || 'gemini-3.6-flash';

  if (!apiKey && !customBaseUrl) {
    throw new Error('No se proporcionó API Key para Gemini. Por favor ingrésala en el menú superior.');
  }

  if (customBaseUrl) {
    const url = `${customBaseUrl.replace(/\/$/, '')}/models/${encodeURIComponent(model)}:generateContent?key=${encodeURIComponent(apiKey || '')}`;
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), config.timeout);

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        signal: controller.signal,
        body: JSON.stringify({
          system_instruction: { parts: [{ text: systemPrompt }] },
          contents: [{ role: 'user', parts: [{ text: message }] }],
          generationConfig: {
            temperature: config.temperature,
            maxOutputTokens: config.maxTokens,
          },
        }),
      });
      clearTimeout(timer);

      if (!res.ok) {
        const errText = await res.text();
        throw new Error(`Error ${res.status} de Gemini API: ${errText}`);
      }
      const data = (await res.json()) as any;
      const text = data?.candidates?.[0]?.content?.parts?.[0]?.text;
      if (typeof text !== 'string' || !text.trim()) {
        throw new Error('Gemini no devolvió texto en su respuesta.');
      }
      return text;
    } catch (err) {
      clearTimeout(timer);
      throw err;
    }
  }

  const ai = new GoogleGenAI({ apiKey: apiKey! });
  const response = await ai.models.generateContent({
    model,
    contents: message,
    config: {
      systemInstruction: systemPrompt,
      temperature: config.temperature,
      maxOutputTokens: config.maxTokens,
    },
  });

  let text = response.text;
  if (typeof text !== 'string' || !text.trim()) {
    const candidate = response.candidates?.[0];
    if (candidate?.content?.parts) {
      text = candidate.content.parts.map((p: any) => p.text || '').join('');
    }
    if ((!text || !text.trim()) && candidate?.finishReason) {
      throw new Error(`Gemini finalizó con motivo: ${candidate.finishReason}`);
    }
  }

  if (typeof text !== 'string' || !text.trim()) {
    throw new Error('Gemini no devolvió texto en su respuesta. Verifica que la API Key sea válida.');
  }
  return text;
}

async function generateWithOpenAi(
  message: string,
  systemPrompt: string,
  config: ReturnType<typeof getLlmConfig>,
  providerName: string = 'OpenAI',
  baseUrlEnv: string = 'OPENAI_API_BASE',
  apiKeyEnv: string = 'OPENAI_API_KEY',
  modelEnv: string = 'OPENAI_MODEL',
  defaultBaseUrl: string = 'https://api.openai.com/v1',
  defaultModel: string = 'gpt-4o-mini',
  overrideApiKey?: string,
  overrideModel?: string,
  overrideBaseUrl?: string
): Promise<string> {
  const apiKey = overrideApiKey?.trim() || process.env[apiKeyEnv];
  const model = overrideModel?.trim() || process.env[modelEnv] || defaultModel;
  const baseUrl = (overrideBaseUrl?.trim() || process.env[baseUrlEnv] || defaultBaseUrl).replace(/\/$/, '');

  if (!apiKey && providerName !== 'OpenAI Compatible') {
    throw new Error(`${apiKeyEnv} no está configurada.`);
  }

  const url = `${baseUrl}/chat/completions`;
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), config.timeout);

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        ...(apiKey ? { Authorization: `Bearer ${apiKey}` } : {}),
      },
      signal: controller.signal,
      body: JSON.stringify({
        model,
        messages: [
          { role: 'system', content: systemPrompt },
          { role: 'user', content: message },
        ],
        temperature: config.temperature,
        max_tokens: config.maxTokens,
      }),
    });
    clearTimeout(timer);

    if (!res.ok) {
      const errText = await res.text();
      throw new Error(`${providerName} API error ${res.status}: ${errText}`);
    }

    const data = (await res.json()) as any;
    const text = data?.choices?.[0]?.message?.content;
    if (typeof text !== 'string' || !text.trim()) {
      throw new Error(`${providerName} no devolvió texto utilizable.`);
    }
    return text;
  } catch (err) {
    clearTimeout(timer);
    throw err;
  }
}

async function generateWithAnthropic(
  message: string,
  systemPrompt: string,
  config: ReturnType<typeof getLlmConfig>,
  overrideApiKey?: string,
  overrideModel?: string,
  overrideBaseUrl?: string
): Promise<string> {
  const apiKey = overrideApiKey?.trim() || process.env.ANTHROPIC_API_KEY;
  const model = overrideModel?.trim() || process.env.ANTHROPIC_MODEL || 'claude-3-5-haiku-latest';
  const baseUrl = (overrideBaseUrl?.trim() || process.env.ANTHROPIC_API_BASE || 'https://api.anthropic.com/v1').replace(/\/$/, '');
  const version = process.env.ANTHROPIC_VERSION || '2023-06-01';

  if (!apiKey) {
    throw new Error('ANTHROPIC_API_KEY no está configurada.');
  }

  const url = `${baseUrl}/messages`;
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), config.timeout);

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'x-api-key': apiKey,
        'anthropic-version': version,
      },
      signal: controller.signal,
      body: JSON.stringify({
        model,
        system: systemPrompt,
        messages: [{ role: 'user', content: message }],
        temperature: config.temperature,
        max_tokens: config.maxTokens,
      }),
    });
    clearTimeout(timer);

    if (!res.ok) {
      const errText = await res.text();
      throw new Error(`Anthropic API error ${res.status}: ${errText}`);
    }

    const data = (await res.json()) as any;
    const text = data?.content?.[0]?.text;
    if (typeof text !== 'string' || !text.trim()) {
      throw new Error('Anthropic no devolvió texto utilizable.');
    }
    return text;
  } catch (err) {
    clearTimeout(timer);
    throw err;
  }
}

async function handleChat(req: express.Request, res: express.Response) {
  const { message, provider: clientProvider, apiKey: clientApiKey, customModel, customBaseUrl }: ChatRequestBody = req.body || {};
  if (typeof message !== 'string' || !message.trim()) {
    return res.status(422).json({ error: 'No se recibió ningún mensaje.' });
  }

  const config = getLlmConfig();
  const selectedProvider = (clientProvider?.trim() || config.provider).toLowerCase();

  if (message.length > config.maxMessageLength) {
    return res.status(422).json({ error: 'El mensaje supera el límite configurado.' });
  }

  try {
    let reply = '';
    switch (selectedProvider) {
      case 'gemini':
        reply = await generateWithGemini(message, config.systemPrompt, config, clientApiKey, customModel, customBaseUrl);
        break;
      case 'openai':
        reply = await generateWithOpenAi(
          message,
          config.systemPrompt,
          config,
          'OpenAI',
          'OPENAI_API_BASE',
          'OPENAI_API_KEY',
          'OPENAI_MODEL',
          'https://api.openai.com/v1',
          'gpt-4o-mini',
          clientApiKey,
          customModel,
          customBaseUrl
        );
        break;
      case 'anthropic':
        reply = await generateWithAnthropic(message, config.systemPrompt, config, clientApiKey, customModel, customBaseUrl);
        break;
      case 'mistral':
        reply = await generateWithOpenAi(
          message,
          config.systemPrompt,
          config,
          'Mistral',
          'MISTRAL_API_BASE',
          'MISTRAL_API_KEY',
          'MISTRAL_MODEL',
          'https://api.mistral.ai/v1',
          'codestral-latest',
          clientApiKey,
          customModel,
          customBaseUrl
        );
        break;
      case 'openai_compatible':
        reply = await generateWithOpenAi(
          message,
          config.systemPrompt,
          config,
          'OpenAI Compatible',
          'OPENAI_COMPATIBLE_API_BASE',
          'OPENAI_COMPATIBLE_API_KEY',
          'OPENAI_COMPATIBLE_MODEL',
          '',
          '',
          clientApiKey,
          customModel,
          customBaseUrl
        );
        break;
      default:
        return res.status(400).json({ error: `Proveedor LLM no soportado: ${selectedProvider}` });
    }

    return res.json({ reply, provider: selectedProvider });
  } catch (err: any) {
    console.error('[Assembler-AI Error]:', err?.message || err);
    return res.status(502).json({
      error: err?.message || 'No se pudo generar la respuesta del proveedor LLM.',
      details: err?.message,
    });
  }
}

app.post('/api_handler.php', handleChat);
app.post('/api/chat', handleChat);

app.use(express.static('.'));

const PORT = 3000;
const HOST = '0.0.0.0';

app.listen(PORT, HOST, () => {
  console.log(`[Assembler-AI] Dev server running on http://${HOST}:${PORT}`);
});
