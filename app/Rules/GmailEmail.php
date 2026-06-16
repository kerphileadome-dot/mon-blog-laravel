<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GmailEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower((string) $value);

        if (! str_ends_with($email, '@gmail.com')) {
            $fail('Seules les adresses Gmail (@gmail.com) sont acceptées pour créer un compte.');
        }
    }
}
