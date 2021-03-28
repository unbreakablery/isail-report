<?php

	include '../inc/config.php';

	$action_name = $_POST['action_name'];

	if ($action_name == 'get_user_gkn') {

		$user_id = $_POST['user_id'];

		$fields = array(
						array(
							'key' 		=> 'userid', 
							'value' 	=> $user_id, 
							'operator' 	=> '='
						)
					);
	
		$query = $common->generateQueryForGetUsersByField($fields);

		$result = mysqli_query($common->db_connect, $query);

		if ($result === FALSE) {
			echo json_encode(
				array(
					"status" 	=> false, 
					"msg" 		=> "Query Execute Error!", 
					"error" 	=> mysqli_error($common->db_connect)
				)
			);
		} else {
			$user = mysqli_fetch_array($result);
			echo json_encode(
				array(
					"status" 	=> true, 
					"msg" 		=> "You got the user information.", 
					"user" 		=> $user
				)
			);
		}

	} else if ($action_name == 'save_user_gkn') {
		$user_id 	= $_POST['user_id'];
		$gkn 		= $_POST['gkn'];

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
			if ($row['userid'] != $user_id) {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, this Player Game Key Number had already been used! <br/>You will need to select another GKN.", 
						"error" 	=> ""
					)
				);
				exit;
			}
		}

		//Save GKN
		$data_fields = array(
							array(
								'key' 	=> 'gkn', 
								'value' => mysqli_real_escape_string($common->db_connect, $gkn)
							)
						);

		$condition_fields = array(
								array(
									'key' 		=> 'userid', 
									'value' 	=> $user_id, 
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
			//Send GKN via email
			$email_result = "";
			$is_email = $_POST['is_email'];
			$user_name = $_POST['user_name'];

			if ($is_email == 1) {
				$to = $_POST['user_email'];
				$subject = "Game Key Number";
				$message = "
                            <html>
                                <head>
                                    <title>Game Key Number</title>
                                    <style>
                                        .default {
                                            text-align: center; 
                                            padding: 20px 0; 
                                            font-size: 20px;
                                        }
                                        .w-200 {
                                            width: 200px;
                                        }
                                        .w-400 {
                                            width: 400px;
                                        }
                                        .gkn-cell {
                                            background-color:#f9f9f9;
                                            color: #646464;
                                        }
                                        .gkn-header {
                                            background-color:#5c90d2;
                                            color: #ffffff;
                                        }
                                        .font-16 {
                                            font-size: 16px;
                                        }
                                    </style>
                                </head>
                                <body>
									<p class='font-16'>
										Hi {$user_name}, <br/> 
										You have received new Game Key Number(GKN) from the manager!
									</p>
                                    <table>
                                        <tr>
                                            <th class='default w-200 gkn-header'>Your GKN</th>
                                            <th class='default w-400 gkn-cell'>{$gkn}</th>
                                        </tr>
									</table>
									<p class='font-16'>
										Thanks, <br/>
										iSAIL Report Team
									</p>
                                </body>
                            </html>
                            ";
                            
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= "From: iSAIL <no-reply@zombiesalesgame.com>";
				
				if (mail($to, $subject, $message, $headers) === FALSE) {
					$email_result = "<br/> Failed to send GKN via email. Please re-try later!";
				} else {
					$email_result = "<br/> GKN has been sent via email to user!";
				}
			}

			echo json_encode(
				array(
					"status" 	=> true, 
					"msg" 		=> "GKN (UNIX_ID : {$user_id}) was assigned correctly!" . $email_result, 
					"gkn" 		=> $gkn
				)
			);
		}
	} else if ($action_name == 'delete_user_gkn') {
		$user_id = $_POST['user_id'];

		$data_fields = array(
							array(
								'key' 	=> 'gkn', 
								'value' => ''
							)
						);

		$condition_fields = array(
									array(
										'key' 		=> 'userid', 
										'value' 	=> $user_id, 
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
					"msg" 		=> "GKN (UNIX_ID : {$user_id}) was removed correctly!"
				)
			);
		}
	}

	exit;

?>