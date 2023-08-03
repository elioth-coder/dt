<table id="DataTableUser" class="table bg-white table-striped table-hover table-bordered">
<thead class="text-primary">
    <tr>
    <th class="text-center">ACTION</th>
    <th class="text-center">PROFILE</th>
    <th class="text-center">FULL NAME</th>
    <th class="text-center">SEX</th>
    <th class="text-center">USERNAME</th>
    <th class="text-center">ROLE</th>
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
            <button class="btn btn-outline-primary" 
                onclick="editPassword(<?php echo $row['id']; ?>)" 
                data-bs-title="Set Password" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top">
                <i class="bi-key-fill"></i>
            </button>
            <button class="btn btn-outline-primary" 
                onclick="editUser(<?php echo $row['id']; ?>)" 
                data-bs-title="Edit" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top">
                <i class="bi-pencil-fill"></i>
            </button>
            <button class="btn btn-outline-primary" 
                onclick="deleteUser(<?php echo $row['id']; ?>)" 
                data-bs-title="Delete" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top">
                <i class="bi-trash-fill"></i>
            </button>
        </td>
        <td class="text-center" style="width: 100px;">
            <img onclick="viewImage('../upload/<?php echo $row['profile']; ?>');" 
                class="rounded-circle" 
                style="cursor: pointer; height: 50px; width: 50px;" 
                src="../upload/<?php echo $row['profile']; ?>" 
            />
        </td>
        <td><?php echo $row['first_name'] . " " .$row['last_name']; ?></td>
        <td class="text-center fs-4 text-<?php echo $row['gender']=='MALE' ? 'primary' : 'danger'; ?>" 
            style="width: 60px;"
            title="<?php echo $row['gender']; ?>">
            <i class="bi bi-gender-<?php echo strtolower($row['gender']); ?>"></i>
        </td>
        <td><?php echo $row['username']; ?></td>
        <td class="text-center"><?php echo $row['role']; ?></td>
        </tr>
    <?php     
    } // end of while...
} catch (PDOException $e) { }
?>
</tbody>
</table>
