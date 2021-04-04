<?php
/**
 * Common class used for processing most of features
 *
 * @author Anna
 *
 */

class Common {
    private 
        $prefix_project_name    = 'iSAIL', 
        $env                    = '',
        $rolePages              = array(
                                    1 => array(
                                        'index',
                                        'aggregate_report', 
                                        'player_report', 
                                        'comparison_report', 
                                        'comparison_model_report', 
                                        'user_management',
                                        'class_management',
                                        'class_create',
                                        'coaching_report',
                                        'gkn_create',
                                        'gkn_management',
                                        'progression_report'
                                    ),
                                    2 => array(
                                        'index',
                                        'aggregate_report', 
                                        'player_report', 
                                        'comparison_report', 
                                        'comparison_model_report', 
                                        'class_management',
                                        'class_create',
                                        'progression_report'
                                    ),
                                    3 => array(
                                        'index',
                                        'aggregate_report', 
                                        'player_report', 
                                        'comparison_report',
                                        'progression_report'
                                    ),
                                    4 => array(
                                        'index',
                                        'aggregate_report', 
                                        'player_report', 
                                        'comparison_report', 
                                        'comparison_model_report',
                                        'class_management',
                                        'class_create'
                                    )
            ),
        $modelsShortLabel       = 'ACE',
        $legendLabels           = array(
                                    'foundation'    => array(
                                            'label'         => 'Foundation Knowledge',
                                            'color'         => 'Red',
                                            'score_range'   => '0 - 67%',
                                            'level'         => 'Lowest Level'
                                        ),
                                    'deep'          => array(
                                            'label'         => 'Deep Knowledge',
                                            'color'         => 'Yellow',
                                            'score_range'   => '68 - 89%',
                                            'level'         => 'Intermediate Level'
                                        ),
                                    'advanced'      => array(
                                            'label'         => 'Advanced Knowledge',
                                            'color'         => 'Green',
                                            'score_range'   => '90 - 100%',
                                            'level'         => 'Highest Level'
                                        )                             
            ),
        $primaryColors          = array(
                                    'default'       =>      '#5c90d2',
                                    'amethyst'      =>      '#a48ad4',
                                    'city'          =>      '#ff6b6b',
                                    'flat'          =>      '#44b4a6',
                                    'modern'        =>      '#14adc4',
                                    'smooth'        =>      '#ff6c9d'
            ),
        $totalSalesPoints       = array(
                                    "level-1" => array(
                                            "sales1"    =>  300,
                                            "sales2"    =>  600,
                                            "sales3"    =>  300,
                                            "sales4"    =>  600,
                                            "sales5"    =>  300,
                                            "sales6"    =>  600,
                                            "sales7"    =>  300,
                                            "sales8"    =>  600,
                                            "sales9"    =>  300
                                        ),
                                    "level-2" => array(
                                            "sales1"    =>  900,
                                            "sales2"    =>  600,
                                            "sales3"    =>  300,
                                            "sales4"    => 1200,
                                            "sales5"    =>  600,
                                            "sales6"    =>  900,
                                            "sales7"    =>  900,
                                            "sales8"    =>    0,
                                            "sales9"    =>  900
                                        ),
                                    "level-3" => array(
                                            "sales1"    => 2100,
                                            "sales2"    => 1500,
                                            "sales3"    =>  900,
                                            "sales4"    => 2400,
                                            "sales5"    => 1500,
                                            "sales6"    => 1500,
                                            "sales7"    => 1200,
                                            "sales8"    => 1200,
                                            "sales9"    =>  600
                                        )
                    ),
        // $salesLabels            = array(
        //                             'sales_group_1'  => array(
        //                                     'label'         => 'Engage',
        //                                     'short_label'   => 'ENGAGE'
        //                                 ),
        //                             'sales_group_2'  => array(
        //                                     'label'         => 'Converse',
        //                                     'short_label'   => 'CONVERSE'
        //                                 ),
        //                             'sales_group_3'  => array(
        //                                     'label'         => 'Close',
        //                                     'short_label'   => 'CLOSE'
        //                                 ),
        //                             'sales1'  => array(
        //                                     'label'         => 'Uncovering Needs',
        //                                     'short_label'   => 'UN'
        //                                 ),
        //                             'sales2'  => array(
        //                                     'label'         => 'Opening',
        //                                     'short_label'   => 'OPN'
        //                                 ),
        //                             'sales3'  => array(
        //                                     'label'         => 'Environmental Questions',
        //                                     'short_label'   => 'EQ'
        //                                 ),
        //                             'sales4'  => array(
        //                                     'label'         => 'Critical Questions',
        //                                     'short_label'   => 'CQ'
        //                                 ),
        //                             'sales5'  => array(
        //                                     'label'         => 'Consultative Feedback',
        //                                     'short_label'   => 'CF'
        //                                 ),
        //                             'sales6'  => array(
        //                                     'label'         => 'Assessing Objectives',
        //                                     'short_label'   => 'AO'
        //                                 ),
        //                             'sales7'  => array(
        //                                     'label'         => 'Discover Uncertainties',
        //                                     'short_label'   => 'DU'
        //                                 ),
        //                             'sales8'  => array(
        //                                     'label'         => 'Concrete Next Step',
        //                                     'short_label'   => 'CNS'
        //                                 ),
        //                             'sales9'  => array(
        //                                     'label'         => 'Message Alignment',
        //                                     'short_label'   => 'MA'
        //                                 )
        //     );
        $salesLabels            = array(
                                        'sales_group_1'  => array(
                                                'label'         => 'Assess',
                                                'short_label'   => 'ASSESS'
                                            ),
                                        'sales_group_2'  => array(
                                                'label'         => 'Clarify',
                                                'short_label'   => 'CLARIFY'
                                            ),
                                        'sales_group_3'  => array(
                                                'label'         => 'Execute',
                                                'short_label'   => 'EXECUTE'
                                            ),
                                        'sales1'  => array(
                                                'label'         => 'Building Impactful Partnerships',
                                                'short_label'   => 'BIP'
                                            ),
                                        'sales2'  => array(
                                                'label'         => 'Strategic Account Planning',
                                                'short_label'   => 'SAP'
                                            ),
                                        'sales3'  => array(
                                                'label'         => 'Identifying A-to-B Shift',
                                                'short_label'   => 'IS'
                                            ),
                                        'sales4'  => array(
                                                'label'         => 'Insightful Questioning',
                                                'short_label'   => 'IQ'
                                            ),
                                        'sales5'  => array(
                                                'label'         => 'Listen Reflectively',
                                                'short_label'   => 'LR'
                                            ),
                                        'sales6'  => array(
                                                'label'         => 'Reframe Thinking',
                                                'short_label'   => 'RT'
                                            ),
                                        'sales7'  => array(
                                                'label'         => 'Aligning Information',
                                                'short_label'   => 'AI'
                                            ),
                                        'sales8'  => array(
                                                'label'         => 'Gaining Commitment',
                                                'short_label'   => 'GC'
                                            ),
                                        'sales9'  => array(
                                                'label'         => 'Objection Handling',
                                                'short_label'   => 'OH'
                                            )
                            );

    public 
        $basePath           = '/',
        $role               = null, 
        $username           = '',
        $full_username      = '',
        $trackPage          = '',
        $cookie_timeout     = 0,
        $session_timeout    = 0,
        $db_connect         = null;

    //constructor
    public function __construct($env = 'dev') {
        $this->env = $env;

        switch ($env) {
            case 'dev':
                $this->basePath = '/';
                break;
            case 'staging':
                $this->basePath = '/';
                break;
            case 'production':
                $this->basePath = '/';
                break;
        }

        $database = new Database($this->env);
        $this->db_connect = $database->getConnect();

        if ($this->db_connect == null) {
            echo $database->getError();
            exit;
        }
    }

    /* 
    * Set cookie timeout
    *
    * @param    int         $days           days of cookie timeout (default value 7 days)
    *
    */
    public function setCookieTimeout($days = 7) {
        $this->cookie_timeout = $days * 24 * 60 * 60;
    }

    /* 
    * Set session timeout
    *
    * @param    int         $mins           minutes of session timeout (default value 20 mins)
    *
    */
    public function setSessionTimeout($mins = 20) {
        $this->session_timeout = $mins * 60;
    }

