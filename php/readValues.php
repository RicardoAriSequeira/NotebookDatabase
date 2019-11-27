<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h4> Por favor preencha os campos em branco. </h4>
        <?php
            require 'pageOps.php';
            require 'regTypeOps.php';

            $userid = $_SESSION['userid'];
            $pageName = $_REQUEST['page_name'];
            $regType = $_REQUEST['reg_type'];

            try {
                $host = "db.ist.utl.pt";
                $user = $_SESSION['db_user'];
                $password = $_SESSION['db_password'];
                $dbname = $user;
                $database = new PDO("mysql:host=$host;dbname=$dbname",
                                    $user, $password);
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Get typeid.
                $typeid = getTypeCnt($database, $userid, $regType);

                //Get pageid.
                $pageid = getPageId($database, $userid, $pageName);

                if ($typeid != NULL && $pageid != NULL) {
                      //Get reg_type fields.
                    $sql = "SELECT nome
                            FROM campo
                            WHERE userid = $userid
                            AND typecnt = $typeid;";

                    $result = $database->query($sql);

                    //Readvalues from user and post them.
                    echo("<form action='insertReg.php' method='post'>");
                    echo("<table>");
                    echo("<tr>");
                    echo("<td> Página </td>");
                    echo("<td> <input type='text' name='page_name' value='$pageName' </td>");
                    echo("</tr>");
                    echo("<tr>");
                    echo("<td> Tipo de Registo </td>");
                    echo("<td> <input type='text' name='reg_type' value='$regType'> </td>");
                    echo("</tr>");
                    echo("<tr>");
                    echo("<td> Registo </td>");
                    echo("<td> <input type='text' name='reg'");
                    echo("</tr>");

                    foreach($result as $row) {
                        echo("<tr>");
                        echo("<td> {$row['nome']} </td>");
                        echo("<td> <input type='text' name='{$row['nome']}'");
                        echo("</tr>");
                    }
                    echo("<tr> <td></td> <td> <input type='submit' value='Save'> </td> </tr>");
                    echo("</table>");
                    echo("</form>");
                } else {
                    echo("<p> Os dados introduzidos não são válidos. </p>");
                }

                //Release database
                $database = NULL;

            } catch (PDOException $exception) {
                echo("<p> {$exception->getMessage()} </p>");
            }
        ?>
    </body>
</html>
