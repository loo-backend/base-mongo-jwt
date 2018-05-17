<?php

$this->post('authenticate', 'Auth\AuthApiController@authenticate');
$this->post('refresh/token', 'Auth\AuthApiController@refreshToken');

