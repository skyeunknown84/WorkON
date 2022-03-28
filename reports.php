<?php include 'db_connect.php' ?>
 <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <b>Tasks Report</b>
            <div class="card-tools">
            	<button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="col-lg-12 d-flex p-3">
              <div class="col-md-4">
                <select name="status" id="status" class="custom-select custom-select-md form-control">
                  <option value="">Select Status</option>
                  <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Not Started</option>
                  <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Started</option>
                  <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>In Progress</option>
                  <option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>In Review</option>
                  <option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Completed</option>
                </select>
              </div>
              <div class="col-md-4">
                <?php

                ?>
                <select name="status" id="status" class="custom-select custom-select-md form-control">
                  <option value="">Select Assignee</option>
                  <option value="1">Jon</option>
                  <option value="2">Ethan</option>
                  <option value="3">Ellie</option>
                  <option value="4">Alice</option>
                  <option value="5">Sage</option>
                </select>
              </div>
              <div class="col-md-4">
                <input type="search" name="search" id="search" class="form-control" placeholder="Search" />
              </div>
            </div>
            <div class="table-responsive" id="printable">
              <table class="table m-0 table-bordered">
               <!--  <colgroup>
                  <col width="5%">
                  <col width="30%">
                  <col width="35%">
                  <col width="15%">
                  <col width="15%">
                </colgroup> -->
                <thead>
                  <th>#</th>
                  <th>Task</th>
                  <th>Assignee</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Work Duration</th>
                  <th>Progress</th>
                  <th>Status</th>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $stat = array("Not-Started","Started","In Progress","In Review","Over Due","Completed");
                $where = "";
                if($_SESSION['login_type'] == 2){
                  $where = " where manager_id = '{$_SESSION['login_id']}' ";
                }elseif($_SESSION['login_type'] == 3){
                  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                }
                $qry = $conn->query("SELECT *, concat(firstname,' ',lastname) AS uname FROM project_list INNER JOIN users $where order by name asc");
                while($row= $qry->fetch_assoc()):
                $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
                $dur = $conn->query("SELECT sum(time_rendered) as duration FROM user_productivity where project_id = {$row['id']}");
                $dur = $dur->num_rows > 0 ? $dur->fetch_assoc()['duration'] : 0;
                if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                if($prod  > 0  || $cprog > 0)
                  $row['status'] = 2;
                else
                  $row['status'] = 1;
                elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                $row['status'] = 4;
                endif;
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
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                              </div>
                          </div>
                          <small>
                              <?php echo $prog ?>% Complete
                          </small>
                      </td>
                      <td class="project-state">
                          <?php
                            if($stat[$row['status']] =='Not-Started'){
                              echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Started'){
                              echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='In Progress'){
                              echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='In Review'){
                              echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Over Due'){
                              echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Completed'){
                              echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                            }
                          ?>
                      </td>
                  </tr>
                <?php endwhile; ?>
                </tbody>  
              </table>
            </div>
          </div>
        </div>
        </div>
<script>
	$('#print').click(function(){
		start_load()
		var _h = $('head').clone()
		var _p = $('#printable').clone()
		var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date("F d, Y") ?>)</b></p>"
		_p.prepend(_d)
		_p.prepend(_h)
		var nw = window.open("","","width=900,height=600")
		nw.document.write(_p.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
			nw.close()
			end_load()
		},750)
	})
</script>