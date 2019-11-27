<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> A página está a ser removida. </h4>
        <?php

            $userid = $_SESSION['userid'];
            $pageName = $_REQUEST['d_nome'];

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user,
                                     $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get pageid.
                $sql = "SELECT pagecounter
                        FROM pagina
                        WHERE nome = '$pageName'
                        AND userid = $userid;";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $pageid = $object->pagecounter;


                if ($pageid != NULL) {

                    //Deactivate page.
                    $sql = "UPDATE pagina
                            SET ativa = 0
                            WHERE nome = '$pageName'
                            AND userid = $userid;";

                    //Deactivate all page registries.
                    $cleanup_sql = "UPDATE reg_pag
                            SET ativa = 0
                            WHERE pageid = $pageid
                            AND userid = $userid;";

                    $database->query("start transaction;");
                    $database->query($cleanup_sql);
                    $database->query($sql);
                    $database->query("commit;");

                    //Return to homepage.
                    $url = "http://web.ist.utl.pt/";
                    $url .= $user;
                    $url .= "/app.html";
                    header("Location:$url");

                } else {
                    echo("<p> As informações inseridas não são válidas. </p>");
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
