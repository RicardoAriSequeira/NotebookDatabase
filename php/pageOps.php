<html>
    <body>
        <?php
            function getPage($database, $userid, $pageName) {
                $sql = "SELECT * 
                        FROM pagina 
                        WHERE nome = '$pageName'
                        AND userid = $userid;";
                $result = $database->query($sql);
                $object = $result->fecthObject();
                return object;
            }

            function getPageId($database, $userid, $pageName) {
                $sql = "SELECT pagecounter
                        FROM pagina
                        WHERE userid = $userid
                        AND nome = '$pageName';";
                $result = $database->query($sql);
                $pageObject = $result->fetchObject();
                $pageid = $pageObject->pagecounter;
                return $pageid;
            }

            function getPageContents($database, $userid, $pageName) {
                $pageObject = getPage($database, $userid, $pageName);
                $pageId = $pageObject->pagecounter;
                $sql = "SELECT R.nome, R.regcounter 
                        FROM registo AS R, pagina AS P, reg_pag AS RP
                        WHERE R.userid = P.userid 
                        AND R.userid = RP.userid 
                        AND RP.pageid = $pageId;";
                $result = $database->query($sql);

                //Build the array for the front end.
                $contensArray = array();
                foreach($result as $row) {
                    array_push($contentsArray, $row['nome']);
                }
                return $contentsArray;
            }
        ?>
    </body>
</html>
