<html>
	<body>
        <?php
            function getUserId($database, $email) {
                $sql = "SELECT userid 
                        FROM utilizador 
                        WHERE email = '$email';";
                $result = $database->query($sql);
                $object = $result->fetchObject();
                $userid = $object->userid;
                return $userid;
            }
        ?>
	</body>
</html>
