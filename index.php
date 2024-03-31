<?php include 'connection/connection.php'; ?>
<?php include 'connection/function.php'; ?>
<?php session_start();?>

<?php

$action=$_POST['action'];

switch ($action) {

    case 'login-api':

        $email=trim($_POST['email']);
        $password=trim($_POST['password']);

        if (($email=="") || ($password=="")) {
            $response['response']=100; 
            $response['success']=false;
            $response['message1']="LOGIN ERROR!";
            $response['message2']="Fill all fields to continue.";
        }else{

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){ /// start if 1
                
                $login_email_query=mysqli_query($conn, "SELECT * FROM staff_tab WHERE `staff_email`='$email'") or die (mysqli_error($conn));
                $login_email_count=mysqli_num_rows($login_email_query);

                if ($login_email_count>0){
                    $fetch=mysqli_fetch_array($login_email_query);
                    $hashedpassword=$fetch['password'];
                    $status_id=$fetch['status_id']; 

                }if(password_verify($password, $hashedpassword)) {

                    if($status_id==1){ /// check if the user is active

                        $s_staff_id=$fetch['staff_id'];
                        $_SESSION['staff_id']=$s_staff_id; // session staff_id out //
                        $s_staff_id=$_SESSION['staff_id'];  // session staff_id in //
                        $_SESSION['access_key'] = $access_key; // session access key//

                        $access_key = bin2hex(random_bytes(16));
                        $hashaccesskey = password_hash($access_key, PASSWORD_BCRYPT);
        
                        $update_accesskey = "UPDATE setup_session_tab SET access_key='$hashaccesskey', system_used='$sysname', ip_address_used='$ip_address', last_access_date=NOW() WHERE staff_id='$s_staff_id'";
                        mysqli_query($conn, $update_accesskey);// update access_key
        
                        $update_last_login_date = "UPDATE staff_tab SET last_login_date=NOW() WHERE staff_id='$s_staff_id'";
                        mysqli_query($conn, $update_last_login_date);// update last_login_date

                        $response['response']=101; 
                        $response['success']=true;
                        $response['message1']="LOGIN SUCCESSFULLY!";
                        $response['message2']="User Successfully Login";
                        $response['staff_id'] = $s_staff_id;
                        $response['access_key'] = $access_key;

                        }elseif ($status_id==2){
                            $response['response']=102; 
                            $response['success']=false;
                            $response['message1']="LOGIN ERROR!";
                            $response['message2']="Account Suspended";
                        }else{
                            $response['response']=103; 
                            $response['success']=false;
                            $response['message1']="LOGIN ERROR!";
                            $response['message2']="Account still on Pending";
                        }

                        }else{

                            $response['response']=104; 
                            $response['success']=false;
                            $response['message1']="LOGIN ERROR!";
                            $response['message2']="Invalid Login Parameters";
                    
                    }

                    }else{
                        $response['response']=105; 
                        $response['success']=false;
                        $response['message1']="LOGIN ERROR!";
                        $response['message2']="Invalid Email Format";
                    }
        
            }
    break;


    
	case 'proceed-reset-password-api':

		$email=trim($_POST['email']);
		if(($email=='')){ ///start if 0
			$response['response']=106; 
			$response['result']=False;
			$response['message']="ERROR! Some Fields are empty!"; 

		}else{//else if 0
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){ /// start if 1
				$query=mysqli_query($conn,"SELECT * FROM staff_tab WHERE staff_email='$email'") or die (mysqli_error($conn));
				$count_user=mysqli_num_rows($query);

					if ($count_user>0){ /// start if 3
						$result = mysqli_fetch_array($query);
                        $staff_id = $result['staff_id']; 
                        $staff_surname = $result['staff_surname']; 
                        $staff_othername = $result['staff_othername']; 
                        $staff_email = $result['staff_email']; 
                        $status_id = $result['status_id']; 
                        $staff_fullname = $staff_surname . ' ' . $staff_othername;
						
							if($status_id==1){ /// start if 2 (check if the user is active)
								$otp = rand(111111,999999);
								////////////////update user OTP///////////////
								mysqli_query($conn,"UPDATE staff_tab SET otp='$otp' WHERE staff_id ='$staff_id'") or die("cannot update staff_tab");
								$response['response']=107; 
								$response['result']=true;
								$response['message1']="SUCCESSFUL!"; 
								$response['message2']="An OTP has been sent to your Email!"; 
								$response['staff_id']=$staff_id; 
                                $response['staff_fullname']=$staff_fullname;
                                $response['staff_email']=$staff_email;

							}else if($status_id==2){/// else if 2
								$response['response']=108; 
								$response['result']=false;
								$response['message1']="ERROR!"; 
								$response['message2']="User Suspended!"; 

							}else{
                                $response['response']=109; 
								$response['result']=false;
								$response['message1']="ERROR!"; 
								$response['message2']="User is still Under Review!"; 
                            } /// end if 2	

					}else{/// else if 2	
						$response['response']=110; 
						$response['result']=false;
						$response['message1']="ERROR!"; 
						$response['message2']="Email Address not Found!"; 	
					}/// end if 2
			}else{
                $response['response']=111; 
                $response['result']=false;
                $response['message1']="ERROR!"; 
                $response['message2']="Invalid Email Address!"; 
            }
		}/// end if 1
	break;


    case 'finish-reset-password-api':

        $staff_id=trim($_POST['staff_id']);
		$otp=trim($_POST['otp']); 
		$password=trim($_POST['password']);

        $hashedpassword = password_hash($password, PASSWORD_BCRYPT);

			if(($otp=='') ||($password=='')){ ///start if 0
			$response['response']=112; 
			$response['result']=False;
			$response['message']="ERROR! Some Fields are empty!"; 
		}else{
			$otpcheck=mysqli_query($conn,"SELECT * FROM staff_tab WHERE  staff_id='$staff_id' AND otp='$otp'");
			$userotp=mysqli_num_rows($otpcheck);
			if ($userotp>0){ ///start if 1
				/// update user on staff_tab
				mysqli_query($conn,"UPDATE `staff_tab` SET `password`='$hashedpassword' WHERE `staff_id`='$staff_id'")or die (mysqli_error($conn));
				$response['response']=113; 
				$response['result']=true;
				$response['message1']="SUCCESSFUL!"; 
				$response['message2']="Password Reset Successful!"; 
				$response['staff_id']=$staff_id; 
		}else{
			$response['response']=114; 
			$response['result']=false;
			$response['message1']="ERROR!!"; 
			$response['message2']="Invalid OTP!"; 				
			}///end if 1
		}///end if 


    break;





   
    









































}
echo json_encode($response);     
?>




