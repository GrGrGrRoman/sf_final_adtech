<?php

namespace App;

session_start();

use App\core\Route;

require './vendor/autoload.php';
require_once 'app/core/config/config.php';
require_once 'app/core/config/helpers.php';

Route::start();