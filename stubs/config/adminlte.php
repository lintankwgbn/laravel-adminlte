<?php

use Lintankwgbn\Adminlte\Features;
use Lintankwgbn\Adminlte\Http\Middleware\AuthenticateSession;

return [
    'path' => '',
    'guard' => 'sanctum',
    'middleware' => ['web'],
    'profile_photo_disk' => 'public',
    'auth_session' => AuthenticateSession::class,
    'features' => [
        // Features::termsAndPrivacyPolicy(),
        // Features::profilePhotos(),
        // Features::api(),
        Features::accountDeletion(),
    ],
];
