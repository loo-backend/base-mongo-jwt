<?php

$this->resource('users', 'UsersController')->except([
    'create', 'edit'
]);
