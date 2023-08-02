<?php
$COLORS = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
$STATUS_COLOR = [
    'RECEIVED'  => "success",
    'SENT'      => "info",
    'FORWARDED' => "warning",
];
$DOCTYPE_COLOR = [];
$stmt = $conn->prepare("SELECT DISTINCT(document_type) AS document_type FROM document");
$stmt->execute();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $DOCTYPE_COLOR[$row['document_type']] = $COLORS[$i];
    $i++;
    if ($i == 8) $i = 0;
}