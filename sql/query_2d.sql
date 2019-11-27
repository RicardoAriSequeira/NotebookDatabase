/* query d */

SELECT U.userid, U.nome
FROM (  
  SELECT ref_p.userid 
  FROM (
          /* Get number of pages of a user */
          SELECT userid, count(*) AS p_counter_ref 
          FROM pagina 
          WHERE ativa = 1
          GROUP BY userid
        ) AS ref_p
        JOIN (
          /* Get the number of reg types of a user */
          SELECT userid, count(*) AS tr_counter_ref 
          FROM tipo_registo
          WHERE ativo = 1 
          GROUP BY userid
        ) AS ref_tr 
        JOIN (
          /* Get all active with registries of a user. */
          SELECT userid, count(*) AS p_counter 
          FROM reg_pag 
          WHERE ativa = 1
          GROUP BY userid, pageid
        ) AS used_p  
        JOIN (
          /* Get all of the reg types in pages of a user. */
          SELECT userid, count(*) AS tr_counter 
          FROM reg_pag 
          WHERE ativa = 1
          GROUP BY userid, typeid
        ) AS used_tr 
  WHERE ref_p.userid = ref_tr.userid 
  AND ref_p.userid = used_p.userid 
  AND ref_p.userid = used_tr.userid 
  AND ref_p.p_counter_ref = used_p.p_counter 
  AND ref_tr.tr_counter_ref = used_tr.tr_counter) as counting_table
JOIN utilizador as U
WHERE counting_table.userid = U.userid;