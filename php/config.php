<?php

include "definitions.php";

$site = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/low_cost_website/assets/json/control.json"), true);
$DB_TABLE = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/low_cost_website/assets/json/db_tables.json"), true);
$WEB_ROLES = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/low_cost_website/assets/json/web_roles.json"), true);

if ($site['database']['DB_SRV'] == 'mysql') {

	$DB_TYPE = 'BASE';

	$con = new PDO(
		"mysql:host=" . $site['database']['DB_HOST'] . "; dbname=" . $site['database']['DB_NAME'],
		$site['database']['DB_USER'],
		$site['database']['DB_PASS'],
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
	);
} elseif ($site['database']['DB_SRV'] == 'sqlsrv') {

	$DB_TYPE = 'BASE';

	$con = new PDO("sqlsrv:Server=" . $site['database']['DB_HOST'] . "; Database=" . $site['database']['DB_NAME'], $site['database']['DB_USER'], $site['database']['DB_PASS']);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} elseif ($site['database']['DB_SRV'] == 'pgsql') {

	$DB_TYPE = 'PG';

	$con = new PDO("pgsql:host=" . $site['database']['DB_HOST'] . "; port=" . $site['database']['DB_PORT'] . "; dbname=" . $site['database']['DB_NAME'] . "; user=" . $site['database']['DB_USER'] . "; password=" . $site['database']['DB_PASS']);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}


date_default_timezone_set($site['datetime']['timezone']);

session_start();

include "functions.php";
