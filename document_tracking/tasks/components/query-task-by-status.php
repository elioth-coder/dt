<?php
$sql = "
    SELECT 
        T.datetime, T.status, T.id, T.name AS task_name, T.deadline, T.user_id AS creator_id,
        TH.tasker_type, TH.user_id, 
        U.username,
        DT.id AS department_id, DT.name AS department_name,
        DTU.id AS department_id_user, DT.name AS department_name_user,
        U.id  AS user_id, CONCAT(U.first_name, ' ', U.last_name) AS user_name,
        UDU.id AS department_user_id, CONCAT(UDU.first_name, ' ', UDU.last_name) AS department_user_name,
        TH.id AS h_id, TH.remarks, TH.status 
    FROM task T
        INNER JOIN task_history TH
            ON T.id = TH.task_id
        LEFT JOIN task_department TD
            ON TH.id = TD.task_history_id
        LEFT JOIN department DT
            ON TD.department_id = DT.id
        LEFT JOIN user_department UD
            ON DT.id = UD.department_id
        LEFT JOIN user UDU
            ON UD.user_id = UDU.id
        LEFT JOIN task_user DU
            ON TH.id = DU.task_history_id
        LEFT JOIN department DTU
            ON DU.department_id = DTU.id
        LEFT JOIN user U 
            ON DU.user_id = U.id
    WHERE T.status = :status 
        AND (UDU.id = :user_id OR U.id = :user_id)
        AND TH.id=(SELECT MAX(id) FROM task_history WHERE task_id=T.id)
    GROUP BY T.id    
    ORDER BY TH.datetime ASC                       
";