<?php

	/*
	Plugin Name: Contact Demo
	Plugin URI: https://localhost/wordpress
	Description: Contact Demo
	Author: Dipti
	Version: 1.0
	Author URI: https://localhost/wordpress
	*/

	register_activation_hook( __FILE__, 'my_plugin_create_db' );
	function my_plugin_create_db() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'contact';

		$sql = "CREATE TABLE $table_name (
			Author_Id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			Author_Name varchar(50) NOT NULL,
			Mobile_No char(10) NOT NULL
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	register_deactivation_hook( __FILE__, 'remove_table' );
	function remove_table(){
	  global $wpdb;
	  $wpdb->query("DROP TABLE wp_contact");
	}

	add_action('admin_init','inc_func_pb');
	function inc_func_pb(){
	   	wp_register_script('jquery_min_js_file',plugin_dir_url(__FILE__).'Css&Js/jquery.min.js');
	   	wp_register_script('jquery_js_file',plugin_dir_url(__FILE__).'Css&Js/jquery-1.12.4.js');
	    wp_register_script('datatables',plugin_dir_url(__FILE__).'Css&JS/jquery.dataTables.min.js');
	    wp_register_style('datatables_css',plugin_dir_url(__FILE__) . 'Css&Js/jquery.dataTables.min.css');
	    wp_register_style('datatables_rowReorder_css',plugin_dir_url(__FILE__) . 'Css&Js/rowReorder.dataTables.min.css');
	    wp_register_script('datatables_rowReorder_js',plugin_dir_url(__FILE__).'Css&Js/dataTables.rowReorder.min.js');
	}

	add_action('widgets_init', create_function('', 'return register_widget("My_Custom_Widget");'));

	function register_custom_menu_page() {
    	add_menu_page('Contacts', 'Contacts', 'add_users', 'contacts', 'List_Contact'); 
    	/*add_submenu_page('contacts', 'Contact List','Contact List', 'add_users', 'contacts', 'List_Contact');
    	add_submenu_page( 'contacts', 'Add Contact', 'Add Contact', 'add_users','add_contact', 'add_contact');*/
	}


	add_action('admin_menu', 'register_custom_menu_page');

	function List_Contact()
	{
		wp_enqueue_script('jquery_min_js_file');
		wp_enqueue_script('jquery_js_file');
		wp_enqueue_script('datatables');
		wp_enqueue_style('datatables_css');
		wp_enqueue_script('datatables_rowReorder_js');
		wp_enqueue_style('datatables_rowReorder_css');
		global $wpdb;
		$sql="select * From wp_contact where is_deleted=0";
		$result=$wpdb->get_results($sql);
		?>
		<table id="Author_List">
			<thead>
				<td>Sequence No</td>
				<td>Author Name</td>
				<td>Mobile Number</td>
			</thead>
			<?php 
			$i=1;
			foreach ($result as $row) {
				echo "<tr><td>$i</td><td>".$row->Author_Name."</td><td>".$row->Mobile_No."</td></tr>";
				$i++;
			}
			?>
		</table>

		<div>
			<!-- <button value="Delete Record" id="delete">Delete Record</button>
			<button value="Update Record" id="Update_getData">Update Record</button> -->
		</div>     
		<script type="text/javascript">
           jQuery(document).ready(function(){
                // Init dataTable
                var table=jQuery('#Author_List').dataTable({
                    "bFilter": false,
					"bLengthChange": false,
					"bSort": true,
					"bProcessing": false,
					"bServerSide": false,
					"info": false,
					"scrollY" : '200px',
					"serverSide": true,
					"ordering": true,
					"searching": true,
					"rowReorder": {
			             "update": true,
			             //"dataSrc": '.ord-id'
			         }
                });
                jQuery('#Author_List tbody').on( 'click', 'tr', function () {
			        if ( $(this).hasClass('selected') ) {
			            $(this).removeClass('selected');
			        }
			        else {
			            table.$('tr.selected').removeClass('selected');
			            $(this).addClass('selected');
			        }
			    });
			    jQuery('#delete').click( function () {
		        var dltstr=table.row('.selected').data();
		        var dltjsonStr=JSON.stringify(dltstr);
	           	//console.log(dltjsonStr);
	           	jQuery.ajax({
		        	type: "POST",
		        	url: "<?php echo admin_url('admin-ajax.php') ?>",         
		           	data: "action=delete_datal&dlt_rec="+dltjsonStr,
		        	success: function(response){
		        		console.log(response);
		        		table.ajax.reload();               
		        	}
				});
		    } );
           });
        </script> 
<?php 
	}

	function add_contact()
	{
?>
		<div>
			<form action="" method="post">
				<input type="text" name="Author_Name" placeholder="Author Name" id="Author_Name"/><br>
				<input type="text" name="Mobile_No" placeholder="Mobile Number" id="Mobile_No" /><br>
				<input type="submit" name="submit"/>
			</form>
		</div>
<?php
		global $wpdb;
		if(isset($_REQUEST['submit']))
		{
			$Author_Name=$_POST['Author_Name'];
			$Mobile_No=$_POST['Mobile_No'];
			$ins_data = "INSERT INTO wp_contact (Author_Name,Mobile_No) VALUES ('$Author_Name',$Mobile_No)";
      		$all_data = $wpdb->query($ins_data);
		}		
	}
	add_shortcode('Add_Contact', 'add_contact');
?>

