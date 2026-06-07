@echo off
cd /d "%~dp0.."
echo Met a jour MAIL_PASSWORD sur Railway depuis .env local...
echo.
call node_modules\.bin\railway.cmd whoami >nul 2>&1
if errorlevel 1 (
    echo Connexion requise. Lancez d abord :
    echo   node_modules\.bin\railway.cmd login --browserless
    echo   node_modules\.bin\railway.cmd link
    pause
    exit /b 1
)
powershell -ExecutionPolicy Bypass -File scripts\sync-mail-to-railway.ps1
echo.
echo Declenchement redeploiement...
call node_modules\.bin\railway.cmd up --detach 2>nul || call node_modules\.bin\railway.cmd redeploy 2>nul
echo Termine.
pause
