<?php

$this->post('/tenants', 'UsersTenantController@store')->name('tenants.store');

//Comentar para testes de TDD
Route::group(['middleware' => ['jwt.auth']], function () {

    $this->resource('admins', 'UsersAdminController')->except([
        'create', 'edit'
    ]);

    $this->resource('tenants', 'UsersTenantController')->except([
        'create', 'edit', 'store'
    ]);

});


// $this->resource('admins', 'UsersAdminController')->except([
//     'create', 'edit'
// ]);

// $this->resource('tenants', 'UsersTenantController')->except([
//     'create', 'edit', 'store'
// ]);
