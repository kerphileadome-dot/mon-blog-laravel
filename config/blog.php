<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Emails administrateurs
    |--------------------------------------------------------------------------
    |
    | Comptes promus admin à l'inscription (Google OAuth) ou pour référence.
    | Séparés par des virgules dans ADMIN_EMAILS (.env).
    |
    */

    'admin_emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('ADMIN_EMAILS', 'kerphilesaint@gmail.com,kerphileadome@gmail.com'))
    ))),

];
