<?php include'db_connect.php';
$where_admin = "";
if($_SESSION['login_type'] == 1){
	$where_admin = "WHERE p.manager_id = {$_SESSION['login_id']}";
}

$where_member = "";
if($_SESSION['login_type'] == 3){
	$where_member = "WHERE chair_id = {$_SESSION['login_id']} OR concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'";
}
?>
<div class="col-lg-12">
	<ul class="nav nav-pills ml-auto p-2">
		<li class="nav-item"><a class="nav-link active" href="#list" data-toggle="tab">List</a></li>
		<li class="nav-item hide"><a class="nav-link" href="#board" data-toggle="tab">Board</a></li>
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
									<?php if($_SESSION['login_type'] == 3): ?>
									<th>Role</th>
									<?php endif ?>
									<th class="" style="max-width:500px;width:500px">Members</th>
									<th>Start Date</th>
									<th>Due Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$stat = array("Not Started","Started","In Progress","In Review","Completed");
								if($_SESSION['login_type'] == 1){
									$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u ON u.type = p.user_type WHERE p.manager_id = 1  GROUP BY p.name ASC");
									while($row= $qry->fetch_assoc()){
										$user_ids = $row['user_ids']; 
										$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
										unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
										$desc = strtr(html_entity_decode($row['description']),$trans);
										$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
										
										// fetch members in array
										$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
										
										// role define
										$userrole = 'Dean';

										// progress calc
										$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
										$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
										$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
										$prog = $prog > 0 ?  number_format($prog,5) : $prog;
										$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
										
										// status calc
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
										$id = urlencode($long_param_id);
									?>
									<tr>
										<th class="text-center"><?php echo $i++ ?></th>
										<td>
											<p><b><?php echo ucwords($row['name']) ?></b></p>
											<p class="truncate"><?php echo strip_tags($desc) ?></p>
										</td>
										<?php if($_SESSION['login_type'] == 3): ?>
										<td>
											<p><b><?php echo ucwords($userrole) ?></b></p>
										</td>
										<?php endif ?>
										<td class="align-left" style="max-width:500px;width:500px">
											<ul class="users-list align-left clearfix">
												<?php while($members = $qrymembers->fetch_assoc()): ?>											
												<li>
													<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
													<span class="users-list-date"></span>
												</li>
												<?php endwhile ?>
											</ul>
											<ul class="list-inline hide">
												<li class="list-inline-item">
													<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
												</li>
											</ul>
										</td>
										<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
										<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
										<td class="text-center">
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
										<td class="text-center">
											<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
											Action
											</button>
											<div class="dropdown-menu" style="">
											<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?=$id;?>" data-id="<?=$id;?>">
												<i class="fas fa-plus mr-2"></i> Add Task</a>
											<div class="dropdown-divider"></div>
											<?php if($_SESSION['login_type'] == 3): ?>
											<a class="dropdown-item" href="./index.php?page=edit_project&id=<?=$id;?>">
												<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?=$id;?>">
											<i class="fas fa-trash mr-2"></i> Delete</a>
											<?php endif; ?>
											</div>
										</td>
									</tr>
								<?php
									}
								}
								if($_SESSION['login_type'] == 3){
									$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u ON u.type = p.user_type $where_member  GROUP BY p.name ASC");
									while($row= $qry->fetch_assoc()){
										$user_ids = $row['user_ids']; 
										$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
										unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
										$desc = strtr(html_entity_decode($row['description']),$trans);
										$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
										
										// fetch members in array
										$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
										
										// role define
										$userrole = 'Dean';

										// progress calc
										$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
										$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
										$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
										$prog = $prog > 0 ?  number_format($prog,5) : $prog;
										$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
										
										// status calc
										if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
										if($prod  > 0  || $cprog > 0)
										$row['status'] = 1;
										else
										$row['status'] = 0;
										elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
										$row['status'] = 4;
										endif;

										// secure id params
										// $param_id = $row['id'];
										// // encrypt data with base64 
										// $url_param_id = base64_encode($param_id);
									?>
									<tr>
										<th class="text-center"><?php echo $i++ ?></th>
										<td>
											<p><b><?php echo ucwords($row['name']) ?></b></p>
											<p class="truncate"><?php echo strip_tags($desc) ?></p>
										</td>
										<td>
											<p><b><?php echo ucwords($userrole) ?></b></p>
										</td>
										<td class="align-left" style="max-width:500px;width:500px">
											<ul class="users-list align-left clearfix">
												<?php while($members = $qrymembers->fetch_assoc()): ?>											
												<li>
													<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
													<span class="users-list-date"></span>
												</li>
												<?php endwhile ?>
											</ul>
											<ul class="list-inline hide">
												<li class="list-inline-item">
													<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
												</li>
											</ul>
										</td>
										<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
										<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
										<td class="text-center">
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
										<td class="text-center">
											<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
											Action
											</button>
											<div class="dropdown-menu" style="">
											<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?=$id;?>" data-id="<?=$id;?>">
												<i class="fas fa-plus mr-2"></i> Add Task</a>
											<div class="dropdown-divider"></div>
											<?php if($_SESSION['login_type'] == 3): ?>
											<a class="dropdown-item" href="./index.php?page=edit_project&id=<?=$id;?>">
												<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?=$id;?>">
											<i class="fas fa-trash mr-2"></i> Delete</a>
											<?php endif; ?>
											</div>
										</td>
									</tr>
								<?php
									}
								}
								?>
							</tbody>

							<tbody class="hide">
								
								<?php
								$i = 1;
								$stat = array("Not Started","Started","In Progress","In Review","Completed");
								
								$adminid = $_SESSION['login_id'];
								$deanid = $_SESSION['login_id'];
								$chairid = $_SESSION['login_id'];
								$memberid = $_SESSION['login_id'];
								if($_SESSION['login_type'] == 3){
									if ($deanid === $_SESSION['login_id']){
										$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u ON u.type = p.user_type WHERE p.manager_id = $deanid  GROUP BY p.name ASC");
										while($row= $qry->fetch_assoc()):
											
											$user_ids = $row['user_ids']; 
											$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
											unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
											$desc = strtr(html_entity_decode($row['description']),$trans);
											$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
											
											// fetch members in array
											$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
											
											// role define
											$userrole = 'Dean';

											// progress calc
											$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
											$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
											$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
											$prog = $prog > 0 ?  number_format($prog,5) : $prog;
											$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
											
											// status calc
											if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
											if($prod  > 0  || $cprog > 0)
											$row['status'] = 1;
											else
											$row['status'] = 0;
											elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
											$row['status'] = 4;
											endif;

											// secure id params
											// $param_id = $row['id'];
											// // encrypt data with base64 
											// $url_param_id = base64_encode($param_id);
										?>
										<tr>
											<th class="text-center"><?php echo $i++ ?></th>
											<td>
												<p><b><?php echo ucwords($row['name']) ?></b></p>
												<p class="truncate"><?php echo strip_tags($desc) ?></p>
											</td>
											<td>
												<p><b><?php echo ucwords($userrole) ?></b></p>
											</td>
											<td class="align-left" style="max-width:500px;width:500px">
												<ul class="users-list align-left clearfix">
													<?php while($members = $qrymembers->fetch_assoc()): ?>											
													<li>
														<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
														<span class="users-list-date"></span>
													</li>
													<?php endwhile ?>
												</ul>
												<ul class="list-inline hide">
													<li class="list-inline-item">
														<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
													</li>
												</ul>
											</td>
											<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
											<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
											<td class="text-center">
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
											<td class="text-center">
												<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
												Action
												</button>
												<div class="dropdown-menu" style="">
												<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?= $id ?>" data-id="<?= $id ?>">
													<i class="fas fa-plus mr-2"></i> Add Task</a>
												<div class="dropdown-divider"></div>
												<?php if($_SESSION['login_type'] == 3): ?>
												<a class="dropdown-item" href="./index.php?page=edit_project&id=<?= $id ?>">
													<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?= $id ?>">
												<i class="fas fa-trash mr-2"></i> Delete</a>
												<?php endif; ?>
												</div>
											</td>
										</tr>	
										<?php endwhile;
										// $where = " where manager_id = '{$_SESSION['login_id']}'";
									}
									if ($chairid === $_SESSION['login_id']){
										$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u ON u.type = p.user_type WHERE p.chair_id = $chairid  GROUP BY p.name ASC");
										while($row= $qry->fetch_assoc()):
											$user_ids = $row['user_ids']; 
											$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
											unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
											$desc = strtr(html_entity_decode($row['description']),$trans);
											$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
											
											// fetch members in array
											$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
											
											// role define
											$userrole = 'Chair';

											// progress calc
											$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
											$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
											$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
											$prog = $prog > 0 ?  number_format($prog,5) : $prog;
											$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
											
											// status calc
											if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
											if($prod  > 0  || $cprog > 0)
											$row['status'] = 2;
											else
											$row['status'] = 1;
											elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
											$row['status'] = 6;
											endif;

											// secure id params
											// $param_id = $row['id'];
											// // encrypt data with base64 
											// $url_param_id = base64_encode($param_id);

										?>
										<tr>
											<th class="text-center"><?php echo $i++ ?></th>
											<td>
												<p><b><?php echo ucwords($row['name']) ?></b></p>
												<p class="truncate"><?php echo strip_tags($desc) ?></p>
											</td>
											<td>
												<p><b><?php echo ucwords($userrole) ?></b></p>
											</td>
											<td class="align-left" style="max-width:500px;width:500px">
												<ul class="users-list align-left clearfix">
													<?php while($members = $qrymembers->fetch_assoc()): ?>											
													<li>
														<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
														<span class="users-list-date"></span>
													</li>
													<?php endwhile ?>
												</ul>
												<ul class="list-inline hide">
													<li class="list-inline-item">
														<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
													</li>
												</ul>
											</td>
											<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
											<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
											<td class="text-center">
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
											<td class="text-center">
												<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
												Action
												</button>
												<div class="dropdown-menu" style="">
												<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?=$id;?>" data-id="<?=$id;?>">
													<i class="fas fa-plus mr-2"></i> Add Task</a>
												<div class="dropdown-divider"></div>
												<?php if($_SESSION['login_type'] == 3): ?>
												<a class="dropdown-item" href="./index.php?page=edit_project&id=<?=$id;?>">
													<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?=$id;?>">
												<i class="fas fa-trash mr-2"></i> Delete</a>
												<?php endif; ?>
												</div>
											</td>
										</tr>	
										<?php endwhile;
										// $where = " where chair_id = '{$_SESSION['login_id']}'";
									}
									if ($memberid === $_SESSION['login_id']){
										$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u ON u.type = p.user_type WHERE concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$memberid}]%' GROUP BY p.name ASC");
										while($row= $qry->fetch_assoc()):
											$user_ids = $row['user_ids']; 
											$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
											unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
											$desc = strtr(html_entity_decode($row['description']),$trans);
											$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
											
											// fetch members in array
											$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
											
											// role define
											$userrole = 'Member';

											// progress calc
											$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
											$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
											$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
											$prog = $prog > 0 ?  number_format($prog,5) : $prog;
											$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
											
											// status calc
											if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
											if($prod  > 0  || $cprog > 0)
											$row['status'] = 2;
											else
											$row['status'] = 1;
											elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
											$row['status'] = 6;
											endif;

											// secure id params
											// $param_id = $row['id'];
											// // encrypt data with base64 
											// $url_param_id = base64_encode($param_id);
										?>
										<tr>
											<th class="text-center"><?php echo $i++ ?></th>
											<td>
												<p><b><?php echo ucwords($row['name']) ?></b></p>
												<p class="truncate"><?php echo strip_tags($desc) ?></p>
											</td>
											<td>
												<p><b><?php echo ucwords($userrole) ?></b></p>
											</td>
											<td class="align-left" style="max-width:500px;width:500px">
												<ul class="users-list align-left clearfix">
													<?php while($members = $qrymembers->fetch_assoc()): ?>											
													<li>
														<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
														<span class="users-list-date"></span>
													</li>
													<?php endwhile ?>
												</ul>
												<ul class="list-inline hide">
													<li class="list-inline-item">
														<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
													</li>
												</ul>
											</td>
											<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
											<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
											<td class="text-center">
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
											<td class="text-center">
												<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
												Action
												</button>
												<div class="dropdown-menu" style="">
												<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?= $id ?>" data-id="<?= $id ?>">
													<i class="fas fa-plus mr-2"></i> Add Task</a>
												<div class="dropdown-divider"></div>
												<?php if($_SESSION['login_type'] != 3): ?>
												<a class="dropdown-item" href="./index.php?page=edit_project&id=<?= $id ?>">
													<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?= $id ?>">
												<i class="fas fa-trash mr-2"></i> Delete</a>
												<?php endif; ?>
												</div>
											</td>
										</tr>	
										<?php endwhile;
										// $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
									}
									else {
										echo 'No Data Found';
									}
								}
								elseif($_SESSION['login_type'] == 1){
									$qry = $conn->query("SELECT *,p.id as id FROM project_list p INNER JOIN users u GROUP BY p.id asc");
									while($row= $qry->fetch_assoc()):
										$user_ids = $row['user_ids']; 
										$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
										unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
										$desc = strtr(html_entity_decode($row['description']),$trans);
										$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
										
										// fetch members in array
										$qrymembers = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
										
										// role define
										$userrole = 'Dean';

										// progress calc
										$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
										$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 5")->num_rows;
										$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
										$prog = $prog > 0 ?  number_format($prog,5) : $prog;
										$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
										
										// status calc
										if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
										if($prod  > 0  || $cprog > 0)
										$row['status'] = 1;
										else
										$row['status'] = 0;
										elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
										$row['status'] = 5;
										endif;

										// secure id params
										// $data = $row['id'];

										// $encrypt_1 = (($data*123456789*5678)/956783);
										// // encrypt data with base64 
										// $url_link = urlencode(base64_encode($encrypt_1));
									?>
									<tr>
										<th class="text-center"><?php echo $i++ ?></th>
										<td>
											<p><b><?php echo ucwords($row['name']) ?></b></p>
											<p class="truncate"><?php echo strip_tags($desc) ?></p>
										</td>
										<?php if($_SESSION['login_type'] == 3): ?>
										<td>
											<p><b><?php echo ucwords($userrole) ?></b></p>
										</td>
										<?php endif ?>
										<td class="align-left" style="max-width:500px;width:500px">
											<ul class="users-list align-left clearfix">
												<?php while($members = $qrymembers->fetch_assoc()): ?>											
												<li>
													<img src="assets/uploads/<?php echo $members['avatar'] ?>" title="<?= $members['uname'] ?>" alt="User Image" class="img-circle elevation-2" style="max-width:100px;cursor:pointer">
													<span class="users-list-date"></span>
												</li>
												<?php endwhile ?>
											</ul>
											<ul class="list-inline hide">
												<li class="list-inline-item">
													<img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
												</li>
											</ul>
										</td>
										<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
										<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
										<td class="text-center">
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
										<td class="text-center">
											<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
											Action
											</button>
											<div class="dropdown-menu" style="">

											<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?= $row['id'] ?>" data-id="<?= $row['id'] ?>">
												<i class="fas fa-plus mr-2"></i> Add Task</a>
											<div class="dropdown-divider"></div>
											<?php if($_SESSION['login_type'] != 3): ?>
											<a class="dropdown-item" href="./index.php?page=edit_project&id=<?= $row['id'] ?>">
												<i class="fas fa-pencil-alt mr-2"></i> Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?= $row['id'] ?>">
											<i class="fas fa-trash mr-2"></i> Delete</a>
										<?php endif; ?>
											</div>
										</td>
									</tr>	
									<?php endwhile;
								}
								?>
								
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>
		<div class="tab-pane hide" id="board" role="tabpanel" aria-labelledby="pills-board-tab">
			<?php include 'board.php' ?>
			<div class="d-flex justify-content-center align-tems-center m-1 hide"><h1>Board Card (Drag & Drop) - Coming Soon!</h1></div>
		</div>
		<div class="tab-pane" id="files" role="tabpanel" aria-labelledby="pills-files-tab">
			<?php include 'files.php' ?>

			<div id="display-files"></div>
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