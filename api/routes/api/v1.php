<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'pong' => time()
    ]);
});

/* Auth Routes */
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('/login', LoginUserAction::class);
    //password reset
    Route::post('/password/forgot', ForgotPasswordAction::class);
    Route::post('/password/recover/{token}', RecoverPasswordAction::class);

    Route::post('/register', RegisterUserAction::class);
});

// /* Protected Routes */
Route::group(['middleware' => 'auth:sanctum'], function () {
    /* Auth Routes */
    Route::group(['namespace' => 'Auth'], function () {
        Route::get('/me', MeUserAction::class);
        Route::post('/logout', LogoutUserAction::class);
    });

    /* User Routes */
    Route::group(['namespace' => 'User'], function () {
        Route::post('/users-paginated', IndexUsersPaginatedAction::class);
        Route::get('/users/{id}', ShowUserAction::class);
        Route::post('/users/{id}', UpdateUserAction::class);
        Route::delete('/users/{id}', DeleteUserAction::class);
        Route::post('users/{id}/activate', ActivateUserAction::class);
    });
});
