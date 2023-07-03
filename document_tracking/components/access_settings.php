<?php
$page_restrictions = [
    'ADMIN' => ['dashboard', 'users', 'departments', 'projects', 'tasks', 'documents', 'reports', 'account'],
    'USER' => ['dashboard', 'tasks', 'documents', 'account'],
];
$role = $_SESSION['user']['role'];
$accessible_pages = $page_restrictions[$role];
?>
