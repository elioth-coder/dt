<?php
session_start();

if (empty($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die(header("Location: ../login.php"));
}
require_once "../components/access_settings.php";
require_once "../connection.php";
try {
    $stmt = $conn->prepare("SELECT DISTINCT(document_type) AS document_type FROM document");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $doc_types[] = $row['document_type'];
    }
    $stmt = $conn->prepare("SELECT id, name FROM department");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $departments[] = $row;
    }
} catch (PDOException $e) {
}
?>
<?php $page = "reports"; ?>
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
                        <li class="breadcrumb-item"><a href="../documents/">Document</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <?php include_once "./components/filter-reports.php"; ?>
        <div class="card mx-3 p-3">
            <div class="table-responsive rounded-3">
                <table id="DocumentReportsTable" class="bg-white table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-primary text-end">DATETIME</th>
                            <th class="text-primary">STATUS</th>
                            <th class="text-primary">DOCTYPE</th>
                            <th class="text-primary">DOCUMENT</th>
                            <th class="text-primary">FROM</th>
                            <th class="text-primary">TO</th>
                            <th class="text-primary">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/papaparse.min.js"></script>
    <script src="../assets/jquery-3.7.0.min.js"></script>
    <script src="../assets/DataTables/datatables.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.dataTables.min.js"></script>
    <script src="../assets/DataTables/JSZip-3.10.1/jszip.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/pdfmake.min.js"></script>
    <script src="../assets/DataTables/pdfmake-0.2.7/vfs_fonts.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.html5.min.js"></script>
    <script src="../assets/DataTables/Buttons-2.4.1/js/buttons.print.min.js"></script>
    <?php require_once "../components/colors-document.php"; ?>
    <script>
        const STATUS_COLOR  = <?php echo json_encode($STATUS_COLOR); ?>;
        const DOCTYPE_COLOR = <?php echo json_encode($DOCTYPE_COLOR); ?>;
    </script>    
    <script src="./script.js?t=<?php echo time(); ?>"></script>
</body>

</html>