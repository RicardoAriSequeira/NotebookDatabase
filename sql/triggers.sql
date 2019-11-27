DROP TRIGGER idSeqTestForTR;
DROP TRIGGER idSeqTestForP;
DROP TRIGGER idSeqTestForC;
DROP TRIGGER idSeqTestForR;
DROP TRIGGER idSeqTestForV;

delimiter //
CREATE TRIGGER idSeqTestForTR BEFORE INSERT ON tipo_registo
FOR EACH ROW
BEGIN
	DECLARE tr_max INT;
	DECLARE page_max INT;
	DECLARE field_max INT;
	DECLARE reg_max INT;
	DECLARE value_max INT;
	DECLARE support_idseq INT;
	DECLARE seq INT;
	DECLARE momentForSeq TIMESTAMP;

	/* Obtem o maior valor de idseq de tipo_registo. */
	SELECT idseq
	INTO tr_max
	FROM tipo_registo
	WHERE idseq >= all (
		SELECT idseq
		FROM tipo_registo);

	IF(tr_max > NEW.idseq)
	THEN
		SET support_idseq = tr_max;
	END IF;

	/* Obtem o maior valor de idseq de pagina. */
	SELECT idseq
	INTO page_max
	FROM pagina
	WHERE idseq >= all (
		SELECT idseq
		FROM pagina);

	IF(page_max > support_idseq OR page_max > NEW.idseq)
	THEN
		SET support_idseq = page_max;
	END IF;

	/* Obtem o maior valor de idseq de campo. */
	SELECT idseq
	INTO field_max
	FROM campo
	WHERE idseq >= all (
		SELECT idseq
		FROM campo);

	IF (field_max > support_idseq OR field_max > NEW.idseq)
	THEN
		SET support_idseq = field_max;
	END IF;

	/* Obtem o maior valor de idseq de registo. */
	SELECT idseq
	INTO reg_max
	FROM registo
	WHERE idseq >= all (
		SELECT idseq
		FROM registo);

	IF (reg_max > support_idseq OR reg_max > NEW.idseq)
	THEN
		SET support_idseq = reg_max;
	END IF;

	/* Obtem o maior valor de idseq de valor. */

	SELECT idseq
	INTO value_max
	FROM valor
	WHERE idseq >= all (
		SELECT idseq
		FROM valor);

	IF (value_max > support_idseq OR value_max > NEW.idseq)
	THEN
		SET support_idseq = value_max;
	END IF;

	SELECT contador_sequencia
	INTO seq
	FROM sequencia
	WHERE contador_sequencia >= all (
		SELECT contador_sequencia
		FROM sequencia);

	IF (seq > support_idseq OR seq > NEW.idseq)
	THEN
		support_idseq = seq;
	END IF;

	SET support_idseq = support_idseq + 1;

	SET NEW.idseq = support_idseq;

	INSERT INTO sequencia(userid, contador_sequencia, moment)
	VALUES (NEW.userid, support_idseq, momentForSeq);
END;

CREATE TRIGGER idSeqTestForP BEFORE INSERT ON pagina
FOR EACH ROW
BEGIN
	DECLARE tr_max INT;
	DECLARE page_max INT;
	DECLARE field_max INT;
	DECLARE reg_max INT;
	DECLARE value_max INT;
	DECLARE support_idseq INT;
	DECLARE seq INT;
	DECLARE momentForSeq TIMESTAMP;

	/* Obtem o maior valor de idseq de tipo_registo. */
	SELECT idseq
	INTO tr_max
	FROM tipo_registo
	WHERE idseq >= all (
		SELECT idseq
		FROM tipo_registo);

	IF(tr_max > NEW.idseq)
	THEN
		SET support_idseq = tr_max;
	END IF;

	/* Obtem o maior valor de idseq de pagina. */
	SELECT idseq
	INTO page_max
	FROM pagina
	WHERE idseq >= all (
		SELECT idseq
		FROM pagina);

	IF(page_max > support_idseq OR page_max > NEW.idseq)
	THEN
		SET support_idseq = page_max;
	END IF;

	/* Obtem o maior valor de idseq de campo. */
	SELECT idseq
	INTO field_max
	FROM campo
	WHERE idseq >= all (
		SELECT idseq
		FROM campo);

	IF (field_max > support_idseq OR field_max > NEW.idseq)
	THEN
		SET support_idseq = field_max;
	END IF;

	/* Obtem o maior valor de idseq de registo. */
	SELECT idseq
	INTO reg_max
	FROM registo
	WHERE idseq >= all (
		SELECT idseq
		FROM registo);

	IF (reg_max > support_idseq OR reg_max > NEW.idseq)
	THEN
		SET support_idseq = reg_max;
	END IF;

	/* Obtem o maior valor de idseq de valor. */

	SELECT idseq
	INTO value_max
	FROM valor
	WHERE idseq >= all (
		SELECT idseq
		FROM valor);

	IF (value_max > support_idseq OR value_max > NEW.idseq)
	THEN
		SET support_idseq = value_max;
	END IF;

	SELECT contador_sequencia
	INTO seq
	FROM sequencia
	WHERE contador_sequencia >= all (
		SELECT contador_sequencia
		FROM sequencia);

	IF (seq > support_idseq OR seq > NEW.idseq)
	THEN
		support_idseq = seq;
	END IF;

	SET support_idseq = support_idseq + 1;

	SET NEW.idseq = support_idseq;

	INSERT INTO sequencia(userid, contador_sequencia, moment)
	VALUES (NEW.userid, support_idseq, momentForSeq);
