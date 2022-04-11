<?php
include 'db_connect.php';
class ProjectManagement {
    function getProjectTaskByStatus($statusId, $projectName) {
        $db_handle = new DBController();
        $query = "SELECT * FROM task_list WHERE status= ? AND project_id = ?";
        $result = $db_handle->runQuery($query, 'is', array($statusId, $projectName));
        return $result;
    }
    
    function getAllStatus() {
        $db_handle = new DBController();
        $query = "SELECT * FROM tbl_status";
        $result = $db_handle->runBaseQuery($query);
        return $result;
    }
    
    function editTaskStatus($status_id, $task_id) {
        $db_handle = new DBController();
        $query = "UPDATE tbl_task SET status_id = ? WHERE id = ?";
        $result = $db_handle->update($query, 'ii', array($status_id, $task_id));
        return $result;
    }
}

?>