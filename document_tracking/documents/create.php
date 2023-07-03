<?php
session_start();
require_once "../connection.php";

try {
    unset($_POST['id']);

    $conn->beginTransaction();

    $stmt = $conn->prepare(
        "INSERT INTO document (name, document_type, user_id, department_id, status)
        VALUES (:name, :document_type, :user_id, :department_id, 'SENT')"
    );
    $stmt->execute([
        "name"          => $_POST['name'],
        "document_type" => $_POST['document_type'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);
    $document_id = $conn->lastInsertId();
    $receiver_data = explode("|", $_POST['receiver']);

    $stmt = $conn->prepare(
        "INSERT INTO document_history (document_id, remarks, user_id, department_id, status, receiver_type)
        VALUES (:document_id, :remarks, :user_id, :department_id, 'SENT', :receiver_type)"
    );
    $stmt->execute([
        "document_id"   => $document_id,
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "receiver_type" => $receiver_data[0],
        "department_id" => $_POST['department_id'],
    ]);
    $document_history_id = $conn->lastInsertId();

    $data = [];
    if($receiver_data[0] == "DEPARTMENT") {
        $sql = "
            INSERT INTO document_department (document_history_id, department_id) 
            VALUES (:document_history_id, :receiver_id)
        ";

        $data = [
            "document_history_id" => $document_history_id,
            "receiver_id"         => $receiver_data[1],
        ];
    } else {
        $sql = "
            INSERT INTO document_user (document_history_id, user_id, department_id) 
            VALUES (:document_history_id, :receiver_id, :department_id)
        ";

        $data = [
            "document_history_id" => $document_history_id,
            "receiver_id"         => $receiver_data[1],
            "department_id"       => $receiver_data[2],
        ];
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    $conn->commit();

    $response = [ 
        "message" => "New document created and sent successfully",
        "status"  => "success"
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $conn->rollBack();

    $response = [ 
        "message" => "Error: " . $e->getMessage(),
        "status"  => "error"
    ];
    echo json_encode($response);
}
$conn = null;