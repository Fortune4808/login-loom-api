<?php
class allClass{
function _get_sequence_count($conn, $item){
	$count=mysqli_fetch_array(mysqli_query($conn,"SELECT counter_value FROM counter_tab WHERE counter_id = '$item' FOR UPDATE"));
	$num=$count[0]+1;
	mysqli_query($conn,"UPDATE `counter_tab` SET `counter_value` = '$num' WHERE counter_id = '$item'")or die (mysqli_error($conn));
	if ($num<10){$no='00'.$num;}elseif($num>=10 && $num<100){$no='0'.$num;}else{$no=$num;}
	return '[{"num":"'.$num.'","no":"'.$no.'"}]';
}


function _validate_accesskey($conn,$access_key){
	$query=mysqli_query($conn,"SELECT a.*, b.* FROM staff_tab a, setup_session_tab b WHERE b.access_key='$access_key' AND a.status_id=1;")or die (mysqli_error($conn));
	$count = mysqli_num_rows($query);
		if ($count>0){
			$fetch_query=mysqli_fetch_array($query);
			$staff_id=$fetch_query['staff_id'];
			$role_id=$fetch_query['role_id'];
			$check=1; 
		}else{
			$check=0;
		}
		return '[{"staff_id":"'.$staff_id.'","check":"'.$check.'","role_id":"'.$role_id.'"}]';
	}


	function _get_staff($conn, $staff_id){
		$query=mysqli_query($conn,"SELECT * FROM staff_tab WHERE staff_id = '$staff_id'");
		$fetch_query=mysqli_fetch_array($query);
		$staff_id=$fetch_query['staff_id'];
		$staff_surname=$fetch_query['staff_surname'];
		$staff_othername=$fetch_query['staff_othername'];
		$staff_email=$fetch_query['staff_email'];
		$staff_phoneno=$fetch_query['staff_phoneno'];
		$role_id=$fetch_query['role_id'];
		$status_id=$fetch_query['status_id'];
		$passport=$fetch_query['passport'];
		$password=$fetch_query['password'];
		$otp=$fetch_query['otp'];
		$created_at=$fetch_query['created_at'];
		$last_login_date=$fetch_query['last_login_date'];
		
		 return '[{"staff_id":"'.$staff_id.'","staff_surname":"'.$staff_surname.'","staff_othername":"'.$staff_othername.'","staff_email":"'.$staff_email.'","staff_phoneno":"'.$staff_phoneno.'","role_id":"'.$role_id.'","status_id":"'.$status_id.'","passport":"'.$passport.'","password":"'.$password.'","otp":"'.$otp.'","created_at":"'.$created_at.'","last_login_date":"'.$last_login_date.'"}]';
	}


	
function _get_role($conn, $role_id){
    $query=mysqli_query($conn, "SELECT * FROM setup_role_tab WHERE role_id='$role_id'");    
    $fetch=mysqli_fetch_array($query);
    $role_name=$fetch['role_name'];

    return '[{"role_name":"'.$role_name.'"}]';
}

function _get_status($conn, $status_id){
    $query=mysqli_query($conn, "SELECT * FROM setup_status_tab WHERE status_id='$status_id'");    
    $fetch=mysqli_fetch_array($query);
    $status_name=$fetch['status_name'];

    return '[{"status_name":"'.$status_name.'"}]';
}





}$callclass=new allClass();
?>