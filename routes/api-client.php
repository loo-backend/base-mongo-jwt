<?php

$this->resource('clients', 'UsersController')->except([
    'create', 'edit'
]);
