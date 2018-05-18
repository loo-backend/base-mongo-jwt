<?php

$this->resource('admins', 'UsersAdminController')->except([
    'create', 'edit'
]);
