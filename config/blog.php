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

    /*
    |--------------------------------------------------------------------------
    | Images de couverture
    |--------------------------------------------------------------------------
    |
    | Les fichiers plus grands que max_edge sont redimensionnés à l'upload.
    | Les images déjà adaptées sont conservées telles quelles (qualité maximale).
    |
    */

    'cover' => [
        'max_edge' => 1920,
        'jpeg_quality' => 92,
        'webp_quality' => 90,
        'png_compression' => 6,
    ],

];
