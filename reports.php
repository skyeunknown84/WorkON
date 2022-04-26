<?php 
  include 'db_connect.php';
  if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
    foreach($user->fetch_array() as $k =>$v){
      $meta[$k] = $v;
    }
    $fname = $_SESSION['login_firstname'];
    $lname = $_SESSION['login_lastname'];
  }
?>
 <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <b>Tasks Report </b>
            <input type="hidden" id="assign_author" value="<?php echo $_SESSION['login_firstname'] ?> <?php echo $_SESSION['login_lastname'] ?>">
            <div class="card-tools">
            	<button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
          <div class="card-body p-0">

          <div class="px-2">
            <div class="col-md-12">
              <div class="col-lg-12 d-flex p-3">
                <div class="col-md-4">
                  <select name="status" id="fetch_status" class="custom-select custom-select-md form-control">
                    <option value="">Select Status</option>
                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Not Started</option>
                    <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Started</option>
                    <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>In Progress</option>
                    <option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>In Review</option>
                    <option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Completed</option>
                  </select>
                </div>
                <div class="col-md-4">
                  
                  <select name="status" id="fetch_assignee" class="custom-select custom-select-md form-control">                
                    <option value="">Select Assignee</option>
                    <?php
                      $qry = $conn->query("SELECT concat(firstname,' ',lastname) as assignee,type FROM users WHERE type BETWEEN 2 AND 3");
                      while($row = $qry->fetch_assoc()):
                        $chair = "(Chair)";
                        $faculty = "(Faculty)";
                        
                        $usertype = $row['type'];
                        if($usertype=='2'){
                          $name = $chair;
                        }elseif($usertype=='3'){
                          $name = $faculty;
                        }
                    ?>
                    <option value="<?php echo $row['assignee'] ?>"><?php echo $row['assignee'] ?> - <?= $name ?></option>
                    <?php endwhile ?>
                    
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="search" name="reports_search" id="reports_search" class="form-control" placeholder="Search Task Name" />
                </div>
              </div>
            </div>
          </div>
            

            <div class="table-responsive px-2" id="printable">
              <div class="text-center" id="filterresult"></div>
              <div class="text-center" id="reportresult">
                <?php
                $where = "";
                if($_SESSION['login_type'] == 2){
                  $where = " where manager_id = '{$_SESSION['login_id']}' ";
                }elseif($_SESSION['login_type'] == 3){
                  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                }
                // $filter_reports = "SELECT *,concat(firstname,' ',lastname) AS assignee FROM users u INNER JOIN project_list p  WHERE type BETWEEN 2 AND 3 and status = ";
                $filter_reports = "SELECT * FROM project_list p INNER JOIN users u ON p.user_ids = u.id $where order by name asc";
                $result = mysqli_query($conn,$filter_reports);
                $count = mysqli_num_rows($result);
                if($count > 0){
                ?>
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
                              if($row['status'] == 1){
                                  echo "<span class='badge badge-secondary'>Not Started</span>";
                              }elseif($row['status'] == 2){
                              echo "<span class='badge badge-primary'>Started</span>";
                              }elseif($row['status'] == 3){
                              echo "<span class='badge badge-info'>In Progress</span>";
                              }elseif($row['status'] == 4){
                              echo "<span class='badge badge-warning'>In Review</span>";
                              }elseif($row['status'] == 5){
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
                }else{ ?>
                <div class="container-fluid">
                  <div class="card card-outline card-success">
                    <div class="card-body py-5">
                      <div class="py-5 my-5 mx-auto">
                        <p class="py-5 my-5 mx-auto text-center">No data(s) found...</p>
                      </div>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        </div>
<style>
  .callout {
    /* border-radius: 0.25rem; */
    box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
    background-color: #fff;
    margin-bottom: 1rem;
    padding: 1rem;
  }
</style>
<script>
  $(function(){
		$('#list').dataTable();
    $('#print').click(function(){
      start_load()
      var assign_author = document.getElementById('assign_author').value;
      console.log(assign_author);
      var _h = $('head').clone()
      var _p = $('#printable').clone()
      var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date("F d, Y") ?>). Printed by: "+ assign_author +"</b></p>"
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
    });
    // Search Live for Task Name
    $("#reports_search").keyup(function(){
        var input = $(this).val();
        // alert(input)
        if(input != ""){
            $.ajax({
                url: "reports_search.php",
                method: "POST",
                data: {input:input},
                beforeSend:function(){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html("<span>Working...</span>");
                },
                success:function(data){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html(data);
                  $("#filterresult").css("display","block");
                }
            });
        }
        else {
          $("#filterresult").css("display","none");
          $("#reportresult").css("display","block");
        }
    });
    // Fetch Status from Selected options in #fetch_assignee ID
    $("#fetch_status").on('change', function(){
        var value = $(this).val();
        if(value != ""){
            $.ajax({
                url: "reports_status.php",
                type: "POST",
                data: 'request=' + value,
                beforeSend:function(){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html("<span>Working...</span>");
                },
                success:function(data){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html(data);
                  $("#filterresult").css("display","block");
                }
            });
        }
        else {
          $("#filterresult").css("display","none");
          $("#reportresult").css("display","block");
        }
    });
    // Fetch Assignee from Selected options in #fetch_assignee ID
    $("#fetch_assignee").on('change', function(){
        var value = $(this).val();
        if(value != ""){
            $.ajax({
                url: "reports_assignee.php",
                type: "POST",
                data: 'request=' + value,
                beforeSend:function(){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html("<span>Working...</span>");
                },
                success:function(data){
                  $("#reportresult").css("display","none");
                  $("#filterresult").html(data);
                  $("#filterresult").css("display","block");
                }
            });
        }
        else {
          $("#filterresult").css("display","none");
          $("#reportresult").css("display","block");
        }
    });
	})
	
</script>