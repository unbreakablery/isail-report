<?php

	include '../inc/config.php';

	$class_id = 0;

	if (isset($_POST) && !empty($_POST['class_id'])) {
		$class_id = $_POST['class_id'];
	} else {
		echo json_encode(
			array(
				"status" 	=> false, 
				"msg" 		=> "POST value should not be empty."
			)
		);
		exit;
	}
	
	//delete class information from class table
	$fields = array(
					array(
						'key' 		=> 'class_ID', 
						'value' 	=> $class_id, 
						'operator' 	=> '=')
				);
	$sql = $common->generateQueryForDeleteClass($fields);

	$result = mysqli_query($common->db_connect, $sql);

	//reset class_ID to 0 in users table
	$data_fields = array(
						array(
							'key' 	=> 'class_ID', 
							'value' => 0
						)
					);

	$condition_fields = array(
							array(
								'key' 		=> 'class_ID', 
								'value' 	=> $class_id, 
								'operator' 	=> '='
							)
						);

	$sql = $common->generateQueryForUpdateUsers($data_fields, $condition_fields);
	
	$result = mysqli_query($common->db_connect, $sql);

    if ($result === FALSE) {
        echo json_encode(
			array(
				"status" 	=> false, 
				"msg" 		=> "Query Execute Error!", 
				"error" 	=> mysqli_error($common->db_connect)
			)
		);
    } else {
        echo json_encode(
			array(
				"status" 	=> true, 
				"msg" 		=> "Class (ID : {$class_id}) was removed correctly!"
			)
		);
    }

	exit;

?>