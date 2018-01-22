<?php

Auth::routes();

Route::group(['middleware' => makeAppRootRouteMiddlewares()], function () {
    require __DIR__.'/services/home.php';

    require __DIR__.'/services/processos.php';

    require __DIR__.'/services/tribunais.php';

    require __DIR__.'/services/acoes.php';

    require __DIR__.'/services/andamentos.php';

    require __DIR__.'/services/andamentos.php';

    require __DIR__.'/services/prazos.php';

    require __DIR__.'/services/juizes.php';
});

/**
 * @return array
 */
function makeAppRootRouteMiddlewares()
{
    return collect([
        config('auth.authentication', true) ? 'auth' : null,
        config('auth.authorization', true) ? 'app.users' : null,
    ])->reject(function ($value) {
        return empty($value);
    })->toArray();
}
