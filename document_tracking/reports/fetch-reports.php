<?php
session_start();
require_once "../connection.php";

if(empty($_POST['from'])) {
    $_POST['from'] = '1970-01-01';
}

if(empty($_POST['to'])) {
    $_POST['to'] = date('Y-m-d');
}

try {
    $sql = "
        SELECT 
            D.document_type, 
            D.name AS document_name,             
            DH.datetime, 
            DH.status, 
            DH.receiver_type, 
            DH.remarks, 
            (SELECT CONCAT(first_name, ' ', last_name) FROM user WHERE id=DH.user_id) AS actor, 
            (SELECT name FROM department WHERE id=DH.department_id) AS actor_department, 
            DT.name AS department, 
            (SELECT name FROM department WHERE id=DU.department_id) AS user_department
        FROM document D 
            INNER JOIN document_history DH 
                ON D.id = DH.document_id 
            LEFT JOIN document_user DU 
                ON DH.id = DU.document_history_id 
            LEFT JOIN document_department DD 
                ON DH.id = DD.document_history_id 
            LEFT JOIN department DT 
                ON DD.department_id = DT.id 
            WHERE D.datetime BETWEEN :from AND :to
                ";

    if(!empty($_POST['department_id'])) {
        $sql .= " AND D.department_id = :department_id";
    }
    if(!empty($_POST['doctype'])) {
        $sql .= " AND D.document_type = :doctype";
    }
        $sql .= " ORDER BY D.id ASC, DH.id ASC, DH.datetime ASC";
        
    $data = [
        "from"    => $_POST['from'] . ' 00:00:00',
        "to"      => $_POST['to'] . ' 23:59:59',
    ];

    if(!empty($_POST['doctype'])) $data['doctype'] = $_POST['doctype'];
    if(!empty($_POST['department_id'])) $data['department_id'] = $_POST['department_id'];

    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    $rows = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    }

    $response = [ 
        "message"    => "Fetched rows successfully!",
        "rows"       => $rows,
        "department" => (!empty($_POST['department'])) ? $_POST['department'] : "",
        "docxtype"   => (!empty($_POST['doctype'])) ? $_POST['doctype'] : "",
        "start_date" => $_POST['from'],
        "end_date"   => $_POST['to'],
        "user"       => $_SESSION['user'],
        "status"     => "success"
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $response = [ 
        "message" => "Error: " . $e->getMessage(),
        "status"  => "error"
    ];
    echo json_encode($response);
}
$conn = null;
?>