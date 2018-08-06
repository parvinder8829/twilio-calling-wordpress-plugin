<?php
		require(TWILIO_PLUGIN_PATH.'twilio-php/Services/Twilio/Capability.php');

		// put your Twilio API credentials here
		$accountSid = $result['0']->account_sid;
		$authToken  = $result['0']->auth_token;
		 
		// put your Twilio Application Sid here
		$appSid     = $result['0']->app_sid;
		$twilio_number     = $result['0']->twilio_number;

		$capability = new Services_Twilio_Capability($accountSid, $authToken);
		$capability->allowClientOutgoing($appSid);
		$capability->allowClientIncoming('jenny');
		$token = $capability->generateToken();
?>
		 
		<!DOCTYPE html>
		<html>
		  <head>
			<title></title>
			    <script type="text/javascript"
      src="//media.twiliocdn.com/sdk/js/client/v1.3/twilio.min.js"></script>
			<script type="text/javascript"
			  src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js">
			</script>
			<!--link href="http://static0.twilio.com/bundles/quickstart/client.css" type="text/css" rel="stylesheet" /-->
			<script type="text/javascript">
		 
			  Twilio.Device.setup("<?php echo $token; ?>");
		 
			  Twilio.Device.ready(function (device) {
				$("#log").text("Ready");
			  });
		 
			  Twilio.Device.error(function (error) {
				$("#log").text("Error: " + error.message);
			  });
		 
			  Twilio.Device.connect(function (conn) {
				$("#log").text("Successfully established call");
			  });
		 
			  Twilio.Device.disconnect(function (conn) {
 
			  	console.log(conn);
				$("#log").text("Call ended");
			  });
		 
			  Twilio.Device.incoming(function (conn) {

			  					$("#log").text("Incoming connection from " + conn.parameters.From);
				// accept the incoming connection and start two-way audio

				conn.accept();
			  });
		 
			  function call() {

			  	if($("#number").val()!="")
			  	{
				// get the phone number to connect the call to
					params = {"PhoneNumber": $("#number").val(),"CallerId":<?php echo $twilio_number?>};
					Twilio.Device.connect(params);
				}
				else
				{
					alert("Please enter number for call");
					return false;
				}
			  }
		 
			  function hangup() {
				Twilio.Device.disconnectAll();
			  }
			</script>
		  </head>
		  <body>
          
          
<div class="container">
    <div class="main">
    <div class="logo"><img src="<?php echo TWILIO_PLUGIN_URL.'images/logo.png'?>"  /></div>
    
    <div class="buttons">
    <div class="btn">
        <button class="call" onclick="call();"><img src="<?php echo TWILIO_PLUGIN_URL.'images/call_icon.png'?>" alt="" /> Call</button>
    </div>
    
    <div class="btn_2">
        <button class="hangup" onclick="hangup();"><img src="<?php echo TWILIO_PLUGIN_URL.'images/hang_up.png'?>" alt="" /> Hangup</button>
    </div>
    <div class="clear"></div>
    
    
    </div>
    <div class="text_box">
    	<input type="text" class="twilio_call_number" id="number"/>

    	 
    <div class="btn_3">
        <div id="log">Loading pigeons...</div><!--<button> READY</button>-->
    </div>
    
    <!--<div class="ready_btn"> <a class="Ready" href="#">READY</a></div>-->
    
    </div>
</div>

<!--
			<button class="call" onclick="call();">
			  Call
			</button>
		 
			<button class="hangup" onclick="hangup();">
			  Hangup
			</button>
		 
			<input type="text" id="number" name="number"
			  placeholder="Enter a phone number to call"/>
		 
			<div id="log">Loading pigeons...</div>
			
-->
		  </body>
		</html>