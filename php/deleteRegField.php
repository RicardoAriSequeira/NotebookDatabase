<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> A remover o campo do registo </h4>
        <?php
            require 'regTypeOps.php';

            $userid = $_SESSION['userid'];
            $regType = $_REQUEST['reg_type'];
            $fieldName = $_REQUEST['field_name'];

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get typecnt
                $typecnt = getTypeCnt($database, $userid, $regType);

                //Get campo id.
                $sql = "SELECT campocnt
                        FROM campo
                        WHERE userid = $userid
                        AND nome = '$fieldName';";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $campoid = $object->campocnt;

                if ($campoid != NULL) {

                    //Remove campo.
                    $sql = "UPDATE campo
                            SET ativo = 0
                            WHERE userid = $userid
                            AND typecnt = $typecnt
                            AND nome = '$fieldName';";

                    //Remove value from field.
                    $value_cleanup_sql = "UPDATE valor
                                          SET ativo = 0
                                          WHERE userid = $userid
                                          AND campoid = $campoid
                                          AND typeid = $typecnt;";

                    $database->query("start transaction;");
                    $database->query($value_cleanup_sql);
                    $database->query($sql);
                    $database->query("commit;");

                    //Return to homepage.
                    $url = "http://web.ist.utl.pt/";
                    $url .= $user;
                    $url .= "/app.html";
                    header("Location:$url");

                } else {
                    echo("Os dados introduzidos não são válidos. </p>");
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
