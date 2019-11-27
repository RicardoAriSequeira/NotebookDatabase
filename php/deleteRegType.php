<?php
    session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> O tipo de registo está a ser removido </h4>
        <?php

            $userid = $_SESSION['userid'];
            $regType = $_REQUEST['reg_type'];

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user,
                                     $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get typecnt.
                $sql = "SELECT typecnt 
                        FROM tipo_registo 
                        WHERE nome = '$regType';";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $typecnt = $object->typecnt;

                if ($userid != NULL && $typecnt != NULL) {
                    //Delete all fields of this type.
                    $sql = "UPDATE campo 
                            SET ativo = 0 
                            WHERE typecnt = $typecnt;";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query("commit");
                    
                    //Rsove RegType.
                    $sql = "UPDATE tipo_registo 
                            SET ativo = 0
                            WHERE nome = '$regType';";

                    //Remove all registries of this type from registo table.
                    $reg_cleanup_sql = "UPDATE registo
                                        SET ativo = 0
                                        WHERE typecounter = $typecnt
                                        AND userid = $userid;";

                    //Remove all registries of this type from reg_pag table.
                    $reg_pag_cleanup_sql = "UPDATE reg_pag
                                            SET ativa = 0
                                            WHERE typeid = $typecnt
                                            AND userid = $userid;";
                            
                    $database->query("start transaction;");
                    $database->query($reg_pag_cleanup_sql);
                    $database->query($reg_cleanup_sql);
                    $database->query($sql);
                    $database->query("commit;");

                    //Return to homepage.
                    $url = "http://web.ist.utl.pt/";
                    $url .= $user;
                    $url .= "/app.html";
                    header("Location:$url");

                } else {
                    echo("<p> As informações inseridas não são válidas.</p>");
                }

                //Release database.
                $database = NULL;

            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
                $database->query("rollback;");
            }
        ?>
    </body>
</html>