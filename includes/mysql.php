<?php
 class Twillo_calling_install {

    public function Twillo_calling_install($file){
        register_activation_hook($file, array($this, "activate"));
        register_deactivation_hook($file, array($this, "deactivate"));
    

    }

   public function activate(){

   	global $wpdb;
	
	$table_name=$wpdb->prefix.'twilio_detail';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  account_sid varchar(300) NULL,
		  auth_token varchar(300) NULL,
		  app_sid varchar(300) NULL,
		  twilio_number varchar(255) NULL,
		  PRIMARY KEY id (id)
		) ";

		require_once(ABSPATH . "wp-admin/includes/upgrade.php");
		dbDelta($sql);

     



	}
    public function deactivate(){ echo "in deactivate"; }
       
}
     