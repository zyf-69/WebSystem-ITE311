# PowerShell Script para i-start ang CodeIgniter 4 Server
# I-double click lang ang file na ito o i-run sa PowerShell

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ITE311-DIGA Server Starter" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Pumunta sa project directory
$projectPath = "C:\xampp\htdocs\ITE311-DIGA"

# I-check kung umiiral ang project directory
if (-not (Test-Path $projectPath)) {
    Write-Host "❌ ERROR: Hindi mahanap ang project directory!" -ForegroundColor Red
    Write-Host "   Path: $projectPath" -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit
}

# Pumunta sa project directory
Set-Location $projectPath
Write-Host "✓ Nasa project directory na: $projectPath" -ForegroundColor Green
Write-Host ""

# I-check kung naka-install ang PHP
$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) {
    Write-Host "❌ ERROR: PHP hindi naka-install o wala sa PATH" -ForegroundColor Red
    Write-Host ""
    Write-Host "Solution:" -ForegroundColor Yellow
    Write-Host "1. I-check kung naka-install ang PHP" -ForegroundColor White
    Write-Host "2. Gamitin ang full path: C:\xampp\php\php.exe spark serve" -ForegroundColor White
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit
}

# Lahat ng checks passed, i-start ang server
Write-Host "✓ PHP naka-install" -ForegroundColor Green
Write-Host ""
Write-Host "🚀 Sinisimulan ang server..." -ForegroundColor Yellow
Write-Host "   Buksan ang: http://localhost:8080" -ForegroundColor Cyan
Write-Host "   Para i-stop: Press Ctrl+C" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# I-start ang server
php spark serve
