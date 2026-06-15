# Remet Railway en SQLite (production).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/setup-railway-sqlite.ps1

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Railway : configuration SQLite (prod)" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Service WEB -> Variables :" -ForegroundColor Yellow
Write-Host ""
Write-Host "  GARDER / AJOUTER :" -ForegroundColor Green
Write-Host "    DB_CONNECTION=sqlite" -ForegroundColor White
Write-Host ""
Write-Host "  SUPPRIMER (si presentes) :" -ForegroundColor Red
Write-Host "    DB_HOST" -ForegroundColor White
Write-Host "    DB_PORT" -ForegroundColor White
Write-Host "    DB_DATABASE" -ForegroundColor White
Write-Host "    DB_USERNAME" -ForegroundColor White
Write-Host "    DB_PASSWORD" -ForegroundColor White
Write-Host ""
Write-Host "  Optionnel : supprimer le service MySQL sur Railway" -ForegroundColor DarkGray
Write-Host "  (s'il a ete cree mais n'est plus utilise)" -ForegroundColor DarkGray
Write-Host ""
Write-Host "Railway redéploie automatiquement." -ForegroundColor Gray
Write-Host "Logs attendus : « Creation de la base SQLite » ou « Demarrage rapide »" -ForegroundColor Gray
Write-Host ""
Write-Host "Test : https://web-production-c5c2f.up.railway.app" -ForegroundColor Cyan
Write-Host ""

Read-Host "Appuyez sur Entree apres avoir modifie les variables Railway"

$prodUrl = "https://web-production-c5c2f.up.railway.app"
try {
    $r = Invoke-WebRequest -Uri $prodUrl -UseBasicParsing -TimeoutSec 20
    Write-Host "Site : HTTP $($r.StatusCode) OK" -ForegroundColor Green
} catch {
    Write-Host "Site : echec - $($_.Exception.Message)" -ForegroundColor Red
}

if (Test-Path "scripts/run-checklist-prod.ps1") {
    powershell -ExecutionPolicy Bypass -File scripts/run-checklist-prod.ps1
}
