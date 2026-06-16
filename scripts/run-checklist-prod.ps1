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
    if ($path -eq "/admin/login") {
        try {
            Invoke-WebRequest -Uri $url -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 20 -ErrorAction Stop | Out-Null
            Write-Host "WARN /admin/login accessible sans key (attendu: 404)" -ForegroundColor Yellow
            $fail++
        } catch {
            $code = $_.Exception.Response.StatusCode.value__
            if ($code -eq 404) {
                Write-Host "OK 404 /admin/login (protege par key)" -ForegroundColor Green
                $ok++
            } else {
                Write-Host "FAIL /admin/login (HTTP $code)" -ForegroundColor Red
                $fail++
            }
        }
        continue
    }

    if ($path -eq "/auth/google") {
        $headers = curl.exe -sI $url
        $status = ($headers | Select-String -Pattern "^HTTP/" | Select-Object -First 1).ToString()
        $loc = ($headers | Select-String -Pattern "^location:" -CaseSensitive:$false | Select-Object -First 1).ToString()
        if ($status -match " 30[237] " -and $loc -match "accounts\.google\.com") {
            Write-Host "OK (OAuth redirect) $path" -ForegroundColor Green
            $ok++
        } else {
            Write-Host "FAIL /auth/google (status=$status, location=$loc)" -ForegroundColor Red
            $fail++
        }
        continue
    }

    try {
        $r = Invoke-WebRequest -Uri $url -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 20 -ErrorAction Stop
        Write-Host "OK $($r.StatusCode) $path" -ForegroundColor Green
        $ok++
    } catch {
        Write-Host "FAIL $path" -ForegroundColor Red
        $fail++
    }
}

Write-Host ""
Write-Host "Resultat : $ok OK, $fail echecs" -ForegroundColor $(if ($fail -eq 0) { "Green" } else { "Yellow" })
exit $(if ($fail -eq 0) { 0 } else { 1 })
