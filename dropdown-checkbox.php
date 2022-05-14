<?php
echo "<br>Project Chair</br>";
include 'db_connect.php';

$qry = "SELECT DISTINCT(concat(firstname,' ',lastname)) as name,id FROM users WHERE type BETWEEN 2 and 3";
if($result = $conn->query($qry)){
    echo "<SELECT id=chair_data onChange='reload()' name='chair_id' class='form-control' style='width:200px;'>";
    while($row=$result->fetch_assoc()){
        echo "<option value></option>";
        echo "<option value=$row[id]>$row[name]</option>";
    }
    echo "</select>";
}
else{
    echo $conn->error;
}
echo "</div><div class='col-3'><br>Team Members<br>";
$cid = $_GET['chairid'];
$qry2 = "SELECT concat(firstname,' ',lastname) as name,id FROM users WHERE type BETWEEN 2 and 3 AND id = ?";
if($stmt = $conn->prepare($qry2)){
    $stmt->bind_param('s',$cid);
    $stmt->execute();
    $result=$stmt->get_result();

    echo "<SELECT id=members multiple='multiple' name='user_ids[]' class='form-control' style='width:200px;'>";
    while($row=$result->fetch_assoc()){
        echo "<option value=$row[id]>$row[name]</option>";
    }
    echo "</select>";
}
else{
    echo $conn->error;
}

?>
<script>
function reload(){
    // alert($('#chairdata').val())
    var cid = document.getElementById('chair_data').value;
    // document.write(cid)
    self.location='dropdown-checkbox.php?chairid=' + cid;
}
</script>
