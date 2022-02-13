<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<ul class="nav nav-pills ml-auto p-2">
		<li class="nav-item"><a class="nav-link active" href="#list" data-toggle="tab">List</a></li>
		<li class="nav-item"><a class="nav-link" href="#board" data-toggle="tab">Board</a></li>
		<li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab">Files</a></li>
	</ul>
	<hr class="border-primary mt-0 mb-3">
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane active" id="list" role="tabpanel" aria-labelledby="pills-list-tab">
			<div class="card card-outline card-success">
				<div class="card-header hide">
					<?php if($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<a class="btn btn-block btn-sm btn-default btn-round border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New Task</a>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table tabe-hover table-condensed" id="data-list">
							<colgroup>
								<col width="5%">
								<col width="35%">
								<col width="15%">
								<col width="15%">
								<col width="20%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th>Project Name</th>
									<th class="">Members</th>
									<th>Start Date</th>
									<th>Due Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$stat = array("Not Started","Started","In-Progress","In-Review","Over Due","Completed");
								$where = "";
								if($_SESSION['login_type'] == 2){
									$where = " where manager_id = '{$_SESSION['login_id']}' ";
								}elseif($_SESSION['login_type'] == 3){
									$where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
								}
								$qry = $conn->query("SELECT * FROM project_list $where order by name asc");
								while($row= $qry->fetch_assoc()):
									$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
									unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
									$desc = strtr(html_entity_decode($row['description']),$trans);
									$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);

									$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
									$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 6")->num_rows;
									$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
									$prog = $prog > 0 ?  number_format($prog,6) : $prog;
									$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
									if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
									if($prod  > 0  || $cprog > 0)
									$row['status'] = 2;
									else
									$row['status'] = 1;
									elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
									$row['status'] = 6;
									endif;
								?>
								<tr>
									<th class="text-center"><?php echo $i++ ?></th>
									<td>
										<p><b><?php echo ucwords($row['name']) ?></b></p>
										<p class="truncate"><?php echo strip_tags($desc) ?></p>
									</td>
									<td class="">
										<ul class="users-list clearfix">
											<?php 
											$user_ids = "";
											if(!empty($user_ids)):
												$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
												while($row=$members->fetch_assoc()):
											?>
											<li>
												<img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image" style="height:35px;width:35px">
												<a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
												<!-- <span class="users-list-date">Today</span> -->
											</li>
											<?php endwhile; endif;
											?>
										</ul>
									</td>
									<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
									<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
									<td class="text-center">
										<?php
										//   if($stat[$row['status']] =='Not Started'){
										//   	echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
										//   }elseif($stat[$row['status']] =='Started'){
										//   	echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
										//   }elseif($stat[$row['status']] =='In Progress'){
										//   	echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
										//   }elseif($stat[$row['status']] =='In Review'){
										//   	echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
										//   }elseif($stat[$row['status']] =='Over Due'){
										//   	echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
										//   }elseif($stat[$row['status']] =='Completed'){
										//   	echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
										//   }
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
										<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">
											<i class="fas fa-plus mr-2"></i> Add Task</a>
										<div class="dropdown-divider"></div>
										<?php if($_SESSION['login_type'] != 3): ?>
										<a class="dropdown-item" href="./index.php?page=edit_project&id=<?php echo $row['id'] ?>">
											<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										<i class="fas fa-trash mr-2"></i> Delete</a>
									<?php endif; ?>
										</div>
									</td>
								</tr>	
							<?php endwhile; ?>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>
		<div class="tab-pane" id="board" role="tabpanel" aria-labelledby="pills-board-tab">
			<div class="card card-outline card-success">
				<div class="card-header hide">
					<?php if($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<a class="btn btn-block btn-sm btn-default btn-round border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New Task</a>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="col-lg-12 d-flex pl-0">
						<div class="card col-md-4 mr-1 p-0">
							<div class="card-header">TODO <a class="btn btn-success btn-sm float-right"><i class="fa fa-plus"></i></a></div>
							<div class="card-body">
								<div class="card">
									<div class="card-header">Task One</div>
									<div class="card-body">One</div>
								</div>
							</div>
						</div>
						<div class="card col-md-4 mr-1 p-0">
							<div class="card-header">IN-PROGRESS </div>
							<div class="card-body">
								<div class="card">
									<div class="card-header">Task One</div>
									<div class="card-body">One</div>
								</div>
							</div>
						</div>
						<div class="card col-md-4 p-0">
							<div class="card-header">DONE</div>
							<div class="card-body">
								<div class="card">
									<div class="card-header">Task One</div>
									<div class="card-body">One</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-center align-tems-center m-1 hide"><h1>Board Card (Drag & Drop) - Coming Soon!</h1></div>
		</div>
		<div class="tab-pane" id="files" role="tabpanel" aria-labelledby="pills-files-tab">
			<div class="card card-outline card-success">
				<div class="card-header hide">
					<?php if($_SESSION['login_type'] != 3): ?>
					<div class="card-tools">
						<a class="btn btn-block btn-sm btn-default btn-round border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New Task</a>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="d-flex justify-content-center align-tems-center m-1"><h1>Files Uploading Page is Coming Soon</h1></div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
	.users-list>li img {
	    border-radius: 50%;
	    height: 35px;
	    width: 35px;
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
	$(document).ready(function(){
		$('#data-list').dataTable();	
        $('.delete_project').click(function(){
        _conf("Are you sure to delete this project?","delete_project",[$(this).attr('data-id')])
        })
	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_project',
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