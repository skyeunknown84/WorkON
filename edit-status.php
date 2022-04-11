<?php 
require_once "ProjectManagement.php";
$boardManagement = new BoardManagement();

$status_id = $_GET["status_id"];
$task_id = $_GET["task_id"];

$result = $boardManagement->editTaskStatus($status_id, $task_id);
?>