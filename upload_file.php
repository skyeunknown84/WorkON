<?php 
// echo "Hello Upload File here!"; 

include 'db_connect.php';

$statusMsg = '';

// File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$fileSize = basename($_FILES["file"]["size"]);
$position= strpos($fileName, ".");
$fileExt= substr($fileName, $position + 1);
$fileextension= strtolower($fileExt);
echo $fileextension;
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
// echo $uploadfileType;

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf','docx','xlsx','pptx');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = $conn->query("INSERT into tbl_files (file_name, file_type, file_size, date_uploaded) VALUES ('".$fileName."', '".$fileextension."', '".$fileSize."', NOW())");
            if($insert){
                $statusMsg = "The file (".$fileName. ") with type (".$fileextension. ") has been uploaded successfully.";
                header( "refresh:3;url=index.php?page=project_list" );
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only with format JPG, JPEG, PNG, GIF for images allowed and only PDF, XLSX, DOCX, PPTX for files are allowed to upload.';
        header( "refresh:3;url=index.php?page=project_list" );
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
echo $statusMsg;

?>