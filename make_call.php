 
<?php
wp_enqueue_style('twilio-phone-css', TWILIO_PLUGIN_URL . 'css/intlTelInput.css');
		wp_enqueue_script('twilio-phone', TWILIO_PLUGIN_URL . 'js/intlTelInput.min.js', array('jquery'));
    	wp_enqueue_script('twilio-util', TWILIO_PLUGIN_URL . 'js/utils.js');
   		 wp_enqueue_script('twilio-script', TWILIO_PLUGIN_URL . 'js/twilio-script.js');
	wp_register_style('style.css', TWILIO_PLUGIN_URL . 'css/style.css');
	wp_enqueue_style('style.css');
	global $wpdb;
	$table_name=$wpdb->prefix.'twilio_detail';
	$result = $count=$wpdb->get_results("SELECT * FROM $table_name");
	if(empty($result)){
		echo 'Please insert your Twilio Details First.';
		exit;
	}
	else{
		
		require('twilio_main.php');
	}
	
?>
