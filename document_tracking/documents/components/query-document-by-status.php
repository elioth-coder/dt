<?php
$sql = "
    SELECT 
        D.datetime, D.status, D.id, D.document_type, D.name AS document_name, 
        DH.receiver_type, DH.user_id,
        U.username,
        DT.id AS department_id, DT.name AS department_name,
        DTU.id AS department_id_user, DT.name AS department_name_user,
        U.id  AS user_id, CONCAT(U.first_name, ' ', U.last_name) AS user_name,
        UDU.id AS department_user_id, CONCAT(UDU.first_name, ' ', UDU.last_name) AS department_user_name,
        DH.id AS h_id, DH.remarks, DH.status 
    FROM document D
        INNER JOIN document_history DH
            ON D.id = DH.document_id
        LEFT JOIN document_department DD
            ON DH.id = DD.document_history_id
        LEFT JOIN department DT
            ON DD.department_id = DT.id
        LEFT JOIN user_department UD
            ON DT.id = UD.department_id
        LEFT JOIN user UDU
            ON UD.user_id = UDU.id
        LEFT JOIN document_user DU
            ON DH.id = DU.document_history_id
        LEFT JOIN department DTU
            ON DU.department_id = DTU.id
        LEFT JOIN user U 
            ON DU.user_id = U.id
    WHERE D.status = :status 
        AND (UDU.id = :user_id OR U.id = :user_id)
        AND DH.id=(SELECT MAX(id) FROM document_history WHERE document_id=D.id)
    GROUP BY D.id    
    ORDER BY DH.datetime ASC                       
";