<?php
include 'db_connect.php';
$stat = array("Not Started","Started","In Progress","In Review","Completed");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 4")->num_rows;
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
$where = "";
if($_SESSION['login_type'] == 2){
	$where = " where manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
	$where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
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
							<dl>
								<dt><b class="border-bottom border-primary">Status</b></dt>
								<dd>
									<?php 
										if($stat[$status] == 'Not Started') {
											echo "<span class='badge badge-secondary'>Not Started</span>";
										}
										elseif($stat[$status] == 'Started') {
											echo "<span class='badge badge-primary'>Started</span>";
										}
										elseif($stat[$status] == 'In Progress') {
											echo "<span class='badge badge-info'>In Progress</span>";
										}
										elseif($stat[$status] == 'In Review') {
											echo "<span class='badge badge-warning'>In Review</span>";
										}
										elseif($stat[$status] == 'Completed') {
											echo "<span class='badge badge-success'>Completed</span>";
										}
									?>
								</dd>
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
						</div>						
						<div class="col-sm-4 hide">
							<dl>
								<dt><b class="border-bottom border-primary">Documentation Link:</b></dt>
								<dd><a href="<?php echo ucwords($project_time_sheet) ?>" class="btn btn-success btn_url mt-1 pt-0 pb-0" target="_blank" rel="noopener noreferrer"><i class="fa fa-link"></i> Google Docs</a></dd>
								<dt><b class="border-bottom border-primary">Time Sheet Link:</b></dt>
								<dd><a href="<?php echo ucwords($project_time_sheet) ?>" class="btn btn-success btn_url mt-1 pt-0 pb-0" target="_blank" rel="noopener noreferrer"><i class="fa fa-link"></i> Time Sheet</a></dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 hide">
			<div class="card card-outline card-success">
				<div class="card-header">
					<span><b>TASK MEMBER/S</b></span>
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
		<div class="col-md-12">
			<div class="card card-outline card-success">
				<div class="card-header">
					<div class="row">
						<div class="col-6 p-0 m-0">
							<ul class="nav nav-pills ml-auto m-0 p-0">
								<li class="nav-item  btn-sm"><a class="nav-link btn-sm" href="#listtask" data-toggle="tab"><i class="fa fa-tasks pr-2"></i>Task List</a></li>
								<li class="nav-item hide btn-sm"><a class="nav-link btn-sm" href="#boardtask" data-toggle="tab"><i class="fa fa-layer-group pr-2"></i> Board</a></li>
							</ul>
						</div>
						<div class="col-6 text-right">
						<?php if($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 1): ?>
							<button class="btn btn-primary bg-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
						<?php endif; ?>
						</div>
					</div>
				</div>
				
				<div class="card-body">
					<div class="tab-content px-0 mx-0" id="pills-tabContent">
						<div class="tab-panel col-lg-12" id="listtask" role="tabpanel" aria-labelledby="pills-list-tab">
							<div class="col-lg-12">
							<table class="table table-hover table-condensed x-scroll" id="datalist">
								<colgroup>
									<col width="5%">
									<col width="50%">
									<col width="15%">
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
										$_SESSION['id'] = $row['id'];
										$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
										unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
										$desc = strtr(html_entity_decode($row['description']),$trans);
										$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);											
											// fetch members in array
											// $proj_ids = $row['project_id']; 
											$_SESSION['pid'] = $row['project_id'];											
											// $data_img = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users u INNER JOIN project_list p ON u.id = p.user_ids INNER JOIN task_list t ON t.project_id = p.id WHERE t.id in ($proj_ids) order by t.task asc");
											// start_time
										// $qry_start_time = "";
									?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td class="" style="min-width:250px"><b><?php echo ucwords($row['task']) ?></b></td>
											<td class="">																						
												<ul>
													<li>
														<span><b><?php echo ucwords($row['task_owner']) ?></b></span>
													</li>
												</ul>
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
											<td class="text-center">
												<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
												Action
												</button>
												<div class="dropdown-menu" style="">
												<a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>"><i class="fa fa-eye mx-1"></i> View</a>
												<div class="dropdown-divider"></div>
												<?php if($_SESSION['login_type'] != 3): ?>
												<a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>"><i class="fa fa-pencil-alt mx-1"></i> Edit</a>
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
						<div class="tab-pane hide" id="boardtask" role="tabpanel" aria-labelledby="pills-list-tab">
							<?php include 'board_task.php' ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card card-outline card-success">
				<div class="card-header">
					<b>TASK FILE/S</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Add Task Files</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$progress = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.task FROM user_productivity p inner join users u on u.id = p.user_id inner join task_list t on t.id = p.task_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
					while($row = $progress->fetch_assoc()):
					?>
						<div class="card pl-1 pr-3 py-2 m-0 post">

							<div class="user-block m-0">
		                      	<?php if($_SESSION['login_id'] == $row['user_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle py-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
									<a class="dropdown-item view_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>"><i class="fa fa-comments mx-1"></i> Comments</a>
										<div class="dropdown-divider"></div>
									<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>"><i class="fa fa-pencil-alt mx-1"></i> Edit</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash mx-1"></i> Delete</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm hide" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
		                        <span class="username py-2 mx-3">
		                          <a href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" class="view_progress"><?php echo ucwords($row['task']) ?></a>
		                        </span>
		                        <span class="description hide">
		                        	<span class="fa fa-calendar-day" title="Date Updated"></span>
		                        	<span><b><?php echo date('M d, Y',strtotime($row['date'])) ?></b></span>
									<span> | </span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span> Start: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['start_time'])) ?></b></span>
		                        	<span> | </span>
                      				<span>End: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['end_time'])) ?></b></span>
	                        	</span>
								<div class="ps-1 hide">
								<span class="fa fa-comments" title="comment">  </span> <?php echo html_entity_decode($row['comment']) ?>
		                      	</div>
								<div class="ps-1 hide">
									<span class="fa fa-link pr-1" title="attachment (screenshots / docs / recorded video)"></span><a href="<?php echo $row['file_path'] ?>" target="_blank" rel="noopener noreferrer"><?php echo $row['file_name'] ?></a>
								</div>
							</div>
							<!-- /.user-block -->
	                    </div>
	                    <div class="clearfix p-1 m-0"></div>
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
		$('#datalist').dataTable();
		
	})
	$('.view_user').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_user.php?id="+$(this).attr('data-id'))
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
		uni_modal("Task Details","view_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	// Add Link to Modal for Add Task Productivity
	$('#new_productivity').click(function(){		
		uni_modal("<i class='fa fa-plus'></i> Task Progress","add_progress.php?pid=<?php echo $id ?>",'large')
	})
	// View Link to Modal for Add Task Productivity
	$('.view_progress').click(function(){
		uni_modal("<i class='fa fa-comments'></i> Task Progress","view_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
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
	// Delete Task
	$('.delete_task').click(function(){
	_conf("Are you sure to delete this task?","delete_task",[$(this).attr('data-id')])
	})
	function delete_task($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_task',
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

    $( function() {
     	var url = 'edit-status.php';
     	$('ul[id^="sort"]').sortable({
         	connectWith: ".sortable",
         	receive: function (e, ui) {
             	var status_id = $(ui.item).parent(".sortable").data("status-id");
             	var task_id = $(ui.item).data("task-id");
             	$.ajax({
                 	url: url+'?status_id='+status_id+'&task_id='+task_id,
                 	success: function(response){
					}
             	});
			}
     
     	}).disableSelection();
    });
</script>