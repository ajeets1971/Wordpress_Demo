<?php
/*
* Plugin Name: WordPress Plugin ShortCode
* Description: Create your WordPress Plugin shortcode.
* Version: 1.0
* Author: Dipti
*/
	register_activation_hook( __FILE__, 'my_plugin_db' );
	function my_plugin_db() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'message';

		$sql = "CREATE TABLE $table_name (
			User_Id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			Name varchar(50) NOT NULL,
			Message varchar(100) NOT NULL
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	register_deactivation_hook( __FILE__, 'remove_tab' );
	function remove_tab(){
	  global $wpdb;
	  $wpdb->query("DROP TABLE wp_message");
	}
function form_creation(){
?>
	<form method="post" action="">
	First name: <input type="text" name="firstname" placeholder="Name"><br>
	Message: <textarea name="message" placeholder="Enter Mesaage"> </textarea>
	<input type="submit" name="submit">
	</form>
<?php
	global $wpdb;
	if(isset($_POST['submit']))
	{
		$Name=$_POST['firstname'];
		$Message=$_POST['message'];
		$ins_data = "INSERT INTO wp_message(Name,Message) VALUES('$Name','$Message')";
  		$all_data = $wpdb->query($ins_data);
	}
}
add_shortcode('form_message', 'form_creation');
?>