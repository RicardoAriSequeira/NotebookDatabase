<?php
	//Destroy any old session.
	session_destroy();

	//Start a new session.
	session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<h4> Login In <h4>
		<?php
			require 'userOps.php';

			$email = $_REQUEST['email'];
			$userPassword = $_REQUEST['user_pass'];

			try {
				$host = "db.ist.utl.pt";
				$user = "ist170489";
				$password = "xniq7401";
				$dbname = $user;
				$database = new PDO("mysql:host=$host;dbname=$dbname",
									$user, $password);
				$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Send user and password for database connection to all files.
				$_SESSION['db_user'] = $user;
				$_SESSION['db_password'] = $password;

				//Get userid.
				$userid = getUserId($database, $email);

				//Set variables for the session.
				$_SESSION['userid'] = $userid;
				$_SESSION['email'] = $email;

				if ($userid != NULL) {
					$sql = "SELECT password
							FROM utilizador
							WHERE userid = $userid;";

					$result = $database->query($sql);					
					$object = $result->fetchObject();
					$dbUserPassword = $object->password;

					$moment = date("Y-m-d G:i:s");

					if (strcmp($dbUserPassword, $userPassword) == 0) {
						$update_login_sql = "INSERT INTO login(userid, sucesso, moment)
											 VALUES($userid, 1, '$moment');";

						$database->query("start transaction;");
						$database->query($update_login_sql);
						$database->query("commit;");

						$url = "http://web.ist.utl.pt/";
						$url .= $user;
						$url .= "/app.html";
						header("Location:$url");
					} else {
						$update_login_sql = "INSERT INTO login(userid, sucesso, moment)
											 VALUES($userid, 0, '$moment');";

						$database->query("start transaction;");
						$database->query($update_login_sql);
						$database->query("commit;");
						echo("<p> A password inserida não é válida. </p>");
					}
				} else {
					echo("<p> O utilizador introduzido não existe </p>");
				}
			} catch (PDOException $exception) {
				echo("<p> {$exception->getMessage()} </p>");
				$database->query("rollback;");
			}
		?>
	</body>
</html>