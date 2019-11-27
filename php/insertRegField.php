<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> O novo campo do registo está a ser inserido </h4>
        <?php
            require 'regTypeOps.php';
            require 'sequenceOps.php';

            $userid = $_SESSION['userid'];
            $fieldName = $_REQUEST['field_name'];
            $regType = $_REQUEST['reg_type'];

            //IMPORVE CODE FOR NULL RETURNS, TEST AND VERIFY

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get typecnt.
                $typecnt = getTypeCnt($database, $userid, $regType);

                //Verify if the field already exists.
                $sql = "SELECT campocnt, typecnt, nome, ativo
                        FROM campo
                        WHERE typecnt = $typecnt
                        AND userid = $userid
                        AND nome = '$fieldName';";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $campoid = $object->campocnt;
                $typeid = $object->typecnt;
                $dbFieldName = $object->nome;
                $active = $object->ativo;

                if($dbFieldName == NULL) {
                    //Create new sequence number.
                    $idseq = createSequenceNumber($database, $userid);

                     //Get campocnt.
                    $sql = "SELECT campocnt
                            FROM campo
                            WHERE campocnt >= ALL (
                                SELECT campocnt
                                FROM campo);";

                    $result = $database->query($sql);
                    $object = $result->fetchObject();
                    $campocnt = $object->campocnt;
                    $campocnt = $campocnt + 1;

                    //Insert new registry field.
                    $sql = "INSERT INTO campo(userid, typecnt, campocnt, idseq, ativo, nome)
                            VALUES($userid, $typecnt, $campocnt, $idseq, 1, '$fieldName');";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query("commit;");

                } elseif ($dbFieldName != NULL && $active != 1) {
                    //Reactivate registry field.
                    $sql = "UPDATE campo
                            SET ativo = 1
                            WHERE userid = $userid
                            AND typecnt = $typecnt
                            AND nome = '$fieldName';";

                    //Reactivate all field values.
                    $value_setup_sql = "UPDATE valor
                                        SET ativo = 1
                                        WHERE userid = $userid
                                        AND campoid = $campoid
                                        AND typeid = $typeid;";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query($value_setup_sql);
                    $database->query("commit;");

                    //Return to homepage.
                    $url = "http://web.ist.utl.pt/";
                    $url .= $user;
                    $url .= "/app.html";
                    header("Location:$url");

                }

                //Release database.
                $databse = NULL;
            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
                $database->query("rollback;");
            }
        ?>
    </body>
</html>
