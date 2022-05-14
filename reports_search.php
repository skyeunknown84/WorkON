<?php
include 'db_connect.php';

if(isset($_POST['input'])){
    $input = $_POST['input'];

    $query = "SELECT *,concat(firstname,' ',lastname) AS assignee FROM users u INNER JOIN project_list p WHERE name LIKE '{$input}%' AND type BETWEEN 2 AND 3 AND p.proj_status='1'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) > 0){ ?>
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <th>#</th>
                <th>TASK NAME</th>
                <th>ASSIGNEE</th>
                <th>STARTED</th>
                <th>ENDED</th>
                <th>PROGRESS</th>
                <th>STATUS</th>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                while($row = mysqli_fetch_assoc($result)){
                    
                    $id = $row['id'];
                    $taskname = $row['name'];
                    $assignee = $row['assignee'];
                    $profile = $row['avatar'];

                    $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                    $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
                    $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                    $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                ?>

                <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $taskname ?></td>
                    <td><?php echo $assignee ?></td>
                    <td><?php echo date("Y-m-d",strtotime($row['start_date'])) ?></td>
                    <td><?php echo date("Y-m-d",strtotime($row['end_date'])) ?></td>
                    <td class="project_progress">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                            </div>
                        </div>
                        <small>
                            <?php echo $prog ?>% Complete
                        </small>
                    </td>
                    <td>
                        <?php
                        if($row['status'] == 0){
                            echo "<span class='badge badge-secondary'>Not Started</span>";
                        }elseif($row['status'] == 1){
                        echo "<span class='badge badge-primary'>Started</span>";
                        }elseif($row['status'] == 2){
                        echo "<span class='badge badge-info'>In Progress</span>";
                        }elseif($row['status'] == 3){
                        echo "<span class='badge badge-warning'>In Review</span>";
                        }elseif($row['status'] == 4){
                        echo "<span class='badge badge-success'>Completed</span>";
                        }
                        // elseif($row['status'] == 6){
                        // 	echo "<span class='badge badge-success'>Completed</span>";
                        // }
                        ?>
                    </td>
                </tr>

                <?php 
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    else { ?>
        <div class="container-fluid">
            <div class="card card-outline card-success">
            <div class="card-body py-5">
                <div class="py-5 my-5 mx-auto">
                <p class="py-5 my-5 mx-auto text-center">No data(s) found...</p>
                </div>
            </div>
            </div>
        </div>
    <?php 
    }
}

?>