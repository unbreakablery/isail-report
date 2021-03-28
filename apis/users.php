<?php
	include '../inc/config.php';

	$action_name = $_POST['action_name'];

	if ($action_name == 'remove_user') {
		$user_id = 0;

		if (isset($_POST) && !empty($_POST['user_id'])) {
			$user_id = $_POST['user_id'];
		} else {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "POST value should not be empty.",
					"error"		=> ""
				)
			);
			exit;
		}

		if ($user_id == $common->username) {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "You can't remove current user!",
					"error"		=> ""
				)
			);
			exit;
		}

		// We have to check (ON DELETE CASCADE in 'conversations' and 'level' tables) 
		// in order to delete cascade because of foregin key constraint.

		$sql = $common->generateQueryForSetForeignKeyCheck(0);
		$result = mysqli_query($common->db_connect, $sql);
		
		$fields = array(
						array(
							'key' 		=> 'userid', 
							'value' 	=> mysqli_real_escape_string($common->db_connect, trim($user_id)), 
							'operator' 	=> '='
						)
					);
		$sql = $common->generateQueryForDeleteUserByField($fields);
		$result = mysqli_query($common->db_connect, $sql);

		$sql = $common->generateQueryForSetForeignKeyCheck(1);
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
					"msg" 		=> "User (UNIX_ID : {$user_id}) was removed correctly!"
				)
			);
		}

		exit;

	} else if ($action_name == 'update_user') {
		
		$id 		= $_POST['id'];
		$first_name = $_POST['first_name'];
		$last_name 	= $_POST['last_name'];
		$email 		= $_POST['email'];
		$password 	= $_POST['password'];
		$role 		= $_POST['role'];
		$org_ID 	= $_POST['org_ID'];
		$franchise 	= $_POST['franchise'];
		$product 	= $_POST['product'];
		$indication = $_POST['indication'];
		$gkn 		= $_POST['gkn'];
		$who_dm 	= $_POST['who_dm'];
		$class_ID 	= $_POST['class_ID'];

		if (!isset($_POST) || empty($_POST['id'])) {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "POST value should not be empty.",
					"error"		=> ""
				)
			);
			exit;
		}

		//Checks whether the same email exists
		$fields = array(
						array(
							'key' 		=> 'email', 
							'value' 	=> $email, 
							'operator' 	=> '='
						)
					);
	
		$query = $common->generateQueryForGetUsersByField($fields);

		$result = mysqli_query($common->db_connect, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if ($row['id'] != $id && !empty($row['email'])) {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, any user with this email exists already! <br/>You will need to use other email.", 
						"error" 	=> ""
					)
				);
				exit;
			}
		}

		//Checks whether the same GKN exists
		$fields = array(
						array(
							'key' 		=> 'gkn', 
							'value' 	=> $gkn, 
							'operator' 	=> '='
						)
					);
	
		$query = $common->generateQueryForGetUsersByField($fields);
		
		$result = mysqli_query($common->db_connect, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if ($row['id'] != $id && !empty($row['gkn'])) {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, this Player Game Key Number has already been used! <br/>You will need to select another GKN.", 
						"error" 	=> ""
					)
				);
				exit;
			}
		}

		$data_fields = array(
							array('key' => 'first_name', 	'value' => mysqli_real_escape_string($common->db_connect, $first_name)),
							array('key' => 'last_name',  	'value' => mysqli_real_escape_string($common->db_connect, $last_name)),
							array('key' => 'email', 	 	'value' => mysqli_real_escape_string($common->db_connect, $email)),
							array('key' => 'password', 	 	'value' => mysqli_real_escape_string($common->db_connect, $password)),
							array('key' => 'role', 		 	'value' => mysqli_real_escape_string($common->db_connect, $role)),
							array('key' => 'org_ID', 	 	'value' => mysqli_real_escape_string($common->db_connect, $org_ID)),
							array('key' => 'franchise',  	'value' => mysqli_real_escape_string($common->db_connect, $franchise)),
							array('key' => 'product', 	 	'value' => mysqli_real_escape_string($common->db_connect, $product)),
							array('key' => 'indication', 	'value' => mysqli_real_escape_string($common->db_connect, $indication)),
							array('key' => 'who_dm', 	 	'value' => mysqli_real_escape_string($common->db_connect, $who_dm)),
							array('key' => 'gkn', 		 	'value' => mysqli_real_escape_string($common->db_connect, $gkn)),
							array('key' => 'class_ID', 	 	'value' => mysqli_real_escape_string($common->db_connect, $class_ID))
						);

		$condition_fields = array(
								array(
									'key' 		=> 'id', 
									'value' 	=> $id, 
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
					"msg"		=> "User ({$first_name} {$last_name}) information was updated correctly!"
				)
			);
		}

		exit;

	} else if ($action_name == 'create_user') {
		
		$userid 	= $_POST['userid'];
		$first_name = $_POST['first_name'];
		$last_name 	= $_POST['last_name'];
		$email 		= $_POST['email'];
		$password 	= $_POST['password'];
		$role 		= $_POST['role'];
		$org_ID 	= $_POST['org_ID'];
		$franchise 	= $_POST['franchise'];
		$product 	= $_POST['product'];
		$indication = $_POST['indication'];
		$gkn 		= $_POST['gkn'];
		$who_dm 	= $_POST['who_dm'];
		$class_ID 	= $_POST['class_ID'];

		//Checks whether the same UNIX_ID exists
		$fields = array(
						array(
							'key' 		=> 'userid', 
							'value' 	=> $userid, 
							'operator' 	=> '='
						)
					);
	
		$query = $common->generateQueryForGetUsersByField($fields);

		$result = mysqli_query($common->db_connect, $query);

		if (mysqli_num_rows($result) > 0) {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "Sorry, any user with this UNIX_ID exists already! <br/>You will need to use other UNIX_ID.", 
					"error" 	=> ""
				)
			);
			exit;
		}

		//Checks whether the same email exists
		$fields = array(
					array(
						'key' 		=> 'email', 
						'value' 	=> $email, 
						'operator' 	=> '='
					)
			);
	
		$query = $common->generateQueryForGetUsersByField($fields);

		$result = mysqli_query($common->db_connect, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if (!empty($row['email'])) {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, any user with this email exists already! <br/>You will need to use other email.", 
						"error" 	=> ""
					)
				);
				exit;
			}
		}

		//Checks whether the same GKN exists
		$fields = array(
						array(
							'key' 		=> 'gkn', 
							'value' 	=> $gkn, 
							'operator' 	=> '='
						)
					);
	
		$query = $common->generateQueryForGetUsersByField($fields);

		$result = mysqli_query($common->db_connect, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if (!empty($row['gkn'])) {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, this Player Game Key Number has already been used! <br/>You will need to select another GKN.", 
						"error" 	=> ""
					)
				);
				exit;
			}
		}

		//Get new id
		$query = $common->generateQueryForGetNewUserId();
		$result = mysqli_query($common->db_connect, $query);

		if ($result === FALSE) {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "Query Execute Error!", 
					"error" 	=> mysqli_error($common->db_connect)
				)
			);
			exit;
		}

		$row = mysqli_fetch_array($result);

		$new_id = $row['new_id'];

		//Create new user
		$data_fields = array(
							array('key' => 'id',          'value' => mysqli_real_escape_string($common->db_connect, $new_id)),
							array('key' => 'userid',      'value' => mysqli_real_escape_string($common->db_connect, $userid)),
							array('key' => 'email',       'value' => mysqli_real_escape_string($common->db_connect, $email)),
							array('key' => 'password',    'value' => mysqli_real_escape_string($common->db_connect, $password)),
							array('key' => 'role',        'value' => mysqli_real_escape_string($common->db_connect, $role)),
							array('key' => 'first_name',  'value' => mysqli_real_escape_string($common->db_connect, $first_name)),
							array('key' => 'last_name',   'value' => mysqli_real_escape_string($common->db_connect, $last_name)),
							array('key' => 'org_ID',      'value' => mysqli_real_escape_string($common->db_connect, $org_ID)),
							array('key' => 'franchise',   'value' => mysqli_real_escape_string($common->db_connect, $franchise)),
							array('key' => 'product',     'value' => mysqli_real_escape_string($common->db_connect, $product)),
							array('key' => 'indication',  'value' => mysqli_real_escape_string($common->db_connect, $indication)),
							array('key' => 'who_dm',      'value' => mysqli_real_escape_string($common->db_connect, $who_dm)),
							array('key' => 'class_ID',    'value' => mysqli_real_escape_string($common->db_connect, $class_ID)),
							array('key' => 'gkn',         'value' => mysqli_real_escape_string($common->db_connect, $gkn))
						);
		$sql = $common->generateQueryForInsertUsers($data_fields);

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
					"msg" 		=> "New User (UNIX_ID : {$userid}) was created correctly!", 
					"error" 	=> ""
				)
			);
		}

		exit;
	} else if ($action_name == 'import_users') {

		$filename = $_FILES["users_source_file"]["tmp_name"];

		if ($_FILES["users_source_file"]["size"] > 0) {
	
			//Read data from uploaded csv file.
			$file = fopen($filename, "r");
			$row = 0;
			
			$same_rows = array();

			while (($data = fgetcsv($file, 0, ",")) !== FALSE) {

				$data = array_map("utf8_encode", $data);

				// In case current row is header line 
				// or unix_ID doesn't exist 
				if ($row < 1 || empty($data[0])) { 
					$row++;
					continue;
				}

				$unix_id = $data[0];

				// Find the same rows
				$fields = array(
								array(
									'key' 		=> 'userid', 
									'value' 	=> mysqli_real_escape_string($common->db_connect, trim($unix_id)), 
									'operator' 	=> '='
								)
							);
			
				$query = $common->generateQueryForGetUsersByField($fields);

				$result = mysqli_query($common->db_connect, $query);

				if (mysqli_num_rows($result) >= 1) {
					array_push($same_rows, $unix_id);
				}

				// Delete the same data
				$fields = array(
								array(
									'key' 		=> 'userid', 
									'value' 	=> mysqli_real_escape_string($common->db_connect, trim($unix_id)), 
									'operator' 	=> '='
								)
							);
				$query = $common->generateQueryForDeleteUserByField($fields);
				$result = mysqli_query($common->db_connect, $query);

				if ($data[10] == '?') {
					$data[10] = '';
				}

				//Get new id
				$query = $common->generateQueryForGetNewUserId();
				$result = mysqli_query($common->db_connect, $query);

				if ($result === FALSE) {
					echo json_encode(
						array(
							"status"	=> false, 
							"msg" 		=> "Query Execute Error!", 
							"error" 	=> mysqli_error($common->db_connect)
						)
					);
					exit;
				}

				$row = mysqli_fetch_array($result);

				$new_id = $row['new_id'];

				$data_fields = array(
									array('key' => 'id',          'value' => mysqli_real_escape_string($common->db_connect, $new_id)),
									array('key' => 'userid',      'value' => mysqli_real_escape_string($common->db_connect, trim($data[0]))),
									array('key' => 'first_name',  'value' => mysqli_real_escape_string($common->db_connect, $data[1])),
									array('key' => 'last_name',   'value' => mysqli_real_escape_string($common->db_connect, $data[2])),
									array('key' => 'password',    'value' => mysqli_real_escape_string($common->db_connect, $data[3])),
									array('key' => 'email',  	  'value' => mysqli_real_escape_string($common->db_connect, $data[4])),
									array('key' => 'role',   	  'value' => mysqli_real_escape_string($common->db_connect, $data[5])),
									array('key' => 'org_ID',      'value' => mysqli_real_escape_string($common->db_connect, $data[6])),
									array('key' => 'franchise',   'value' => mysqli_real_escape_string($common->db_connect, $data[7])),
									array('key' => 'product',     'value' => mysqli_real_escape_string($common->db_connect, $data[8])),
									array('key' => 'indication',  'value' => mysqli_real_escape_string($common->db_connect, $data[9])),
									array('key' => 'who_dm',      'value' => mysqli_real_escape_string($common->db_connect, $data[10]))
								);
				$sql = $common->generateQueryForInsertUsers($data_fields);

				$result = mysqli_query($common->db_connect, $sql);
	
				if ($result === FALSE) {
					fclose($file);
	
					$status = false;
					$msg = "While importing data, error was occured !";
	
					echo json_encode(
						array(
							"status" 		=> $status, 
							"msg" 			=> $msg,
							"error_query" 	=> $query
						)
					);
					exit;
				}

				$row++;
			}
			fclose($file);
			
			echo json_encode(
				array(
					"status" 		=> true, 
					"msg" 			=> "Users data was imported correctly!",
					"same_rows" 	=> $same_rows
				)
			);
			exit;
		} else {
			$status = false;
			$msg = "File size should be greater than 0 !";
			
			echo json_encode(
				array(
					"status" 		=> $status, 
					"msg" 			=> $msg,
					"file_size" 	=> $_FILES["users_source_file"]["size"]
				)
			);
			exit;
		}
	} else if ($action_name == 'remove_users') {
		$user_id = $common->username;

		if ($user_id == '' || $user_id == null) {
			echo json_encode(
				array(
					"status" 		=> false, 
					"msg" 			=> "You can't get current user!", 
					"error_query" 	=> 'Please sign in again!'
				)
			);

			exit;
		}

		// We have to check (ON DELETE CASCADE in 'conversations' and 'level' tables) 
		// in order to delete cascade because of foregin key constraint.
		$sql = $common->generateQueryForSetForeignKeyCheck(0);
		$result = mysqli_query($common->db_connect, $sql);

		$fields = array(
						array(
							'key' 		=> 'userid',
							'value' 	=> mysqli_real_escape_string($common->db_connect, trim($user_id)),
							'operator' 	=> '!='
						)
					);
		$sql = $common->generateQueryForDeleteUserByField($fields);
		$result = mysqli_query($common->db_connect, $sql);

		$sql = $common->generateQueryForSetForeignKeyCheck(1);
		$result = mysqli_query($common->db_connect, $sql);

		if ($result === FALSE) {
			echo json_encode(
				array(
					"status" 		=> false,
					"msg" 			=> "Query Execute Error!",
					"error_query" 	=> mysqli_error($common->db_connect)
				)
			);
		} else {
			echo json_encode(
				array(
					"status" 		=> true, 
					"msg" 			=> "All users were removed correctly!"
				)
			);
		}
		
		exit;
	}
	
?>