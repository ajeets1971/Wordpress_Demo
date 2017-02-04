<?php
/*
* Plugin Name: Search Plugin
* Description: Read CSV File and Search
* Version: 1.0
* Author: Dipti
*/

register_activation_hook( __FILE__, 'my_plugin_create_db' );
function my_plugin_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'Location';

	$sql = "CREATE TABLE $table_name (
		franchise_id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		franchise_name varchar(50) NOT NULL,
		phone char(20) NOT NULL,
		website varchar(100) NOT NULL,
		email varchar(50) NOT NULL,
		county_codes varchar(100) NOT NULL
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'Zipcode';

	$sql = "CREATE TABLE $table_name (
		ZIP varchar(50) NOT NULL,
		County_Code varchar(50) NOT NULL,
		City varchar(50) NOT NULL,
		ST varchar(100) NOT NULL,
		County varchar(50) NOT NULL
	) $charset_collate;";

	dbDelta( $sql );
}
register_deactivation_hook( __FILE__, 'remove_table' );
function remove_table(){
  global $wpdb;
  $wpdb->query("DROP TABLE wp_Location");
  $wpdb->query("DROP TABLE wp_Zipcode");
}


add_action('admin_init','inc_func_pb');
function inc_func_pb(){
	wp_register_script('jquery_min_js_file',plugin_dir_url(__FILE__).'Css&Js/jquery.min.js');
   	wp_register_script('jquery_js_file',plugin_dir_url(__FILE__).'Css&Js/jquery-1.12.4.js');
}

add_action('admin_menu', 'register_custom_menu_page');

function register_custom_menu_page() {
	add_menu_page('Search', 'Search', 'search_code', 'search', 'import_csv'); 
	/*add_submenu_page('contacts', 'Contact List','Contact List', 'add_users', 'contacts', 'List_Contact');
	add_submenu_page( 'contacts', 'Add Contact', 'Add Contact', 'add_users','add_contact', 'add_contact');*/
}

function import_csv()
{
	global $wpdb;
	?>
	<form action="" method="post" enctype="multipart/form-data">
		Location CSV File : <input type="file" name="Location_File" id="Location_File" /><br><br>
		Zipcode CSV File  : <input type="file" name="Zipcode_File" id="Zipcode_File"/><br><br>
		<input type="submit" name="submit_file" value="Import">
	</form><?php 
	if(isset($_POST['submit_file']))
	{
		$loc=$_FILES['Location_File']['name'];
		$zip=$_FILES['Zipcode_File']['name'];
		if ($loc)
		{
			move_uploaded_file($_FILES["Location_File"]["tmp_name"], "/var/www/html/wordpress/wp-content/plugins/Search_Plugin/upload/" . $loc);
			chmod('/var/www/html/wordpress/wp-content/plugins/Search_Plugin/upload/' . $loc,0777);

		}
		if ($zip)
		{
			move_uploaded_file($_FILES["Zipcode_File"]["tmp_name"], "/var/www/html/wordpress/wp-content/plugins/Search_Plugin/upload/" . $zip);
			chmod('/var/www/html/wordpress/wp-content/plugins/Search_Plugin/upload/' . $zip,0777);
		}
		if($loc!="")
		{
			if (($handle = fopen(plugin_dir_url(__FILE__)."upload/".$loc, "r")) !== FALSE) 
			{
				$r=0;
				while (($data = fgetcsv($handle, "\n")) !== FALSE) 
		    	{ 
		    		if($r!=0)
		    		{
		    			$rset="";
		    			$query="select * from wp_Location where franchise_id=".$data[0];
						$rset=$wpdb->get_results($query);
						if(count($rset)!=0)
						{
							$upq="";
			    			if($rset[0]->franchise_name != $data[1])
			    			{
			    				$upq='update wp_Location set franchise_name="'.$data[1].'" where franchise_id='.$data[0];
			    			}
			    			if($rset[0]->phone != $data[2])
			    			{
			    				$upq='update wp_Location set phone="'.$data[2].'"where franchise_id='.$data[0];
			    			}
			    			if($rset[0]->website != $data[3])
			    			{
			    				$upq='update wp_Location set website="'.$data[3].'" where franchise_id='.$data[0];
			    			}
			    			if($rset[0]->email != $data[4])
			    			{
			    				$upq='update wp_Location set email="'.$data[4].'" where franchise_id='.$data[0];
			    			}
			    			if($rset[0]->county_codes != $data[5])
			    			{
			    				$upq='update wp_Location set county_codes="'.$data[5].'" where franchise_id='.$data[0];
			    			}
			    			$update = $wpdb->query($upq);
						}
						else
						{
					        $ins_data = 'INSERT INTO wp_Location VALUES ('.$data[0].',"'.$data[1].'","'.$data[2].'","'.$data[3].'","'.$data[4].'","'.$data[5].'")';
		      				$all_data = $wpdb->query($ins_data);
						}
		    		}
		    		$r++;
		    	}
		    }
		}				
		if($zip!="")
		{
			if (($handle = fopen(plugin_dir_url(__FILE__)."upload/".$zip, "r")) !== FALSE) 
			{
				$r=0;
			    while (($data = fgetcsv($handle, "\n")) !== FALSE) { 	
			    	if($r!=0)
			    	{
				        $ins_data = "INSERT INTO wp_Zipcode VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
	      				$all_data = $wpdb->query($ins_data);
			    	}
			    	$r++;		        
			    }
			    fclose($handle);
			}		
		}
		
	}
}

