<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header hide">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-round border-primary" href="./index.php?page=new_user"><i class="fa fa-plus"></i> Add New User</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive xpand xpand-table x-scroll">
				<table class="table tabe-hover table-condensed " id="list">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Group Name</th>
							<th>Group Manager</th>
							<th>Group Members</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$type = array('',"Admin","Project Manager","Employee");
						$qry = $conn->query("SELECT * FROM group_list order by group_name asc");
						while($row= $qry->fetch_assoc()):
						?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td><b><?php echo ucwords($row['group_name']) ?></b></td>
							<td><b><?php echo $row['group_manager'] ?></b></td>
							<td><b><?php echo $row['group_members'] ?></b></td>
							<td class="text-center">
								<button type="button" class="btn btn-default btn-sm btn-round border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
								Action
								</button>
								<div class="dropdown-menu" style="">
								<a class="dropdown-item view_group" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="./index.php?page=edit_group&id=<?php echo $row['id'] ?>">Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_group" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
<style>
	.table-responsive .x-scroll table{
		width: 375px !important;
		max-width: 375px !important;
		overflow-x: auto !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.view_group').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> Group Details","view_group.php?id="+$(this).attr('data-id'))
	})
	$('.delete_group').click(function(){
	_conf("Are you sure to delete this group?","delete_group",[$(this).attr('data-id')])
	})
	})
	function delete_group($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_group',
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