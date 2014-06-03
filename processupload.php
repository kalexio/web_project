<?php
// Start output buffering:
ob_start();
// Initialize a session:
session_start();

if (isset($_SESSION ['user_id'])){
		$user_id = $_SESSION ['user_id'];
}
else{
	exit();
}

if(isset($_POST['submit']))
{	
    $destination = 'uploads';
    $images = array('ImageFile1','ImageFile2','ImageFile3','ImageFile4');
    $k = 0;
    
    require "db_config.php";
    $query = sprintf("SELECT report_id FROM reports WHERE user_id=$user_id ORDER BY report_id DESC LIMIT 1;");
	$result = mysqli_query($dbhandle,$query);
	$row = mysqli_fetch_assoc($result);
	$report_id = $row['report_id'];
	
	
	for ($i = 0; $i <= 3; $i++) {
		$img = $images[$i];
		$ImageType = $_FILES[$img]['type']; //"image/png", image/jpeg etc.
		if ((($ImageType == "image/gif") || ($ImageType == "image/jpeg") || ($ImageType == "image/jpg") || ($ImageType == "image/png") || ($ImageType == ""))
		&& ($_FILES["file"]["size"] < 20000000)){
			$k = $k + 1;
		}
	}
	
	if($k==4){
		//echo '<table width="100%" border="0" cellpadding="4" cellspacing="0">';
		for ($i = 0; $i <= 3; $i++) {
			$img = $images[$i];
			$ImageType = $_FILES[$img]['type']; //"image/png", image/jpeg etc.
			if ($ImageType != ""){
				$ImageExt = substr($ImageType,6);
				$ImageName = "image_".$report_id."_(".$i.").".$ImageExt;
				move_uploaded_file($_FILES[$img]['tmp_name'], "$destination/$ImageName");
				$query = "INSERT INTO photos (photo_name, report_id) VALUES ('$ImageName', '$report_id');";
				$result = mysqli_query($dbhandle,$query);
				//echo '<tr>';
				//echo '<td align="center"><img src="uploads/'.$ImageName.'"></td>';
				//echo '</tr>';
			}
		}
		//echo '</table>';
		ob_end_clean(); // Delete the buffer.
		header("Location: my_reports.php");
		exit(); // Quit the script.
	}
	else {
		echo Prosoxi;
	} 
}
?> 
