<?php 
session_start();
error_reporting(0);

function Single_userFetch($table,$con){
    $data = array(); 
    $query = "SELECT * FROM $table order by id desc";
    $result = mysqli_query($con, $query);
   if($result) {
    while($row = mysqli_fetch_assoc($result)) {
     $data[] = $row;
 }
  mysqli_free_result($result);
  } else {
 echo "Error fetching data: " . mysqli_error($con);
 }
  return $data;
 }
//  new fetch
function AlreadyRegisterCheck($table, $whereClause, $con) {
    $query = "SELECT * FROM $table WHERE $whereClause";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        $single_user = mysqli_fetch_assoc($result);
        return array('exists' => true, 'user' => $single_user);
    } else {
         "Error fetching data: " . mysqli_error($con);
        return array('exists' => false, 'user' => null);
    }
}
// 
function User_details($table, $user, $con) {
    $data = array(); 
    $query = "SELECT * FROM $table WHERE $user ";
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result);
    } else {
        echo "Error fetching data: " . mysqli_error($con);
    }
    return $data;
}
// 
function CallSelect($con, $value) {
    $options = ''; 
    $query = "SELECT DISTINCT $value FROM admin_details";
    $result = mysqli_query($con, $query);
    if ($result) {
        
        while ($row = mysqli_fetch_array($result)) {
            $options .= "<option value='" . $row[$value] . "'>" . $row[$value] . "</option>";
        }
    } else {
        $options .= "<option value=''>Error retrieving data</option>";
    }
    return $options; 
}
// 
function CallSelect_drop($con, $value,$find) {
    $options = ''; 
    $query = "SELECT DISTINCT $value FROM admin_details where $find";
    $result = mysqli_query($con, $query);
    if ($result) {
        $options .= "<option value=''>Select any one option</option>";
        while ($row = mysqli_fetch_array($result)) {
            $options .= "<option value='" . $row[$value] . "'>" . $row[$value] . "</option>";
        }
    } else {
        $options .= "<option value=''>Error retrieving data</option>";
    }
    return $options; 
}

// 

function MK_CallSelect($con, $value, $table) {
    $options = '';
    $query = "SELECT DISTINCT `$value` FROM `$table`";
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . htmlspecialchars($row[$value], ENT_QUOTES) . "'>" . htmlspecialchars($row[$value], ENT_QUOTES) . "</option>";
        }
    } else {
        $options .= "<option value=''>Error retrieving data</option>";
    }
    return $options;
}

// 
function MK_CallSelect_drop($con, $value,$find,$table) {
    $options = ''; 
    $query = "SELECT DISTINCT $value FROM $table where $find";
    $result = mysqli_query($con, $query);
    if ($result) {
        $options .= "<option value=''>Select any one option</option>";
        while ($row = mysqli_fetch_array($result)) {
            $options .= "<option value='" . $row[$value] . "'>" . $row[$value] . "</option>";
        }
    } else {
        $options .= "<option value=''>Error retrieving data</option>";
    }
    return $options; 
}

?>