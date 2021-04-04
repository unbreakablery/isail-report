<?php
    require_once('../../inc/config.php');

    $condition = array();

    if ($common->role == 4) {
        $condition = array(
                            'key'       => 'who_dm', 
                            'value'     => $common->username, 
                            'operator'  => '='
                        );
    }
?>

<div class="form-group">
    <div class="col-sm-12">
        <div class="form-material floating">
            <select class="form-control" id="time_period" name="time_period" size="1">
                <option></option>
                <option value="first-time">First Time Playing the Level</option>
                <option value="last-time">Most Recent Time Playing the Level</option>
                <option value="average-score">Average Score for all Times Playing</option>
                <option value="game-key-instance">Game Key Instance</option>
                <option value="specific-date">Specific Date</option>
            </select>
            <label for="time_period">What period would you like to select for the report?</label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6 d-none specific-date-row">
        <div class="form-material">
            <input class="form-control js-datepicker" type="text" id="specific_date" name="specific_date" data-date-format="yyyy-mm-dd" placeholder="Specific Date (yyyy-mm-dd)">
            <label for="specific_date">Please Select specific date</label>
        </div>
    </div>

    <div class="col-sm-6 d-none specific-time-row">
        <div class="form-material floating">
            <select class="form-control" id="specific_time" name="specific_time" size="1">
                <option></option>
            </select>
            <label for="specific_time">Which game instance on </label>
        </div>
    </div>

    <div class="col-sm-6 d-none gkn-row">
        <div class="form-material">
            <input type="text" id="game_key_number" name="game_key_number" class="form-control" placeholder="Game Key Number" maxlength="24"/>                                    
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <div class="form-material floating">
            <select class="form-control" id="organization" name="organization" size="1">
                <option></option><!-- Empty value for demostrating material select box -->
                <?php
                    $fields = array();
                    $fields[] = array('key' => 'org_ID', 'value' => NULL,   'operator' => 'IS NOT');
                    $fields[] = array('key' => 'org_ID', 'value' => '',     'operator' => '!=');
                    if (!empty($condition)) {
                        $fields[] = $condition;
                    }

                    $query = $common->generateQueryForGetItemFromUsers('org_ID', 'organization', $fields);
                    
                    $result = mysqli_query($common->db_connect, $query);
                    
                    while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['organization']; ?>"><?php echo $row['organization']; ?></option>
                <?php } ?>
            </select>
            <label for="organization">Please Select BU</label>
        </div>
    </div>

    <div class="col-sm-6 d-none bu-row">
        <div class="form-material floating">
            <select class="form-control" id="product" name="product" size="1">
                <option></option>
                <?php
                    $fields = array();
                    $fields[] = array('key' => 'product', 'value' => NULL,  'operator' => 'IS NOT');
                    $fields[] = array('key' => 'product', 'value' => '',    'operator' => '!=');
                    if (!empty($condition)) {
                        $fields[] = $condition;
                    }

                    $query = $common->generateQueryForGetItemFromUsers('product', 'product', $fields);
                    
                    $result = mysqli_query($common->db_connect, $query);
                    
                    while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['product']; ?>"><?php echo $row['product']; ?></option>
                <?php } ?>
            </select>
            <label for="product">Please Select Product</label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6 d-none bu-row">
        <div class="form-material floating">
            <select class="form-control" id="indication" name="indication" size="1">
                <option></option><!-- Empty value for demostrating material select box -->
                <?php
                    $fields = array();
                    $fields[] = array('key' => 'indication', 'value' => NULL,   'operator' => 'IS NOT');
                    $fields[] = array('key' => 'indication', 'value' => '',     'operator' => '!=');
                    if (!empty($condition)) {
                        $fields[] = $condition;
                    }

                    $query = $common->generateQueryForGetItemFromUsers('indication', 'indication', $fields);
                    
                    $result = mysqli_query($common->db_connect, $query);
                    
                    while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['indication']; ?>"><?php echo $row['indication']; ?></option>
                <?php } ?>
            </select>
            <label for="indication">Please Select Indication</label>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-material floating">
            <select class="form-control" id="franchise" name="franchise" size="1">
                <option></option><!-- Empty value for demostrating material select box -->
                <?php
                    $fields = array();
                    $fields[] = array('key' => 'franchise', 'value' => NULL,    'operator' => 'IS NOT');
                    $fields[] = array('key' => 'franchise', 'value' => '',      'operator' => '!=');
                    if (!empty($condition)) {
                        $fields[] = $condition;
                    }

                    $query = $common->generateQueryForGetItemFromUsers('franchise', 'franchise', $fields);
                    
                    $result = mysqli_query($common->db_connect, $query);
                    
                    while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['franchise']; ?>"><?php echo $row['franchise']; ?></option>
                <?php } ?>
            </select>
            <label for="franchise">Please Select Franchise</label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <div class="form-material">
            <label for="manager_first_name">Manager</label>
            <input type="text" name="manager_first_name" class="form-control" placeholder="First Name" />
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-material">
            <input type="text" name="manager_last_name" class="form-control" placeholder="Last Name" />
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <div class="form-material">
            <label for="customer_first_name">Customer Specialist</label>
            <input type="text" name="customer_first_name" class="form-control" placeholder="First Name" />                                    
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-material">
            <input type="text" name="customer_last_name" class="form-control" placeholder="Last Name" />
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <div class="form-material">
            <label for="unix_id">UNIX_ID</label>
            <input type="text" name="unix_id" class="form-control" placeholder="multiple separated by a comma" />                                    
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-6">
        <div class="form-material">
            <label for="class_id">Class</label>
            <input type="text" name="class_id" class="form-control" placeholder="Class ID" />
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-material">
            <input class="form-control js-datepicker" type="text" name="class_date" data-date-format="yyyy-mm-dd" placeholder="Class Date(yyyy-mm-dd)">
        </div>
    </div>
</div>