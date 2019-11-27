<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4>A guardar o novo Tipo de Registo</h4>
        <?php
            require 'sequenceOps.php';

            $userid = $_SESSION['userid'];
            $regType = $_REQUEST['tipo_reg'];

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Verify if the Reg_Type exists for the user.
                $sql = "SELECT typecnt, nome, ativo
                        FROM tipo_registo
                        WHERE nome = '$regType'
                        AND userid = $userid;";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $regTypeDB = $object->nome;
                $typeid = $object->typecnt;
                $active = $object->activo;

                if ($regTypeDB == NULL) {

                    $sql = "SELECT typecnt
                            FROM tipo_registo
                            WHERE
                            typecnt >= ALL (
                                SELECT typecnt
                                FROM tipo_registo);";

                    $result = $database->query($sql);
                    $object = $result->fetchObject();
                    $typecnt = $object->typecnt;
                    $typecnt = $typecnt + 1;

                    //Add a new Sequence Number for the new type.
                    $idseq = createSequenceNumber($database, $userid);

                    //Add the new RegType.
                    $sql = "INSERT INTO tipo_registo(userid, typecnt, nome, ativo, idseq)
                            VALUES($userid, $typecnt, '$regType', 1, $idseq);";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query("commit;");

                } elseif ($regTypeDB != NULL && $active != 1) {

                    //Reactivate registry type.
                    $sql = "UPDATE tipo_registo
                            SET ativo = 1
                            WHERE nome = '$regType'
                            AND userid = $userid;";

                    $setup_regType_fields = "UPDATE campo
                                             SET ativo = 1
                                             WHERE userid = $userid
                                             AND typecnt = $typeid;";

                    //Reactivate all registries of this type.
                    $setup_reg_sql = "UPDATE registo
                                      SET ativo = 1
                                      WHERE userid = $userid
                                      AND typecounter = $typeid;";

                    //Reactivate all page entries of this type.
                    $setup_reg_pag_sql = "UPDATE reg_pag
                                          SET ativa = 1
                                          WHERE userid = $userid
                                          AND typeid = $typeid;";

                    $database->query("start transaction;");
                    $database->query($sql);
                    $database->query($setup_reg_sql);
                    $database->query($setup_reg_pag_sql);
                    $database->query($setup_regType_fields);
                    $database->query("commit;");

                    //Return to homepage.
                    $url = "http://web.ist.utl.pt/";
                    $url .= $user;
                    $url .= "/app.html";
                    header("Location:$url");
                }

                //Release database.
                $database = NULL;
            } catch (PDOException $exception) {
                echo("<p>{$exception->getMessage()}</p>");
                $database->query("rollback;");
            }
        ?>
    </body>
</html>
