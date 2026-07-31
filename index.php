<?php

ini_set(
'display_errors',
1
);

error_reporting(
E_ALL
);

require_once
'app/controllers/AuthController.php';

$action =
$_GET['action']
?? '';

switch(
$action
){

case 'register':

(new AuthController())
->register();

break;

case 'dashboard':

include
'app/views/dashboard/index.php';

break;
case 'transaction':

include
'app/views/transaction/create.php';

break;
default:

include
'app/views/auth/register.php';

}