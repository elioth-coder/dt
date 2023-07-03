<?php
require_once "../connection.php";

try {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare(
        "SELECT * FROM user WHERE (username LIKE :q 
        OR first_name LIKE :q OR last_name LIKE :q
        OR gender LIKE :q OR role LIKE :q) AND id NOT IN(1)"
    );
    $stmt->bindValue(':q', $q);
    $stmt->execute();
    $rows = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    }

    $response = [ 
        "message" => "Fetched rows successfully!",
        "rows"    => $rows,
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
