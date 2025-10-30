# Claude Code Oturum Kapatma Script'i
# Tarih: 30 Ekim 2025

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Claude Code Oturumu Kapatılıyor..." -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Tarih ve saat bilgisi
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$dateForFile = Get-Date -Format "yyyy-MM-dd"
$dateTimeForFile = Get-Date -Format "yyyy-MM-dd_HHmmss"

# Log dosyası
$logFile = ".claude/logs/daily/$dateForFile.log"
$logEntry = "[$timestamp] Oturum kapatıldı"
Add-Content -Path $logFile -Value $logEntry

# CURRENT_SESSION.md'yi session history'ye kaydet
$sessionFile = ".claude/memory/CURRENT_SESSION.md"
if (Test-Path $sessionFile) {
    $archivePath = ".claude/memory/session_history/session_$dateTimeForFile.md"
    Copy-Item $sessionFile $archivePath
    Write-Host "[✓] Oturum arşivlendi: $archivePath" -ForegroundColor Green
}

# Git commit önerisi
Write-Host ""
Write-Host "==================================" -ForegroundColor Yellow
Write-Host "Git Commit Önerisi" -ForegroundColor Yellow
Write-Host "==================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "Değişiklikleri commit etmek ister misiniz? (E/H)" -ForegroundColor Cyan
$response = Read-Host

if ($response -eq "E" -or $response -eq "e") {
    Write-Host ""
    Write-Host "Commit mesajını girin:" -ForegroundColor Cyan
    $commitMessage = Read-Host
    
    if ($commitMessage) {
        Write-Host ""
        Write-Host "Git işlemleri yapılıyor..." -ForegroundColor Yellow
        
        git add .
        git commit -m "🔄 $commitMessage"
        
        Write-Host ""
        Write-Host "[✓] Commit başarılı!" -ForegroundColor Green
        
        Write-Host ""
        Write-Host "Push yapmak ister misiniz? (E/H)" -ForegroundColor Cyan
        $pushResponse = Read-Host
        
        if ($pushResponse -eq "E" -or $pushResponse -eq "e") {
            git push
            Write-Host "[✓] Push başarılı!" -ForegroundColor Green
        }
    }
}

# Eski log dosyalarını temizle (30 günden eski)
$oldLogs = Get-ChildItem ".claude/logs/daily" -Filter "*.log" | Where-Object { 
    $_.LastWriteTime -lt (Get-Date).AddDays(-30) 
}

if ($oldLogs.Count -gt 0) {
    Write-Host ""
    Write-Host "30 günden eski $($oldLogs.Count) adet log dosyası bulundu. Silinsin mi? (E/H)" -ForegroundColor Yellow
    $cleanResponse = Read-Host
    
    if ($cleanResponse -eq "E" -or $cleanResponse -eq "e") {
        $oldLogs | Remove-Item
        Write-Host "[✓] Eski log dosyaları temizlendi" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "==================================" -ForegroundColor Green
Write-Host "Oturum Başarıyla Kapatıldı!" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host ""
Write-Host "İyi çalışmalar! 👋" -ForegroundColor Cyan
Write-Host ""
