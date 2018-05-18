<?php

$this->post('authenticate', 'Auth\AuthApiController@authenticate')
    ->name('authenticate');
