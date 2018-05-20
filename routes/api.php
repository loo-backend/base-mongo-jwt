<?php

$this->post('/tenants', 'UsersTenantController@store')->name('tenants.store');

//Comentar para testes de TDD
$this->group(['middleware' => ['jwt.auth']], function () {

    $this->resource('admins', 'UsersAdminController')->except([
        'create', 'edit'
    ]);

    $this->resource('tenants', 'UsersTenantController')->except([
        'create', 'edit', 'store'
    ]);

});
