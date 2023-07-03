<?php
$sql = "
    SELECT 
        T.datetime, T.status, T.id, T.name AS task_name, T.deadline, T.user_id AS creator_id,
        TH.tasker_type, TH.user_id, 
        U.username,
        DT.id AS department_id, DT.name AS department_name,
        DTU.id AS department_id_user, DT.name AS department_name_user,
        U.id  AS user_id, CONCAT(U.first_name, ' ', U.last_name) AS user_name,
        UDU.id AS department_user_id, CONCAT(UDU.first_name, ' ', UDU.last_name) AS department_user_name,
        TH.id AS h_id, TH.remarks, TH.status 
    FROM task T
        INNER JOIN task_history TH
            ON T.id = TH.task_id
        LEFT JOIN task_department TD
            ON TH.id = TD.task_history_id
        LEFT JOIN department DT
            ON TD.department_id = DT.id
        LEFT JOIN user_department UD
            ON DT.id = UD.department_id
        LEFT JOIN user UDU
            ON UD.user_id = UDU.id
        LEFT JOIN task_user DU
            ON TH.id = DU.task_history_id
        LEFT JOIN department DTU
            ON DU.department_id = DTU.id
        LEFT JOIN user U 
            ON DU.user_id = U.id
    WHERE T.status = :status 
        AND (UDU.id = :user_id OR U.id = :user_id)
        AND TH.id=(SELECT MAX(id) FROM task_history WHERE task_id=T.id)
    GROUP BY T.id    
    ORDER BY TH.datetime ASC                       
";
?>

<table class="table bg-white table-striped table-hover table-bordered">
    <thead class="text-primary">
        <tr>
            <th class="text-center">DATETIME</th>
            <th class="text-center">STATUS</th>
            <th class="text-center">TASK</th>
            <th class="text-center">ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                "user_id" => $_SESSION['user']['id'],
                "status"  => $_GET['status']
            ]);
            $BACKGROUNDS = [
                'DONE'        => "bg-success",
                'ASSIGNED'    => "bg-info",
                'RE-ASSIGNED' => "bg-info",
                'IN-PROGRESS' => "bg-warning",
                'COMPLETED'   => "bg-danger",
            ];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data = str_replace("'", "_", json_encode($row)); ?>
                    <tr>
                        <td style="width: 190px;" class="text-center"><?php echo $row['datetime']; ?></td>
                        <td style="width: 170px;" class="text-center">
                            <span class="fs-6 p-2 d-block w-100 badge <?php echo $BACKGROUNDS[$row['status']]; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <h4><?php echo $row['task_name']; ?></h4>
                            <?php
                            $stmt_1 = $conn->prepare("
                                SELECT * FROM task_history_files 
                                WHERE task_history_id=(SELECT id FROM task_history WHERE task_id=:task_id AND status='ASSIGNED')
                            ");
                            $stmt_1->execute(["task_id" => $row['id']]);
                            
                            if($stmt_1->rowCount() > 0) { ?>
                                <p>Attachments: 
                                    <?php
                                    while($attachment = $stmt_1->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <a href="./files/<?php echo $attachment['generated_name']; ?>">
                                            <?php echo $attachment['filename']; ?>
                                        </a>, 
                                    <?php                                
                                    } // end of while..
                                    ?> 
                                </p>
                            <?php
                            } // end of if...
                            ?>
                        </td>
                        <td style="width: 160px;" class="text-center">
                            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick='viewTaskHistory(<?php echo $data; ?>);' class="btn btn-outline-info">
                                <i class="bi bi-eye-fill"></i>
                            </button>

                            <?php
                            if (in_array($row['status'], ['ASSIGNED', 'RE-ASSIGNED'])) { ?>
                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Start working" 
                                    onclick='startTask(<?php echo $data; ?>);' class="btn btn-outline-success">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            <?php
                            }

                            if ($row['status'] == 'IN-PROGRESS') { ?>
                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Mark as done" 
                                    onclick='markAsDone(<?php echo $data; ?>);' class="btn btn-outline-warning">
                                    <i class="bi bi-check-square"></i>
                                </button>
                            <?php
                            }

                            if ($row['status'] == 'DONE' && $row['creator_id'] == $_SESSION['user']['id']) { ?>
                                <button data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Re-Assign"`,
                                    onclick='reAssignTask(<?php echo $data; ?>);' class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                                <button data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Mark as Completed"
                                    onclick='markTaskAsComplete(<?php echo $data; ?>);' class="btn btn-outline-danger">
                                    <i class="bi bi-check-square-fill"></i>
                                </button>
                            <?php
                            }
                            ?>

                        </td>
                    </tr>
                <?php
                }
            } else { ?>
                <tr>
                    <td colspan="4" class="text-center">
                        No data found..
                    </td>
                </tr>
            <?php
            }
        } catch (PDOException $e) {}  ?>
    </tbody>
</table>