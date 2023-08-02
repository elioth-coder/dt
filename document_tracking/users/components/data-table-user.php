<table id="DataTableUser" class="table bg-white table-striped table-hover table-bordered">
<thead class="text-primary">
    <tr>
    <th class="text-center">ACTION</th>
    <th class="text-end">ID</th>
    <th class="text-center">PROFILE</th>
    <th>FULL NAME</th>
    <th>GENDER</th>
    <th>USERNAME</th>
    <th>ROLE</th>
    </tr>
</thead>
<tbody>
<?php
try {
    $stmt = $conn->prepare("SELECT *, (SELECT GROUP_CONCAT(department_id SEPARATOR ',') FROM user_department WHERE user_id=id) AS departments FROM user WHERE id NOT IN(1)");
    $stmt->execute();
    $users = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $users[] = $row;
        ?>
        <tr>
        <td style="width: 160px;" class="text-center">
            <button onclick="editPassword(<?php echo $row['id']; ?>)" class="btn btn-outline-primary">
                <i class="bi-key-fill"></i>
            </button>
            <button onclick="editUser(<?php echo $row['id']; ?>)" class="btn btn-outline-primary">
                <i class="bi-pencil-fill"></i>
            </button>
            <button onclick="deleteUser(<?php echo $row['id']; ?>)" class="btn btn-outline-primary">
                <i class="bi-trash-fill"></i>
            </button>
        </td>
        <td class="text-end"><?php echo $row['id']; ?></td>
        <td class="text-center">
            <img onclick="viewImage('../upload/<?php echo $row['profile']; ?>');" class="rounded-circle" style="cursor: pointer; height: 50px; width: 50px;" src="../upload/<?php echo $row['profile']; ?>" />
        </td>
        <td><?php echo $row['first_name'] . " " .$row['last_name']; ?></td>
        <td><?php echo $row['gender']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['role']; ?></td>
        </tr>
    <?php     
    } // end of while...
} catch (PDOException $e) { }
?>
</tbody>
</table>