END;

CREATE TRIGGER idSeqTestForC BEFORE INSERT ON campo
FOR EACH ROW
BEGIN
	DECLARE tr_max INT;
	DECLARE page_max INT;
	DECLARE field_max INT;
	DECLARE reg_max INT;
	DECLARE value_max INT;
	DECLARE support_idseq INT;
	DECLARE seq INT;
	DECLARE momentForSeq TIMESTAMP;

	/* Obtem o maior valor de idseq de tipo_registo. */
	SELECT idseq
	INTO tr_max
	FROM tipo_registo
	WHERE idseq >= all (
		SELECT idseq
		FROM tipo_registo);

	IF(tr_max > NEW.idseq)
	THEN
		SET support_idseq = tr_max;
	END IF;

	/* Obtem o maior valor de idseq de pagina. */
	SELECT idseq
	INTO page_max
	FROM pagina
	WHERE idseq >= all (
		SELECT idseq
		FROM pagina);

	IF(page_max > support_idseq OR page_max > NEW.idseq)
	THEN
		SET support_idseq = page_max;
	END IF;

	/* Obtem o maior valor de idseq de campo. */
	SELECT idseq
	INTO field_max
	FROM campo
	WHERE idseq >= all (
		SELECT idseq
		FROM campo);

	IF (field_max > support_idseq OR field_max > NEW.idseq)
	THEN
		SET support_idseq = field_max;
	END IF;

	/* Obtem o maior valor de idseq de registo. */
	SELECT idseq
	INTO reg_max
	FROM registo
	WHERE idseq >= all (
		SELECT idseq
		FROM registo);

	IF (reg_max > support_idseq OR reg_max > NEW.idseq)
	THEN
		SET support_idseq = reg_max;
	END IF;

	/* Obtem o maior valor de idseq de valor. */

	SELECT idseq
	INTO value_max
	FROM valor
	WHERE idseq >= all (
		SELECT idseq
		FROM valor);

	IF (value_max > support_idseq OR value_max > NEW.idseq)
	THEN
		SET support_idseq = value_max;
	END IF;

	SELECT contador_sequencia
	INTO seq
	FROM sequencia
	WHERE contador_sequencia >= all (
		SELECT contador_sequencia
		FROM sequencia);

	IF (seq > support_idseq OR seq > NEW.idseq)
	THEN
		support_idseq = seq;
	END IF;

	SET support_idseq = support_idseq + 1;

	SET NEW.idseq = support_idseq;

	INSERT INTO sequencia(userid, contador_sequencia, moment)
	VALUES (NEW.userid, support_idseq, momentForSeq);
END;

