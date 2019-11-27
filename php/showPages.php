<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/mainStyle.css">
    </head>
    <body>
        <h2> Páginas </h2>
        <?php
            $userid = $_SESSION['userid'];
            $pageName = $_REQUEST['page_name'];

            echo("<h4> Pagina: $pageName</h4>");

            try {
                require 'userOps.php';

                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                     $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //Fetch page id.
                    $sql = "SELECT pagecounter 
                            FROM pagina 
                            WHERE userid = $userid 
                            AND nome = '$pageName'
                            AND ativa = 1;";
                    $result = $database->query($sql);
                    $object = $result->fetchObject();
                    $pageid = $object->pagecounter;
                        

                if ($pageid != NULL) {
                    //Get registers from page.
                    $sql = "SELECT nome, regid, userid 
                            FROM (SELECT R.nome, RP.regid, RP.userid, RP.pageid
                                  FROM reg_pag as RP join registo as R 
                                  WHERE RP.regid = R.regcounter
                                  AND RP.typeid = R.typecounter
                                  AND RP.userid = R.userid
                                  AND RP.ativa = 1
                                  AND R.ativo = 1
                                  GROUP BY RP.regid) as op_table
                            WHERE pageid = $pageid
                            AND userid = $userid;";

                    $result = $database->query($sql);

                    echo("<table>");
                    echo("<tr>");
                    echo("<td> Registo </td>");
                    echo("<td> Nº de Registo </td>");
                    echo("<td> User ID </td>");
                    echo("</tr>");

                    foreach($result as $row) {
                        echo("<tr>");
                        echo("<td> {$row['nome']} </td>");
                        echo("<td> {$row['regid']} </td>");
                        echo("<td> {$row['userid']} </td>");
                        echo("</tr>");
                    }
                    echo("</table>");
                } else {
                    echo("<p> O utilizador não têm a página pretendida. </p>");
                }

                //Release database.
                $database = NULL;

            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
            }
        ?>
    </body>
</html>