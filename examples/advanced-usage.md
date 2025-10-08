# Blackbox.ai Provider için Gelişmiş Örnekler

## 1. Özel Konfigürasyon

```javascript
import BlackboxProvider from 'blackbox-ai-provider';

const blackbox = new BlackboxProvider({
  apiKey: process.env.BLACKBOX_API_KEY,
  baseUrl: 'https://custom-proxy.example.com',
  timeout: 60000 // 60 saniye
});

// Event dinleyiciler
blackbox.on('request', (config) => {
  console.log(`📤 ${config.method?.toUpperCase()} ${config.url}`);
});

blackbox.on('response', (response) => {
  console.log(`📥 ${response.status} ${response.statusText}`);
});

blackbox.on('error', (error) => {
  console.error('💥 API Hatası:', error.message);
});
```

## 2. Gelişmiş Sohbet Örnekleri

```javascript
// Sistem mesajı ile context belirleme
async function contextualChat() {
  const response = await blackbox.chat({
    model: 'blackboxai',
    messages: [
      {
        role: 'system',
        content: 'Sen bir Python uzmanısın. Sadece Python ile ilgili sorulara cevap veriyorsun.'
      },
      {
        role: 'user',
        content: 'FastAPI ile bir REST API nasıl yapabilirim?'
      }
    ],
    temperature: 0.1, // Daha tutarlı cevaplar
    max_tokens: 800
  });

  return response.choices[0].message.content;
}

// Sohbet geçmişi ile devam eden konuşma
async function continuousChat() {
  let conversation = [
    { role: 'system', content: 'Sen yardımcı bir kodlama asistanısın.' }
  ];

  // İlk soru
  conversation.push({ role: 'user', content: 'JavaScript ile array nasıl sıralanır?' });
  
  let response = await blackbox.chat({
    model: 'blackboxai',
    messages: conversation
  });

  conversation.push(response.choices[0].message);
  console.log('Asistan:', response.choices[0].message.content);

  // Takip sorusu
  conversation.push({ role: 'user', content: 'Peki büyükten küçüğe nasıl sıralarım?' });
  
  response = await blackbox.chat({
    model: 'blackboxai',
    messages: conversation
  });

  console.log('Asistan:', response.choices[0].message.content);
}
```

## 3. Streaming ile Real-time UI

```javascript
// React bileşeni örneği
import React, { useState } from 'react';
import BlackboxProvider from 'blackbox-ai-provider';

function ChatComponent() {
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(false);
  
  const blackbox = new BlackboxProvider({
    apiKey: process.env.REACT_APP_BLACKBOX_API_KEY
  });

  const sendMessage = async (userMessage) => {
    setLoading(true);
    setMessages(prev => [...prev, { role: 'user', content: userMessage }]);

    try {
      const stream = await blackbox.stream({
        model: 'blackboxai',
        messages: [...messages, { role: 'user', content: userMessage }]
      });

      let assistantMessage = '';
      
      for await (const chunk of stream) {
        if (chunk.choices[0]?.delta?.content) {
          assistantMessage += chunk.choices[0].delta.content;
          
          // UI'ı gerçek zamanlı güncelle
          setMessages(prev => {
            const newMessages = [...prev];
            const lastMessage = newMessages[newMessages.length - 1];
            
            if (lastMessage?.role === 'assistant') {
              lastMessage.content = assistantMessage;
            } else {
              newMessages.push({ role: 'assistant', content: assistantMessage });
            }
            
            return newMessages;
          });
        }
      }
    } catch (error) {
      console.error('Chat error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="chat-container">
      {/* Chat UI komponenti */}
    </div>
  );
}
```

## 4. Batch İşleme

```javascript
// Çoklu prompt'ları toplu işleme
async function batchProcess(prompts) {
  const results = [];
  
  for (const [index, prompt] of prompts.entries()) {
    try {
      console.log(`İşleniyor ${index + 1}/${prompts.length}: ${prompt.slice(0, 50)}...`);
      
      const response = await blackbox.chat({
        model: 'blackboxai',
        messages: [{ role: 'user', content: prompt }],
        temperature: 0.3
      });

      results.push({
        prompt,
        response: response.choices[0].message.content,
        tokens: response.usage.total_tokens
      });

      // API rate limiting için bekleme
      await new Promise(resolve => setTimeout(resolve, 1000));
      
    } catch (error) {
      results.push({
        prompt,
        error: error.message
      });
    }
  }

  return results;
}

// Kullanım
const prompts = [
  'Python ile fibonacci dizisi nasıl hesaplanır?',
  'JavaScript async/await nasıl kullanılır?',
  'SQL JOIN türleri nelerdir?'
];

const results = await batchProcess(prompts);
console.log('Toplu işlem tamamlandı:', results);
```

## 5. Hata Yönetimi ve Retry Logic

```javascript
class RobustBlackboxProvider {
  constructor(options) {
    this.provider = new BlackboxProvider(options);
    this.maxRetries = 3;
    this.retryDelay = 1000;
  }

  async chatWithRetry(request, retries = 0) {
    try {
      return await this.provider.chat(request);
    } catch (error) {
      if (retries < this.maxRetries && this.shouldRetry(error)) {
        console.log(`Yeniden deneniyor... (${retries + 1}/${this.maxRetries})`);
        
        await this.delay(this.retryDelay * Math.pow(2, retries));
        return this.chatWithRetry(request, retries + 1);
      }
      
      throw error;
    }
  }

  shouldRetry(error) {
    // Ağ hataları veya geçici server hataları için retry
    return error.code === 'ECONNRESET' || 
           error.code === 'ETIMEDOUT' ||
           (error.response?.status >= 500);
  }

  delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
}

// Kullanım
const robustProvider = new RobustBlackboxProvider({
  apiKey: process.env.BLACKBOX_API_KEY
});

try {
  const response = await robustProvider.chatWithRetry({
    model: 'blackboxai',
    messages: [{ role: 'user', content: 'Merhaba!' }]
  });
  
  console.log(response.choices[0].message.content);
} catch (error) {
  console.error('Tüm yeniden denemeler başarısız:', error.message);
}
```

## 6. Token ve Maliyet Takibi

```javascript
class CostTracker {
  constructor() {
    this.totalTokens = 0;
    this.requestCount = 0;
    this.costs = {
      'blackboxai': { input: 0.001, output: 0.002 } // Token başına fiyat
    };
  }

  trackUsage(response, model = 'blackboxai') {
    const usage = response.usage;
    this.totalTokens += usage.total_tokens;
    this.requestCount += 1;

    const cost = (usage.prompt_tokens * this.costs[model].input) + 
                 (usage.completion_tokens * this.costs[model].output);

    console.log(`📊 Token kullanımı: ${usage.total_tokens} (Maliyet: $${cost.toFixed(4)})`);
    
    return {
      tokens: usage.total_tokens,
      cost: cost
    };
  }

  getStats() {
    return {
      totalTokens: this.totalTokens,
      requestCount: this.requestCount,
      averageTokensPerRequest: Math.round(this.totalTokens / this.requestCount)
    };
  }
}

// Kullanım
const tracker = new CostTracker();
const blackbox = new BlackboxProvider({ apiKey: process.env.BLACKBOX_API_KEY });

const response = await blackbox.chat({
  model: 'blackboxai',
  messages: [{ role: 'user', content: 'Uzun bir makale yaz' }]
});

tracker.trackUsage(response);
console.log('İstatistikler:', tracker.getStats());
```
