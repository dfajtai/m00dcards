<?php
require_once '../vendor/autoload.php';
use Hashids\Hashids;
$hashids = new Hashids('m00dcards',20);
echo $hashids->encode(11111);