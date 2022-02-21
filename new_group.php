<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<div class="col-lg-12">
	<div class="row hide">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
				</div>
			</div>
		</div>
	</div>
	<div class="row">
        <div class="col-md-12 pb-2"><button class="btn btn-primary bg-primary btn-sm" type="button" id="new_group"><i class="fa fa-plus"></i> Add Group</button></div>
		<div class="col-md-4">
			<div class="card card-outline card-success">
				<div class="card-header">
					<span><b>TASK MEMBER/S</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						
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
						</div>
					</div>
				</div>
				<div class="card-body ps-3 pe-3 pb-2-pt-1">
					<div class="">
					</div>
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