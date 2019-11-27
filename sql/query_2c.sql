/* query c */

SELECT U.nome, counter_table.userid, avg(counter)
FROM (
    SELECT userid, regid, count(*) AS counter 
    FROM reg_pag 
    WHERE ativa = 1
    GROUP BY pageid) as counter_table 
JOIN utilizador AS U
WHERE U.userid = counter_table.userid 
GROUP BY counter_table.userid
HAVING avg(counter) >= 
    ALL (
        SELECT avg(counter) 
        FROM (
            SELECT userid, count(*) AS counter 
            FROM reg_pag 
            WHERE ativa = 1
            GROUP BY pageid) AS counter_table
        GROUP BY userid);