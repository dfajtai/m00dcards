<?php
session_start();
require_once '../vendor/autoload.php';
use Hashids\Hashids;
$hashids = new Hashids('m00dcards', 20);
require_once 'init_functions.php';
include 'connect.php';
include "get_state.php";