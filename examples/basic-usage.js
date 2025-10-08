import BlackboxProvider from '../src/index.js';

// Temel kullanım örneği
async function basicExample() {
  const blackbox = new BlackboxProvider({
    apiKey: process.env.BLACKBOX_API_KEY || 'your-api-key-here'
  });

  try {
    console.log('🤖 Blackbox.ai ile sohbet başlatılıyor...\n');

    const response = await blackbox.chat({
      model: 'blackboxai',
      messages: [
        { role: 'system', content: 'Sen yardımcı bir programlama asistanısın.' },
        { role: 'user', content: 'JavaScript ile basit bir to-do list uygulaması nasıl yapabilirim?' }
      ],
      max_tokens: 500,
      temperature: 0.7
    });

    console.log('📝 Yanıt:');
    console.log(response.choices[0].message.content);
    console.log('\n📊 Token kullanımı:', response.usage);
    
  } catch (error) {
    console.error('❌ Hata:', error.message);
  }
}

// Streaming örneği
async function streamingExample() {
  const blackbox = new BlackboxProvider({
    apiKey: process.env.BLACKBOX_API_KEY || 'your-api-key-here'
  });

  try {
    console.log('🎬 Streaming ile hikaye yazılıyor...\n');

    const stream = await blackbox.stream({
      model: 'blackboxai',
      messages: [
        { role: 'user', content: 'Bana kısa bir bilim kurgu hikayesi yaz.' }
      ]
    });

    console.log('📖 Hikaye:\n');
    
    for await (const chunk of stream) {
      if (chunk.choices[0]?.delta?.content) {
        process.stdout.write(chunk.choices[0].delta.content);
      }
    }
    
    console.log('\n\n✅ Hikaye tamamlandı!');
    
  } catch (error) {
    console.error('❌ Hata:', error.message);
  }
}

// Model listesi örneği
async function listModelsExample() {
  const blackbox = new BlackboxProvider({
    apiKey: process.env.BLACKBOX_API_KEY || 'your-api-key-here'
  });

  try {
    console.log('📋 Mevcut modeller getiriliyor...\n');

    const models = await blackbox.listModels();
    
    console.log('🤖 Mevcut modeller:');
    models.data.forEach(model => {
      console.log(`- ${model.id} (${model.owned_by})`);
    });
    
  } catch (error) {
    console.error('❌ Hata:', error.message);
  }
}

// Event listener örneği
function eventListenerExample() {
  const blackbox = new BlackboxProvider({
    apiKey: process.env.BLACKBOX_API_KEY || 'your-api-key-here'
  });

  // İstek gönderildiğinde
  blackbox.on('request', (config) => {
    console.log('📤 İstek gönderiliyor:', config.url);
  });

  // Yanıt alındığında
  blackbox.on('response', (response) => {
    console.log('📥 Yanıt alındı:', response.status);
  });

  // Hata oluştuğunda
  blackbox.on('error', (error) => {
    console.error('💥 Event hatası:', error.message);
  });

  return blackbox;
}

// Örnek çalıştırma
async function runExamples() {
  console.log('🚀 Blackbox.ai Provider Örnekleri\n');
  console.log('=' .repeat(50));
  
  // Temel örnek
  console.log('\n1️⃣ Temel Kullanım:');
  await basicExample();
  
  // Event listener örneği
  console.log('\n2️⃣ Event Listeners:');
  const eventProvider = eventListenerExample();
  
  // Model listesi (sadece API anahtarı geçerliyse)
  console.log('\n3️⃣ Model Listesi:');
  try {
    await listModelsExample();
  } catch (error) {
    console.log('ℹ️ Model listesi için geçerli API anahtarı gerekli');
  }
  
  // Streaming örneği (sadece API anahtarı geçerliyse)
  console.log('\n4️⃣ Streaming:');
  try {
    await streamingExample();
  } catch (error) {
    console.log('ℹ️ Streaming için geçerli API anahtarı gerekli');
  }
}

// Eğer bu dosya doğrudan çalıştırılıyorsa örnekleri çalıştır
if (import.meta.url === `file://${process.argv[1]}`) {
  runExamples().catch(console.error);
}

export {
  basicExample,
  streamingExample,
  listModelsExample,
  eventListenerExample
};
