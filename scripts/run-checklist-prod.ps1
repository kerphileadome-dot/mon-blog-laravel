# Verification automatique des pages cles en production.
# Usage : powershell -ExecutionPolicy Bypass -File scripts/run-checklist-prod.ps1

$base = "https://web-production-c5c2f.up.railway.app"
$paths = @(
    "/",
    "/login",
    "/register",
    "/forgot-password",
    "/admin/login",
    "/categories",
    "/tags",
    "/about",
    "/search?q=benin",
    "/posts/romuald-wadagni-le-parcours-du-nouveau-president-du-benin",
    "/auth/google"
)

$ok = 0
$fail = 0
foreach ($path in $paths) {
    $url = $base + $path
    try {
        $maxRedirect = if ($path -eq "/auth/google") { 5 } else { 0 }
        $r = Invoke-WebRequest -Uri $url -UseBasicParsing -MaximumRedirection $maxRedirect -TimeoutSec 20 -ErrorAction Stop
        $label = if ($path -eq "/auth/google") { "OK (OAuth redirect) $path" } else { "OK $($r.StatusCode) $path" }
        Write-Host $label -ForegroundColor Green
        $ok++
    } catch {
        Write-Host "FAIL $path" -ForegroundColor Red
        $fail++
    }
}

Write-Host ""
Write-Host "Resultat : $ok OK, $fail echecs" -ForegroundColor $(if ($fail -eq 0) { "Green" } else { "Yellow" })
exit $(if ($fail -eq 0) { 0 } else { 1 })