CREATE TRIGGER idSeqTestForR BEFORE INSERT ON registo
FOR EACH ROW
BEGIN
	DECLARE tr_max INT;
	DECLARE page_max INT;
	DECLARE field_max INT;
	DECLARE reg_max INT;
	DECLARE value_max INT;
	DECLARE support_idseq INT;
	DECLARE seq INT;
	DECLARE momentForSeq TIMESTAMP;

	/* Obtem o maior valor de idseq de tipo_registo. */
	SELECT idseq
	INTO tr_max
	FROM tipo_registo
	WHERE idseq >= all (
		SELECT idseq
		FROM tipo_registo);

	IF(tr_max > NEW.idseq)
	THEN
		SET support_idseq = tr_max;
	END IF;

	/* Obtem o maior valor de idseq de pagina. */
	SELECT idseq
	INTO page_max
	FROM pagina
	WHERE idseq >= all (
		SELECT idseq
		FROM pagina);

	IF(page_max > support_idseq OR page_max > NEW.idseq)
	THEN
		SET support_idseq = page_max;
	END IF;

	/* Obtem o maior valor de idseq de campo. */
	SELECT idseq
	INTO field_max
	FROM campo
	WHERE idseq >= all (
		SELECT idseq
		FROM campo);

	IF (field_max > support_idseq OR field_max > NEW.idseq)
	THEN
		SET support_idseq = field_max;
	END IF;

	/* Obtem o maior valor de idseq de registo. */
	SELECT idseq
	INTO reg_max
	FROM registo
	WHERE idseq >= all (
		SELECT idseq
		FROM registo);

	IF (reg_max > support_idseq OR reg_max > NEW.idseq)
	THEN
		SET support_idseq = reg_max;
	END IF;

	/* Obtem o maior valor de idseq de valor. */

	SELECT idseq
	INTO value_max
	FROM valor
	WHERE idseq >= all (
		SELECT idseq
		FROM valor);

	IF (value_max > support_idseq OR value_max > NEW.idseq)
	THEN
		SET support_idseq = value_max;
	END IF;

	SELECT contador_sequencia
	INTO seq
	FROM sequencia
	WHERE contador_sequencia >= all (
		SELECT contador_sequencia
		FROM sequencia);

	IF (seq > support_idseq OR seq > NEW.idseq)
	THEN
		support_idseq = seq;
	END IF;

	SET support_idseq = support_idseq + 1;

	SET NEW.idseq = support_idseq;

	INSERT INTO sequencia(userid, contador_sequencia, moment)
	VALUES (NEW.userid, support_idseq, momentForSeq);
END;

CREATE TRIGGER idSeqTestForV BEFORE INSERT ON valor
FOR EACH ROW
BEGIN
	DECLARE tr_max INT;
	DECLARE page_max INT;
	DECLARE field_max INT;
	DECLARE reg_max INT;
	DECLARE value_max INT;
	DECLARE support_idseq INT;
	DECLARE seq INT;
	DECLARE momentForSeq TIMESTAMP;

	/* Obtem o maior valor de idseq de tipo_registo. */
	SELECT idseq
	INTO tr_max
	FROM tipo_registo
	WHERE idseq >= all (
		SELECT idseq
		FROM tipo_registo);

	IF(tr_max > NEW.idseq)
	THEN
		SET support_idseq = tr_max;
	END IF;

	/* Obtem o maior valor de idseq de pagina. */
	SELECT idseq
	INTO page_max
	FROM pagina
	WHERE idseq >= all (
		SELECT idseq
		FROM pagina);

	IF(page_max > support_idseq OR page_max > NEW.idseq)
	THEN
		SET support_idseq = page_max;
	END IF;

	/* Obtem o maior valor de idseq de campo. */
	SELECT idseq
	INTO field_max
	FROM campo
	WHERE idseq >= all (
		SELECT idseq
		FROM campo);

	IF (field_max > support_idseq OR field_max > NEW.idseq)
	THEN
		SET support_idseq = field_max;
	END IF;

	/* Obtem o maior valor de idseq de registo. */
	SELECT idseq
	INTO reg_max
	FROM registo
	WHERE idseq >= all (
		SELECT idseq
		FROM registo);

	IF (reg_max > support_idseq OR reg_max > NEW.idseq)
	THEN
		SET support_idseq = reg_max;
	END IF;

	/* Obtem o maior valor de idseq de valor. */

	SELECT idseq
	INTO value_max
	FROM valor
	WHERE idseq >= all (
		SELECT idseq
		FROM valor);

	IF (value_max > support_idseq OR value_max > NEW.idseq)
	THEN
		SET support_idseq = value_max;
	END IF;

	/* Obtem o maior valor de idseq da sequencia */

	SELECT contador_sequencia
	INTO seq
	FROM sequencia
	WHERE contador_sequencia >= all (
		SELECT contador_sequencia
		FROM sequencia);

	IF (seq > support_idseq OR seq > NEW.idseq)
	THEN
		support_idseq = seq;
	END IF;

	SET support_idseq = support_idseq + 1;

	SET NEW.idseq = support_idseq;

	INSERT INTO sequencia(userid, contador_sequencia, moment)
	VALUES (NEW.userid, support_idseq, momentForSeq);
END //
delimiter ;