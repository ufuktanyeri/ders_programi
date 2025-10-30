# 🤖 Claude Memory System Kurulum Kılavuzu

## 📋 İçindekiler
1. [Kurulum](#kurulum)
2. [Kullanım](#kullanım)
3. [Dosya Yapısı](#dosya-yapısı)
4. [PowerShell Script'leri](#powershell-scriptleri)
5. [Claude Komutları](#claude-komutları)
6. [İpuçları](#ipuçları)

---

## 🚀 Kurulum

### 1. PowerShell Execution Policy Ayarı
İlk defa PowerShell script çalıştıracaksanız, PowerShell'i **yönetici olarak** açın ve şu komutu çalıştırın:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### 2. Script Dosyalarını Kontrol Edin
Proje kök dizininde şu dosyaların olduğunu doğrulayın:
- `claude-start.ps1` ✅
- `claude-end.ps1` ✅
- `.claude-instructions` ✅

### 3. .claude Klasör Yapısı
İlk çalıştırmada otomatik oluşturulacaktır:
```
.claude/
├── context/
│   └── PROJECT_CONTEXT.md
├── memory/
│   ├── CURRENT_SESSION.md
│   ├── TASK_TRACKER.md
│   └── session_history/
└── logs/
    └── daily/
```

---

## 💻 Kullanım

### Her Oturum Başında

1. **PowerShell'i açın** (proje dizininde):
```powershell
cd C:\xampp\htdocs\ders_programi
```

2. **Oturumu başlatın**:
```powershell
.\claude-start.ps1
```

3. **Claude Code'a şu komutu verin**:
```
Lütfen .claude/memory/CURRENT_SESSION.md ve .claude/context/PROJECT_CONTEXT.md dosyalarını okuyarak kaldığımız yerden devam edelim
```

### Çalışma Sırasında

Claude'a düzenli olarak:
```
CURRENT_SESSION.md dosyasını son yaptığımız işlerle güncelle
```

### Oturum Bitiminde

1. **Oturumu kapatın**:
```powershell
.\claude-end.ps1
```

2. Script sizden:
   - Git commit yapmak isteyip istemediğinizi soracak
   - Commit mesajı isteyecek
   - Push yapmak isteyip istemediğinizi soracak
   - Eski log'ları temizlemek isteyip istemediğinizi soracak

---

## 📁 Dosya Yapısı

### .claude/context/PROJECT_CONTEXT.md
Proje hakkında genel bilgiler:
- Teknoloji stack
- Proje hedefleri
- Önemli kurallar
- Dizin yapısı
- Aktif özellikler

### .claude/memory/CURRENT_SESSION.md
Mevcut oturum bilgileri:
- Yapılan işler
- Çözülen sorunlar
- Aktif dosyalar
- Bir sonraki adımlar

### .claude/memory/TASK_TRACKER.md
Görev takip sistemi:
- 🔴 Yüksek öncelikli görevler
- 🟡 Orta öncelikli görevler
- 🟢 Düşük öncelikli görevler
- ✅ Tamamlanan görevler
- 🐛 Bug listesi

### .claude/memory/session_history/
Geçmiş oturum kayıtları (arşiv)

### .claude/logs/daily/
Günlük log dosyaları

---

## 🛠 PowerShell Script'leri

### claude-start.ps1
**Ne yapar?**
- Klasör yapısını kontrol eder ve oluşturur
- Önceki oturumu arşivler
- Yeni log dosyası oluşturur
- Claude için hazırlık yapar

**Kullanım:**
```powershell
.\claude-start.ps1
```

### claude-end.ps1
**Ne yapar?**
- Oturumu kaydeder ve arşivler
- Git commit önerir
- Eski log'ları temizleme seçeneği sunar
- Oturumu düzgün şekilde kapatır

**Kullanım:**
```powershell
.\claude-end.ps1
```

---

## 🗣 Claude Komutları

### Oturum Başlatma
```
Lütfen .claude/memory/CURRENT_SESSION.md ve .claude/context/PROJECT_CONTEXT.md dosyalarını okuyarak kaldığımız yerden devam edelim
```

### Proje Bağlamını Öğrenme
```
Proje bağlamını öğrenmek için .claude klasöründeki tüm md dosyalarını oku
```

### Görev Listesi
```
TASK_TRACKER.md'deki yüksek öncelikli görevleri listele
```

### Oturum Güncelleme
```
Bugün yaptığımız değişiklikleri CURRENT_SESSION.md'ye kaydet
```

### Özel Komutlar
```
Bu hatayı .claude/logs/daily/[bugün].log dosyasına kaydet
```

---

## 💡 İpuçları

### 1. Düzenli Güncelleme
Her önemli değişiklikten sonra Claude'a:
```
CURRENT_SESSION.md güncelle
```

### 2. Task Tracking
Yeni görev eklerken:
```
TASK_TRACKER.md'ye [görev açıklaması] ekle (yüksek öncelikli)
```

### 3. Git Commit
Anlamlı commit mesajları kullanın:
```
✨ feat: yeni özellik eklendi
🐛 fix: bug düzeltildi
📝 docs: dokümantasyon güncellendi
```

### 4. Backup
Önemli değişikliklerden önce:
```powershell
# .claude klasörünü yedekle
Copy-Item -Path .claude -Destination .claude_backup -Recurse
```

### 5. Temizlik
Aylık bakım:
```powershell
# 30 günden eski session'ları temizle
# claude-end.ps1 script'i bunu otomatik önerir
```

---

## 🔧 Sorun Giderme

### Script Çalışmıyor
```powershell
# Execution policy'yi kontrol edin
Get-ExecutionPolicy

# Eğer Restricted ise, değiştirin (yönetici olarak)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Klasör Oluşturulamıyor
```powershell
# Manuel olarak oluşturun
New-Item -ItemType Directory -Path .claude -Force
New-Item -ItemType Directory -Path .claude/context -Force
New-Item -ItemType Directory -Path .claude/memory -Force
New-Item -ItemType Directory -Path .claude/logs -Force
```

### Claude Dosyaları Okumuyor
- Dosya yollarını kontrol edin
- Dosyaların mevcut olduğunu doğrulayın
- Claude'a explicit olarak dosya yolunu verin

---

## 📚 Faydalı Komutlar

### Hızlı Er
