<?php
function generateDocumentCountQuery($status) {
    $sql = "
    SELECT COUNT(*) FROM
        (
        SELECT 
            D.id, D.document_type, D.name AS document_name, 
            DT.id AS department_id, DT.name AS department_name,
            U.id  AS user_id, CONCAT(U.first_name, ' ', U.last_name) AS user_name,
            UDU.id AS department_user_id, CONCAT(UDU.first_name, ' ', UDU.last_name) AS department_user_name,
            DH.datetime, DH.id AS h_id, DH.remarks, DH.status 
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
            LEFT JOIN user U
                ON DU.user_id = U.id
        WHERE D.status = '" . $status . "' 
            AND (UDU.id = :user_id OR U.id = :user_id)
            AND DH.id=(SELECT MAX(id) FROM document_history WHERE document_id=D.id)
        GROUP BY D.id    
        ORDER BY DH.datetime ASC   
        ) AS tbl
    ";
    
    return $sql;
}

function generateTaskCountQuery($status) {
    $sql = "
    SELECT COUNT(*) FROM
        (
        SELECT 
            T.id AS tid 
        FROM task T
            INNER JOIN task_history TH
                ON T.id = TH.task_id
            LEFT JOIN task_department TD
                ON TH.id = TD.task_history_id
            LEFT JOIN department DT
                ON TD.department_id = DT.id
            LEFT JOIN user_department UD
                ON DT.id = UD.department_id
            LEFT JOIN user UDU
                ON UD.user_id = UDU.id
            LEFT JOIN task_user DU
                ON TH.id = DU.task_history_id
            LEFT JOIN user U 
                ON DU.user_id = U.id
        WHERE T.status = '" . $status . "' 
            AND (UDU.id = :user_id OR U.id = :user_id)
            AND TH.id=(SELECT MAX(id) FROM task_history WHERE task_id=T.id)
        GROUP BY T.id    
        ORDER BY TH.datetime ASC   
        ) AS tbl
    ";
    
    return $sql;
}

$COUNT_QUERY_COMPLETED   = generateTaskCountQuery('COMPLETED');
$COUNT_QUERY_RE_ASSIGNED = generateTaskCountQuery('RE-ASSIGNED');
$COUNT_QUERY_DONE        = generateTaskCountQuery('DONE');
$COUNT_QUERY_IN_PROGRESS = generateTaskCountQuery('IN-PROGRESS');
$COUNT_QUERY_ASSIGNED    = generateTaskCountQuery('ASSIGNED');

$COUNT_QUERY_SENT        = generateDocumentCountQuery('SENT');
$COUNT_QUERY_RECEIVED    = generateDocumentCountQuery('RECEIVED');
$COUNT_QUERY_FORWARDED   = generateDocumentCountQuery('FORWARDED');

try {
    $stmt = $conn->prepare("
        SELECT 
        (SELECT COUNT(*) FROM user) AS users, 
        (SELECT COUNT(*) FROM department) AS departments,
        (SELECT COUNT(*) FROM task WHERE user_id=:user_id) AS tasks,
        (SELECT COUNT(*) FROM document WHERE user_id=:user_id) AS documents,
        (SELECT COUNT(*) FROM task WHERE user_id=:user_id) AS documents,
        (". $COUNT_QUERY_COMPLETED . ") AS completed,
        (". $COUNT_QUERY_RE_ASSIGNED . ") AS re_assigned,
        (". $COUNT_QUERY_DONE . ") AS done,
        (". $COUNT_QUERY_IN_PROGRESS . ") AS in_progress,
        (". $COUNT_QUERY_ASSIGNED . ") AS assigned,
        (". $COUNT_QUERY_SENT . ") AS sent,
        (". $COUNT_QUERY_RECEIVED . ") AS received,
        (". $COUNT_QUERY_FORWARDED . ") AS forwarded
    ");
    $stmt->execute(["user_id" => $_SESSION['user']['id']]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}
$counter_data = [
    "document_tracking" => [
        [
            "page"       => "documents",
            "link"       => "./documents/",
            "bg-color"   => "primary",
            "font-color" => "text-white",
            "title"      => "Documents",
            "count"      => $count['documents']
        ],    
        [
            "page"       => "documents",
            "link"       => "./documents/?status=SENT",
            "bg-color"   => "info",
            "font-color" => "text-white",
            "title"      => "Sent",
            "count"      => $count['sent']
        ],
        [
            "page"       => "documents",
            "link"       => "./documents/?status=RECEIVED",
            "bg-color"   => "success",
            "font-color" => "text-white",
            "title"      => "Received",
            "count"      => $count['received']
        ],
        [
            "page"       => "documents",
            "link"       => "./documents/?status=FORWARDED",
            "bg-color"   => "warning",
            "font-color" => "text-white",
            "title"      => "Forwarded",
            "count"      => $count['forwarded']
        ],
    
    ],
    "task_management" => [
        [
            "page"       => "tasks",
            "link"       => "./tasks/",
            "bg-color"   => "primary",
            "font-color" => "text-white",
            "title"      => "Tasks",
            "count"      => $count['tasks']
        ],
        [
            "page"       => "tasks",
            "link"       => "./tasks/?status=COMPLETED",
            "bg-color"   => "danger",
            "font-color" => "text-white",
            "title"      => "Completed",
            "count"      => $count['completed']
        ],
        [
            "page"       => "tasks",
            "link"       => "./tasks/?status=RE-ASSIGNED",
            "bg-color"   => "info",
            "font-color" => "text-white",
            "title"      => "Re-Assigned",
            "count"      => $count['re_assigned']
        ],    
        [
            "page"       => "tasks",
            "link"       => "./tasks/?status=DONE",
            "bg-color"   => "success",
            "font-color" => "text-white",
            "title"      => "Done",
            "count"      => $count['done']
        ],    
        [
            "page"       => "tasks",
            "link"       => "./tasks/?status=IN-PROGRESS",
            "bg-color"   => "warning",
            "font-color" => "text-white",
            "title"      => "In-Progress",
            "count"      => $count['in_progress']
        ],         
        [
            "page"       => "tasks",
            "link"       => "./tasks/?status=ASSIGNED",
            "bg-color"   => "info",
            "font-color" => "text-white",
            "title"      => "Assigned",
            "count"      => $count['assigned']
        ],    
    
    ],
    "human_resource" => [
        [
            "page"       => "users",
            "link"       => "./users/",
            "bg-color"   => "danger",
            "font-color" => "text-white",
            "title"      => "Users",
            "count"      => $count['users']
        ],
        [
            "page"       => "departments",
            "link"       => "./departments/",
            "bg-color"   => "success",
            "font-color" => "text-white",
            "title"      => "Departments",
            "count"      => $count['departments']
        ],       
    ],
]
?>

