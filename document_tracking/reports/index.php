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
    <style>
        body {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <?php $page = "reports"; ?>
    <?php include_once "../components/navbar.php"; ?>
    <?php include_once "../components/greeting.php"; ?>
    <?php
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
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="w-50 float-start">
                    <select id="department_id" class="form-control form-control-lg">
                        <option value="">Filter department.</option>
                        <?php
                        foreach ($departments as $department) { ?>
                            <option value="<?php echo $department['id']; ?>">
                                <?php echo $department['name']; ?>
                            </option>
                        <?php
                        } // end of foreach..
                        ?>
                    </select>
                </div>
                <div class="w-50 float-start">
                    <select id="doctype" class="form-control form-control-lg">
                        <option value="">Filter document type.</option>
                        <?php
                        foreach ($doc_types as $doc_type) { ?>
                            <option value="<?php echo $doc_type; ?>">
                                <?php echo $doc_type; ?>
                            </option>
                        <?php
                        } // end of foreach..
                        ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div style="width: calc(100% - 120px);" class="d-inline-block">
                    <div class="w-50 float-start">
                        <input type="date" id="from" class="form-control form-control-lg" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Start date." />
                    </div>
                    <div class="w-50 float-start">
                        <input type="date" id="to" class="form-control form-control-lg" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="End date." />
                    </div>
                </div>
                <button style="margin-top: -40px;" class="btn btn-lg btn-primary" type="button" id="generate">
                    Generate
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive mt-3 rounded-3">
                    <table id="ReportsTable" class="bg-white table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-primary">DATETIME</th>
                                <th class="text-primary">STATUS</th>
                                <th class="text-primary">DEPARTMENT</th>
                                <th class="text-primary">DOCTYPE</th>
                                <th class="text-primary">DOCUMENT</th>
                                <th class="text-primary">REMARKS</th>
                                <th class="text-primary">BY</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="float-end">
                    <a href="" download="report.csv" style="display: none;" id="download" 
                        class="btn btn-lg btn-primary mb-3">
                        Download Report
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script src="../assets/papaparse.min.js"></script>
    <script src="./script.js?t=<?php echo time(); ?>"></script>
</body>

</html>