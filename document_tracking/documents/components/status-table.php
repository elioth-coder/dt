<?php
$sql = "
    SELECT 
        D.datetime, D.status, D.id, D.document_type, D.name AS document_name, 
        DH.receiver_type, DH.user_id,
        U.username,
        DT.id AS department_id, DT.name AS department_name,
        DTU.id AS department_id_user, DT.name AS department_name_user,
        U.id  AS user_id, CONCAT(U.first_name, ' ', U.last_name) AS user_name,
        UDU.id AS department_user_id, CONCAT(UDU.first_name, ' ', UDU.last_name) AS department_user_name,
        DH.id AS h_id, DH.remarks, DH.status 
    FROM document D
        INNER JOIN document_history DH
            ON D.id = DH.document_id
        LEFT JOIN document_department DD
            ON DH.id = DD.document_history_id
        LEFT JOIN department DT
            ON DD.department_id = DT.id
        LEFT JOIN user_department UD
            ON DT.id = UD.department_id
        LEFT JOIN user UDU
            ON UD.user_id = UDU.id
        LEFT JOIN document_user DU
            ON DH.id = DU.document_history_id
        LEFT JOIN department DTU
            ON DU.department_id = DTU.id
        LEFT JOIN user U 
            ON DU.user_id = U.id
    WHERE D.status = :status 
        AND (UDU.id = :user_id OR U.id = :user_id)
        AND DH.id=(SELECT MAX(id) FROM document_history WHERE document_id=D.id)
    GROUP BY D.id    
    ORDER BY DH.datetime ASC                       
";
?>

<table class="table bg-white table-striped table-hover table-bordered">
    <thead class="text-primary">
        <tr>
            <th class="text-center">DATETIME</th>
            <th class="text-center">STATUS</th>
            <th class="text-center">DOCUMENT</th>
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
                'RECEIVED'  => "bg-success",
                'SENT'      => "bg-info",
                'FORWARDED' => "bg-warning",
            ];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data = str_replace("'", "_", json_encode($row));
        ?>
                    <tr>
                        <td style="width: 190px;" class="text-center"><?php echo $row['datetime']; ?></td>
                        <td style="width: 170px;" class="text-center">
                            <span class="fs-6 p-2 d-block w-100 badge <?php echo $BACKGROUNDS[$row['status']]; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <h4><?php echo $row['document_name']; ?></h4>
                            <span class="badge bg-primary p-2"><?php echo $row['document_type']; ?></span><br>
                            <?php
                            $stmt_1 = $conn->prepare("SELECT * FROM user WHERE id=(SELECT user_id FROM document WHERE id=:document_id)");
                            $stmt_1->execute(["document_id" => $row['id']]);
                            $creator = $stmt_1->fetch(PDO::FETCH_ASSOC);
                            ?>
                        </td>
                        <td style="width: 140px;" class="text-center">
                            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick='viewDocumentHistory(<?php echo $data; ?>);' class="btn btn-outline-info">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <?php
                            if (in_array($row['status'], ['SENT', 'FORWARDED'])) { ?>
                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Receive" onclick='receiveDocument(<?php echo $data; ?>);' class="btn btn-outline-success">
                                    <i class="bi bi-box-arrow-in-down-left"></i>
                                </button>
                            <?php
                            }

                            if ($row['status'] == 'RECEIVED') { ?>
                                <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Forward" onclick='forwardDocument(<?php echo $data; ?>);' class="btn btn-outline-secondary">
                                    <i class="bi bi-box-arrow-up-right"></i>
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
        } catch (PDOException $e) {
        }  ?>
    </tbody>
</table>