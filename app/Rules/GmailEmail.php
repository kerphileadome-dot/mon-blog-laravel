<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GmailEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower(trim((string) $value));
        $pattern = '/^(?!.*\.\.)[a-z0-9](?:[a-z0-9.]{0,62}[a-z0-9])?@gmail\.com$/';

        if (! preg_match($pattern, $email)) {
            $fail('Adresse Gmail invalide. Utilisez un format valide (ex: nom@gmail.com, sans point en debut/fin).');
        }
    }
}
