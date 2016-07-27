<?php
class Base
{
	public function save_twilio_details(){		
		global $wpdb;
		$table_name=$wpdb->prefix.'twilio_detail';
		
		if(isset($_POST['detail']['submit'])){



			if($_POST['detail']['account_sid']!="" && $_POST['detail']['auth_token']!="")
			{ 
				if($_POST['detail']['twilio_number']!="")
				{
					$return_status = $this->verify_phone_details($_POST['detail']);
					if($return_status['status'] ==1)
					{
						$wpdb->replace($table_name, array(
							"id"			=>	1,
							"account_sid"	=> $_POST['detail']['account_sid'],
							"auth_token"	=> $_POST['detail']['auth_token'],
							"app_sid"		=> $_POST['detail']['app_sid'],
							"twilio_number"	=> $_POST['detail']['twilio_number']
						));

					}
					if($return_status['status'] ==0)
					{
						 echo "<div class='error_message'>".$return_status['message']."</div>";
					}
					else
					{
						 echo "<div class='success_message'>Credentials updated successfully</div>";
					}

				}
				else
				{
					$wpdb->replace($table_name, array(
							"id"			=>	1,
							"account_sid"	=> $_POST['detail']['account_sid'],
							"auth_token"	=> $_POST['detail']['auth_token'],
							"app_sid"		=> $_POST['detail']['app_sid'],
							"twilio_number"	=> $_POST['detail']['twilio_number']
						));
				}
				
			}
			else
			{
				echo "<div class='error_message'>Please provide Account SID and Auth Token</div>";
			}

		}
		
	}

	public function get_warning_logs($sid,$auth_token)
	{
		include( TWILIO_PLUGIN_PATH. 'twilio-php/Services/Twilio.php');
		$client = new Monitor_Services_Twilio($sid, $auth_token);

		return $client;

	}

	public function get_call_logs($sid,$auth_token)
	{
		//include( TWILIO_PLUGIN_PATH. 'twilio-php/Services/Twilio.php');

		 $version = '2010-04-01';

 	
    	$client = new Services_Twilio($sid, $auth_token, $version);
		//$client = new Monitor_Services_Twilio($sid, $auth_token);

		return $client;

	}
	private function verify_phone_details($data) 
	{
		include( TWILIO_PLUGIN_PATH. 'twilio-php/Services/Twilio.php');
		$sid = $data['account_sid'];
		$token = $data['auth_token'];
		$client = new Services_Twilio($sid, $token);

		$numbers = array();
		$verifiedNumbers = array();
		$i = 0;
		foreach ($client->account->incoming_phone_numbers as $number) 
		{
		    $numbers[$i] = $number->phone_number;
			$i++;
		}
		//get verified numbers
		$j = 0;
		foreach ($client->account->outgoing_caller_ids as $verifiedNumber)
		{
		  $verifiedNumbers[$j]=$verifiedNumber->phone_number;
		  $j++;
		}
		
		$numbers=array_merge($numbers,$verifiedNumbers);
		
		if(!empty($numbers))
		{
			if (in_array($data['twilio_number'],$numbers)) 
			{
    			return array('status'=>1);
			}
			else
			{
				return array('status'=>0,'message'=>'This number is not exist in your twilio account.');
			}
		}
		else
		{
			return array('status'=>0,'message'=>'You have not any verified number in twilio.');
		}
		 
	}
	private function randomString($length) {
	$str = "";
	$characters = array_merge(range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}
	public function api_request($data)
	{
		if(isset($data['action']) && $data['action']=="create_twilio_api")
		{
			if($data['account_sid']!="" && $data['auth_token']!="")
			{

					    try 
					    {
							$directory = TWILIO_PLUGIN_PATH."twimil_xml";
							if (!file_exists($directory))
							{
					    		mkdir($directory, 0755,true);

							}
							$newfile = $this->randomString(8).".php";
							 
							$myFile = $directory."/".$newfile;
							$message = '<?php header("Content-type: text/xml");$callerId = $_REQUEST["CallerId"];if (isset($_REQUEST["PhoneNumber"])) {$number = htmlspecialchars($_REQUEST["PhoneNumber"]);}?><Response><Dial callerId="<?php echo $callerId; ?>"><Number><?php echo $number; ?></Number></Dial></Response>';
							/*$fh = file_exists($myFile) ? fopen($myFile, 'a') : fopen($myFile, 'w');
							fwrite($fh, $message."\n");*/

							$voiceURL = TWILIO_PLUGIN_URL."twimil_xml/".$newfile;
							include( TWILIO_PLUGIN_PATH. 'twilio-php/Services/Twilio.php');
							$sid = $data['account_sid'];
					//echo "   ";
							$token = $data['auth_token'];
							$client = new Services_Twilio($sid, $token);
   					  		$app = $client->account->applications->create($this->randomString(8)."_plugin", array(
					         "VoiceUrl" => $voiceURL,
					         "VoiceMethod" => "POST"
					      ));
							$fh = file_exists($myFile) ? fopen($myFile, 'a') : fopen($myFile, 'w');
							fwrite($fh, $message."\n");

						// Loop over the list of caller_ids and echo a property for each one
						$numbers = array();
						$i = 0;
						foreach ($client->account->incoming_phone_numbers as $number) 
						{
					    	$numbers[$i] = $number->phone_number;
							$i++;
						}
   					 	if(isset($app->sid))
   					 	{
   					 		global $wpdb;
							$table_name=$wpdb->prefix.'twilio_detail';
							$wpdb->replace($table_name, array(
								"id"			=>	1,
								"account_sid"	=> $data['account_sid'],
								"auth_token"	=> $data['auth_token'],
								"app_sid"		=> $app->sid
							));
   					 		echo json_encode(array('status'=>1,'message'=>'App ID created successfully','app_id'=>$app->sid));exit;
					  }
					  else
					  {
					  		echo json_encode(array('status'=>0,'message'=>'Some thing going wrong please try again later.'));exit;
					  }                        
                    	} 
						catch (Exception $e)
						{

                    		$message = $e->getMessage();
                    		if($message == "Authenticate")
                    		{
								
                    			$message="Please enter correct SID and Token";
                    		}
                    		echo json_encode(array('status'=>0,'message'=>$message));exit;
                        
                    	}
			}
			else
			{
				echo json_encode(array('status'=>0,'message'=>'Please enter Auth token and Account SID'));exit;
			}
		}
		 
         
	}
	public function get_twilio_details(){
		 
		global $wpdb;
		$table_name=$wpdb->prefix.'twilio_detail';
		$result=$wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	}
	
}

?>
