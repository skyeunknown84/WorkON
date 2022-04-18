<?php 
echo 'fetch';
include 'db_connect.php';
$where = "";
if($_SESSION['login_type'] == 2){
    $where = " where manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
    $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
if(isset($_POST['request'])){
    
    $request = $_POST['request'];

    $query = "SELECT * FROM project_list p INNER JOIN users u WHERE p.status = '$request'";
    
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

?>
<table class="table">
    <?php 
        if($count) {
    ?>

    <thead>
        <th>#</th>
        <th>Task</th>
        <th>Assignee</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Work Duration</th>
        <th>Progress</th>
        <th>Status</th>

        <?php 
            }else{
                echo 'Sorry! No Record Found';
            }
        ?>
    </thead>

    <tbody>
        <?php 
            while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td width="10px">
                <?php echo $i++ ?>
            </td>
            <td>
                <a>
                    <?php echo ucwords($row['name']) ?>
                </a>
                <br>
                <small>
                    Due: <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
                </small>
            </td>
            <td>
            <?php echo ucwords($row['uname']) ?>
            </td>
            <td class="text-center">
            <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
            </td>
            <td class="text-center">
            <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
            </td>
            <td class="text-center">
            <?php echo number_format($dur).' Hr/s.' ?>
            </td>
            <td class="project_progress">
                
            </td>
            <td class="project-state">
            
            </td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>
<?php
}
?>