    /* 
    * Authenticate pages
    *
    * @param    nothing
    *
    */
    public function authenticate() {
        if ($this->trackPage === 'logout') {
            header('Location: /login.php');
        } else {
            $redir = $_SERVER['REQUEST_URI'];
            if ($redir == '' || $redir == '/' || $redir == '/errors/404.php' || $redir == '/errors/403.php' || $redir == '/errors/500.php') {
                $redir = '/index.php';
            }
            
            header('Location: /login.php?redir=' . urlencode($redir));
        }
        
        exit;
    }

    /* 
    * Authorize pages
    *
    * @param    string      $page           page for authorize
    *
    */
    public function authorizePage($page) {
        if (!isset($this->role) || empty($this->role) || $this->role == "") {
            $this->authenticate();
        }
    
        if (!in_array($page, $this->rolePages[(int) $this->role])) {
            header('Location: /errors/403.php');
            exit;
        }
    }

    /* 
    * Check if user has the permission of page
    *
    * @param    string      $page
    *
    */
    public function hasPermissionPage($page) {
        return in_array($page, $this->rolePages[(int) $this->role]);
    }

    /* 
    * Get Sales Label from params
    *
    * @param    string      $param1         first param for sales label (sales1, sales2, ...)
    * @param    string      $param2         second param for sales label (element, label, short label)
    *
    */
    public function getSalesLabels($param1, $param2) {
        return $this->salesLabels[$param1][$param2];
    }

    /* 
    * Get Elements Short Label
    *
    * @param    nothing
    *
    */
    public function getModelsShortLabel() {
        return $this->modelsShortLabel;
    }

    /* 
    * Get Legend
    *
    * @param    string      $legend     legend param : 'foundation', 'deep', 'advanced'
    *
    */
    public function getLegend($legend) {
        return $this->legendLabels[$legend]['label'] . ' (' . $this->legendLabels[$legend]['color'] . ' ' . $this->legendLabels[$legend]['score_range'] . ')';
    }

    /* 
    * Get Legend Label
    *
    * @param    string      $legend     legend param : 'foundation', 'deep', 'advanced'
    *
    */
    public function getLegendLabel($legend) {
        return $this->legendLabels[$legend]['label'];
    }

    /* 
    * Get Legend Color
    *
    * @param    string      $legend     legend param : 'foundation', 'deep', 'advanced'
    *
    */
    public function getLegendColor($legend) {
        return $this->legendLabels[$legend]['color'];
    }

    /* 
    * Get Legend Score Range
    *
    * @param    string      $legend     legend param : 'foundation', 'deep', 'advanced'
    *
    */
    public function getLegendScoreRange($legend) {
        return $this->legendLabels[$legend]['score_range'];
    }

    /* 
    * Get Legend Level
    *
    * @param    string      $legend     legend param : 'foundation', 'deep', 'advanced'
    *
    */
    public function getLegendLevel($legend) {
        return $this->legendLabels[$legend]['level'];
    }

    /* 
    * Get Rubric / Legend Set as array
    *
    * @param    nothing
    *
    */
    public function getRubricSet() {
        $rubricSet = array();

        foreach($this->legendLabels as $key => $value) {
            $rubricSet[] = $key;
        }

        return $rubricSet;
    }

    /* 
    * Get primary color by theme name. It will be used only export PDF.
    *
    * @param    string      $theme     theme param : 'default', 'amethyst', 'city', 'flat', 'modern', 'smooth'
    *
    */
    public function getPrimaryColorByTheme($theme = 'default') {
        $themeSet = array();

        foreach($this->primaryColors as $key => $value) {
            $themeSet[] = $key;
        }

        if(!in_array($theme, $themeSet)) {
            $theme = 'default';
        }

        return $this->primaryColors[$theme];
    }
    
    /* 
    * Get percentage of score by param from total sales points
    *
    * @param    int         $level      level for getting score percentage
    * @param    string      $param      param name
    * @param    int         $value      value for getting score percentage
    *
    */
    public function getScoreByParam($level, $param, $value) {
        $score = 0;
        if ($this->totalSalesPoints['level-' . $level][$param] > 0) {
            $score = round($value / $this->totalSalesPoints['level-' . $level][$param] * 100);
        } else {
            $score = 100;
        }
        return $score;
    }
    
    /* 
    * Get role text from role
    *
    * @param    string      $role       role for getting role text
    *
    */
    public function getRoleText($role = '') {
        $role_text = '';

        if (!isset($role) || $role == '' || $role == 0) {
            $role = $this->role;
        }
    
        switch ((int) $role) {
            case 1:
                $role_text = 'Administrator';
                break;
            case 2:
                $role_text = 'Traniner';
                break;
            case 3:
                $role_text = 'CS';
                break;
            case 4:
                $role_text = 'Manager';
                break;
            default:
                $role_text = 'Unknown User';
        }
    
        return $role_text;
    }
    
    /* 
    * Generate progress bars
    *
    * @param    int         $progress1
    * @param    int         $progress2
    * @param    int         $progress3
    *
    */
    public function generateProgressBar($progress1, $progress2, $progress3) {
        $total = $progress1 + $progress2 + $progress3;
        if ($total == 0) return;
        
        echo '<table style="width:100%"><tbody><tr>';
        if ($progress1 > 0) {
            echo '<td class="progress-bar progress-bar-danger" style="width:'. ($progress1 * 100 / $total) . '%">' . $progress1 . '</td>';
        }
        if ($progress2 > 0) {
            echo '<td class="progress-bar progress-bar-warning" style="width:'. ($progress2 * 100 / $total) . '%">' . $progress2 . '</td>';
        }
        if ($progress3 > 0) {
            echo '<td class="progress-bar progress-bar-success" style="width:'. ($progress3 * 100 / $total) . '%">' . $progress3 . '</td>';
        }
        echo '</tr></tbody></table>';
    }
    
    /* 
    * Get color for Donut bars
    *
    * @param    int         $score
    *
    */
    public function getDonutBarColor($score) {
        if ($score <= 67) {
            return '#d26a5c';
        }
        if ($score > 67 && $score < 90) {
            return '#f3b760';
        }
        if ($score >= 90) {
            return '#46c37b';
        }
    }
    
    /* 
    * Get class name from percentage
    *
    * @param    int         $progress       percentage for class name
    *
    */
    public function getProgressClassName($progress) {
        if ($progress <= 67) {
            return 'progress-bar-danger';
        } else if ($progress > 67 && $progress < 90) {
            return 'progress-bar-warning';
        } else { // if($progress >= 90) {
            return 'progress-bar-success';
        }
    }
    
    /* 
    * Create player for comparison report
    *
    * @param    nothing
    *
    */
    public function createPlayer() {
        $player = array();
        $player['userid'] = '';
        $player['username'] = '';
        for ($level = 1; $level <= 3 ; $level++) {
            $player['time_' . $level] = 0;
            $player['score_' . $level] = 0;
            for ($i = 1;  $i <= 9; $i++) {
                $player['sales' . $i . '_' . $level] = 0;
            }
        }
        return $player;
    }

    /* 
    * Get text class name from rubric name
    *
    * @param    string      $rubric
    *
    */
    public function getTextClassNameByRubric($rubric) {
        $text_class_name = '';
        switch (strtolower($rubric)) {
            case 'foundation':
                $text_class_name = 'text-danger';
                break;
            case 'deep':
                $text_class_name = 'text-warning';
                break;
            case 'advanced':
                $text_class_name = 'text-success';
                break;
            default:
                $text_class_name = 'text-primary';
        }
        return $text_class_name;
    }

    /* 
    * Get time period label from time period
    *
    * @param    string      $time_period
    *
    */
    public function getTimePeriodLabel($time_period) {
        $time_period_label = '';
        switch ($time_period) {
            case 'first-time':
                $time_period_label = 'First Time Playing the Level';
                break;
            case 'last-time':
                $time_period_label = 'Most Recent Time Playing the Level';
                break;
            case 'average-score':
                $time_period_label = 'Average Score for all Times Playing';
                break;
            case 'game-key-instance':
                $time_period_label = 'Game Key Instance';
                break;
            case 'specific-date':
                $time_period_label = 'Specific Date';
                break;
            default:
                $time_period_label = 'ALL';
        }
        return $time_period_label;
    }

