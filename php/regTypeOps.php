<html>
    <body>
        <?php
            function getTypeCnt($database, $userid, $regType) {
                $sql = "SELECT typecnt 
                        FROM tipo_registo
                        WHERE userid = $userid 
                        AND nome = '$regType';";
                $result = $database->query($sql);
                $object = $result->fetchObject();
                $typecnt = $object->typecnt;
                return $typecnt;
            }
        ?>
    </body>
</html>