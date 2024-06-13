<?php

use Illuminate\Support\Facades\Route;
use Lintankwgbn\Adminlte\Adminlte;
use Lintankwgbn\Adminlte\Http\Controllers\ApiTokenController;
use Lintankwgbn\Adminlte\Http\Controllers\PrivacyPolicyController;
use Lintankwgbn\Adminlte\Http\Controllers\TermsOfServiceController;
use Lintankwgbn\Adminlte\Http\Controllers\UserProfileController;


Route::group(['middleware' => config('adminlte.middleware', ['web'])], function () {
    if (Adminlte::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    $authMiddleware = config('adminlte.guard') ? 'auth:'.config('adminlte.guard') : 'auth';
    $authSessionMiddleware = config('adminlte.auth_session', false) ? config('adminlte.auth_session') : null;

    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');
        Route::group(['middleware' => 'verified'], function () {
            // API...
            if (Adminlte::hasApiFeatures()) {
                Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
            }
        });
    });
});
