<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
$where_admin = "";
if($_SESSION['login_type'] == 1){
	$where_admin = "WHERE p.manager_id = {$_SESSION['login_id']}";
}

$where_member = "";
if($_SESSION['login_type'] == 3){
	$where_member = "WHERE chair_id = {$_SESSION['login_id']} OR concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'";
}
// else{
// 	$where_member = "WHERE concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'";
// }
?>
<div class="row">
        <div class="col-md-8">
          <div class="card card-outline card-success">
            <div class="card-header">
              <b>Project Progress</b>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0 table-hover">
                  <colgroup>
                    <col width="5%">
                    <col width="30%">
                    <col width="35%">
                    <col width="15%">
                    <col width="15%">
                  </colgroup>
                  <thead>
                    <th># </th>
                    <th>Project Name</th>
                    <th class="hide">Assignee</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th></th>
                  </thead>
                  <tbody>
                  <?php
                  $i = 1;
                  $stat = array("Not-Started","Started","In Progress","In Review","Completed");
                  $where = "";
                  if($_SESSION['login_type'] == 2){
                    $where = " where manager_id = '{$_SESSION['login_id']}' ";
                  }elseif($_SESSION['login_type'] == 3){
                    $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                  }

                  $adminid = $_SESSION['login_id'];
                  $deanid = $_SESSION['login_id'];
                  $chairid = $_SESSION['login_id'];
                  $memberid = $_SESSION['login_id'];
                  if($_SESSION['login_type'] == 1){
                    $qry = $conn->query("SELECT * FROM project_list GROUP BY id asc");
                    while($row= $qry->fetch_assoc()):
                      $prog= 0;
                      $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                      $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
                      // $prostat = $conn->query("SELECT *,t.status as tstat  FROM task_list t INNER JOIN project_list p ON t.project_id = p.id where t.project_id = {$row['id']}");
                      //   if($row= $qry->fetch_assoc()):
                      //     if($row['tstat'] <= 4)
                      $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                      $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                      $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
                      if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                      if($prod  > 0  || $cprog > 0)
                        $row['status'] = 1;
                      else
                        $row['status'] = 0;
                      elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                      $row['status'] = 4;
                      endif;
                      // encrypt id params
                      $param_id = $row['id'];
                      // make id longer
                      $long_param_id = ($param_id * '8967452390');
                      // encrypt data with base64 
                      $url_param_id = urlencode($long_param_id);
                    ?>
                    <tr>
                      <td>
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
                      <td class="hide">
                        <?php echo ucwords($row['name']) ?>
                      </td>
                      <td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar progressbar-success" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                              </div>
                          </div>
                          <small>
                              <?php echo $prog ?>% Complete
                          </small>
                      </td>
                      <td class="project-state">
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
                      <td>
                        <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $url_param_id ?>">
                              <i class="fas fa-folder">
                              </i>
                              View
                        </a>
                      </td>
                    </tr>
                    <?php endwhile; 
                  }	
                  if($_SESSION['login_type'] == 3){
                    $qry = $conn->query("SELECT * FROM project_list $where_member GROUP BY name asc");
                    while($row = $qry->fetch_assoc()){
                      // $user_id = explode(',',$row['user_ids']);
                      $prog= 0;
                        $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                        $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
                        // $prostat = $conn->query("SELECT *,t.status as tstat  FROM task_list t INNER JOIN project_list p ON t.project_id = p.id where t.project_id = {$row['id']}");
                        //   if($row= $qry->fetch_assoc()):
                        //     if($row['tstat'] <= 4)
                        $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                        $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                        $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
                        if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                        if($prod  > 0  || $cprog > 0)
                          $row['status'] = 1;
                        else
                          $row['status'] = 0;
                        elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                        $row['status'] = 4;
                        endif;
                      if ($row['chair_id'] == $_SESSION['login_id']){ ?>
                        <tr>
                          <td>
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
                          <td class="hide">
                            <?php echo ucwords($row['name']) ?>
                          </td>
                          <td class="project_progress">
                              <div class="progress progress-sm">
                                  <div class="progress-bar bg-green" role="progressbar progressbar-success" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                  </div>
                              </div>
                              <small>
                                  <?php echo $prog ?>% Complete
                              </small>
                          </td>
                          <td class="project-state">
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
                          <td>
                            <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                                  <i class="fas fa-folder">
                                  </i>
                                  View
                            </a>
                          </td>
                        </tr>
                      <?php
                      }
                      if($row['chair_id'] != $_SESSION['login_id']){
                      ?>
                        <tr>
                          <td>
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
                          <td class="hide">
                            <?php echo ucwords($row['name']) ?>
                          </td>
                          <td class="project_progress">
                              <div class="progress progress-sm">
                                  <div class="progress-bar bg-green" role="progressbar progressbar-success" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                  </div>
                              </div>
                              <small>
                                  <?php echo $prog ?>% Complete
                              </small>
                          </td>
                          <td class="project-state">
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
                          <td>
                            <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                                  <i class="fas fa-folder">
                                  </i>
                                  View
                            </a>
                          </td>
                        </tr>
                      <?php  
                      }
                    }
                  }
                  ?>
                  </tbody>  
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="row">
          <div class="col-12 col-sm-6 col-md-12">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner bg-success">
                <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>

                <p>Total Projects</p>
              </div>
              <div class="icon">
                <i class="fa fa-layer-group"></i>
              </div>
            </div>
          </div>
           <div class="col-12 col-sm-6 col-md-12">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner bg-info">
                <h3><?php echo $conn->query("SELECT t.*,p.* FROM task_list t INNER JOIN project_list p ON t.project_id = p.id WHERE t.status = '5' AND p.status = '5' GROUP BY p.id HAVING count(p.id)")->num_rows; ?></h3>
                <p>Completed Projects</p>
              </div>
              <div class="icon">
                <i class="fa fa-tasks"></i>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>