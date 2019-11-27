/* query b */

SELECT count_reg_pag.userid, count_reg_pag.regid, pageid, n_pages, n_reg_pag
FROM (
    /* Conta numero de paginas do utilizador */
    SELECT userid, count(*) as n_pages
    FROM pagina
    WHERE ativa = 1
    GROUP BY userid) as reg_table 
JOIN (
    /* Conta numero de paginas diferentes por regdisto de um utilizador. */
    SELECT userid, regid, pageid, count(DISTINCT pageid) as n_reg_pag
    FROM reg_pag
    WHERE ativa = 1
    GROUP BY userid, regid, pageid) as count_reg_pag
WHERE n_pages = n_reg_pag
AND reg_table.userid = count_reg_pag.userid;
GROUP BY userid;
