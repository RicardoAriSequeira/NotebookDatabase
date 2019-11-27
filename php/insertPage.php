<?php
    session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
	</head>
    <body>
        <h4> A página está a ser guardada. </h4>
        <?php
            require 'sequenceOps.php';

            $pageName = $_REQUEST['nome'];
            $userid = $_SESSION['userid'];

            try {
    			$host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user,
                                     $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get PageCounter value.
    			$sql = "SELECT pagecounter
                        FROM pagina
    					WHERE pagecounter >= ALL (
                            SELECT pagecounter
    					    FROM pagina);";
    			$pagecounter = 0;
    			$result = $database->query($sql);
    			$object = $result->fetchObject();
                $pagecounter = $object->pagecounter;

                //Verify if page exists.
    			$sql = "SELECT nome, ativa
                        FROM pagina
                        WHERE nome = '$pageName'
                        AND userid = $userid;";
    			$result = $database->query($sql);
    			$object = $result->fetchObject();
                $pageFromDatabase = $object->nome;
                $active = $object->ativa;

                if ($pageFromDatabase == NULL) {

                    $pagecounter = $pagecounter + 1;

                    //Create new Sequence Number.
                    $idseq = createSequenceNumber($database, $userid);

                    //Insert new page.
                    $sql = "INSERT INTO pagina(userid, pagecounter, nome, idseq, ativa)
                            VALUES($userid, $pagecounter, '$pageName', $idseq, 1);";

                    $database->query("start transaction;");
                    $database->query($sql);
    				$database->query("commit;");

                } elseif ($pageFromDatabase != NULL && $active != 1) {

                    //Reactivate page.
                    $sql = "UPDATE pagina SET ativa = 1
                            WHERE nome = '$pageName'
                            AND userid = $userid;";
s
                    //Setup old registries.
                    $setup_reg_sql = "UPDATE reg_pag
                                      SET ativa = 1
                                      WHERE userid = $userid
                                      AND pageid = $pagecounter;";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query($setup_reg_sql);
                    $database->query("commit;");

                }

                //Return to homepage.
                $url = "http://web.ist.utl.pt/";
                $url .= $user;
                $url .= "/app.html";
                header("Location:$url");

                //Release Database.
                $database = NULL;

            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
    			$database->query("rollback;");
            }
        ?>
    </body>
</html>
