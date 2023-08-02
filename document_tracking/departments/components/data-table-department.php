<table id="DataTableDepartment" class="table bg-white table-striped table-hover table-bordered">
<thead class="text-primary">
    <tr>
    <th class="text-center">ACTION</th>
    <th class="text-end">ID</th>
    <th class="text-center">PROFILE</th>
    <th>DEPARTMENT NAME</th>
    <th class="text-center">MEMBERS</th>
    </tr>
</thead>
<tbody>
<?php
try {
    $stmt = $conn->prepare("
        SELECT *, 
        (SELECT COUNT(*) FROM user_department WHERE department_id=id) AS members 
        FROM department
    ");    
    $stmt->execute();
    $departments = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $departments[]= $row;
        ?>
        <tr>
        <td style="width: 160px;" class="text-center">
            <button onclick="editDepartment(<?php echo $row['id']; ?>)" class="btn btn-outline-primary">
                <i class="bi-pencil-fill"></i>
            </button>
            <button onclick="deleteDepartment(<?php echo $row['id']; ?>)" class="btn btn-outline-primary">
                <i class="bi-trash-fill"></i>
            </button>
        </td>
        <td class="text-end"><?php echo $row['id']; ?></td>
        <td class="text-center">
            <img onclick="viewImage('../upload/<?php echo $row['profile']; ?>');" 
                class="rounded-circle" 
                style="cursor: pointer; height: 50px; width: 50px;" 
                src="../upload/<?php echo $row['profile']; ?>" 
            />
        </td>
        <td><?php echo $row['name']; ?></td>
        <td class="text-center">
            <a href="#" onclick="showMembersModal(<?php echo $row['id']; ?>);"><?php echo $row['members']; ?></a>
        </td>
        </tr>
        <?php     
    } // end of while...
} catch (PDOException $e) { }
?>
</tbody>
</table>
