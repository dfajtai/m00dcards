<?php
<<<<<<< Updated upstream
    $db_host = 'localhost';	
    $db_name = 'syncdb';
	$db_name = 'id16365625_syncdb';
	$db_user = 'syncusr';
	$db_user = 'id16365625_syncusr';
    //$db_user = "root";
	$db_pass = 'MooD.base.123';	
	//$db_pass = "root";
    $db_port = 8889;    
    $db_port = 3306;
    include('Medoo.php');	
=======
    $db_host = 'localhost';
    $db_user = 'syncusr';
    $db_pass = 'MooD.base.123';
    $db_name = 'syncdb';  

	//$db_user = 'id16365625_syncusr';
    //$db_name = 'id16365625_syncdb';

    $db_port = 8888;
    
    #$link = mysqli_init();
    #$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    #if (!$link) {
    #    die("Connection failed: " . mysqli_connect_error());
    #}
    
    require_once 'Medoo.php';
>>>>>>> Stashed changes
    use Medoo\Medoo;
    $database = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => $db_name,
	'server' => $db_host,
	'username' => $db_user,
	'password' => $db_pass,
 	// [optional]
	'charset' => 'utf8mb4',
	'collation' => 'utf8mb4_general_ci',
	'port' => $db_port,
	// [optional] Table prefix
	'prefix' => 'moodcards_',
	// [optional] Enable logging (Logging is disabled by default for better performance)
	'logging' => true, 
	// [optional] MySQL socket (shouldn't be used with server and port)
	// 'socket' => '/tmp/mysql.sock', 
	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	],
 	// [optional] Medoo will execute those commands after connected to the database for initialization
	'command' => [
		'SET SQL_MODE=ANSI_QUOTES'
	]
    ]);