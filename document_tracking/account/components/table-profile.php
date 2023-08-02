<?php
try {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=:user_id");
    $stmt->execute(["user_id" => $_SESSION['user']['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <div class="card m-3">
        <div class="card-header bg-info-subtle">
            <h3 class="text-center mt-1">Profile Account Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="text-end text-primary">Photo:</th>
                                <td>
                                    <img style="height: 65px;" src="../upload/<?php echo $user['profile']; ?>" class="rounded-circle border shadow" />
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">Username:</th>
                                <td><?php echo $user['username']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">Password:</th>
                                <td>* * * * * * * * * *</td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">User Role:</th>
                                <td class="text-capitalize"><?php echo strtolower($user['role']); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="text-end text-primary">Full Name:</th>
                                <td><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                            </tr>

                            <tr>
                                <th class="text-end text-primary">Gender:</th>
                                <td><?php echo $user['gender']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">Birthday:</th>
                                <td><?php echo $user['birthday']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">Email:</th>
                                <td><?php echo $user['email']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-end text-primary">Departments:</th>
                                <td>
                                    <?php
                                    try {
                                        $stmt = $conn->prepare("SELECT * FROM `department` WHERE id IN (SELECT department_id FROM user_department WHERE user_id=:user_id)");
                                        $stmt->execute(["user_id" => $_SESSION['user']['id']]);
                                        $i = 0;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $i++; ?>
                                            <p><?php echo $i . ". " . $row['name']; ?></p>
                                    <?php
                                        } // end of while..
                                    } catch (PDOException $e) {
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
} catch (PDOException $e) { }
?>