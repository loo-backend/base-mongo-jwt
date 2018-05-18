<?php

$this->resource('admins', 'UsersAdminController')->except([
    'create', 'edit'
]);


$this->resource('clients', 'UsersController')->except([
    'create', 'edit'
]);
