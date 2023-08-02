<table id="DataTableProject" class="table bg-white table-striped table-hover table-bordered">
    <thead class="text-primary">
        <tr>
            <th class="text-center">ACTION</th>
            <th class="text-center">DATE STARTED</th>
            <th class="text-center">STATUS</th>
            <th class="text-center">PROJECT NAME</th>
            <th class="text-center">TASKS</th>
            <th class="text-center">DEADLINE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $stmt = $conn->prepare(
                "SELECT *, (SELECT COUNT(task_id) FROM project_tasks WHERE project_id=id) AS tasks FROM project 
        WHERE user_id=:user_id
    "
            );
            $stmt->execute(["user_id" => $_SESSION['user']['id']]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td class="text-center">
                        <button class="btn btn-outline-primary" 
                            onclick="showTasksModal(<?php echo $row['id']; ?>);" 
                            data-bs-title="Add tasks" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top">
                            <i class="bi bi-list-task"></i>
                        </button>
                        <a class="btn btn-outline-primary" 
                            href="./view.php?project_id=<?php echo $row['id']; ?>" 
                            data-bs-title="View project" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                    <td class="text-end"><?php echo $row['date_started']; ?></td>
                    <td class="">
                        <span class="badge text-bg-<?php echo $STATUS_COLOR[$row['status']]; ?>"><?php echo $row['status']; ?></span>
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td class="text-center"><?php echo $row['tasks']; ?></td>
                    <td class="text-end"><?php echo $row['deadline']; ?></td>
                </tr>
            <?php
            } // end of while..
        } catch (PDOException $e) { } 
        ?>
    </tbody>
</table>