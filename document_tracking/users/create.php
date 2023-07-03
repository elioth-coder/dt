<?php
require_once "../vendor/autoload.php";
require_once "../connection.php";

use Intervention\Image\ImageManagerStatic as Image;

try {
    if($_POST['profile']) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['profile']));
        $_POST['profile'] = "_user-" . time() . ".png";
        file_put_contents("../upload/" . $_POST['profile'], $data);
        $img = Image::make("../upload/" . $_POST['profile']);
        $img->resize(200, 200);
        $img->save("../upload/" . $_POST['profile']);
    } else {
        $_POST['profile'] = "profile.png";
    }
    unset($_POST['id']);
    $departments = $_POST['department'];
    unset($_POST['department']);

    $conn->beginTransaction();

    $stmt = $conn->prepare(
        "INSERT INTO user (profile, username, password, email, birthday, first_name, last_name, gender, role)
        VALUES (:profile, :username, SHA1(:password), :email, :birthday, :first_name, :last_name, :gender, :role)"
    );
    $stmt->execute($_POST);
    $user_id = $conn->lastInsertId();

    foreach($departments as $department) {
        $stmt = $conn->prepare(
            "INSERT INTO user_department (user_id, department_id) VALUES (:user_id, :department_id)"
        );
        $stmt->execute(["user_id" => $user_id, "department_id" => $department]);
    }

    $conn->commit();

    $response = [ 
        "message" => "New user created successfully",
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