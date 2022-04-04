<?php
require "db_connect.php";
class BoardManagement {
    function getProjectTaskByStatus($statusId, $projectName) {
        $db_handle = new DBController();
        $query = "SELECT * FROM task_list WHERE status= ? AND task = ?";
        $result = $db_handle->runQuery($query, 'is', array($statusId, $projectName));
        return $result;
    }
    
    function getAllStatus() {
        $db_handle = new DBController();
        $query = "SELECT * FROM task_list";
        $result = $db_handle->runBaseQuery($query);
        return $result;
    }
    
    function editTaskStatus($status, $task_id) {
        $db_handle = new DBController();
        $query = "UPDATE task_list SET status = ? WHERE id = ?";
        $result = $db_handle->update($query, 'ii', array($status, $task_id));
        return $result;
    }
}
?>