# Claude Code Oturum Başlatma Script'i
# Tarih: 30 Ekim 2025

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Claude Code Oturum Başlatılıyor..." -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Proje dizini
$projectPath = Get-Location

# Tarih ve saat bilgisi
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$dateForFile = Get-Date -Format "yyyy-MM-dd"

# .claude klasör yapısını kontrol et ve oluştur
$claudeDirs = @(
    ".claude",
    ".claude/context",
    ".claude/memory",
    ".claude/memory/session_history",
    ".claude/logs",
    ".claude/logs/daily"
)

foreach ($dir in $claudeDirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "[✓] Oluşturuldu: $dir" -ForegroundColor Green
    }
}

# CURRENT_SESSION.md dosyasını kontrol et
$sessionFile = ".claude/memory/CURRENT_SESSION.md"
if (Test-Path $sessionFile) {
    # Mevcut oturumu arşivle
    $archivePath = ".claude/memory/session_history/session_$dateForFile.md"
    if (-not (Test-Path $archivePath)) {
        Copy-Item $sessionFile $archivePath
        Write-Host "[✓] Önceki oturum arşivlendi: $archivePath" -ForegroundColor Yellow
    }
}

# Log dosyası oluştur
$logFile = ".claude/logs/daily/$dateForFile.log"
$logEntry = "[$timestamp] Oturum başlatıldı - $projectPath"
Add-Content -Path $logFile -Value $logEntry

Write-Host ""
Write-Host "==================================" -ForegroundColor Green
Write-Host "Oturum Başarıyla Başlatıldı!" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host ""
Write-Host "Claude'a şu komutu verin:" -ForegroundColor Cyan
Write-Host "  'Lütfen .claude/memory/CURRENT_SESSION.md ve .claude/context/PROJECT_CONTEXT.md dosyalarını okuyarak kaldığımız yerden devam edelim'" -ForegroundColor White
Write-Host ""
Write-Host "Log dosyası: $logFile" -ForegroundColor Gray
Write-Host ""
