# Değişiklik Geçmişi

Bu dosya projenin tüm önemli değişikliklerini belgelemektedir.

Format [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standardına dayanmaktadır.
Bu proje [Semantic Versioning](https://semver.org/spec/v2.0.0.html) kullanmaktadır.

## [Unreleased]

### Eklenen
- İlk sürüm hazırlıkları

## [1.0.0] - 2024-01-15

### Eklenen
- 🚀 İlk stabil sürüm
- ✨ Chat completion API desteği
- 🌊 Streaming chat completion
- 📋 Model listeleme
- 🔧 Konfigürasyon seçenekleri
- 📡 Event system (request, response, error)
- 🛡️ Hata yönetimi
- 📚 TypeScript tip desteği
- 🧪 Jest ile test suite
- 📖 Kapsamlı dokümantasyon
- 💡 Kullanım örnekleri

### Teknik Özellikler
- ⚡ Axios tabanlı HTTP client
- 🎭 EventEmitter3 ile olay yönetimi
- 📦 ESM ve CommonJS desteği
- 🔍 TypeScript declarations
- 📊 Token kullanım takibi
- ⏱️ Configurable timeout
- 🔐 Bearer token authentication

### Desteklenen Endpointler
- `/v1/chat/completions` - Chat completion (sync/stream)
- `/v1/models` - Model listeleme

### Örnek Kullanım
```javascript
import BlackboxProvider from 'blackbox-ai-provider';

const blackbox = new BlackboxProvider({
  apiKey: 'your-api-key'
});

const response = await blackbox.chat({
  model: 'blackboxai',
  messages: [{ role: 'user', content: 'Merhaba!' }]
});
```

## [0.1.0] - 2024-01-10

### Eklenen
- 🎯 Temel proje yapısı
- 📄 Package.json konfigürasyonu
- 🔧 TypeScript setup
- 🧰 Build tools (tsup)
- 📋 ESLint konfigürasyonu

---

### Versiyon Notları

#### Gelecek Planları (v1.1.0)
- [ ] Resim analizi desteği
- [ ] Fine-tuning API
- [ ] Rate limiting
- [ ] Caching mekanizması
- [ ] Plugin sistemi

#### Bilinen Sorunlar
- Streaming'de çok büyük yanıtlar için memory optimizasyonu gerekebilir
- Node.js 14 ve altı sürümler test edilmemiş

#### Güvenlik
- API anahtarları environment variables olarak saklanmalı
- HTTPS zorunlu
- Request/response logging production'da devre dışı bırakılmalı