    /* 
    * Generate query for aggregate, player and comparison model(ACE or ECC) reports from $_SESSION or $_POST
    *
    * @param    array       $data       $_SESSION or $_POST
    *
    */
    public function generateISAILQuery($data) {

		$sql = "SELECT 
                    level.*, 
                    session.`username`, 
                    session.`date` AS played_date, 
                    session.`time` AS played_time, 
                    session.`duration`,
                    session.`trainer_ID` AS trainer,
                    session.`trainee_ID` AS trainee,
                    session.`guest_ID` AS guest,
                    session.`class_ID` AS class,
                    session.`gkn` AS gkn,
                    class.`date` AS class_date, 
                    users.`userid`,
                    users.`role`,
                    users.`first_name`,
                    users.`last_name`,
                    users.`org_ID` AS organization,
                    users.`franchise`,
                    users.`product`,
                    users.`indication`,
                    users.`who_dm`,
                    dm.`first_name` AS dm_first_name,
                    dm.`last_name` AS dm_last_name 
				FROM level 
				LEFT JOIN session ON session.`session_ID` = level.`session_ID` 
				LEFT JOIN users ON users.`userid` = session.`username` 
				LEFT JOIN class ON session.`class_ID` = class.`class_ID` 
				LEFT JOIN users AS dm on users.who_dm = dm.userid 
				";

		$conditions = [];
		
		// save values
		$keys = [
					'time_period', 'specific_date', 'specific_time', 'game_key_number',
					'organization', 'franchise', 'product', 'indication', 'level', 
					'manager_first_name', 'manager_last_name', 
					'customer_first_name', 'customer_last_name', 
					'unix_id', 
					'class_id', 'class_date'
				];
		foreach ($keys as $key) {
			if (isset($data[$key])) {
				$_SESSION[$key] = $data[$key];
			} else {
                unset($_SESSION[$key]);
            }
		}

		//level
		if (isset($data['level']) && !empty($data['level'])) {
			$conditions[] = "level.`type` = " . mysqli_real_escape_string($this->db_connect, $data['level']);
		}

		if (isset($data['time_period']) && !empty($data['time_period'])) {
			switch ($data['time_period']) {
				case "first-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MIN(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "last-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MAX(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "average-score":
					break;
				case "game-key-instance":
					if (isset($data['game_key_number']) && !empty($data['game_key_number'])) {
						$conditions[] = "session.`gkn` = '" . $data['game_key_number'] . "'";
					}
					break;
				case "specific-date":
					if (isset($data['specific_date']) && !empty($data['specific_date'])) {
						$conditions[] = "session.`date` = '" . $data['specific_date'] . "'";
					}
					if (isset($data['specific_time']) && !empty($data['specific_time'])) {
						$conditions[] = "session.`time` = '" . $data['specific_time'] . "'";
					}
					break;
				default:
			}
		}

		if (isset($data['organization']) && !empty($data['organization'])) {
			$conditions[] = "users.`org_ID` = '" . mysqli_real_escape_string($this->db_connect, $data['organization']) . "'";
			if ($data['organization'] == 'BioOnc') {
				if (isset($data['product']) && !empty($data['product'])) {
					$conditions[] = "users.`product` = '" . mysqli_real_escape_string($this->db_connect, $data['product']) . "'";
				}
				if (isset($data['indication']) && !empty($data['indication'])) {
					$conditions[] = "users.`indication` = '" . mysqli_real_escape_string($this->db_connect, $data['indication']) . "'";
				}
			}
		}

		if (isset($data['franchise']) && !empty($data['franchise'])) {
			$conditions[] = "users.`franchise` = '" . mysqli_real_escape_string($this->db_connect, $data['franchise']) . "'";
		}

		if (isset($data['customer_first_name']) && !empty($data['customer_first_name'])) {
			$conditions[] = "users.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_first_name']) . "%'";
		}

		if (isset($data['customer_last_name']) && !empty($data['customer_last_name'])) {
			$conditions[] = "users.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_last_name']) . "%'";
		}

		if (isset($data['manager_first_name']) && !empty($data['manager_first_name'])) {
			$conditions[] = "dm.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_first_name']) . "%'";
		}

		if (isset($data['manager_last_name']) && !empty($data['manager_last_name'])) {
			$conditions[] = "dm.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_last_name']) . "%'";
		}

		if (isset($data['unix_id']) && !empty($data['unix_id'])) {
			$ids = explode(',', $data['unix_id']);
			for ($i = 0; $i < count($ids); $i += 1) {
				$ids[$i] = "'" . mysqli_real_escape_string($this->db_connect, $ids[$i]) . "'";
			}
			$conditions[] = "users.`userid` IN (" . join(',', $ids) . ")";
		}
			
		// class conditions
		if (isset($data['class_id']) && !empty($data['class_id'])) {
			$conditions[] = "session.`class_ID` = " . mysqli_real_escape_string($this->db_connect, $data['class_id']);
		}

		if (isset($data['class_date']) && !empty($data['class_date'])) {
			$conditions[] = "class.`date` = '" . mysqli_real_escape_string($this->db_connect, $data['class_date']) . "'";
		}

		//check if role = 4
		if ($this->role == 4) {
			$conditions[] = "users.`who_dm` = '" . $this->username . "'"; 
		}

		// make query
		if (count($conditions) > 0) {
        	$sql .= " WHERE " . join(" AND ", $conditions);
		}
		
		//make query for average score
		if (isset($data['time_period']) && !empty($data['time_period']) && $data['time_period'] == "average-score") {
			$sql = "SELECT 
						0 AS level_ID,
						'Average Score' AS userid,
						'' AS organization,
                        '' AS franchise,
                        '' AS product,
						'Average' AS first_name,
						'Score' AS last_name,
					" . $data['level'] . " AS level, 
						ROUND(AVG(dataset.`time`)) AS time,
						ROUND(AVG(dataset.`score`)) AS score,
						AVG(dataset.`sales1`) AS sales1,
						AVG(dataset.`sales2`) AS sales2,
						AVG(dataset.`sales3`) AS sales3,
						AVG(dataset.`sales4`) AS sales4,
						AVG(dataset.`sales5`) AS sales5,
						AVG(dataset.`sales6`) AS sales6,
						AVG(dataset.`sales7`) AS sales7,
						AVG(dataset.`sales8`) AS sales8,
						AVG(dataset.`sales9`) AS sales9
					FROM (
				" . $sql . ") AS dataset";
		}

		return $sql;
	}

    /* 
    * Generate query for comparison report from $_SESSION or $_POST
    *
    * @param    array       $data       $_SESSION or $_POST
    *
    */
	public function generateISAILQueryForComparison($data) {
		$sub_sql = "SELECT 
                        level.*, 
                        session.`username`, 
                        session.`date` AS played_date, 
                        session.`time` AS played_time, 
                        session.`duration`,
                        session.`trainer_ID` AS trainer,
                        session.`trainee_ID` AS trainee,
                        session.`guest_ID` AS guest,
                        session.`class_ID` AS class,
                        session.`gkn` AS gkn,
                        class.`date` AS class_date, 
                        users.`userid`,
                        users.`role`,
                        users.`first_name`,
                        users.`last_name`,
                        users.`org_ID` AS organization,
                        users.`franchise`,
                        users.`product`,
                        users.`indication`,
                        users.`who_dm`,
                        dm.`first_name` AS dm_first_name,
                        dm.`last_name` AS dm_last_name 
                    FROM level 
                    LEFT JOIN session ON session.`session_ID` = level.`session_ID` 
                    LEFT JOIN users ON users.`userid` = session.`username` 
                    LEFT JOIN class ON session.`class_ID` = class.`class_ID` 
                    LEFT JOIN users AS dm on users.who_dm = dm.userid 
				";

		$conditions = [];
		
		// save values
		$keys = [
					'time_period', 'specific_date', 'specific_time', 'game_key_number', 
					'organization', 'franchise', 'product', 'indication', 'level', 
					'manager_first_name', 'manager_last_name', 
					'customer_first_name', 'customer_last_name', 
					'unix_id', 
					'class_id', 'class_date'
				];
		foreach ($keys as $key) {
			if (isset($data[$key])) {
				$_SESSION[$key] = $data[$key];
			} else {
                unset($_SESSION[$key]);
            }
		}

		//level
		if (isset($data['level']) && !empty($data['level'])) {
			$conditions[] = "level.`type` = " . mysqli_real_escape_string($this->db_connect, $data['level']);
		}

		if (isset($data['time_period']) && !empty($data['time_period'])) {
			switch ($data['time_period']) {
				case "first-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MIN(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "last-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MAX(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "average-score":
					break;
				case "game-key-instance":
					if (isset($data['game_key_number']) && !empty($data['game_key_number'])) {
						$conditions[] = "session.`gkn` = '" . $data['game_key_number'] . "'";
					}
					break;
				case "specific-date":
					if (isset($data['specific_date']) && !empty($data['specific_date'])) {
						$conditions[] = "session.`date` = '" . $data['specific_date'] . "'";
					}
					if (isset($data['specific_time']) && !empty($data['specific_time'])) {
						$conditions[] = "session.`time` = '" . $data['specific_time'] . "'";
					}
					break;
				default:
			}
		}

		if (isset($data['organization']) && !empty($data['organization'])) {
			$conditions[] = "users.`org_ID` = '" . mysqli_real_escape_string($this->db_connect, $data['organization']) . "'";
			if ($data['organization'] == 'BioOnc') {
				if (isset($data['product']) && !empty($data['product'])) {
					$conditions[] = "users.`product` = '" . mysqli_real_escape_string($this->db_connect, $data['product']) . "'";
				}
				if (isset($data['indication']) && !empty($data['indication'])) {
					$conditions[] = "users.`indication` = '" . mysqli_real_escape_string($this->db_connect, $data['indication']) . "'";
				}
			}
		}

		if (isset($data['franchise']) && !empty($data['franchise'])) {
			$conditions[] = "users.`franchise` = '" . mysqli_real_escape_string($this->db_connect, $data['franchise']) . "'";
		}

		if (isset($data['customer_first_name']) && !empty($data['customer_first_name'])) {
			$conditions[] = "users.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_first_name']) . "%'";
		}

		if (isset($data['customer_last_name']) && !empty($data['customer_last_name'])) {
			$conditions[] = "users.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_last_name']) . "%'";
		}

		if (isset($data['manager_first_name']) && !empty($data['manager_first_name'])) {
			$conditions[] = "dm.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_first_name']) . "%'";
		}

		if (isset($data['manager_last_name']) && !empty($data['manager_last_name'])) {
			$conditions[] = "dm.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_last_name']) . "%'";
		}

		if (isset($data['unix_id']) && !empty($data['unix_id'])) {
			$ids = explode(',', $data['unix_id']);
			for ($i = 0; $i < count($ids); $i += 1) {
				$ids[$i] = "'" . mysqli_real_escape_string($this->db_connect, $ids[$i]) . "'";
			}
			$conditions[] = "users.`userid` IN (" . join(',', $ids) . ")";
		}
			
		// class conditions
		if (isset($data['class_id']) && !empty($data['class_id'])) {
			$conditions[] = "session.`class_ID` = " . mysqli_real_escape_string($this->db_connect, $data['class_id']);
		}

		if (isset($data['class_date']) && !empty($data['class_date'])) {
			$conditions[] = "class.`date` = '" . mysqli_real_escape_string($this->db_connect, $data['class_date']) . "'";
		}

		//check if role = 4
		if ($_SESSION['role'] == 4) {
			$conditions[] = "users.`who_dm` = '" . $_SESSION['username'] . "'"; 
		}

		// make query
		if (count($conditions) > 0) {
        	$sub_sql .= " WHERE " . join(" AND ", $conditions);
		}
		
		$sql = "SELECT 
                    dataset.`userid` AS userid,
                    dataset.`first_name` AS first_name,
                    dataset.`last_name` AS last_name,
                    dataset.`type` AS level,
                    ROUND(AVG(dataset.`time`)) AS time,
                    ROUND(AVG(dataset.`score`)) AS score,
                    AVG(dataset.`sales1`) AS sales1,
                    AVG(dataset.`sales2`) AS sales2,
                    AVG(dataset.`sales3`) AS sales3,
                    AVG(dataset.`sales4`) AS sales4,
                    AVG(dataset.`sales5`) AS sales5,
                    AVG(dataset.`sales6`) AS sales6,
                    AVG(dataset.`sales7`) AS sales7,
                    AVG(dataset.`sales8`) AS sales8,
                    AVG(dataset.`sales9`) AS sales9
                FROM (
            " . $sub_sql . ") AS dataset GROUP BY dataset.`userid`";
		
		//make query for average score
		if (isset($data['time_period']) && !empty($data['time_period']) && $data['time_period'] == "average-score") {
			$sql = "SELECT 
						'Average Score' AS userid,
						'Average' AS first_name,
						'Score' AS last_name,
						dataset.`type`  AS level, 
						ROUND(AVG(dataset.`time`)) AS time,
						ROUND(AVG(dataset.`score`)) AS score,
						AVG(dataset.`sales1`) AS sales1,
						AVG(dataset.`sales2`) AS sales2,
						AVG(dataset.`sales3`) AS sales3,
						AVG(dataset.`sales4`) AS sales4,
						AVG(dataset.`sales5`) AS sales5,
						AVG(dataset.`sales6`) AS sales6,
						AVG(dataset.`sales7`) AS sales7,
						AVG(dataset.`sales8`) AS sales8,
						AVG(dataset.`sales9`) AS sales9
					FROM (
				" . $sub_sql . ") AS dataset";
		}

		return $sql;
    }
    
    /* 
    * Generate query for getting users who played the game session
    *
    * @param    nothing
    *
    */
    public function generateQueryForSessionPlayer() {
        if ($this->role == 1) {
            //Get all users who played the game session
            $query = "SELECT 
                            DISTINCT session.`username` AS userid,
                            CONCAT(users.`first_name`, ' ' , users.`last_name`) AS username
                        FROM session 
                        JOIN users ON session.username = users.userid 
                        WHERE 1 = 1";
        } else if ($this->role == 2) {
            //Get users who played the game session with this trainer
            $query = "SELECT 
                            DISTINCT session.`username` AS userid,
                            CONCAT(users.`first_name`, ' ' , users.`last_name`) AS username
                        FROM session 
                        JOIN users ON session.username = users.userid 
                        JOIN trainers ON trainers.`trainer_ID` = session.`trainer_ID`
                        WHERE trainers.`unix_ID` = '{$this->username}'";
        } else if ($this->role == 3) {
            //Get user who played the game session
            $query = "SELECT 
                            DISTINCT session.`username` AS userid,
                            CONCAT(users.`first_name`, ' ' , users.`last_name`) AS username
                        FROM session 
                        JOIN users ON session.username = users.userid 
                        WHERE session.`username` = '{$this->username}'";
        }

        return $query;
    }

    /* 
    * Generate query for getting some data from level
    *
    * @param    int         $level
    *
    */
    public function generateQueryForGetLevelByID($level_id = 0) {
        $query = "SELECT
                        users.`userid`,
                        users.`first_name`,
                        users.`last_name`,
                        level.`type` AS level,
                        level.`sales1`, level.`sales2`, level.`sales3`, 
                        level.`sales4`, level.`sales5`, level.`sales6`,
                        level.`sales7`, level.`sales8`, level.`sales9` 
                    FROM level 
                    JOIN session ON session.`session_ID` = level.`session_ID`
                    JOIN users ON session.`username` = users.`userid` 
                    WHERE `level_ID` = " . $level_id;
        
        return $query;
    }

    /* 
    * Get prefix project name
    *
    * @param    nothing
    *
    */
    public function getPrefixProjectName() {
        return $this->prefix_project_name;
    }
    
    /* 
    * Get organizations from current user id (unix_id)
    *
    * @param    nothing
    *
    */
    public function getBuFromUnixID() {
		$query = "SELECT 
                        `org_ID` AS bu 
                    FROM users 
                    WHERE users.userid = '" . $this->username . "'";
		
		if ($result = mysqli_query($this->db_connect, $query)) {
			$rowcount = mysqli_num_rows($result);
			if ($rowcount == 1) {
				$row = mysqli_fetch_row($result);
				return $row[0];
			} else {
				return '';
			}
		} else {
			return false;
		}
	}

    /* 
    * Generate query for getting users from id
    *
    * @param    int         $id
    *
    */
    public function generateQueryForGetUsers($id = '') {
        if (!isset($id) || $id == null || $id == '') {
            $query = "SELECT
                            users.`id`,
                            users.`userid`,
                            users.`first_name`,
                            users.`last_name`,
                            users.`org_ID` AS organization,
                            users.`role` 
                        FROM users 
                        WHERE 1 = 1 ";
        } else {
            $query = "SELECT
                            users.* 
                        FROM users 
                        WHERE users.`id` = {$id} ";
        }
    
        return $query;
    }

    /* 
    * Generate query for getting users are not direct managers
    *
    * @param    nothing
    *
    */
    public function generateQueryForGetUsersForDM() {
        $query = "SELECT
                        users.`userid`,
                        CONCAT(users.`first_name`, ' ', users.`last_name`) AS username 
                    FROM users 
                    WHERE users.`role` != 3";
    
        return $query;
    }

    /* 
    * Generate query for getting classes from conditions
    *
    * @param    array       $fields         fields for conditions (each field includes column as key, operator and value)
    * @param    string      $order_by_key   column as key for order by
    *
    */
    public function generateQueryForGetClasses($fields = array(), $order_by_key = NULL) {
        $sql = "SELECT 
                        * 
                    FROM class";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'class_ID' || $field['key'] === 'trainer_ID') {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " " . mysqli_real_escape_string($this->db_connect, $field['value']);
            } else {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }

        if ($order_by_key != NULL) {
            $conidtion_sql .= " ORDER BY class.`" . $order_by_key . "`";
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting new user id
    *
    * @param    nothing
    *
    */
    public function generateQueryForGetNewUserId() {
        $query = "SELECT 
                        MAX(users.`id`) + 1 AS new_id 
                    FROM users";
        return $query;
    }

    /* 
    * Generate query for deleting users from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForDeleteUserByField($fields) {
        $sql = "DELETE FROM users";

        $conditions = array();

        foreach ($fields as $field) {
            if (($field['key'] == 'id' || $field['key'] == 'role' || $field['key'] == 'class_ID') && 
                    !empty($field['value'])) {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting users from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForGetUsersByField($fields = array(), $no_password = TRUE) {
        if($no_password === TRUE) {
            $sql = "SELECT
                        users.`id`,
                        users.`userid`,
                        users.`email`,
                        users.`role`,
                        users.`first_name`,
                        users.`last_name`,
                        users.`org_ID`,
                        users.`franchise`,
                        users.`product`,
                        users.`indication`,
                        users.`who_dm`,
                        users.`class_ID`,
                        users.`gkn`,
                        users.`org_ID` AS organization 
                    FROM users";
        } else {
            $sql = "SELECT 
                        users.*,
                        users.`org_ID` AS organization 
                    FROM users";
        }
        
        $conditions = array();

        foreach ($fields as $field) {
            if (($field['key'] == 'id' || $field['key'] == 'role' || $field['key'] == 'class_ID') && !empty($field['value'])) {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                if ($field['value'] === NULL) {
                    $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " NULL";
                } else if ($field['operator'] === 'LIKE' && !empty($field['value'])) {
                    $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " '%" . mysqli_real_escape_string($this->db_connect, $field['value']) . "%'";
                } else if ($field['operator'] === 'IN' && !empty($field['value'])) {
                    $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " (" . join(',', $field['value']) . ")";
                } else {
                    $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
                }
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting distincted item(column) from conditions
    *
    * @param    string      $item               column for getting
    * @param    string      $item_name          new item name
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForGetItemFromUsers($item, $item_name, $fields) {
        $sql = "SELECT 
                        DISTINCT users.`{$item}` AS $item_name 
                    FROM users";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['value'] === NULL) {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " NULL";
            } else {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting users from conversation
    *
    * @param nothing
    *
    */
    public function generateQueryForGetUserFromConv() {
        $query = "SELECT 
                        DISTINCT users.`userid`, 
                        CONCAT(users.`first_name`, ' ' , users.`last_name`) AS username 
                    FROM conversations 
                    JOIN users ON conversations.`unix_ID` = users.`userid` ";
        return $query;
    }

    /* 
    * Generate query to get level instances for coaching
    *
    * @param    array      $data
    *
    */
    public function generateQueryForGetLevelInstances($data) {
        $conditions = array();
        $player = $data['player'];
        $level = $data['level'];

        $sql = "SELECT 
                        level.`level_ID`,
                        level.`type` AS `level`,
                        session.`session_ID`,
                        session.`username` AS `unix_id`,
                        session.`date`,
                        SUBSTR(session.`time`, 1, 8) AS `time`,
                        CONCAT(users.`first_name`, ' ', users.`last_name`) AS `username` 
                    FROM level
                    JOIN session ON 
                        session.`session_ID` = level.`session_ID`
                    JOIN users ON
                        users.`userid` = session.`username`

                ";

        $conditions[] = "level.`type` = {$level}";
        $conditions[] = "session.`username` = '{$player}'";

        if (isset($data['time_period']) && !empty($data['time_period'])) {
            switch ($data['time_period']) {
                case "first-time":
                    $conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MIN(CONCAT(session.`date`, ' ', session.`time`)) FROM session WHERE session.`username` = '{$player}')";
                    break;
                case "last-time":
                    $conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MAX(CONCAT(session.`date`, ' ', session.`time`)) FROM session WHERE session.`username` = '{$player}')";
                    break;
                case "game-key-instance":
                    if (isset($data['game_key_number']) && !empty($data['game_key_number'])) {
                        $conditions[] = "session.`gkn` = '" . $data['game_key_number'] . "'";
                    }
                    break;
                case "specific-date":
                    if (isset($data['specific_date']) && !empty($data['specific_date'])) {
                        $conditions[] = "session.`date` = '" . $data['specific_date'] . "'";
                    }
                    if (isset($data['specific_time']) && !empty($data['specific_time'])) {
                        $conditions[] = "session.`time` = '" . $data['specific_time'] . "'";
                    }
                    break;
                default:
            }
        }

        // make query
		if (count($conditions) > 0) {
        	$sql .= ' WHERE ' . join(' AND ', $conditions);
        }
        
        $sql .= ' ORDER BY level.`level_ID` DESC';

		return $sql;
    }

    /* 
    * Generate query for getting conversations from level ID
    *
    * @param    int      $level_ID
    *
    */
    public function generateQueryForGetConvs($level_ID) {
        $sql = "SELECT 
                        level.`type` AS level, 
                        conv.`conversation_ID`, 
                        conv.`unix_ID`, 
                        conv.`session_ID`, 
                        conv.`npcName` AS actor, 
                        dn.`npc#` AS npc_number,
                        dn.`character_name`
                    FROM conversations AS conv 
                    JOIN level ON 
                        level.`level_ID` = {$level_ID} AND 
                        (conv.`conversation_ID` = level.`conversation_ID1` OR 
                        conv.`conversation_ID` = level.`conversation_ID2` OR 
                        conv.`conversation_ID` = level.`conversation_ID3` OR 
                        conv.`conversation_ID` = level.`conversation_ID4` OR 
                        conv.`conversation_ID` = level.`conversation_ID5`) 
                    JOIN dialogue_npc AS dn ON
                        dn.`level` = level.`type` AND 
                        LOWER(dn.`character_name`) = conv.`npcName`
                ";
        
        return $sql;
    }

    /* 
    * Generate query for getting dialogues from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    * @param    string      $order_by_key       column as key for order by
    *
    */
    public function generateQueryForGetDialogue($fields, $order_by_key = NULL) {
        $sql = "SELECT 
                        d.*,
                        d.`dialogue` AS correct_response 
                    FROM dialogue AS d";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'node_ID' && strtolower($field['operator']) === 'like') {
                $conditions[] = "d.`{$field['key']}` " . $field['operator'] . " CONCAT('" . mysqli_real_escape_string($this->db_connect, $field['value']) . "', '.%')";
            } else if ($field['key'] === 'level' || $field['key'] === 'actor' || $field['key'] === 'conversant') {
                $conditions[] = "d.`{$field['key']}` " . $field['operator'] . " " . mysqli_real_escape_string($this->db_connect, $field['value']);
            } else {
                $conditions[] = "d.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }

        if ($order_by_key != NULL) {
            $conidtion_sql .= " ORDER BY d.`" . $order_by_key . "`";
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting game session data from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    * @param    string      $order_by_key       column as key for order by
    * @param    boolean     $get_time_period
    *
    */
    public function generateQueryForGetSessionData($fields, $order_by_key = NULL, $get_time_period = FALSE) {
        if ($get_time_period === TRUE) {
            $sql = "SELECT 
                        DISTINCT `time` 
                    FROM session";
        } else {
            $sql = "SELECT 
                        * 
                    FROM session";
        }
        
        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'session_ID' || $field['key'] === 'duration' || 
                $field['key'] === 'trainer_ID' || $field['key'] === 'trainee_ID' || 
                $field['key'] === 'guest_ID' || $field['key'] === 'class_ID') {
                $conditions[] = "session.`{$field['key']}` " . $field['operator'] . " " . mysqli_real_escape_string($this->db_connect, $field['value']);
            } else {
                $conditions[] = "session.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }

        if ($order_by_key != NULL) {
            $conidtion_sql .= " ORDER BY session.`" . $order_by_key . "`";
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting level data from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    * @param    string      $order_by_key       column as key for order by
    *
    */
    public function generateQueryForGetLevelData($fields, $order_by_key = NULL) {
        $sql = "SELECT 
                        * 
                    FROM level";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'level_ID' || $field['key'] === 'session_ID' || 
                $field['key'] === 'type' || $field['key'] === 'environment_ID') {
                $conditions[] = "level.`{$field['key']}` " . $field['operator'] . " " . mysqli_real_escape_string($this->db_connect, $field['value']);
            } else {
                $conditions[] = "level.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }

        if ($order_by_key != NULL) {
            $conidtion_sql .= " ORDER BY level.`" . $order_by_key . "`";
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for getting users from conditions
    *
    * @param    array       $data               conditions
    *
    */
    public function generateQueryForGetUsersForClassID($data) {
        $sql = "SELECT users.* FROM users";
        $conditions = [];
        
        if (isset($data['organization']) && !empty($data['organization'])) {
            $conditions[] = "users.`org_ID`='" . mysqli_real_escape_string($this->db_connect, $data['organization']) . "'";
        }

        if (isset($data['franchise']) && !empty($data['franchise'])) {
            $conditions[] = "users.`franchise` = '" . mysqli_real_escape_string($this->db_connect, $data['franchise']) . "'";
        }

        if (isset($data['customer_first_name']) && !empty($data['customer_first_name'])) {
            $conditions[] = "users.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_first_name']) . "%'";
        }

        if (isset($data['customer_last_name']) && !empty($data['customer_last_name'])) {
            $conditions[] = "users.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_last_name']) . "%'";
        }

        if (isset($data['manager_first_name']) || isset($data['manager_last_name'])) {
            $sql .= " LEFT JOIN users AS dm ON dm.`userid` = users.`who_dm`";
        }

        if (isset($data['manager_first_name']) && !empty($data['manager_first_name'])) {
            $conditions[] = "dm.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_first_name']) . "%'";
        }

        if (isset($data['manager_last_name']) && !empty($data['manager_last_name'])) {
            $conditions[] = "dm.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_last_name']) . "%'";
        }

        if (isset($data['unix_id']) && !empty($data['unix_id'])) {
            $ids = explode(',', $data['unix_id']);
            for ($i = 0; $i < count($ids); $i += 1) {
                $ids[$i] = "'" . mysqli_real_escape_string($this->db_connect, $ids[$i]) . "'";
            }
            $conditions[] = "users.`userid` IN (" . join(',', $ids) . ")";
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . join(" AND ", $conditions);
        }

        return $sql;
    }

    /* 
    * Generate query for updating class data
    *
    * @param    array       $data_fields        fields for updating (each field includes column as key and value)
    * @param    array       $condition_fields   fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForUpdateClass($data_fields, $condition_fields) {
        $sql = "UPDATE class";

        $update_set = array();
        $conditions = array();

        // make update set sql
        $updata_set_sql = '';
        foreach ($data_fields as $data) {
            if ($data['key'] === 'trainer_ID') {
                $update_set[] = "class.`{$data['key']}` = " . $data['value'];
            } else {
                $update_set[] = "class.`{$data['key']}` = '" . $data['value'] . "'";
            }
        }

        if (count($update_set) > 0) {
        	$updata_set_sql .= " SET " . join(" , ", $update_set);
        }

        // make condition query
        $condition_sql = '';
        foreach ($condition_fields as $field) {
            if ($field['key'] === 'class_ID' || $field['key'] === 'trainer_ID') {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " '" . $field['value'] . "'";
            }
        }

        if (count($conditions) > 0) {
        	$condition_sql .= " WHERE " . join(" AND ", $conditions);
        }

        return $sql . $updata_set_sql . $condition_sql;
    }

    /* 
    * Generate query for updating user data
    *
    * @param    array       $data_fields        fields for updating (each field includes column as key and value)
    * @param    array       $condition_fields   fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForUpdateUsers($data_fields, $condition_fields) {
        $sql = "UPDATE users";

        $update_set = array();
        $conditions = array();

        // make update set sql
        $updata_set_sql = '';
        foreach ($data_fields as $data) {
            if ($data['key'] === 'role' || $data['key'] === 'class_ID') {
                $update_set[] = "users.`{$data['key']}` = " . $data['value'];
            } else {
                $update_set[] = "users.`{$data['key']}` = '" . $data['value'] . "'";
            }
        }

        if (count($update_set) > 0) {
        	$updata_set_sql .= " SET " . join(" , ", $update_set);
        }

        // make condition query
        $condition_sql = '';
        foreach ($condition_fields as $field) {
            if ($field['key'] === 'id' || $field['key'] === 'role' || $field['key'] === 'class_ID') {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else if ($field['key'] === 'userid' && (strtolower($field['operator']) === 'not in' || strtolower($field['operator']) === 'in')) {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " (" . join(',', $field['value']) . ")";
            } else {
                $conditions[] = "users.`{$field['key']}` " . $field['operator'] . " '" . $field['value'] . "'";
            }
        }

        if (count($conditions) > 0) {
        	$condition_sql .= " WHERE " . join(" AND ", $conditions);
        }

        return $sql . $updata_set_sql . $condition_sql;
    }

    /* 
    * Generate query for inserting class data
    *
    * @param    array       $data_fields        fields for inserting (each field includes column as key and value)
    *
    */
    public function generateQueryForInsertClass($data_fields) {
        $sql = "INSERT INTO class";

        $fields_sql = '';
        $values_sql = '';

        $fields = array();
        $values = array();

        foreach ($data_fields as $data) {
            $fields[] = "`" . $data['key'] . "`";

            if ($data['key'] === 'trainer_ID') {
                $values[] = $data['value'];
            } else {
                $values[] = "'" . $data['value'] . "'";
            }
        }

        $fields_sql = " (" . join(",", $fields) . ")";
        $values_sql = " VALUES (" . join(",", $values) . ")";

        return $sql . $fields_sql . $values_sql;
    }

    /* 
    * Generate query for inserting user data
    *
    * @param    array       $data_fields        fields for inserting (each field includes column as key and value)
    *
    */
    public function generateQueryForInsertUsers($data_fields) {
        $sql = "INSERT INTO users";

        $fields_sql = '';
        $values_sql = '';

        $fields = array();
        $values = array();

        foreach ($data_fields as $data) {
            $fields[] = "`" . $data['key'] . "`";

            if ($data['key'] === 'id' || $data['key'] === 'role' || $data['key'] === 'class_ID') {
                $values[] = $data['value'];
            } else {
                $values[] = "'" . $data['value'] . "'";
            }
        }

        $fields_sql = " (" . join(",", $fields) . ")";
        $values_sql = " VALUES (" . join(",", $values) . ")";

        return $sql . $fields_sql . $values_sql;
    }

    /* 
    * Generate query for inserting dialogue_npc data
    *
    * @param    array       $data_fields        fields for inserting (each field includes column as key and value)
    *
    */
    public function generateQueryForInsertDialogueNPC($data_fields) {
        $sql = "INSERT INTO dialogue_npc";

        $fields_sql = '';
        $values_sql = '';

        $fields = array();
        $values = array();

        foreach ($data_fields as $data) {
            $fields[] = "`" . $data['key'] . "`";

            if ($data['key'] === 'id' || $data['key'] === 'level' || $data['key'] === 'npc#') {
                $values[] = $data['value'];
            } else {
                $values[] = "'" . $data['value'] . "'";
            }
        }

        $fields_sql = " (" . join(",", $fields) . ")";
        $values_sql = " VALUES (" . join(",", $values) . ")";

        return $sql . $fields_sql . $values_sql;
    }

    /* 
    * Generate query for inserting dialogue data
    *
    * @param    array       $data_fields        fields for inserting (each field includes column as key and value)
    *
    */
    public function generateQueryForInsertDialogue($data_fields) {
        $sql = "INSERT INTO dialogue";

        $fields_sql = '';
        $values_sql = '';

        $fields = array();
        $values = array();

        foreach ($data_fields as $data) {
            $fields[] = "`" . $data['key'] . "`";

            if ($data['key'] === 'id' || $data['key'] === 'level' || $data['key'] === 'actor' || $data['key'] === 'conversant') {
                $values[] = $data['value'];
            } else {
                $values[] = "'" . mysqli_real_escape_string($this->db_connect, $data['value']) . "'";
            }
        }

        $fields_sql = " (" . join(",", $fields) . ")";
        $values_sql = " VALUES (" . join(",", $values) . ")";

        return $sql . $fields_sql . $values_sql;
    }

    /* 
    * Generate query for deleting class from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForDeleteClass($fields = array()) {
        $sql = "DELETE FROM class";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] == 'class_ID') {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                $conditions[] = "class.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for deleting dialogue_npc from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForDeleteDialogueNPC($fields = array()) {
        $sql = "DELETE FROM dialogue_npc";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'id' || $field['key'] === 'level' || $field['key'] === 'npc#') {
                $conditions[] = "dialogue_npc.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                $conditions[] = "dialogue_npc.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for deleting dialogue from conditions
    *
    * @param    array       $fields             fields for conditions (each field includes column as key, operator and value)
    *
    */
    public function generateQueryForDeleteDialogue($fields = array()) {
        $sql = "DELETE FROM dialogue";

        $conditions = array();

        foreach ($fields as $field) {
            if ($field['key'] === 'id' || $field['key'] === 'level' || $field['key'] === 'actor' || $field['key'] === 'conversant') {
                $conditions[] = "dialogue.`{$field['key']}` " . $field['operator'] . " " . $field['value'];
            } else {
                $conditions[] = "dialogue.`{$field['key']}` " . $field['operator'] . " '" . mysqli_real_escape_string($this->db_connect, $field['value']) . "'";
            }
        }

        // make query
        $conidtion_sql = '';
		if (count($conditions) > 0) {
        	$conidtion_sql .= " WHERE " . join(" AND ", $conditions);
        }
        
        return $sql . $conidtion_sql;
    }

    /* 
    * Generate query for altering auto increment of any table
    *
    * @param    string      $table_name         table name for altering
    * @param    int         $value              value for setting
    *
    */
    public function generateQueryForSetAutoIncrement($table_name, $value = 1) {
        $sql = "ALTER TABLE " . $table_name . " AUTO_INCREMENT = " . $value;

        return $sql;
    }

    /* 
    * Generate query for aggregate report from $_SESSION or $_POST
    *
    * @param    array       $data               $_SESSION or $_POST
    *
    */
    public function generateQueryForAggregateReport($data) {

        $sql = "SELECT ";
        if (isset($data['time_period']) && !empty($data['time_period']) && $data['time_period'] == "average-score") {
            $sql .= "   IF((AVG(level.`sales1`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) < 68, 1, 0) AS `sales1_red`,";
            $sql .= "   IF((AVG(level.`sales1`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) < 90 && (AVG(level.`sales1`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) >=68, 1, 0) AS `sales1_yellow`,";
            $sql .= "   IF((AVG(level.`sales1`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) >= 90, 1, 0) AS `sales1_green`,";
            $sql .= "   IF((AVG(level.`sales2`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) < 68, 1, 0) AS `sales2_red`,";
            $sql .= "   IF((AVG(level.`sales2`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) < 90 && (AVG(level.`sales2`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) >=68, 1, 0) AS `sales2_yellow`,";
            $sql .= "   IF((AVG(level.`sales2`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) >= 90, 1, 0) AS `sales2_green`,";
            $sql .= "   IF((AVG(level.`sales3`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) < 68, 1, 0) AS `sales3_red`,";
            $sql .= "   IF((AVG(level.`sales3`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) < 90 && (AVG(level.`sales3`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) >=68, 1, 0) AS `sales3_yellow`,";
            $sql .= "   IF((AVG(level.`sales3`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) >= 90, 1, 0) AS `sales3_green`,";
            $sql .= "   IF((AVG(level.`sales4`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) < 68, 1, 0) AS `sales4_red`,";
            $sql .= "   IF((AVG(level.`sales4`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) < 90 && (AVG(level.`sales4`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) >=68, 1, 0) AS `sales4_yellow`,";
            $sql .= "   IF((AVG(level.`sales4`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) >= 90, 1, 0) AS `sales4_green`,";
            $sql .= "   IF((AVG(level.`sales5`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) < 68, 1, 0) AS `sales5_red`,";
            $sql .= "   IF((AVG(level.`sales5`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) < 90 && (AVG(level.`sales5`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) >=68, 1, 0) AS `sales5_yellow`,";
            $sql .= "   IF((AVG(level.`sales5`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) >= 90, 1, 0) AS `sales5_green`,";
            $sql .= "   IF((AVG(level.`sales6`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) < 68, 1, 0) AS `sales6_red`,";
            $sql .= "   IF((AVG(level.`sales6`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) < 90 && (AVG(level.`sales6`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) >=68, 1, 0) AS `sales6_yellow`,";
            $sql .= "   IF((AVG(level.`sales6`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) >= 90, 1, 0) AS `sales6_green`,";
            $sql .= "   IF((AVG(level.`sales7`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) < 68, 1, 0) AS `sales7_red`,";
            $sql .= "   IF((AVG(level.`sales7`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) < 90 && (AVG(level.`sales7`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) >=68, 1, 0) AS `sales7_yellow`,";
            $sql .= "   IF((AVG(level.`sales7`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) >= 90, 1, 0) AS `sales7_green`,";
            $sql .= "   IF((AVG(level.`sales8`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) < 68, 1, 0) AS `sales8_red`,";
            $sql .= "   IF((AVG(level.`sales8`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) < 90 && (AVG(level.`sales8`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) >=68, 1, 0) AS `sales8_yellow`,";
            $sql .= "   IF((AVG(level.`sales8`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) >= 90, 1, 0) AS `sales8_green`,";
            $sql .= "   IF((AVG(level.`sales9`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) < 68, 1, 0) AS `sales9_red`,";
            $sql .= "   IF((AVG(level.`sales9`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) < 90 && (AVG(level.`sales9`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) >=68, 1, 0) AS `sales9_yellow`,";
            $sql .= "   IF((AVG(level.`sales9`) / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) >= 90, 1, 0) AS `sales9_green`";
        } else {
            $sql .= "   COUNT(IF((level.`sales1` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) < 68, 1, NULL)) AS `sales1_red`,";
            $sql .= "   COUNT(IF((level.`sales1` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) < 90 && (level.`sales1` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) >=68, 1, NULL)) AS `sales1_yellow`,";
            $sql .= "   COUNT(IF((level.`sales1` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales1'] . " * 100) >= 90, 1, NULL)) AS `sales1_green`,";
            $sql .= "   COUNT(IF((level.`sales2` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) < 68, 1, NULL)) AS `sales2_red`,";
            $sql .= "   COUNT(IF((level.`sales2` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) < 90 && (level.`sales2` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) >=68, 1, NULL)) AS `sales2_yellow`,";
            $sql .= "   COUNT(IF((level.`sales2` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales2'] . " * 100) >= 90, 1, NULL)) AS `sales2_green`,";
            $sql .= "   COUNT(IF((level.`sales3` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) < 68, 1, NULL)) AS `sales3_red`,";
            $sql .= "   COUNT(IF((level.`sales3` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) < 90 && (level.`sales3` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) >=68, 1, NULL)) AS `sales3_yellow`,";
            $sql .= "   COUNT(IF((level.`sales3` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales3'] . " * 100) >= 90, 1, NULL)) AS `sales3_green`,";
            $sql .= "   COUNT(IF((level.`sales4` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) < 68, 1, NULL)) AS `sales4_red`,";
            $sql .= "   COUNT(IF((level.`sales4` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) < 90 && (level.`sales4` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) >=68, 1, NULL)) AS `sales4_yellow`,";
            $sql .= "   COUNT(IF((level.`sales4` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales4'] . " * 100) >= 90, 1, NULL)) AS `sales4_green`,";
            $sql .= "   COUNT(IF((level.`sales5` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) < 68, 1, NULL)) AS `sales5_red`,";
            $sql .= "   COUNT(IF((level.`sales5` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) < 90 && (level.`sales5` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) >=68, 1, NULL)) AS `sales5_yellow`,";
            $sql .= "   COUNT(IF((level.`sales5` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales5'] . " * 100) >= 90, 1, NULL)) AS `sales5_green`,";
            $sql .= "   COUNT(IF((level.`sales6` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) < 68, 1, NULL)) AS `sales6_red`,";
            $sql .= "   COUNT(IF((level.`sales6` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) < 90 && (level.`sales6` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) >=68, 1, NULL)) AS `sales6_yellow`,";
            $sql .= "   COUNT(IF((level.`sales6` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales6'] . " * 100) >= 90, 1, NULL)) AS `sales6_green`,";
            $sql .= "   COUNT(IF((level.`sales7` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) < 68, 1, NULL)) AS `sales7_red`,";
            $sql .= "   COUNT(IF((level.`sales7` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) < 90 && (level.`sales7` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) >=68, 1, NULL)) AS `sales7_yellow`,";
            $sql .= "   COUNT(IF((level.`sales7` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales7'] . " * 100) >= 90, 1, NULL)) AS `sales7_green`,";
            $sql .= "   COUNT(IF((level.`sales8` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) < 68, 1, NULL)) AS `sales8_red`,";
            $sql .= "   COUNT(IF((level.`sales8` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) < 90 && (level.`sales8` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) >=68, 1, NULL)) AS `sales8_yellow`,";
            $sql .= "   COUNT(IF((level.`sales8` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales8'] . " * 100) >= 90, 1, NULL)) AS `sales8_green`,";
            $sql .= "   COUNT(IF((level.`sales9` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) < 68, 1, NULL)) AS `sales9_red`,";
            $sql .= "   COUNT(IF((level.`sales9` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) < 90 && (level.`sales9` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) >=68, 1, NULL)) AS `sales9_yellow`,";
            $sql .= "   COUNT(IF((level.`sales9` / " . $this->totalSalesPoints["level-{$data['level']}"]['sales9'] . " * 100) >= 90, 1, NULL)) AS `sales9_green`";
        }
        $sql .= "   FROM level 
                    LEFT JOIN session ON session.`session_ID` = level.`session_ID` 
                    LEFT JOIN users ON users.`userid` = session.`username` 
                    LEFT JOIN class ON session.`class_ID` = class.`class_ID` 
                    LEFT JOIN users AS dm on users.who_dm = dm.userid 
                    ";

		$conditions = [];
		
		// save values
		$keys = [
					'time_period', 'specific_date', 'specific_time', 'game_key_number',
					'organization', 'franchise', 'product', 'indication', 'level', 
					'manager_first_name', 'manager_last_name', 
					'customer_first_name', 'customer_last_name', 
					'unix_id', 
					'class_id', 'class_date'
				];
		foreach ($keys as $key) {
			if (isset($data[$key])) {
				$_SESSION[$key] = $data[$key];
			} else {
                unset($_SESSION[$key]);
            }
		}

		//level
		if (isset($data['level']) && !empty($data['level'])) {
			$conditions[] = "level.`type` = " . mysqli_real_escape_string($this->db_connect, $data['level']);
		}

		if (isset($data['time_period']) && !empty($data['time_period'])) {
			switch ($data['time_period']) {
				case "first-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MIN(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "last-time":
					$conditions[] = "CONCAT(session.`date`, ' ', session.`time`) = (SELECT MAX(CONCAT(session.`date`, ' ', session.`time`)) FROM session)";
					break;
				case "average-score":
					break;
				case "game-key-instance":
					if (isset($data['game_key_number']) && !empty($data['game_key_number'])) {
						$conditions[] = "session.`gkn` = '" . $data['game_key_number'] . "'";
					}
					break;
				case "specific-date":
					if (isset($data['specific_date']) && !empty($data['specific_date'])) {
						$conditions[] = "session.`date` = '" . $data['specific_date'] . "'";
					}
					if (isset($data['specific_time']) && !empty($data['specific_time'])) {
						$conditions[] = "session.`time` = '" . $data['specific_time'] . "'";
					}
					break;
				default:
			}
		}

		if (isset($data['organization']) && !empty($data['organization'])) {
			$conditions[] = "users.`org_ID` = '" . mysqli_real_escape_string($this->db_connect, $data['organization']) . "'";
			if ($data['organization'] == 'BioOnc') {
				if (isset($data['product']) && !empty($data['product'])) {
					$conditions[] = "users.`product` = '" . mysqli_real_escape_string($this->db_connect, $data['product']) . "'";
				}
				if (isset($data['indication']) && !empty($data['indication'])) {
					$conditions[] = "users.`indication` = '" . mysqli_real_escape_string($this->db_connect, $data['indication']) . "'";
				}
			}
		}

		if (isset($data['franchise']) && !empty($data['franchise'])) {
			$conditions[] = "users.`franchise` = '" . mysqli_real_escape_string($this->db_connect, $data['franchise']) . "'";
		}

		if (isset($data['customer_first_name']) && !empty($data['customer_first_name'])) {
			$conditions[] = "users.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_first_name']) . "%'";
		}

		if (isset($data['customer_last_name']) && !empty($data['customer_last_name'])) {
			$conditions[] = "users.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['customer_last_name']) . "%'";
		}

		if (isset($data['manager_first_name']) && !empty($data['manager_first_name'])) {
			$conditions[] = "dm.`first_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_first_name']) . "%'";
		}

		if (isset($data['manager_last_name']) && !empty($data['manager_last_name'])) {
			$conditions[] = "dm.`last_name` LIKE '%" . mysqli_real_escape_string($this->db_connect, $data['manager_last_name']) . "%'";
		}

		if (isset($data['unix_id']) && !empty($data['unix_id'])) {
			$ids = explode(',', $data['unix_id']);
			for ($i = 0; $i < count($ids); $i += 1) {
				$ids[$i] = "'" . mysqli_real_escape_string($this->db_connect, $ids[$i]) . "'";
			}
			$conditions[] = "users.`userid` IN (" . join(',', $ids) . ")";
		}
			
		// class conditions
		if (isset($data['class_id']) && !empty($data['class_id'])) {
			$conditions[] = "session.`class_ID` = " . mysqli_real_escape_string($this->db_connect, $data['class_id']);
		}

		if (isset($data['class_date']) && !empty($data['class_date'])) {
			$conditions[] = "class.`date` = '" . mysqli_real_escape_string($this->db_connect, $data['class_date']) . "'";
		}

		//check if role = 4
		if ($this->role == 4) {
			$conditions[] = "users.`who_dm` = '" . $this->username . "'"; 
		}

		// make query
		if (count($conditions) > 0) {
        	$sql .= " WHERE " . join(" AND ", $conditions);
		}

		return $sql;
    }
    
    /* 
    * Generate query for set foreign key check
    *
    * @param    int       $value            value for set foreign key check (0: disable foreign key constraints, 1: enable)
    *
    */
    public function generateQueryForSetForeignKeyCheck($value) {
        $sql = "SET FOREIGN_KEY_CHECKS = " . $value;

        return $sql;
    }
}