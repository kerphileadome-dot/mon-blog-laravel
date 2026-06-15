# Guide interactif : passer Railway de SQLite vers MySQL.
# Usage : powershell -ExecutionPolicy Bypass -File scripts/setup-railway-mysql.ps1

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$prodUrl = "https://web-production-c5c2f.up.railway.app"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Railway : passage SQLite -> MySQL" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "ETAPE 1 — Ajouter MySQL sur Railway" -ForegroundColor Yellow
Write-Host "  1. Ouvrez https://railway.app" -ForegroundColor Gray
Write-Host "  2. Projet elegant-serenity -> + New -> Database -> MySQL" -ForegroundColor Gray
Write-Host "  3. Attendez que MySQL soit Active (vert)" -ForegroundColor Gray
Write-Host ""
Read-Host "Appuyez sur Entree quand MySQL est cree"

Write-Host ""
Write-Host "ETAPE 2 — Variables du service WEB" -ForegroundColor Yellow
Write-Host "  Service web -> Variables :" -ForegroundColor Gray
Write-Host ""
Write-Host "  A) Supprimez : DB_CONNECTION=sqlite" -ForegroundColor Red
Write-Host ""
Write-Host "  B) Ajoutez (valeur fixe) :" -ForegroundColor Green
Write-Host "     DB_CONNECTION=mysql" -ForegroundColor White
Write-Host ""
Write-Host "  C) Ajoutez via « Add Reference » (service MySQL) :" -ForegroundColor Green
Write-Host "     DB_HOST      -> MYSQLHOST" -ForegroundColor White
Write-Host "     DB_PORT      -> MYSQLPORT" -ForegroundColor White
Write-Host "     DB_DATABASE  -> MYSQLDATABASE" -ForegroundColor White
Write-Host "     DB_USERNAME  -> MYSQLUSER" -ForegroundColor White
Write-Host "     DB_PASSWORD  -> MYSQLPASSWORD" -ForegroundColor White
Write-Host ""
Write-Host "  (Railway affiche parfois : `${{MySQL.MYSQLHOST}}` etc.)" -ForegroundColor DarkGray
Write-Host ""
Read-Host "Appuyez sur Entree quand les variables sont enregistrees"

Write-Host ""
Write-Host "ETAPE 3 — Redeploiement" -ForegroundColor Yellow
Write-Host "  Railway redéploie automatiquement." -ForegroundColor Gray
Write-Host "  Deployments -> attendez Success" -ForegroundColor Gray
Write-Host "  Logs : cherchez « Base de données : MySQL »" -ForegroundColor Gray
Write-Host ""
Read-Host "Appuyez sur Entree quand le deploiement est Success"

Write-Host ""
Write-Host "ETAPE 4 — Verification production" -ForegroundColor Yellow
try {
    $r = Invoke-WebRequest -Uri $prodUrl -UseBasicParsing -TimeoutSec 20
    Write-Host "  Site : HTTP $($r.StatusCode) OK" -ForegroundColor Green
} catch {
    Write-Host "  Site : echec - $($_.Exception.Message)" -ForegroundColor Red
}

if (Test-Path "scripts/run-checklist-prod.ps1") {
    powershell -ExecutionPolicy Bypass -File scripts/run-checklist-prod.ps1
}

Write-Host ""
Write-Host "Termine. Guide detaille : docs/MYSQL_RAILWAY.md" -ForegroundColor Green
Write-Host "Admin : $prodUrl/admin/login" -ForegroundColor Gray
Write-Host "Email : kerphilesaint@gmail.com" -ForegroundColor Gray
