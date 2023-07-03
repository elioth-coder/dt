<?php
session_start();
require_once "../connection.php";

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare(
        "INSERT INTO task (name, deadline, status, user_id, department_id)
        VALUES (:name, :deadline, 'ASSIGNED', :user_id, :department_id)"
    );
    $stmt->execute([
        "name"          => $_POST['name'],
        "deadline"      => $_POST['deadline'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
    ]);
    $task_id = $conn->lastInsertId();
    $tasker_data = explode("|", $_POST['tasker']);

    $stmt = $conn->prepare(
        "INSERT INTO task_history (task_id, remarks, status, user_id, department_id, tasker_type)
        VALUES (:task_id, :remarks, 'ASSIGNED', :user_id, :department_id, :tasker_type)"
    );
    $stmt->execute([
        "task_id"       => $task_id,
        "remarks"       => $_POST['remarks'],
        "user_id"       => $_SESSION['user']['id'],
        "department_id" => $_POST['department_id'],
        "tasker_type"   => $tasker_data[0],
    ]);
    $task_history_id = $conn->lastInsertId();

    if($_FILES['attachments']['error'][0] == 0) { // checks if there is an uploaded file
        $file_ary = reArrayFiles($_FILES['attachments']);
        $i = 0;
        foreach ($file_ary as $file) {
            $i++;
            $file['generated_name'] = "file_" . time() . "_" . $i . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
            move_uploaded_file($file['tmp_name'], "./files/".$file["generated_name"]);
            
            $stmt = $conn->prepare(
                "INSERT INTO task_history_files (task_history_id, filename, generated_name)
                VALUES (:task_history_id, :filename, :generated_name)"
            );
            $stmt->execute([
                "task_history_id" => $task_history_id,
                "filename"        => $file['name'],
                "generated_name"  => $file['generated_name'],
            ]);
        }
    }

    $data = [];
    if($tasker_data[0] == "DEPARTMENT") {
        $sql = "
            INSERT INTO task_department (task_history_id, department_id) 
            VALUES (:task_history_id, :department_id)
        ";

        $data = [
            "task_history_id" => $task_history_id,
            "department_id"   => $tasker_data[1],
        ];
    } else {
        $sql = "
            INSERT INTO task_user (task_history_id, user_id, department_id) 
            VALUES (:task_history_id, :user_id, :department_id)
        ";

        $data = [
            "task_history_id" => $task_history_id,
            "user_id"         => $tasker_data[1],
            "department_id"   => $tasker_data[2],
        ];
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    $conn->commit();

    $response = [ 
        "message" => "New task created and assigned successfully",
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