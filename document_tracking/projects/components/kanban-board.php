<div class="row">
    <?php
    foreach ($parameters as $param) { ?>
        <div class="col">
            <div class="bg-<?php echo $param['color']; ?> p-3 rounded-3">
                <h3 class="text-center text-white"><?php echo $param['status']; ?></h3>
                <hr>
                <?php
                try {
                    if($param['status'] == 'ASSIGNED') {
                        $stmt = $conn->prepare(
                            "SELECT * FROM task 
                            WHERE status IN('ASSIGNED','RE-ASSIGNED')
                            AND id IN (SELECT task_id FROM project_tasks WHERE project_id=:project_id)"
                        );
                        $stmt->execute([
                            "project_id" => $_GET['project_id']
                        ]);
                    } else {
                        $stmt = $conn->prepare(
                            "SELECT * FROM task 
                            WHERE status=:status
                            AND id IN (SELECT task_id FROM project_tasks WHERE project_id=:project_id)"
                        );
                        $stmt->execute([
                            "project_id" => $_GET['project_id'],
                            "status"     => $param['status'],
                        ]);
                    }

                    if($stmt->rowCount() <= 0) { ?>
                        <p class="text-center text-secondary-subtle">No tasks found.</p>
                    <?php
                    }
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