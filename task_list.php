<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">

				<a class="hide btn btn-block btn-sm btn-default btn-round border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<!-- <th>Project</th> -->
						<th style="width:400px">Task</th>
						<th>Started Date</th>
						<th>Due Date</th>
						<!-- <th>Project Status</th> -->
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 2){
						$where = " where p.manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3){
						$where = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					
					$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
					$qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
						$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 6")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['pid']}")->num_rows;
		                if($row['pstatus'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
		                if($prod  > 0  || $cprog > 0)
		                  $row['pstatus'] = 1;
		                else
		                  $row['pstatus'] = 1;
		                elseif($row['pstatus'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
		                $row['pstatus'] = 4;
		                endif;


					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class="hide">
							<p><b><?php echo ucwords($row['pname']) ?></b></p>
						</td>
						<td style="width:400px">
							<p><b><?php echo ucwords($row['task']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
						<td class="text-center hide">
							<?php
							//   if($stat[$row['pstatus']] =='Not Started'){
							//   	echo "<span class='badge badge-secondary'>{$stat[$row['pstatus']]}</span>";
							//   }elseif($stat[$row['pstatus']] =='Started'){
							//   	echo "<span class='badge badge-primary'>{$stat[$row['pstatus']]}</span>";
							//   }elseif($stat[$row['pstatus']] =='In Progress'){
							//   	echo "<span class='badge badge-info'>{$stat[$row['pstatus']]}</span>";
							//   }elseif($stat[$row['pstatus']] =='In Review'){
							//   	echo "<span class='badge badge-warning'>{$stat[$row['pstatus']]}</span>";
							//   }elseif($stat[$row['pstatus']] =='Over Due'){
							//   	echo "<span class='badge badge-danger'>{$stat[$row['pstatus']]}</span>";
							//   }elseif($stat[$row['pstatus']] =='Completed'){
							//   	echo "<span class='badge badge-success'>{$stat[$row['pstatus']]}</span>";
							//   }
								if($row['pstatus'] == 1){
									echo "<span class='badge badge-secondary'>Not Started</span>";
								}elseif($row['pstatus'] == 2){
								echo "<span class='badge badge-primary'>Started</span>";
								}elseif($row['pstatus'] == 3){
								echo "<span class='badge badge-primary'>In Progress</span>";
								}elseif($row['pstatus'] == 4){
								echo "<span class='badge badge-primary'>In Review</span>";
								}elseif($row['pstatus'] == 5){
								echo "<span class='badge badge-primary'>Over Due</span>";
								}elseif($row['pstatus'] == 6){
									echo "<span class='badge badge-success'>Completed</span>";
								}
							?>
						</td>
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
			                      <a class="dropdown-item new_productivity" data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-task = '<?php echo ucwords($row['task']) ?>'  href="javascript:void(0)">Add Productivity</a>
								</div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
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
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New Progress for: "+$(this).attr('data-task'),"manage_progress.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
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