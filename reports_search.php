<?php
echo "HELLO";
include 'db_connect.php';
if(isset($_POST['input'])){
    $input = $_POST['input'];

    $query = "SELECT * FROM project_list WHERE name LIKE {$input}%";
    $result = mysqli_query($conn, $query);

    if(mysql_num_rows()){

    }
}
?>