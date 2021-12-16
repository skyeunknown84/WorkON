<?php
include 'db_connect.php';
$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 5")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-4">
							<dl>
								<dt><b class="border-bottom border-primary">Project Name</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Description</b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
							</dl>
						</div>
						<div class="col-md-4">
							<dl>
								<dt><b class="border-bottom border-primary">Start Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">End Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Status</b></dt>
								<dd>
									<?php
									  if($stat[$status] =='Not Started'){
									  	echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Started'){
									  	echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='In Progress'){
									  	echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='In Review'){
									  	echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Over Due'){
									  	echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Completed'){
									  	echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									  }
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Project Manager</b></dt>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['name']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Manager Deleted from Database</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
						<div class="col-sm-4">
							<dl>
								<dt><b class="border-bottom border-primary">Documentation Link:</b></dt>
								<dd><a href="<?php echo $project_url ?>" class="btn btn-success btn_url mt-1 pt-0 pb-0" target="_blank" rel="noopener noreferrer"><i class="fa fa-link"></i> Google Docs</a></dd>
								<dt><b class="border-bottom border-primary">Time Sheet Link:</b></dt>
								<dd><a href="<?php echo $project_time_sheet ?>" class="btn btn-success btn_url mt-1 pt-0 pb-0" target="_blank" rel="noopener noreferrer"><i class="fa fa-link"></i> Time Sheet</a></dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-success">
				<div class="card-header">
					<span><b>PROJECT MEMBER/S</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
			                        <!-- <span class="users-list-date">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-success">
				<div class="card-header">
					<div class="row">
						<div class="col-6"><b>Task List:</b></div>
						<div class="col-6 text-right">
						<?php if($_SESSION['login_type'] != 3): ?>
							<button class="btn btn-primary bg-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
						<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="card-body ps-3 pe-3 pb-2-pt-1">
					<div class="">
					<table class="table table-condensed table-hover table-responsive x-scroll" id="datalist">
						<colgroup>
							<col width="5%">
							<col width="25%">
							<col width="30%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<th>#</th>
							<th>Task</th>
							<th>Assignee</th>
							<th>Status</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$tasks = $conn->query("SELECT * FROM task_list where project_id = {$id} order by task asc");
							while($row=$tasks->fetch_assoc()):
								// $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
								// unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
								// $desc = strtr(html_entity_decode($row['description']),$trans);
								// $desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
							?>
								<tr>
			                        <td class="text-center"><?php echo $i++ ?></td>
			                        <td class="" style="min-width:250px"><b><?php echo ucwords($row['task']) ?></b></td>
			                        <td class=""><b><?php echo ucwords($row['task_owner']) ?></b></td>
			                        <td>
			                        	<?php 
			                        	if($row['status'] == 1){
											echo "<span class='badge badge-secondary'>Not Started</span>";
									  }elseif($row['status'] == 2){
										  echo "<span class='badge badge-primary'>Started</span>";
										}elseif($row['status'] == 3){
										  echo "<span class='badge badge-primary'>In Progress</span>";
										}elseif($row['status'] == 4){
										  echo "<span class='badge badge-primary'>In Review</span>";
										}elseif($row['status'] == 5){
										  echo "<span class='badge badge-primary'>Over Due</span>";
										}elseif($row['status'] == 6){
											echo "<span class='badge badge-success'>Completed</span>";
									  }
			                        	?>
			                        </td>
			                        <td class="text-center">
										<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					                      Action
					                    </button>
					                    <div class="dropdown-menu" style="">
					                      <a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>"><i class="fa fa-eye mx-1"></i> View</a>
					                      <div class="dropdown-divider"></div>
					                      <?php if($_SESSION['login_type'] != 3): ?>
					                      <a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>"><i class="fa fa-pencil-alt mx-1"></i> Edit</a>
					                      <div class="dropdown-divider"></div>
					                      <a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash mx-1"></i> Delete</a>
					                  <?php endif; ?>
					                    </div>
									</td>
		                    	</tr>
							<?php 
							endwhile;
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card card-outline card-success">
				<div class="card-header">
					<b>PROJECT FILE/S</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Add Task Productivity</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$progress = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.task FROM user_productivity p inner join users u on u.id = p.user_id inner join task_list t on t.id = p.task_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
					while($row = $progress->fetch_assoc()):
					?>
						<div class="post">

							<div class="user-block">
		                      	<?php if($_SESSION['login_id'] == $row['user_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>"><i class="fa fa-pencil-alt mx-1"></i> Edit</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash mx-1"></i> Delete</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
		                        <span class="username">
		                          <a href="#"><?php echo ucwords($row['uname']) ?>[ <?php echo ucwords($row['task']) ?> ]</a>
		                        </span>
		                        <span class="description">
		                        	<span class="fa fa-calendar-day" title="Date Updated"></span>
		                        	<span><b><?php echo date('M d, Y',strtotime($row['date'])) ?></b></span>
									<span> | </span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span> Start: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['start_time'])) ?></b></span>
		                        	<span> | </span>
                      				<span>End: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['end_time'])) ?></b></span>
	                        	</span>
								<div class="ps-1">
		                       		<?php echo html_entity_decode($row['comment']) ?>
		                      	</div>
								  	<div class="ps-1">
									  <span class="fa fa-link pr-1" title="attachment (screenshots / docs / recorded video)"></span><a href="<?php echo html_entity_decode($row['url_productivity']) ?>" target="_blank" rel="noopener noreferrer"><?php echo html_entity_decode($row['url_productivity']) ?></a>
		                      		</div>
							</div>
		                      	<!-- /.user-block -->
		                      	
								<!-- Files/Documents/Images/Recordings -->
								<div class="col-md-12 hide">
									<!-- <form action="upload_file.php" id="form" method="post" encytype="multipart/form-data">
										<input type="file" name="file" id="myFile">
										<input type="submit" id="submit" value="Upload">
									</form>	 -->
								</div>								
		                      <p class="hide">
		                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a> -->
		                      </p>
	                    </div>
	                    <!-- <div class="post clearfix"></div> -->
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.btn_url {
		text-decoration: none !important;
		color: #fff !important;
	}
	.users-list>li img {
	    border-radius: 50%;
	    height: 57px;
	    width: 57px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important;
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	// dataTables Search and Sort
	$(document).ready(function(){
		$('#datalist').dataTable()
	})
	// Links For Pages & Modal which connect to ajax.php and admin_class.php
	$('#new_task').click(function(){
		uni_modal("New Task For <?php echo ucwords($name) ?>","manage_task.php?pid=<?php echo $id ?>","mid-large")
	})
	// Modal goes to manage_task php to edit
	$('.edit_task').click(function(){
		uni_modal("Edit Task: "+$(this).attr('data-task'),"manage_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	// Modal goes to view_task php
	$('.view_task').click(function(){
		uni_modal("Task Details","view_task.php?id="+$(this).attr('data-id'),"mid-large")
	})
	// Add Link to Modal for Add Task Productivity
	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New Progress","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	// Edit Link to Modal for Add Task Productivity
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Edit Progress","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	// Delete Link to Modal for Add Task Productivity
	$('.delete_progress').click(function(){
	_conf("Are you sure to delete this progress?","delete_progress",[$(this).attr('data-id')])
	})
	function delete_progress($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>