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
    <?php $page = "projects"; ?>
    <?php include_once "../components/navbar.php"; ?>
    <?php include_once "../components/greeting.php"; ?>
    <?php
    require_once "../connection.php";
    try {
        $stmt = $conn->prepare("SELECT * FROM project WHERE id=:project_id");
        $stmt->execute(["project_id" => $_GET['project_id']]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    }
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col p-3 pt-0">
                <div class="text-center">
                    <h1 class="text-primary">
                        <?php echo $project['name']; ?>
                    </h1>
                    <i>
                        Date started: <?php echo $project['date_started']; ?> -
                        Deadline: <?php echo $project['deadline']; ?>
                    </i>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $parameters = [
                [
                    "status" => "ASSIGNED",
                    "color"  => "info",
                ],
                [
                    "status" => "IN-PROGRESS",
                    "color"  => "warning",
                ],
                [
                    "status" => "DONE",
                    "color"  => "success",
                ],
                [
                    "status" => "COMPLETED",
                    "color"  => "danger",
                ],
            ];

            foreach ($parameters as $param) { ?>
                <div class="col">
                    <div class="bg-<?php echo $param['color']; ?> p-3 rounded-3">
                        <h3 class="text-center text-white"><?php echo $param['status']; ?></h3>
                        <hr>
                        <?php
                        try {
                            $stmt = $conn->prepare(
                                "SELECT * FROM task 
                                WHERE status=:status
                                AND id IN (SELECT task_id FROM project_tasks WHERE project_id=:project_id)"
                            );
                            $stmt->execute([
                                "project_id" => $_GET['project_id'],
                                "status"     => $param['status'],
                            ]);
                            while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <?php $task['task_name'] = $task['name']; ?>
                                <div style="cursor: pointer;" onclick='viewTaskHistory(<?php echo json_encode($task); ?>);' class="alert alert-<?php echo $param['color']; ?>">
                                    <?php
                                    $stmt_1 = $conn->prepare(
                                        "SELECT * FROM task_history 
                                        WHERE id=(SELECT id FROM task_history WHERE task_id=:task_id ORDER BY id DESC LIMIT 1)"
                                    );
                                    $stmt_1->execute(["task_id" => $task['id']]);
                                    $task_history = $stmt_1->fetch(PDO::FETCH_ASSOC);

                                    if ($task_history['tasker_type'] == "DEPARTMENT") {
                                        $stmt_2 = $conn->prepare(
                                            "SELECT * FROM department 
                                            WHERE id=(SELECT department_id FROM task_department WHERE task_history_id=:task_history_id)"
                                        );
                                        $stmt_2->execute(["task_history_id" => $task_history['id']]);
                                        $department = $stmt_2->fetch(PDO::FETCH_ASSOC);
                                        $tasker['name']    = $department['name'];
                                        $tasker['profile'] = $department['profile'];
                                    } else {
                                        $stmt_2 = $conn->prepare(
                                            "SELECT * FROM user 
                                            WHERE id=(SELECT user_id FROM task_user WHERE task_history_id=:task_history_id)"
                                        );
                                        $stmt_2->execute(["task_history_id" => $task_history['id']]);
                                        $user = $stmt_2->fetch(PDO::FETCH_ASSOC);
                                        $tasker['name']    = $user['first_name'] . " " . $user['last_name'];
                                        $tasker['profile'] = $user['profile'];
                                    }
                                    ?>
                                    <img src="../upload/<?php echo $tasker['profile']; ?>" data-bs-title="<?php echo $tasker['name']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" style="height: 38px;" class="rounded-circle shadow-lg border me-1" />
                                    <?php echo $task['name']; ?>
                                </div>
                        <?php
                            } // end of while..
                        } catch (PDOException $e) {
                        }
                        ?>
                    </div>
                </div>
            <?php
            } // end of foreach..
            ?>
        </div>
    </div>
    <?php include_once "./components/task_history-modal.php"; ?>
    <script src="../assets/bootstrap-5.3.0-alpha3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/sweetalert2-11.7.3/package/dist/sweetalert2.min.js"></script>
    <script>
        var BACKGROUNDS = {
            'RE-ASSIGNED' : "bg-info",
            'DONE'        : "bg-success",
            'ASSIGNED'    : "bg-info",
            'IN-PROGRESS' : "bg-warning",
            'COMPLETED'   : "bg-danger",
        }

        const taskHistoryModal = new bootstrap.Modal('#TaskHistoryModal', {
            keyboard: false
        });

        async function viewTaskHistory(task) {
            taskHistoryModal.show();

            let response = await fetch('../tasks/fetch-history.php?task_id=' + task.id);
            let {
                status,
                message,
                rows,
                creator
            } = await response.json();

            if (status == 'success') {
                let tbodyContent = "";
                rows.forEach(row => {
                    let department = (row.tasker_type == 'DEPARTMENT') ?
                        row.department :
                        row.user_department;

                    tbodyContent += [
                        `<tr>`,
                        `<td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD">`,
                        `   <div class="bg-secondary-subtle position-absolute end-0 rounded-circle" `,
                        `       style="border: 2px solid #0D6EFD; margin-right: -16px; width: 30px; height: 30px;"></div>`,
                        `</td>`,
                        `<td style="width: 50px;"></td>`,
                        `<td style="width: 190px;" class="text-center">${row.datetime}</td>`,
                        `<td style="width: 170px;" class="text-center">`,
                        `   <span class="fs-6 p-2 d-block w-100 badge ${BACKGROUNDS[row.status]}">${row.status}</span>`,
                        `</td>`,
                        `<td class="text-center">${department}</td>`,
                        `<td>`,
                        `   <p>${row.remarks}</p>`,
                        (row.attachments.length) ?
                        `<p>Attachments: ${row.attachments.map(file => `<a target="_blank" href="./files/${file.generated_name}" download="${file.filename}">${file.filename}</a>`).join(", ")}</p>` :
                        "",
                        `</td>`,
                        `<td>${row.actor_department}<br> - ${row.actor}</td>`,
                        `</tr>`,
                    ].join("\n");
                });

                let table = [
                    `<table class="table table-striped table-bordered">`,
                    `   <thead>`,
                    `   <tr>`,
                    `   <td class="position-relative" style="width: 50px; border-right: 2px solid #0D6EFD"></td><td></td>`,
                    `   <th class="text-center text-primary">DATETIME</th>`,
                    `   <th class="text-center text-primary">STATUS</th>`,
                    `   <th class="text-center text-primary">DEPARTMENT</th>`,
                    `   <th class="text-primary">REMARKS</th>`,
                    `   <th class="text-primary">BY</th>`,
                    `   </tr>`,
                    `   </thead>`,
                    `   <tbody>`,
                    tbodyContent,
                    `   <tbody>`,
                    `</table>`,
                ].join("\n");

                let modalBodyContent = [
                    `<table class="table table-bordered">`,
                    `<tr>`,
                    `   <th style="width: 190px;" class="bg-primary text-white text-center align-middle">TASK</th>`,
                    `   <td>`,
                    `       <h4 class="text-primary">${task.task_name}</h4>`,
                    `       <i>Deadline on: ${task.deadline}</i><br>`,
                    `       <i>Assigned by: ${creator.first_name} ${creator.last_name} - ${creator.department.name}</i>`,
                    `   </td>`,
                    `</tr>`,
                    `</table>`,
                    `<hr>`,
                    table,
                ].join("\n");

                TaskHistoryModal.querySelector('.modal-body').innerHTML = modalBodyContent;
            } else {
                TaskHistoryModal.querySelector('.modal-body').innerHTML = [
                    `<h3 class="text-center text-danger">${message}</h3>`,
                ].join("\n");
            }
        }

        function triggerTooltips() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }

        triggerTooltips();
    </script>
</body>

</html>