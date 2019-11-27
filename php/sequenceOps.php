<html>
    <body>
        <?php
            function createSequenceNumber($database, $userid) {
                $moment = date("Y-m-d G:i:s");
                $sql = "INSERT INTO sequencia(moment, userid)
                        VALUES('$moment', $userid);";
                $database->query("start transaction;");
                $database->query($sql);
                $database->query("commit;");

                //Get sequence Number
                $sql = "SELECT contador_sequencia 
                        FROM sequencia
                        WHERE userid = $userid 
                        AND moment = '$moment';";
                $result = $database->query($sql);
                $object = $result->fetchObject();
                $idseq = $object->contador_sequencia;
                return $idseq;
            }
        ?>
    </body>
</html>