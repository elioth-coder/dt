<table id="DataTableDocument" class="table bg-light table-striped table-hover table-bordered">
    <thead class="text-primary">
        <tr>
            <th class="text-center">ACTION</th>
            <th class="text-end">DATETIME</th>
            <th class="">STATUS</th>
            <th class="">DOCTYPE</th>
            <th class="text-center">DOCUMENT</th>
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
                        <td style="width: 140px;" class="text-center">
                            <button data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                data-bs-title="View History" 
                                onclick='viewDocumentHistory(<?php echo $data; ?>);' 
                                class="btn btn-outline-primary">
                                <i class="bi bi-clock-history"></i>
                            </button>
                            <?php
                            if (in_array($row['status'], ['SENT', 'FORWARDED'])) { ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Receive" 
                                    onclick='receiveDocument(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-box-arrow-in-down-left"></i>
                                </button>
                            <?php
                            }

                            if ($row['status'] == 'RECEIVED') { ?>
                                <button data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-title="Forward" 
                                    onclick='forwardDocument(<?php echo $data; ?>);' 
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </button>
                            <?php
                            }
                            ?>
                        </td>
                        <td class="text-end">
                            <?php echo $row['datetime']; ?>
                        </td>
                        <td>
                            <span class="badge text-bg-<?php echo $STATUS_COLOR[$row['status']]; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-<?php echo $DOCTYPE_COLOR[$row['document_type']]; ?>">
                                <?php echo $row['document_type']; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $row['document_name']; ?>
                        </td>
                    </tr>
                <?php
                }
            }
        } catch (PDOException $e) { }  ?>
    </tbody>
</table>