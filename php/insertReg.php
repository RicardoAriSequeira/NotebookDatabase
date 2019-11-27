<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> O Registo está guardado </h4>
        <?php
            require 'pageOps.php';
            require 'regTypeOps.php';
            require 'sequenceOps.php';

            $regFieldValues = array();
            $testArray = array("page_name", "reg_type", "reg");

            $userid = $_SESSION['userid'];
            $pageName = $_REQUEST['page_name'];
            $regType = $_REQUEST['reg_type'];
            $regName = $_REQUEST['reg'];

            //Support arrays.
            $regFieldValues = array();
            $regFieldNames = array();

            foreach($_REQUEST as $key=>$value) {
                if (!in_array($key, $testArray)) {
                    array_push($regFieldNames, $key);
                    array_push($regFieldValues, $value);
                }
            }

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                    $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get pageid
                $pageid = getPageId($database, $userid, $pageName);

                //Get typeid
                $typeid = getTypeCnt($database, $userid, $regType);

                $regFieldIds = array();

                $sql = "SELECT typecnt, campocnt
                        FROM campo
                        WHERE userid = $userid
                        AND typecnt = $typeid;";

                $result = $database->query($sql);
                foreach($result as $row) {
                    array_push($regFieldIds, $row['campocnt']);
                }

                //Verify if the registry already exists.
                $sql = "SELECT regcounter
                        FROM registo
                        WHERE userid = $userid
                        AND nome = '$regName'
                        AND typecounter = $typeid
                        AND ativo = 1;";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $regid = $object->regcounter;

                if ($regid != NULL) {
                    //Create a new one a deactivate the old one along with
                    //all its associations.

                    //Deactivate all values associated with the old registry.
                    $value_cleanup_sql = "UPDATE valor
                                          SET ativo = 0
                                          WHERE regid = $regid
                                          AND typeid = $typeid
                                          AND userid = $userid;";

                    //Deactivate all reg_pag entries associated with the registry.
                    $reg_pag_cleanup_sql = "UPDATE reg_pag
                                            SET ativa = 0
                                            WHERE regid = $regid
                                            AND typeid = $typeid
                                            AND userid = $userid;";

                    //Deactivate old registry.
                    $sql = "UPDATE registo
                            SET ativo = 0
                            WHERE regcounter = $regid
                            AND userid = $userid
                            AND typecounter = $typeid;";

                    $database->query("start transaction;");
                    $database->query($value_cleanup_sql);
                    $database->query($reg_pag_cleanup_sql);
                    $database->query($sql);
                    $database->query("commit;");
                }

                //Create the new registry.

                $reg_idseq = createSequenceNumber($database, $userid);

                //Get regcounter.
                $sql = "SELECT regcounter
                        FROM registo
                        WHERE regcounter >= all (
                            SELECT regcounter
                            FROM registo);";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $regcounter = $object->regcounter;

                $regcounter = $regcounter + 1;

                //Create the new registry.
                $create_reg_sql = "INSERT INTO registo(userid, typecounter, regcounter, nome, ativo, idseq)
                                   VALUES($userid, $typeid, $regcounter, '$regName', 1, $reg_idseq);";

                $database->query("start transaction;");

                $database->query($create_reg_sql);

                //Create the value entries.
                for ($iter = 0; $iter < count($regFieldNames); $iter++) {
                    $field_idseq = createSequenceNumber($database, $userid);

                    $fieldValue = $regFieldValues[$iter];
                    $fieldId = $regFieldIds[$iter];
                    echo("FIELD VALUE: $fieldValue FIELD ID: $fieldId");
                    $sql = "INSERT INTO valor(userid, typeid, regid, campoid, valor, idseq, ativo)
                            VALUES($userid, $typeid, $regcounter, $fieldId, '$fieldValue', $field_idseq, 1);";

                    $database->query($sql);
                }

                //Get new regid.
                $sql = "SELECT regcounter
                        FROM registo
                        WHERE userid = $userid
                        AND typecounter = $typeid
                        AND nome = '$regName';";

                $result = $database->query($sql);
                $object = $result->fetchObject();
                $newRegId = $object->regcounter;

                //Create the reg_pag entry.
                $rp_idseq = createSequenceNumber($database, $userid);
                $reg_pag_update_sql = "INSERT INTO reg_pag(userid, pageid, typeid, regid, idseq, ativa)
                                       VALUES($userid, $pageid, $typeid, $newRegId, $rp_idseq, 1);";
                $database->query($reg_pag_update_sql);

                $database->query("commit;");

                //Return to homepage.
                $url = "http://web.ist.utl.pt/";
                $url .= $user;
                $url .= "/app.html";
                header("Location:$url");

                //Release database.
                $database = NULL;

            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
                $database->query("rollback;");
            }
        ?>
    </body>
</html>
