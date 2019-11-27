/* query a */
SELECT nome,
       email,
       (count(*) - sum(sucesso)) as insucessos,
       sum(sucesso) as sucessos
FROM login 
NATURAL JOIN utilizador
GROUP BY userid
HAVING insucessos > sucessos;