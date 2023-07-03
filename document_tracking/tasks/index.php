<?php
session_start();

if (empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
include_once "../components/access_settings.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracking System</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/selectize-0.15.2/selectize.default.min.css">
    <style>
        body {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <?php $page = "tasks"; ?>
    <?php include_once "../components/navbar.php"; ?>
    <?php include_once "../components/greeting.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="p-3 d-block d-md-none"></div>
            <div class="col p-3 pt-0">
            <?php
                require_once "../connection.php";
                require_once "../components/counter_data.php";
                ?>
                <div class="">
                    <button id="OpenModalButton"
                        type="button" 
                        class="btn btn-success" 
                        style="width: 202px;"
                        data-bs-toggle="modal" data-bs-target="#FormModal">
                        Create new task
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <span class="mx-3 text-muted fs-4">|</span>
                    <?php
                    foreach($counter_data['task_management'] as $data) { 
                        if($data['count'] <= 0) continue;
                        if(in_array($data['page'], ['users', 'departments', 'documents'])) continue;
                        if(!in_array($data['page'], $accessible_pages)) continue;
                        ?>
                        <a href=".<?php echo $data['link']; ?>" 
                            class="btn btn-<?php echo $data['bg-color']; ?> me-2"
                            style="width: 160px;">
                                <?php echo $data['title']; ?>
                                <span class="badge bg-white text-<?php echo $data['bg-color']; ?>"><?php echo $data['count']; ?></span>
                        </a>
                    <?php
                    } // end of foreach..
                    ?>
                </div>
                <hr>
                <?php
                if(!empty($_GET['status'])) { 
                    require_once "../connection.php";
                    include_once "./components/status-table.php";
                } else { ?>
                    <section class="mb-3">
                        <input id="search" type="text" class="form-control form-control-lg"
                            placeholder="Search name of task."
                        />
                    </section>
                    <div class="table-container" style="overflow-x: scroll; max-width: 100%;">
                        <?php include_once "./components/table.php"; ?>
                    </div> 
                <?php
                } // end of if...
                ?>               
            </div>
        </div>
    </div>
    <?php
    $taskers   = [];
    $my_departments = [];
    $departments = [];
    require_once "../connection.php";
    try {
        $stmt = $conn->prepare("SELECT id, name FROM department WHERE id IN(SELECT department_id FROM user_department WHERE user_id=:user_id)");
        $stmt->execute(["user_id" => $_SESSION['user']['id']]);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $my_departments[] = $row;
        } 

        $stmt = $conn->prepare("SELECT id, name FROM department");
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['type'] = "DEPARTMENT";
            $departments[] = $row;
        } 
        foreach($departments as $department) {  
            $taskers[] = $department;              
            $stmt = $conn->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM user WHERE id IN(SELECT user_id FROM user_department WHERE department_id=:department_id)");
            $stmt->execute(["department_id" => $department['id']]);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['type'] = "PERSONNEL";
                $row['department'] = $department;
                $taskers[] = $row;
            }
        }
    } catch (PDOException $e) {}                
    ?>
    <?php include_once "./components/form-modal.php"; ?>  
    <?php include_once "./components/task_history-modal.php"; ?>
    <?php include_once "./components/mark_as_done-modal.php"; ?>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/js/alert.js"></script>
    <script src="../assets/jquery-3.7.0.min.js"></script>
    <script src="../assets/selectize-0.15.2/selectize.min.js"></script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
</body>
</html>