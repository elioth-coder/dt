<table id="DataTableTask" class="table bg-white table-striped table-hover table-bordered">
    <thead class="text-primary">
        <tr>
            <th class="text-center">ACTION</th>
            <th class="text-center">DATETIME</th>
            <th class="text-center">STATUS</th>
            <th class="text-center">
                <i class="bi bi-file-earmark-text"></i>
            </th>
            <th class="text-center">TASK</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($parameters);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data = str_replace("'", "_", json_encode($row)); ?>
                    <tr>
                        <td style="width: 160px;" class="text-center">
                            <?php $buttonCount = 0; ?>
                            <button data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                data-bs-title="View History" 
                                onclick='viewTaskHistory(<?php echo $data; ?>);' 
                                class="btn btn-outline-primary">
                                <i class="bi bi-clock-history"></i>
                            </button>
                            <?php $buttonCount++; ?>

                            <?php
                            if (in_array($row['status'], ['ASSIGNED', 'RE-ASSIGNED'])) { ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Start Working" 
                                    onclick='startTask(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <?php $buttonCount++; ?>
                            <?php
                            }

                            if ($row['status'] == 'IN-PROGRESS') { ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Mark as Done" 
                                    onclick='markAsDone(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-check-square"></i>
                                </button>
                                <?php $buttonCount++; ?>
                            <?php
                            }

                            if ($row['status'] == 'DONE' && $row['creator_id'] == $_SESSION['user']['id']) { ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Re-Assign" `, 
                                    onclick='reAssignTask(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-repeat"></i>
                                </button>
                                <?php $buttonCount++; ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Mark as Completed" 
                                    onclick='markTaskAsComplete(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <?php $buttonCount++; ?>
                            <?php
                            }
                            $fillButtonCount = 3 - $buttonCount;
                            for($i=0; $i<$fillButtonCount; $i++) { ?>
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                            <?php 
                            } // end of for...
                            ?>

                        </td>
                        <td style="width: 190px;" class="text-end">
                            <?php echo $row['datetime']; ?>
                        </td>
                        <td class="">
                            <span class="badge text-bg-<?php echo $STATUS_COLOR[$row['status']]; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php
                            $stmt_1 = $conn->prepare("
                                SELECT * FROM task_history_files 
                                WHERE task_history_id=(SELECT id FROM task_history WHERE task_id=:task_id AND status='ASSIGNED')
                            ");
                            $stmt_1->execute(["task_id" => $row['id']]);

                            if ($stmt_1->rowCount() > 0) { 
                                $content = "<ul>";
                                while ($attachment = $stmt_1->fetch(PDO::FETCH_ASSOC)) { 
                                    $content .= 
                                        "<li>" .
                                            "<a target='_blank' href='./files/" . $attachment['generated_name'] . "'>"
                                                . $attachment['filename'] . 
                                            "</a>" . 
                                        "</li>";
                                } // end of while..
                                $content .= "</ul>"
                                ?>
                                <a href="#" 
                                    data-bs-toggle="popover" 
                                    data-bs-trigger="focus" 
                                    data-bs-placement="top" 
                                    data-bs-html="true"
                                    data-bs-title="<?php echo $stmt_1->rowCount(); ?> file attachment(s)."
                                    data-bs-content="<?php echo $content; ?>">
                                    <i class="bi bi-file-earmark-text"></i>(<?php echo $stmt_1->rowCount(); ?>)
                                </a>
                                
                            <?php
                            } // end of if...
                            ?>
                        </td>
                        <td><p><?php echo $row['task_name']; ?></p></td>
                    </tr>
                <?php
                }
            }
        } catch (PDOException $e) {
        }  ?>
    </tbody>
</table>