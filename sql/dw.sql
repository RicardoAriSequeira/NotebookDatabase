USE ist170489;

DROP TABLE IF EXISTS login_attempts;
DROP TABLE IF EXISTS d_utilizador;
DROP TABLE IF EXISTS d_tempo;

CREATE TABLE d_utilizador (
	email VARCHAR(255) NOT NULL,
	nome VARCHAR(255) NOT NULL,
	pais VARCHAR(45) NOT NULL,
	categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (email)
);

CREATE TABLE d_tempo (
	contador_login INT NOT NULL,
	dia INT(8) NOT NULL,
	mes INT(8) NOT NULL,
	ano INT(8) NOT NULL,
PRIMARY KEY (contador_login)
);

CREATE TABLE login_attempts (
	email VARCHAR (255) NOT NULL,
	contador_login INT NOT NULL,
	sucesso TINYINT(1) NOT NULL,
FOREIGN KEY(email) REFERENCES d_utilizador(email),
FOREIGN KEY (contador_login) REFERENCES d_tempo(contador_login)
);

INSERT INTO d_utilizador SELECT email, nome, pais, categoria FROM utilizador GROUP BY userid;
INSERT INTO d_tempo SELECT contador_login, DATE_FORMAT(moment, '%d'), DATE_FORMAT(moment, '%m'), DATE_FORMAT(moment, '%Y') FROM login;
INSERT INTO login_attempts SELECT email, contador_login, sucesso
						   FROM login AS L 
						   JOIN utilizador AS U
						   WHERE L.userid = U.userid;
