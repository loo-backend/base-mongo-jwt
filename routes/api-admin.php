<?php

$this->resource('users', 'UsersAdminController')->except([
    'create', 'edit'
]);
