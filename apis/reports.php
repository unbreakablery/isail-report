<?php

	include '../inc/config.php';

	$action_name = "";

	if (isset($_POST) && !empty($_POST['action_name'])) {
		$action_name = $_POST['action_name'];
	} else {
		echo json_encode(
			array(
				"status" 	=> false, 
				"msg" 		=> "Action name should not be empty."
			)
		);
		exit;
	}

	switch ($action_name) {
		case "get_time_period":

			$specific_date = $_POST['date'];

			$fields = array(
						array(
							'key' 		=> 'date', 
							'value' 	=> $specific_date, 
							'operator' 	=> '='
						)
				);
			$query = $common->generateQueryForGetSessionData($fields, 'time', TRUE);

			$result = mysqli_query($common->db_connect, $query);
			
			$times = array();
			while ($row = mysqli_fetch_array($result)) {
				$times[] = array(
								'time' => substr($row['time'], 0, 8)
							);
			}

			if (count($times) != 0) {
				echo json_encode(
					array(
						"status" 	=> true, 
						"times" 	=> $times,
						"msg" 		=> "You have gotten data correctly!"
					)
				);
			} else {
				echo json_encode(
					array(
						"status" 	=> false, 
						"msg" 		=> "Sorry, can't get any game data for selected date!"
					)
				);
			}
			
			break;

		case "import_coaching_dialogue":

			$filename = $_FILES["dialogue_source_file"]["tmp_name"];

			if ($_FILES["dialogue_source_file"]["size"] > 0) {
		
				//Read data from uploaded csv file.
				$file = fopen($filename, "r");
				$row = 0;
				
				$same_rows = array();
				
				while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
					if ($row == 0 || empty($data[2])) { //in case current row is header line or node data doesn't exist
						$row++;
						continue;
					}

					//$node_id = str_replace("DP ", "", $data[2]);
					$node_id = $data[2];

					//Find the same rows
					$fields = array(
									array('key' => 'level', 		'value' => $data[0], 'operator' => '='),
									array('key' => 'node_ID', 		'value' => $node_id, 'operator' => '='),
									array('key' => 'actor', 		'value' => $data[4], 'operator' => '='),
									array('key' => 'conversant', 	'value' => $data[5], 'operator' => '=')
								);
					$query = $common->generateQueryForGetDialogue($fields);
					$result = mysqli_query($common->db_connect, $query);
					if (mysqli_num_rows($result) >= 1) {
						array_push($same_rows, $node_id);
					}

					//Delete the same data
					$sql = $common->generateQueryForDeleteDialogue($fields);
					$result = mysqli_query($common->db_connect, $sql);
					
					//Insert new data
					$dialogue = str_replace('"', '\"', $data[6]);
					$feedback = str_replace('"', '\"', $data[7]);

					$data_fields = array(
										array('key' => 'level', 		'value' => $data[0]),
										array('key' => 'node_ID', 		'value' => $node_id),
										array('key' => 'description', 	'value' => $data[3]),
										array('key' => 'actor', 		'value' => $data[4]),
										array('key' => 'conversant', 	'value' => $data[5]),
										array('key' => 'dialogue', 		'value' => $dialogue),
										array('key' => 'feedback', 		'value' => $feedback),
										array('key' => 'rubric', 		'value' => $data[8]),
										array('key' => 'param', 		'value' => $data[9])
									);
					$sql = $common->generateQueryForInsertDialogue($data_fields);
					
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
						"status" 	=> true, 
						"msg" 		=> "Dialogue data was imported correctly!",
						"same_rows" => $same_rows
					)
				);
			} else {
				$status = false;
				$msg = "File size should be greater than 0 !";
				
				echo json_encode(
					array(
						"status" 	=> $status, 
						"msg" 		=> $msg
					)
				);
			}
			
			break;

		case "import_coaching_dialogue_npc":

			$filename = $_FILES["dialogue_npc_source_file"]["tmp_name"];

			if ($_FILES["dialogue_npc_source_file"]["size"] > 0) {

				//Delete old data
				$sql = $common->generateQueryForDeleteDialogueNPC();
				$result = mysqli_query($common->db_connect, $sql);

				$sql = $common->generateQueryForSetAutoIncrement('dialogue_npc', 1);
				$result = mysqli_query($common->db_connect, $sql);
		
				//Read data from uploaded csv file.
				$file = fopen($filename, "r");
				$row = 0;
				
				while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
					if ($row == 0 || empty($data[0]) || empty($data[1]) || empty($data[2])) { 
						//in case current row is header line or level, NPC#, character data doesn't exist
						$row++;
						continue;
					}

					//Insert new data
					$data_fields = array(
										array('key' => 'level', 			'value' => mysqli_real_escape_string($common->db_connect, $data[0])),
										array('key' => 'npc#',  			'value' => mysqli_real_escape_string($common->db_connect, $data[1])),
										array('key' => 'character_name', 	'value' => mysqli_real_escape_string($common->db_connect, $data[2]))
									);
					$sql = $common->generateQueryForInsertDialogueNPC($data_fields);

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
						"status" 	=> true, 
						"msg" 		=> "Dialogue NPC Character data was imported correctly!"
					)
				);
			} else {
				$status = false;
				$msg = "File size should be greater than 0 !";
				
				echo json_encode(
					array(
						"status" 	=> $status, 
						"msg" 		=> $msg
					)
				);
			}
			
			break;

		case "remove_coaching":

			$table_name = $_POST['table_name'];
			
			$query = "TRUNCATE TABLE `{$table_name}`;";

			$result = mysqli_query($common->db_connect, $query);

			if ($result === FALSE) {
				echo json_encode(
					array(
						"status" 		=> false,
						"msg" 			=> "Error was occured while removing all {$table_name} data from database !",
						"error_query" 	=> $query
					)
				);
				exit;
			} else {
				$sql = $common->generateQueryForSetAutoIncrement($table_name, 1);

				$result = mysqli_query($common->db_connect, $sql);

				if ($result === FALSE) {
					echo json_encode(
						array(
							"status" 		=> false,
							"msg" 			=> "Error was occured while setting auto increment !",
							"error_query" 	=> $query
						)
					);
					exit;
				} else {
					echo json_encode(
						array(
							"status" 	=> true,
							"msg" 		=> "All {$table_name} data were removed from database !"
						)
					);
				}
			} 
			
			break;

		case "levels_for_coaching":
			
			$sql = $common->generateQueryForGetLevelInstances($_POST);
			$result = mysqli_query($common->db_connect, $sql);

			$level_instances = array();
			while ($row = mysqli_fetch_array($result)) {
				$level_instances[] = $row;
			}

			echo json_encode(
				array(
					"status" 		=> true,
					"instances"		=> $level_instances
				)
			);
			break;
			
		default:
	}

	exit;

?>