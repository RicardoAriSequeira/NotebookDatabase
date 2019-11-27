SELECT pais, categoria, mes, ano, avg(n_attempts) 
FROM (
    SELECT email, mes, ano, count(*) AS n_attempts 
    FROM login_attempts 
    NATURAL JOIN d_tempo 
    GROUP BY mes, ano, email with rollup) AS attempts 
NATURAL JOIN d_utilizador 
WHERE pais = 'Portugal' 
GROUP BY pais, categoria, mes, ano;
