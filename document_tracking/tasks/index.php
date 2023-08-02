<?php
session_start();

if (empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
include_once "../components/access_settings.php";
$taskers   = [];
$my_departments = [];
$departments = [];
require_once "../connection.php";
try {
    $stmt = $conn->prepare("SELECT id, name FROM department WHERE id IN(SELECT department_id FROM user_department WHERE user_id=:user_id)");
    $stmt->execute(["user_id" => $_SESSION['user']['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $my_departments[] = $row;
    }

    $stmt = $conn->prepare("SELECT id, name FROM department");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['type'] = "DEPARTMENT";
        $departments[] = $row;
    }
    foreach ($departments as $department) {
        $taskers[] = $department;
        $stmt = $conn->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM user WHERE id IN(SELECT user_id FROM user_department WHERE department_id=:department_id)");
        $stmt->execute(["department_id" => $department['id']]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['type'] = "PERSONNEL";
            $row['department'] = $department;
            $taskers[] = $row;
        }
    }
} catch (PDOException $e) { }
?>
<?php $page = "tasks"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <?php require_once "../components/favicons.php"; ?>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.0-alpha3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/selectize-0.15.2/selectize.default.min.css">
    <link rel="stylesheet" href="../assets/DataTables/datatables.min.css">
    <link rel="stylesheet" href="../assets/DataTables/Buttons-2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../style.css?t=<?php echo time(); ?>">
</head>

<body>
    <div class="sidebar-menu shadow-sm">
        <?php include_once "../components/navbar.php"; ?>
    </div>
    <div class="content">
        <div class="card m-3">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="../">Home</a></li>
                        <li class="breadcrumb-item"><a href="../tasks/">Task Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tasks</li>
                    </ol>
                </nav>

                <button class="btn-success btn position-absolute my-2 mx-3 end-0 top-0" 
                    data-bs-toggle="modal" 
                    data-bs-target="#NewTaskModal">
                    New Task
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </div>
        <?php
        require_once "../connection.php";
        require_once "../components/counter_data.php";
        ?>
        <ul class="nav nav-tabs m-3 mb-0">
            <?php
            foreach ($counter_data['task_management'] as $data) {
                if ($data['count'] <= 0) continue;
                if (in_array($data['page'], ['users', 'departments', 'documents'])) continue;
                if (!in_array($data['page'], $accessible_pages)) continue;
                if (!empty($_GET['status'])) {
                    $active = (strtoupper($data['title']) == $_GET['status']) ? "active" : "";
                } else {
                    $active = ($data['title'] == 'Tasks') ? "active" : "";
                }
            ?>
                <li class="nav-item">
                    <a href=".<?php echo $data['link']; ?>" class="nav-link position-relative <?php echo $active; ?>">
                        <?php echo $data['title']; ?>
                        <span class="badge text-bg-danger">
                            <?php echo $data['count']; ?>
                        </span>
                    </a>
                </li>
            <?php
            } // end of foreach..
            ?>
        </ul>
        <div class="bg-white m-3 mt-0 p-3 border border-top-0 rounded" style="border-top-left-radius: 0 !important;">
            <?php
            if (!empty($_GET['status'])) {
                require_once "./components/query-task-by-status.php";
                $parameters = [
                    "status"  => $_GET['status'],
                    "user_id" => $_SESSION['user']['id']
                ];
            } else {
                $sql = "SELECT *, user_id AS creator_id, name AS task_name FROM task WHERE user_id=:user_id";
                $parameters = [
                    "user_id" => $_SESSION['user']['id']
                ];
            } // end of if...

            require_once "../components/colors-task.php";
            include_once "./components/data-table-task.php";
            ?>
        </div>
    </div>
    <?php include_once "./components/modal-new-task.php"; ?>
    <?php include_once "./components/modal-task-history.php"; ?>
    <?php include_once "./components/modal-mark-as-done.php"; ?>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/js/alert.js"></script>
    <script src="../assets/jquery-3.7.0.min.js"></script>
    <script src="../assets/DataTables/datatables.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.dataTables.min.js"></script>
    <script src="../assets/DataTables/JSZip-3.10.1/jszip.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/pdfmake.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/vfs_fonts.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.html5.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.print.min.js"></script>
    <script src="../assets/selectize-0.15.2/selectize.min.js"></script>
    <script>
        const STATUS_COLOR  = <?php echo json_encode($STATUS_COLOR); ?>;
    </script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
    <script src="./task-history.js?t=<?php echo time(); ?>"></script>
</body>

</html>