<?php
    include 'db_connect.php';

    $sql = "SELECT *,concat(firstname,' ',lastname) as name FROM users WHERE id = {$_POST['id']}";

    $res = $conn->query($sql);

    echo "<option value=''>select</option>";
    while($row->$res->fetch_assoc())
    {
        echo "<option value='{$row["id"]}'>{$row['name']}</option>";
    }

    // if(isset($_GET['']))
    
?>