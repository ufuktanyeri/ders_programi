# Blackbox.ai Provider

Modern JavaScript/TypeScript ile yazılmış Blackbox.ai API client.

## Kurulum

```bash
npm install blackbox-ai-provider
# veya
yarn add blackbox-ai-provider
```

## Kullanım

```javascript
import BlackboxProvider from 'blackbox-ai-provider';

const blackbox = new BlackboxProvider({
  apiKey: 'your-api-key-here'
});

// Chat tamamlama
const response = await blackbox.chat({
  model: 'blackboxai',
  messages: [
    { role: 'user', content: 'Merhaba, nasılsın?' }
  ]
});

console.log(response.choices[0].message.content);
```

## API Referansı

### Constructor

```javascript
const blackbox = new BlackboxProvider(options)
```

- `options.apiKey` - API anahtarınız
- `options.baseUrl` - Temel URL (varsayılan: 'https://api.blackbox.ai')
- `options.timeout` - İstek zaman aşımı (varsayılan: 30000ms)

### Metodlar

#### chat(options)

Chat tamamlama API'sini kullanır.

```javascript
await blackbox.chat({
  model: 'blackboxai',
  messages: [
    { role: 'system', content: 'Sen yardımcı bir asistansın' },
    { role: 'user', content: 'Python ile merhaba dünya nasıl yazılır?' }
  ],
  max_tokens: 100,
  temperature: 0.7
});
```

#### stream(options)

Akış modunda chat tamamlama.

```javascript
const stream = await blackbox.stream({
  model: 'blackboxai',
  messages: [{ role: 'user', content: 'Uzun bir hikaye yaz' }]
});

for await (const chunk of stream) {
  if (chunk.choices[0]?.delta?.content) {
    process.stdout.write(chunk.choices[0].delta.content);
  }
}
```

## Örnekler

Daha fazla örnek için `examples/` klasörüne bakın.

## Lisans

MIT