add_action("Searched_Zipcode_plugin","search_Zipcode");
add_action('wp_ajax_get_result', 'get_result');
add_action('wp_ajax_nopriv_get_result', 'get_result');

function get_result()
{
	global $wpdb;
	$zip=$_POST['zip'];
	$sql="select * From wp_Zipcode where ZIP=".$zip;
	$result=$wpdb->get_results($sql);
	if(count($result)>0)
	{
		$sql="select * From wp_Location where county_codes LIKE '%".$result[0]->County_Code."%'";
		$result=$wpdb->get_results($sql);
	    $num=count($result);
	    for($i=0;$i<$num;$i++)
	    {
	    	$tbhtml .= "<tr><td>".$result[$i]->franchise_id
	."</td><td>".$result[$i]->franchise_name."</td><td>".$result[$i]->phone."</td><td>".$result[$i]->website."</td><td>".$result[$i]->email."</td><td>".$result[$i]->county_codes."</td></tr>";
	    }
	}
	
    echo $tbhtml;
}

add_action('wp_head','hook_header');

function hook_header()
{
	?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	  	<style type="text/css">
	  	#recordModal table { display: block !important; overflow: scroll !important; }
	  	#Searched_Zip {
	  		width: 300px;
	  		display: inline;
	  		margin-left: 250px;
	  		margin-right: 50px;
	  		margin-top: 22px;
	  	}
	  	#Searchplugin{
	  		background-color: lightgrey;
	  		height: 80px;
	  	}
	  	#submitzip{
	  		margin-top: 22px;
	  	}
	  	</style>

	  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		jQuery("document").ready(function(){
			jQuery(".site-content-contain").prepend('<div id="Searchplugin"><input type="text" name="Searched_Zip" placeholder="Enter Zipcode" id="Searched_Zip"/><button id="submitzip">Submit</button></div>');
			jQuery("#submitzip").click(function(){
			    var zip = jQuery("#Searched_Zip").val();
		        jQuery.ajax({
		              url: "<?php echo admin_url('admin-ajax.php') ?>",
		              type: 'POST',
		              data: {'action': 'get_result','zip':zip},
		              error: function (ds, dd, ff) {
		                 console.log(ff);
		              }
		          }).done(function (data) {
		          	var tbhtml="<thead><td>Franchise Id</td><td>Franchise_Name</td><td>Phone</td><td>Website</td><td>Email</td><td>Country Codes</td></thead><tbody>";
		          	console.log(data);
		            if(data == 0)
		            {
		            	jQuery('.modal_body').html('No Record Found');
		            }
		            else
		            {
		            	tbhtml += data;
		            	jQuery('.modal_body table').html(tbhtml+'</tbody>');       	
		            } 
		            jQuery('#recordModal').modal('show');
		        });
				jQuery(document).keyup(function(ev){
				    if(ev.keyCode == 27)
				        jQuery("#recordModal").trigger("click");
				});
			});
		});
	</script>
	<div class="modal fade" id="recordModal" role="dialog">
    	<div class="modal-dialog">
	    	<div class="modal-content">
		        <div class="modal-header">
<!-- 		          	<button type="button" class="close" data-dismiss="modal">&times;</button> -->
		          	<h4 class="modal_title">Location Data</h4>
		        </div>
		        <div class="modal-body modal_body" id="modal_table">
			        <table border ="1">     
		            </table>
		        </div>
		        <div class="modal-footer">
		        </div>
	    	</div>
		</div>
	</div>
	<?php
}

?>





