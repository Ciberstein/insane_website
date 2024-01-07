<?php

require './config.php';

$TO = $_GET['to'];

if ($TO == 'JoinSystem') {

	if (isset($_POST["inputLoginUsername"]) && isset($_POST["inputLoginPassword"])) {

		if (!empty($_POST["inputLoginUsername"]) && !empty($_POST["inputLoginPassword"])) {

			$USER_PASS  = hash('sha512', $_POST["inputLoginPassword"]);

			$SQL_LOGIN_CHECK = $con->prepare(
				'SELECT COUNT(*) FROM '

					. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
					. $DB_TABLE['USER_TABLE']['USER_TABLE'] 			 . ' WHERE '
					. $DB_TABLE['USER_TABLE']['USER_USERNAME'][$DB_TYPE] . ' = ? AND '
					. $DB_TABLE['USER_TABLE']['USER_PASSWORD'][$DB_TYPE] . ' = ?'

			);

			$SQL_LOGIN_CHECK->execute([

				htmlentities($_POST['inputLoginUsername']),
				$USER_PASS

			]);

			$LOGIN_CHECK = $SQL_LOGIN_CHECK->fetch();

			if ($LOGIN_CHECK[0] > 0) {

				$SQL_LOGIN = $con->prepare(
					'SELECT * FROM '

						. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
						. $DB_TABLE['USER_TABLE']['USER_TABLE'] 		   . ' WHERE '
						. $DB_TABLE['USER_TABLE']['USER_USERNAME'][$DB_TYPE] . ' = ? AND '
						. $DB_TABLE['USER_TABLE']['USER_PASSWORD'][$DB_TYPE] . ' = ?'

				);

				$SQL_LOGIN->execute([

					htmlentities($_POST['inputLoginUsername']),
					$USER_PASS
				]);

				$LOGIN     = $SQL_LOGIN->fetch();

				$_SESSION["USER_ID"] = $LOGIN[$DB_TABLE['USER_TABLE']['USER_ID']['BASE']];
				$_SESSION["USER_NICK"] = $LOGIN[$DB_TABLE['USER_TABLE']['USER_USERNAME']['BASE']];
				$_SESSION["USER_RANGE"] = $LOGIN[$DB_TABLE['USER_TABLE']['USER_RANGE']['BASE']];

				echo '{ "refresh": true }';
			} else {
				echo '{ "icon": "error", "title": "Error", "text": "Username or password incorrect", "refresh": false }';
			}
		}
	}

	if (isset($_POST['inputRegisterUsername']) && isset($_POST['inputRegisterEmail']) && isset($_POST['inputRegisterPassword']) && isset($_POST['inputRegisterPasswordAgain'])) {

		if (!empty($_POST['inputRegisterUsername']) && !empty($_POST['inputRegisterEmail']) && !empty($_POST['inputRegisterPassword']) && !empty($_POST['inputRegisterPasswordAgain'])) {

			if ($_POST['inputRegisterPassword'] == $_POST['inputRegisterPasswordAgain']) {

				$SQL_USER_CHECK = $con->prepare(
					'SELECT COUNT(*) FROM ' .

						$DB_TABLE['USER_TABLE']['USER_SCHEMA'] .
						$DB_TABLE['USER_TABLE']['USER_TABLE'] . ' WHERE ' .
						$DB_TABLE['USER_TABLE']['USER_USERNAME'][$DB_TYPE] . ' = ?'

				);

				$SQL_USER_CHECK->execute([htmlentities($_POST['inputRegisterUsername'])]);
				$USER_CHECK = $SQL_USER_CHECK->fetch();

				$SQL_EMAIL_CHECK = $con->prepare(
					'SELECT COUNT(*) FROM ' .

						$DB_TABLE['USER_TABLE']['USER_SCHEMA'] .
						$DB_TABLE['USER_TABLE']['USER_TABLE'] . ' WHERE ' .
						$DB_TABLE['USER_TABLE']['USER_EMAIL'][$DB_TYPE] . ' = ?'

				);

				$SQL_EMAIL_CHECK->execute([htmlentities($_POST['inputRegisterEmail'])]);
				$EMAIL_CHECK = $SQL_EMAIL_CHECK->fetch();

				if ($USER_CHECK[0] == 0) {

					if ($EMAIL_CHECK[0] == 0) {

						$P_PIC = rand(1, 10);

						$USER_PASS = hash('sha512', $_POST["inputRegisterPassword"]);

						$SQL_USER_REGISTER = $con->prepare(
							'INSERT INTO '

								. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
								. $DB_TABLE['USER_TABLE']['USER_TABLE'] . ' (

	                            ' . $DB_TABLE['USER_TABLE']['USER_USERNAME'][$DB_TYPE] . ',
	                            ' . $DB_TABLE['USER_TABLE']['USER_PASSWORD'][$DB_TYPE] . ',
	                            ' . $DB_TABLE['USER_TABLE']['USER_PROFILE_PIC'][$DB_TYPE] . ',
	                            ' . $DB_TABLE['USER_TABLE']['USER_EMAIL'][$DB_TYPE] . ',
	                            ' . $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . ',
	                            ' . $DB_TABLE['USER_TABLE']['USER_IP'][$DB_TYPE] . ',
								' . $DB_TABLE['USER_TABLE']['USER_RANGE'][$DB_TYPE] . '

	                        ) VALUES (?, ?, ?, ?, ?, ?, ?)'
						);

						if ($SQL_USER_REGISTER->execute([

							htmlentities($_POST['inputRegisterUsername']),
							$USER_PASS,
							$P_PIC,
							htmlentities($_POST['inputRegisterEmail']),
							0,
							$_SERVER['REMOTE_ADDR'],
							0

						]) > 0) {

							echo '{ "icon": "success", "title": "Amazing", "text": "Your new accoun has been created!", "refresh": false }';
						} else  echo '{ "icon": "error", "title": "Error", "text": "Query exception", "refresh": false }';
					} else  echo '{ "icon": "error", "title": "Error", "text": "The email is already registered", "refresh": false }';
				} else  echo '{ "icon": "error", "title": "Error", "text": "The username is already registered", "refresh": false }';
			} else  echo '{ "icon": "error", "title": "Error", "text": "Passwords do not match", "refresh": false }';
		} else  echo '{ "icon": "error", "title": "Error", "text": "Internal error: UND3F1N3D", "refresh": false }';
	}
} elseif ($TO == 'buyShopItem') {
	if (isset($_POST['selectCharacter'])) {
		if (isset($_POST["itemVNum"])) {

			$SQL_CHAR_CHECK = $con->prepare(
				'SELECT ' . $DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID'][$DB_TYPE] . ' FROM '
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_SCHEMA']
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_TABLE'] . ' WHERE '
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_ID'][$DB_TYPE] . ' = ?'
			);
			$SQL_CHAR_CHECK->execute([htmlentities($_POST['selectCharacter'])]);
			$CHAR_CHECK = $SQL_CHAR_CHECK->fetch();

			if ($CHAR_CHECK) {

				if ($CHAR_CHECK[$DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID']['BASE']] === $_SESSION['USER_ID']) {
					$SQL_FOUNDS_CHECK = $con->prepare(
						'SELECT ' . $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . ' FROM '
							. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
							. $DB_TABLE['USER_TABLE']['USER_TABLE'] . ' WHERE '
							. $DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . ' = ?'
					);
					$SQL_FOUNDS_CHECK->execute([$_SESSION['USER_ID']]);
					$USER_BALANCE = $SQL_FOUNDS_CHECK->fetch();

					$SQL_SHOP_ITEM = $con->prepare(
						'SELECT * FROM '
							. $DB_TABLE['SHOP_TABLE']['SHOP_SCHEMA']
							. $DB_TABLE['SHOP_TABLE']['SHOP_TABLE']
							. ' WHERE '
							. $DB_TABLE['SHOP_TABLE']['SHOP_VNUM'][$DB_TYPE] . ' = ?'
					);
					$SQL_SHOP_ITEM->execute([htmlentities($_POST["itemVNum"])]);
					$SHOP_ITEM = $SQL_SHOP_ITEM->fetch();

					if ($SHOP_ITEM) {

						$AMOUNT_CHECK = 1;

						if (isset($_POST['amount'])) {

							$AMOUNTS = explode("|", $SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_AMOUNT']['BASE']]);

							foreach ($AMOUNTS as $AMOUNT) {
								if ($AMOUNT == $_POST['amount']) {
									$AMOUNT_CHECK = $AMOUNT;
									break;
								}
							}
						}

						$SQL_ITEM =  $con->prepare(
							'SELECT * FROM '
								. $DB_TABLE['ITEM_TABLE']['ITEM_SCHEMA']
								. $DB_TABLE['ITEM_TABLE']['ITEM_TABLE']
								. ' WHERE '
								. $DB_TABLE['ITEM_TABLE']['ITEM_VNUM'][$DB_TYPE] . ' = ?'
						);
						$SQL_ITEM->execute([htmlentities($_POST["itemVNum"])]);
						$ITEM = $SQL_ITEM->fetch();

						if ($ITEM) {
							if ($USER_BALANCE[0] >= $SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_PRICE']['BASE']] * $AMOUNT_CHECK) {

								$SQL_DEBIT = $con->prepare(
									'UPDATE '
										. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
										. $DB_TABLE['USER_TABLE']['USER_TABLE']  . ' SET '
										. $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . " = "
										. $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . " - ? WHERE "
										. $DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . " = ?"
								);

								if ($SQL_DEBIT->execute([$SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_PRICE']['BASE']] * $AMOUNT_CHECK, $_SESSION['USER_ID']])) {

									$SQL_SEND_ITEM = $con->prepare(
										'INSERT INTO '

											. $DB_TABLE['MAIL_TABLE']['MAIL_SCHEMA']
											. $DB_TABLE['MAIL_TABLE']['MAIL_TABLE'] . ' (

											' . $DB_TABLE['MAIL_TABLE']['MAIL_DATE'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_SENDER'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_RECIVER'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_AMOUNT'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_VNUM'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_LVL'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_RARITY'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_UPGRADE'][$DB_TYPE] . ',
											' . $DB_TABLE['MAIL_TABLE']['MAIL_TITLE'][$DB_TYPE] . '

										) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
									);

									if ($SQL_SEND_ITEM->execute([
										date('Ymd'),
										htmlentities($_POST['selectCharacter']),
										htmlentities($_POST['selectCharacter']),
										$AMOUNT_CHECK,
										$ITEM[$DB_TABLE['ITEM_TABLE']['ITEM_VNUM']['BASE']],
										$SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_LVL']['BASE']],
										$SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_RARITY']['BASE']],
										$SHOP_ITEM[$DB_TABLE['SHOP_TABLE']['SHOP_UPGRADE']['BASE']],
										"Item Shop"
									]) > 0) {

										echo '{ "icon": "success", "title": "Done", "text": "The item was send", "refresh": false }';
									} else  echo '{ "icon": "error", "title": "Error", "text": "Error sending item", "refresh": false }';
								} else  echo '{ "icon": "error", "title": "Error", "text": "Debit error", "refresh": false }';
							} else  echo '{ "icon": "error", "title": "Error", "text": "Insuficient balance", "refresh": false }';
						} else  echo '{ "icon": "error", "title": "Error", "text": "Item not exist", "refresh": false }';
					} else  echo '{ "icon": "error", "title": "Error", "text": "Item not exist in the shop", "refresh": false }';
				} else  echo '{ "icon": "error", "title": "Error", "text": "Character not belongs to auth user", "refresh": false }';
			} else  echo '{ "icon": "error", "title": "Error", "text": "Character not found", "refresh": false }';
		} else  echo '{ "icon": "error", "title": "Error", "text": "Item VNum cannot be empty", "refresh": false }';
	} else  echo '{ "icon": "error", "title": "Error", "text": "Character cannot be empty", "refresh": false }';
} elseif ($TO == 'roulette') {

	header('Content-Type: application/json');

	$response = [];

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$data = json_decode(file_get_contents('php://input'), true);
		http_response_code(405);

		if (isset($data['characterId'])) {
			if (is_numeric($data['characterId'])) {

				if (isset($data['vnum'])) {
					if (is_numeric($data['vnum'])) {

						$SQL_CHAR_CHECK = $con->prepare(
							'SELECT ' . $DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID'][$DB_TYPE] . ' FROM '
								. $DB_TABLE['CHARACT_TABLE']['CHARACT_SCHEMA']
								. $DB_TABLE['CHARACT_TABLE']['CHARACT_TABLE'] . ' WHERE '
								. $DB_TABLE['CHARACT_TABLE']['CHARACT_ID'][$DB_TYPE] . ' = ?'
						);
						$SQL_CHAR_CHECK->execute([$data['characterId']]);
						$CHAR_CHECK = $SQL_CHAR_CHECK->fetch();

						if ($CHAR_CHECK) {
							if ($CHAR_CHECK[$DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID']['BASE']] === $_SESSION['USER_ID']) {

								$SQL_FOUNDS_CHECK = $con->prepare(
									'SELECT ' . $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . ' FROM '
										. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
										. $DB_TABLE['USER_TABLE']['USER_TABLE'] . ' WHERE '
										. $DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . ' = ?'
								);
								$SQL_FOUNDS_CHECK->execute([$_SESSION['USER_ID']]);
								$USER_BALANCE = $SQL_FOUNDS_CHECK->fetch();

								$SQL_ROULETTE_ITEM = $con->prepare(
									'SELECT * FROM '
										. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_SCHEMA']
										. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_TABLE']
										. ' WHERE '
										. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_VNUM'][$DB_TYPE] . ' = ?'
								);
								$SQL_ROULETTE_ITEM->execute([$data['vnum']]);
								$ROULETTE_ITEM = $SQL_ROULETTE_ITEM->fetch();

								if ($ROULETTE_ITEM) {

									$SQL_ITEM = $con->prepare(
										'SELECT * FROM '
											. $DB_TABLE['ITEM_TABLE']['ITEM_SCHEMA']
											. $DB_TABLE['ITEM_TABLE']['ITEM_TABLE']
											. ' WHERE '
											. $DB_TABLE['ITEM_TABLE']['ITEM_VNUM'][$DB_TYPE] . ' = ?'
									);
									$SQL_ITEM->execute([$data['vnum']]);
									$ITEM = $SQL_ITEM->fetch();

									if ($ITEM) {
										if ($USER_BALANCE[0] >= $site['wheel']['price']) { // PRECIO DE LA RULETA

											$SQL_DEBIT = $con->prepare(
												'UPDATE '
													. $DB_TABLE['USER_TABLE']['USER_SCHEMA']
													. $DB_TABLE['USER_TABLE']['USER_TABLE']  . ' SET '
													. $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . " = "
													. $DB_TABLE['USER_TABLE']['USER_BALANCE'][$DB_TYPE] . " - ? WHERE "
													. $DB_TABLE['USER_TABLE']['USER_ID'][$DB_TYPE] . " = ?"
											);

											if ($SQL_DEBIT->execute([$site['wheel']['price'], $_SESSION['USER_ID']])) {

												$SQL_SEND_ITEM = $con->prepare(
													'INSERT INTO '

														. $DB_TABLE['MAIL_TABLE']['MAIL_SCHEMA']
														. $DB_TABLE['MAIL_TABLE']['MAIL_TABLE'] . ' (
			
														' . $DB_TABLE['MAIL_TABLE']['MAIL_DATE'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_SENDER'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_RECIVER'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_AMOUNT'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_VNUM'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_LVL'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_RARITY'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_UPGRADE'][$DB_TYPE] . ',
														' . $DB_TABLE['MAIL_TABLE']['MAIL_TITLE'][$DB_TYPE] . '
			
													) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
												);

												if ($SQL_SEND_ITEM->execute([
													date('Ymd'),
													$data['characterId'],
													$data['characterId'],
													1,
													$ITEM[$DB_TABLE['ITEM_TABLE']['ITEM_VNUM']['BASE']],
													$ROULETTE_ITEM[$DB_TABLE['ROULETTE_TABLE']['ROULETTE_LVL']['BASE']],
													$ROULETTE_ITEM[$DB_TABLE['ROULETTE_TABLE']['ROULETTE_RARITY']['BASE']],
													$ROULETTE_ITEM[$DB_TABLE['ROULETTE_TABLE']['ROULETTE_UPGRADE']['BASE']],
													"Item Roulette"
												]) > 0) {

													http_response_code(200);
													$response = ['message' => 'success', 'data' => $data];
												} else $response = ['error' => 'Error sending item'];
											} else $response = ['error' => 'Debit error'];
										} else $response = ['error' => 'Insuficient balance'];
									} else $response = ['error' => 'Item not exist'];
								} else $response = ['error' => 'Item not exist in the roulette'];
							} else $response = ['error' => 'Character not belongs to auth user'];
						} else $response = ['error' => 'Character not found'];
					} else $response = ['error' => 'VNum must be a valid integer'];
				} else $response = ['error' => 'VNum cannot be empty'];
			} else $response = ['error' => 'Character ID must be a valid integer'];
		} else $response = ['error' => 'Character cannot be empty'];
	} else $response = ['error' => 'Método no soportado'];


	echo json_encode($response);
} elseif ($TO == 'request') {

	header('Content-Type: application/json');

	$TARGET = $_GET['target'];

	if ($TARGET == 'characters') {

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {

			$SQL_CHAR = $con->prepare(
				'SELECT * FROM '
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_SCHEMA']
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_TABLE']
					. ' WHERE '
					. $DB_TABLE['CHARACT_TABLE']['CHARACT_ACC_ID'][$DB_TYPE] . ' = ?'
			);
			$SQL_CHAR->execute([$_SESSION['USER_ID']]);

			$RES = $SQL_CHAR->fetchAll(PDO::FETCH_OBJ);

			$response = ['message' => 'success', 'data' => $RES];
			echo json_encode($response);
		} else {

			http_response_code(405);
			$response = ['error' => 'Método no soportado'];
			echo json_encode($response);
		}
	}

	if ($TARGET == 'roulette') {

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {

			$SQL_ROULETTE = $con->prepare(
				'SELECT * FROM '
					. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_SCHEMA']
					. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_TABLE'] . ' ORDER BY '
					. $DB_TABLE['ROULETTE_TABLE']['ROULETTE_ID'][$DB_TYPE] . ' DESC LIMIT 6'
			);
			$SQL_ROULETTE->execute();

			$RES = $SQL_ROULETTE->fetchAll(PDO::FETCH_OBJ);

			$response = ['message' => 'success', 'data' => $RES];
			echo json_encode($response);
		} else {

			http_response_code(405);
			$response = ['error' => 'Método no soportado'];
			echo json_encode($response);
		}
	}
}
