<?php
session_start();

if (empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
include_once "../components/access_settings.php";
?>
<?php $page = "projects"; ?>
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
    <link rel="stylesheet" href="../style.css?t=<?php echo time(); ?>">
</head>

<body>
    <div class="sidebar-menu shadow-sm">
        <?php include_once "../components/navbar.php"; ?>
    </div>
    <div class="content">
        <?php
        require_once "../connection.php";
        try {
            $stmt = $conn->prepare("SELECT * FROM project WHERE id=:project_id");
            $stmt->execute(["project_id" => $_GET['project_id']]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { }
        ?>
        <div class="card m-3">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="../">Home</a></li>
                        <li class="breadcrumb-item"><a href="../tasks/">Task Management</a></li>
                        <li class="breadcrumb-item"><a href="../projects/">Projects</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $project['name']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card m-3">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="text-primary">
                        <?php echo $project['name']; ?>
                    </h1>
                    <table class="table table-borderless m-auto table-sm" style="width: 500px;">
                        <tbody class="text-start">
                            <tr>
                            <td class="">Date started: </td>
                            <td>
                                <span class="badge text-bg-success">
                                    <?php echo $project['date_started']; ?>
                                </span>
                            </td>
                            <td class="text-end">Deadline:</td>
                            <td>
                                <span class="badge text-bg-danger">
                                    <?php echo $project['deadline']; ?>
                                </span>
                            </td>
                            </tr>
                            <tr>
                            <td class="">Created by:</td>
                            <td>
                                <img class="shadow-sm" style="height: 25px;" 
                                    src="../upload/<?php echo $_SESSION['user']['profile']; ?>" alt="">
                                <?php echo $_SESSION['user']['first_name']. " " . $_SESSION['user']['last_name']; ?>
                            </td>
                            <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="overflow-hidden overflow-y-scroll" style="max-height: 60vh;">
                    <?php 
                    require_once "../components/colors-task.php";
                    $parameters = [];
                    foreach(['ASSIGNED','IN-PROGRESS','DONE','COMPLETED'] as $status) {
                        $parameters[] = [
                            "status" => $status,
                            "color"  => $STATUS_COLOR[$status],
                        ];
                    }
                    ?>
                    <?php include_once "./components/kanban-board.php"; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once "../tasks/components/modal-task-history.php"; ?>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <?php require_once "../components/colors-task.php"; ?>
    <script>
        const STATUS_COLOR  = <?php echo json_encode($STATUS_COLOR); ?>;
    </script>
    <script src="../tasks/task-history.js?t=<?php echo time(); ?>"></script>
    <script src="./view.js?t=<?php echo time(); ?>"></script>
</body>

</html>