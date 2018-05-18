<?php

$this->resource('admins', 'UsersAdminController')->except([
    'create', 'edit'
]);


$this->post('tenants', 'UsersTenantController@store')->name('tenants.store');

$this->resource('tenants', 'UsersTenantController')->except([
    'store', 'create', 'edit'
]);
