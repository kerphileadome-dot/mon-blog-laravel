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
        explode(',', env('ADMIN_EMAILS', 'kerphilesaint@gmail.com'))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Clé d'accès page admin (/admin/login?key=...)
    |--------------------------------------------------------------------------
    |
    | Sans cette clé, la page de connexion admin renvoie 404 (invisible au public).
    |
    */

    'admin_login_key' => env('ADMIN_LOGIN_KEY'),

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

    /*
    |--------------------------------------------------------------------------
    | Synchronisation production → local
    |--------------------------------------------------------------------------
    */

    'production_url' => env('PRODUCTION_URL', 'https://web-production-c5c2f.up.railway.app'),

    'sync_export_token' => env('SYNC_EXPORT_TOKEN'),

];
