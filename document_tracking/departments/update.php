<?php
require_once "../vendor/autoload.php";
require_once "../connection.php";

use Intervention\Image\ImageManagerStatic as Image;

try {
    if(str_contains($_POST['profile'], 'data:image')) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['profile']));
        $_POST['profile'] = "_department-" . time() . ".png";
        file_put_contents("../upload/" . $_POST['profile'], $data);
        $img = Image::make("../upload/" . $_POST['profile']);
        $img->resize(200, 200);
        $img->save("../upload/" . $_POST['profile']);
    }
    if($_POST['profile'] == "") {
        $_POST['profile'] = "profile.png";
    }

    $stmt = $conn->prepare("SELECT * FROM department WHERE id=" . $_POST['id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['profile'] != $_POST['profile']) {
        if($row['profile'] != "profile.png") {
            unlink("../upload/" . $row['profile']);
        }
    }

    $stmt = $conn->prepare(
        "UPDATE department SET name=:department_name, profile=:profile WHERE id=:id"
    );
    $stmt->execute($_POST);

    $response = [ 
        "message" => "Department updated successfully",
        "status"  => "success"
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
