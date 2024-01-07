<?php

function SendCoins($amount, $user_id)
{

	global $con, $DB_TABLE, $DB_TYPE;

	$SQL_UPDATE_COINS = $con->prepare(
		'UPDATE ' .

			$DB_TABLE['USER_TABLE']['USER_SCHEMA'] .
			$DB_TABLE['USER_TABLE']['USER_TABLE'] . ' SET ' .
			$DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . ' = ' .
			$DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . ' + ? WHERE ' .
			$DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . " = ?"

	);

	if ($SQL_UPDATE_COINS->execute([$amount, $user_id]) > 0)
		return true;
	else
		return false;
}
