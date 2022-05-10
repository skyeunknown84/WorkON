<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
		include 'db_connect.php';
		
		$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
				}
				return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	// function login2(){
	// 	extract($_POST);
	// 	$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
	// 	if($qry->num_rows > 0){
	// 		foreach ($qry->fetch_array() as $key => $value) {
	// 			if($key != 'password' && !is_numeric($key))
	// 				$_SESSION['rs_'.$key] = $value;
	// 		}
	// 			return 1;
	// 	}else{
	// 		return 3;
	// 	}
	// }
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
			$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
				if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	// function save_file(){
	// 	extract($_FILES['file']);
	// 	if(!empty($tmp_name)){
	// 		$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
	// 		$move = move_uploaded_file($tmp_name,'assets/uploads/files/'. $fname);
	// 		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
	// 		$hostName = $_SERVER['HTTP_HOST'];
	// 		$path =explode('/',$_SERVER['PHP_SELF']);
	// 		$currentPath = '/'.$path[1]; 
	// 		if($move){
	// 			return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/files/'.$fname;
	// 		}
	// 	}
	// }
	function save_project(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($user_ids)){
			$data .= ", user_ids='".implode(',',$user_ids)."' ";
		}
		// echo $data;exit;
		if(empty($id)){
			$save = $this->db->query("INSERT INTO project_list set $data");
		}else{
			$save = $this->db->query("UPDATE project_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_project(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM project_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_task(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		// if(isset($task_owner)){
		// 	$data .= ", task_owner='".implode(',',$task_owner)."' ";
		// }
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_list set $data");
		}else{
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_progress(){
		extract($_POST);
		$data = "";
		$status = 1;
		// $date_uploaded = now();
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'comment')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($v)){
					$comment = "$v";
				}else{
					$comment = "$v";
				}
			}
		}
		$timedur = abs(strtotime("2020-01-01 ".$end_time)) - abs(strtotime("2020-01-01 ".$start_time));
		$dur = $timedur / (60 * 60);
		$duration = $dur;
		// echo "INSERT INTO user_productivity set $data"; exit;
		// if(isset($_FILES['taskfile']) && $_FILES['taskfile']['tmp_name'] != ''){
		// 	$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['taskfile']['name'];
		// 	$move = move_uploaded_file($_FILES['taskfile']['tmp_name'],'assets/uploads/'. $fname);
		// 	$data .= ", avatar = '$fname' ";

		// }
		if(isset($_FILES['taskfile']) && $_FILES['taskfile']['tmp_name'] != ''){
			// File upload path
			$targetDir = "assets/uploads/files/";
			$fileName = basename($_FILES["taskfile"]["name"]);
			$fileSize = basename($_FILES["taskfile"]["size"]);
			$position= strpos($fileName, ".");
			$fileExt= substr($fileName, $position + 1);
			$fileextension= strtolower($fileExt);
			$targetFilePath = $targetDir . $fileName;
			$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

			// $data .= ", file_name={$fileName}";
		}
		if(empty($id)){
			$user_id = "{$_SESSION['login_id']}";
			
			$allowTypes = array('jpg','png','jpeg','gif','pdf','docx','xlsx','pptx');
			if(in_array($fileType, $allowTypes)){
				// $data .= ", file_type={$fileType}";

				// Upload file to server
				if(move_uploaded_file($_FILES["taskfile"]["tmp_name"], $targetFilePath)){
					// prepare data to Insert query
					// $data .= ", file_size={$fileSize}";
					// $data .= ", file_path={$targetFilePath}";
					// Insert image file name into database
					// $save = $this->db->query("INSERT INTO user_productivity set $data");
					$save = $this->db->query("INSERT into user_productivity 
						(project_id, 
						task_id, 
						description, 
						date, 
						start_time, 
						end_time, 
						user_id, 
						file_name, 
						file_type, 
						file_size, 
						file_path, 
						date_uploaded, 
						status, 
						time_rendered, 
						date_created) 
						VALUES 
						('$project_id', 
						'$task_id', 
						'$description', 
						'$date', 
						'$start_time', 
						'$end_time', 
						'$user_id', 
						'$fileName', 
						'$fileextension', 
						'$fileSize', 
						'$targetFilePath', 
						NOW(), 
						'$status', 
						'$duration', 
						NOW())");
				}
			}
			else{
				return 2;
				// $statusMsg = 'Sorry, only with format JPG, JPEG, PNG, GIF for images allowed and only PDF, XLSX, DOCX, PPTX for files are allowed to upload.';
				header( "refresh:3;url=index.php?page=view_project" );
			}
			// $save = $this->db->query("INSERT INTO user_productivity set $data");
		}else{
			$user_id = "{$_SESSION['login_id']}";
			$save = $this->db->query("UPDATE user_productivity SET
			project_id='$project_id', 
			task_id='$task_id', 
			description='$description', 
			date='$date', 
			start_time='$start_time', 
			end_time='$end_time', 
			user_id='$user_id', 
			file_name='$fileName', 
			file_type='$fileextension', 
			file_size='$fileSize', 
			file_path='$targetFilePath', 
			date_uploaded=NOW(), 
			status='$status', 
			time_rendered='$duration', 
			date_created=NOW() 
			WHERE id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_progress(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM user_productivity where id = $id");
		if($delete){
			return 1;
		}
	}
	function get_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where date(t.date_created) between '$date_from' and '$date_to' order by unix_timestamp(t.date_created) desc ");
		while($row= $get->fetch_assoc()){
			$row['date_created'] = date("M d, Y",strtotime($row['date_created']));
			$row['name'] = ucwords($row['name']);
			$row['adult_price'] = number_format($row['adult_price'],2);
			$row['child_price'] = number_format($row['child_price'],2);
			$row['amount'] = number_format($row['amount'],2);
			$data[]=$row;
		}
		return json_encode($data);
	}

	function save_group(){
		extract($_POST);
		// $data = "";
		$check = $this->db->query("SELECT * FROM group_list where group_name ='$group_name' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO group_list (group_name,group_manager,group_members,group_tasks,date_created) VALUES ('$group_name','$group_manager','$group_members','$group_tasks',now()) ");
		}else{
			echo $id;
			$save = $this->db->query("UPDATE group_list SET group_name='".$group_name."', group_manager='".$group_manager."', group_members='".$group_members."', group_tasks='".$group_tasks."' WHERE id = $id");
		}
		if($save){
			return 1;
		}
	}
	function update_group(){
		extract($_POST);
		$data = "";
		$check = $this->db->query("SELECT * FROM group_list where group_name ='$group_name' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO group_list set $data");
		}else{
			$save = $this->db->query("UPDATE group_list set $data where id = $id");
		}
	}
	function delete_group(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM group_list where id = ".$id);
		if($delete)
			return 1;
	}
	
	function save_file(){	
		extract($_POST);
		$statusMsg = '';
		$file_name = "";
		$check = $this->db->query("SELECT * FROM user_productivity where file_name ='$file_name' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['file']) && $_FILES['file']['tmp_name'] != ''){
			// File upload path
			$targetDir = "assets/uploads/files/";
			$fileName = basename($_FILES["file"]["name"]);
			$fileSize = basename($_FILES["file"]["size"]);
			$position= strpos($fileName, ".");
			$fileExt= substr($fileName, $position + 1);
			$fileextension= strtolower($fileExt);
			$targetFilePath = $targetDir . $fileName;
			$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

		}
		if(empty($id)){
			// Allow certain file formats
			$allowTypes = array('jpg','png','jpeg','gif','pdf','docx','xlsx','pptx','txt','zip','rar');
			if(in_array($fileType, $allowTypes)){
				// Upload file to server
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
					// Insert image file name into database
					$save = $this->db->query("INSERT into user_productivity (file_name, file_type, file_size, date_uploaded) VALUES ('".$fileName."', '".$fileextension."', '".$fileSize."', NOW())");
					if($save){
						return 1;
						$statusMsg = "The file (".$fileName. ") with type (".$fileextension. ") has been uploaded successfully.";
						$page = $_SERVER['PHP_SELF'];
						$sec = "3";
						header("Refresh: $sec; url=$page");
					}else{
						return 2;
						header( "refresh:2;url=index.php?page=project_list" );
						$statusMsg = "File upload failed, please try again.";
					} 
				}else{
					return 2;
					$statusMsg = "Sorry, there was an error uploading your file.";
					header( "refresh:2;url=index.php?page=project_list" );
				}
			}else{
				return 2;
				$statusMsg = 'Sorry, only with format JPG, JPEG, PNG, GIF for images allowed and only PDF, XLSX, DOCX, PPTX for files are allowed to upload.';
				header( "refresh:3;url=index.php?page=project_list" );
			}
		}else{
			return 2;
			$statusMsg = 'Please select a file to upload.';
		}
		echo $statusMsg;
	}
	function delete_file(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM user_productivity where id = $id");
		if($delete){
			return 1;
		}
	}

	function get_notifications(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT * FROM task_list ");
		while($row= $get->fetch_assoc()){
			// $row['date_created'] = date("M d, Y",strtotime($row['date_created']));
			// $row['name'] = ucwords($row['name']);
			// $row['adult_price'] = number_format($row['adult_price'],2);
			// $row['child_price'] = number_format($row['child_price'],2);
			// $row['amount'] = number_format($row['amount'],2);
			$data[]=$row;
		}
		return json_encode($data);
	}

	function update_progress(){
		extract($_POST);
		$data = "";
		$status = 1;
		// $date_uploaded = now();
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'comment')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($v)){
					$comment = "$v";
				}else{
					$comment = "$v";
				}
			}
		}
		if(empty($id)){
			$user_id = "{$_SESSION['login_id']}";
			$save = $this->db->query("UPDATE user_productivity SET
			project_id='$project_id', 
			task_id='$task_id', 
			comment='$comment', 
			user_id='$user_id', 
			status='$status', 
			date_created=NOW()  
			WHERE project_id = $project_id AND id = $id");
		}
		if($save){
			return 1;
		}
	}


}