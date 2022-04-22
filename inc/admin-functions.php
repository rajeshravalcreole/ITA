<?php
/**
 *  Custom functions file for the admin user.
 *  @author investorestrust.
 *
 */


/**
 * Custom sub manu for the user import data of CSV
 */
add_action('admin_menu', 'investorstrust_users_custom_submenu_page');
function investorstrust_users_custom_submenu_page() {
   add_submenu_page('users.php', __('Insert form csv','investorstrust'), __('Insert form csv','investorstrust'), 'manage_options', 'user-csv-import-insert', 'investorstrust_csv_insert_callback' );
   add_submenu_page('users.php', __('Delete using csv','investorstrust'), __('Delete using csv','investorstrust'), 'manage_options', 'user-csv-import-delete', 'investorstrust_csv_delete_callback' );
}

function investorstrust_csv_insert_callback() {
   echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
       echo '<h2>'.__('Import Users from CSV File','investorstrust').'</h2>'; ?> 
   </div>

   <!-- 
		Request is sent with f

		orm data to page itself.
    -->
   <form method="post" enctype="multipart/form-data" action=<?php echo $_SERVER['PHP_SELF'].'?page=user-csv-import-insert';?>>
	   <div id="welcome-panel" class="welcome-panel">
	   		<h2 class="about-description" ><?php _e('User Import File','investorstrust' )?></h2> <input class="" id="import_csv_file" name="import_csv_file" type="file" required="required" />	
		   <div>
		   		<a href="<?php echo get_template_directory_uri().'/media/demo.csv';?>" target="_blank"> <?php _e('See the Demo', 'investorstrust') ?></a>
		   </div>
	   </div>
	   <button type="submit" name="import_csv_file" id="import_csv_file" class="button button-primary" value="import_csv_file"> <?php echo __("Click To Import", "investorstrust") ?> </button>
   </form>
   <?php
   		$langs = icl_get_languages();
	    $lang = array();
	    foreach ($langs as $key => $value) {
	   		$lang[] = $key;
		}
	
	if(isset($_FILES['import_csv_file'])){
		$filename = $_FILES["import_csv_file"]["tmp_name"] ;

		// The nested array to hold all the arrays
		$the_big_array = []; 

		// Open the file for reading
		if (($h = fopen("{$filename}", "r")) !== FALSE) 
		{
		  // Each line in the file is converted into an individual array that we call $data
		  // The items of the array are comma separated
		  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
		  {
		    // Each individual array is being pushed into the nested array
		    $the_big_array[] = $data;		
		  }

		  // Close the file
		  fclose($h);
		}

		$csv_header = array_shift($the_big_array);
		/*
		*   TO GENERATE THE HTML OUTPUT AFTER SUBMITTING THE FORM
		*/
		$response = '<div class="update-nag"><h2>'.__("Below Emails are repeated" , "investorstrust").'<h2>';
		$counter = $insert_user_count = $error_count=0;
		foreach ($the_big_array as $key => $value) {
			$name = $email_address = $introducer_code = "";
			$introducer_code_flag = 0; // The flag variable for the checking errors
			$name = trim($value[0]);
			$email_address = trim($value[1]);
			$introducer_code = trim($value[2]);	
			$language_code = trim(strtolower($value[3]));
			if (in_array($language_code, $lang))
			{
				$language_code = $language_code; 
			}else{
				$language_code = 'en';
			}
			if( strlen($introducer_code) >= 6 && strlen($introducer_code) <= 8){
				$args = array('meta_query' => array(				 
										        array(
										            'key' => 'introducer_code',
										            'value' => $introducer_code ,
										            'compare' => '='
										        )
									    ) 
						);
				$user_query = new WP_User_Query( $args );	
										if(! $user_query->get_results()){
											$introducer_code_flag = 1; // if the flag is 1 then user introducer code is valid and ready to insert
										}
			}							
			if(is_email( $email_address ) && $introducer_code_flag === 1 ){ //VALIDATE THE EMAIL FOR THE USER					
				if(email_exists( $email_address )){ //CHECK THE IF USER EMAIL IS EXSISIT IN THE DATABASE. 
					$response .= '<p>'.$email_address.'</p>';
					if(!$introducer_code){
						$error_count++;
				    }
				} else {
					/**
					 *  Code to insert the user in the wordpress data
					 */
					$user_details = array(
						'user_email' => $email_address,
						'user_login' => $email_address,
						'role' 		 => 'agent'	
					);
					if($introducer_code){
						$last_inserted_user_id = wp_insert_user( $user_details );
						// NOW CHECKING THE USER IS INSERTED IS OR NOT IN THE DATABASE
						if(! is_wp_error( $last_inserted_user_id )){										
							update_user_meta( $last_inserted_user_id, 'introducer_code', $introducer_code );
							update_user_meta( $last_inserted_user_id, 'full_name', $name );
							update_user_meta( $last_inserted_user_id, 'current_language', $language_code );
							update_user_meta( $last_inserted_user_id, 'notification_language', $language_code );
							update_user_meta( $last_inserted_user_id, 'user_activation_status', 1 );
							$insert_user_count++;
						} else {
							$error_count++;
						}
					} else {
						$error_count++;
					}
				}
			}
			$counter++;	 
		}
		$response .= '</div>';
		/**
		 *  @param $cuont
		 */
		$updates = '<div class="update-nag"><h4>'.__("Total", "investorstrust").' "'.$counter.'" '. __("Records Processed Inserted" , "investorstrust").' : ' .$insert_user_count.' <h4> </div>';
		if($insert_user_count) {
			echo $response;
		}
		echo $updates;
		if($error_count){
			echo '<div class="update-nag"><h4>'.__("Total Errors found in data", "investorstrust").' "'.$error_count.'" <h4> </div>';
		}

	}


}

/**
 *  FUNCTION TO REGISTER SUB PAGE OF USER DELETE 
 *
 *   @author investorestrust
 */

function investorstrust_csv_delete_callback() {
    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>'.__('Delete Users using CSV File','investorstrust').'</h2>';
    echo '</div>'; ?>
    <form method="post" enctype="multipart/form-data" action=<?php echo $_SERVER['PHP_SELF'].'?page=user-csv-import-delete';?>>
	   <div id="welcome-panel" class="welcome-panel">
	   		<h2 class="about-description" ><?php _e('User Delete File','investorstrust' )?></h2> <input class="" id="delete_csv_file" name="delete_csv_file" type="file" required="required" />	
		   <div>
		   		<a href="<?php echo get_template_directory_uri().'/media/demo.csv';?>" target="_blank" > <?php _e('See the Demo', 'investorstrust') ?></a>
		   </div>
	   </div>
	   <button type="submit" name="delete_csv_file" id="delete_csv_file" class="button button-primary" value="delete_csv_file"> <?php echo __("Click To Delete", "investorstrust") ?> </button>
    </form>	
   <?php

   if(isset($_FILES['delete_csv_file']) && $_FILES['delete_csv_file'] != ""){
		$filename = $_FILES["delete_csv_file"]["tmp_name"] ;

		// The nested array to hold all the arrays
		$the_big_array = []; 

		// Open the file for reading
		if (($h = fopen("{$filename}", "r")) !== FALSE) 
		{
		  // Each line in the file is converted into an individual array that we call $data
		  // The items of the array are comma separated
		  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
		  {
		    // Each individual array is being pushed into the nested array
		    $the_big_array[] = $data;		
		  }

		  // Close the file
		  fclose($h);
		}

		$csv_header = array_shift($the_big_array);
		$response = '<div class="update-nag"><h2>'.__("Below Emails are Not found in Database" , "investorstrust").'<h2>';
		$counter= $delete_user_count = $error_count= $email_not_found = $errorlog = 0;
		foreach ($the_big_array as $key => $value) {
			/*
			*  check the parameter and the remove whitespaces on both side of string
			*/
			$names[$key] = trim($value[0]);
			$emails[$key] = trim($value[1]);
			$introducer_codes[$key] = trim($value[2]);

			if(is_email($emails[$key]) && !empty($introducer_codes[$key])){
				if( email_exists( $emails[$key] ) ) {

					$user = get_user_by('email', $emails[$key]);
					$introducer_code = get_user_meta( $user->ID, $meta_key = 'introducer_code', 'true' );

					if( (strtolower($user->user_email) == strtolower($emails[$key])) && $introducer_code == $introducer_codes[$key] ) {
						//WP DELETE USER FUNCTION WILL DELETE THE USER FROM THE DATABASE.
						$delete_user_count = wp_delete_user( $user->ID ) ? $delete_user_count + 1 : $delete_user_count;
					} else { 

						$error_count++;
					}

				} else {

					/*
					*  @param $email_not_found if data is not available in the database.
					*/				
					$email_not_found++;
					$response .= '<p>'.$emails[$key].'</p>';
					$error_count++;

				}

			} else {

				$error_count++;				
			}
			$counter++;
		}
		$response .='</div>';

		if($email_not_found){
			echo $response;
		}
		echo '<div class="update-nag">'.__("Total" , "investorstrust").' : '.$counter.' '.__("Records processed" , "investorstrust").' , "'.$error_count.' "'.__("Error Found" , "investorstrust").' , '.$delete_user_count.__("User Deleted" , "investorstrust").'</div>';

	}



}

add_action('admin_menu', 'investorstrust_funds_custom_submenu_page');
function investorstrust_funds_custom_submenu_page() {
   add_submenu_page('edit.php?post_type=funds', __('Insert form csv','investorstrust'), __('Insert form csv','investorstrust'), 'manage_options', 'funds-csv-import-insert', 'investorstrust_csv_funds_insert_callback' );
  // add_submenu_page('edit.php?post_type=funds', __('Delete using csv','investorstrust'), __('Delete using csv','investorstrust'), 'manage_options', 'funds-csv-import-delete', 'investorstrust_csv_funds_delete_callback' );
}

function investorstrust_csv_funds_insert_callback() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
       echo '<h2>'.__('Import Funds from CSV File','investorstrust').'</h2>'; ?> 
   </div>

   <form method="post" enctype="multipart/form-data" action=<?php echo $_SERVER['PHP_SELF'].'?post_type=funds&page=funds-csv-import-insert';?>>
	   <div id="welcome-panel" class="welcome-panel">
	   		<h2 class="about-description" ><?php _e('Funds Import File','investorstrust' )?></h2> <input class="" id="import_csv_file" name="import_csv_file" type="file" required="required" />	
		   <div>
		   		<a href="<?php echo get_template_directory_uri().'/media/funds-demo.csv';?>" target="_blank"> <?php _e('See the Demo', 'investorstrust') ?></a>
		   </div>
	   </div>
	   <button type="submit" name="import_csv_file" id="import_csv_file" class="button button-primary" value="import_csv_file"> <?php echo __("Click To Import", "investorstrust") ?> </button>
   </form>
   <?php

   	$errors = $category = array(); 
   	$error_counter = 0;

	if( empty($_FILES["import_csv_file"]) ){
		$errors[] = __('File is not provided, please provide file' , 'investorstrust');
		$error_counter++;
	} else {
	
		if( $_FILES["import_csv_file"]["type"] != 'text/csv' ){
			$errors[] = __('This file extension is not valid' , 'investorstrust');
			$error_counter++;	
		}
		
		if( $_FILES["import_csv_file"]["size"] == 0 ){
			$errors[] = __('This file is empty' , 'investorstrust');
			$error_counter++;	
		}

	   	if( $error_counter == 0 ){
			$filename = $_FILES["import_csv_file"]["tmp_name"] ;

			// The nested array to hold all the arrays
			$the_big_array = []; 

			// Open the file for reading
			if (($h = fopen("{$filename}", "r")) !== FALSE) 
			{
			  // Each line in the file is converted into an individual array that we call $data
			  // The items of the array are comma separated
			  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
			  {
			    // Each individual array is being pushed into the nested array
			    $the_big_array[] = $data;		
			  }

			  // Close the file
			  fclose($h);
			  $csv_header = array_shift($the_big_array);
			}

			if( empty($the_big_array) ){
				$errors[] =__( 'The CSV File is empty Please add Data to it', 'investorstrust' ); 
				$error_counter++;
			}

			foreach ($the_big_array as $key => $value) {
				if( empty($value[3]) ){
					$errors[] =__( 'Your provided name is empty', 'investorstrust' ); 
				} else { // name 
					$args = array(
						'name' => sanitize_title($value[3]),
						'post_type'   => 'funds',
						'post_status' => array('publish','draft'),
						
					);
					if( !empty($value[1]) ){  //ISIN
					 $args['meta_query']     = array(
							array(
								'key'     => 'isin',
								'value'   => trim($value[1]),
								'compare' => '=',
							)
						);
					}
					$query = new WP_Query( $args );
					if(!$query->have_posts()){
						$insert_post = array(
										"post_title"  => trim( $value[3] ),
										"post_type"   => 'funds',
										"post_status" => "publish"
									);
						$post_id = wp_insert_post($insert_post);
				    }
				}
				if( !empty($post_id) ){ 
					$id_of_term  = array();
					//GETTING THE TERMS ID FOR THE INSERTING PROCESS
					$id_of_term['fundfamily'] = (get_term_by('slug', sanitize_title( $value[2] ), 'fundfamily'))->term_id;
					if( !empty($id_of_term['fundfamily'])){
						$category[$key][] = $id_of_term['fundfamily'];
						wp_set_post_terms( $post_id , array($id_of_term['fundfamily']), 'fundfamily');
					}

					$id_of_term['assetclass'] = (get_term_by('slug', sanitize_title( $value[4] ), 'assetclass'))->term_id;
					if( !empty($id_of_term['assetclass'])){
						wp_set_post_terms( $post_id , array($id_of_term['assetclass']), 'assetclass');
					}
					$id_of_term['investmentuniverse'] = (get_term_by('slug', sanitize_title( $value[5] ), 'investmentuniverse'))->term_id;
					if( !empty($id_of_term['investmentuniverse'])){
						wp_set_post_terms( $post_id , array($id_of_term['investmentuniverse']), 'investmentuniverse');
					}
					if(!empty($value[6])){
						update_field('select_currency', strtolower( trim($value[6]) ), $post_id);
					}
					if(!empty( $value[1] )){
						update_field('isin', strtoupper(trim($value[1])) , $post_id);	
					}
					if(!empty( $value[0] )){
						update_field('fund_code', strtoupper(trim($value[0])) , $post_id);	
					}	
				}	
				
			}
		}
		$response ='<div class="update-nag">';
		foreach ($errors as $key => $value) {
			$response .='<br>'.$value.'</br>'; 
		}
		$response .='</div>';
		echo $response;
		echo'<div id="message" class="updated"><p>Data inserted suceesfully Field group updated.</p></div>';

	}	

}
function investorstrust_csv_funds_delete_callback() {
	echo "<h1>Hello<h1>";
}

/**
 *  User introducer code @param introducer_code is Meta field for the user.
 *   @author investorstrust
 * 
 */

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <table class="form-table">
    <tr>
        <th><label for="address"><?php _e("Full Name", 'investorstrust'); ?></label></th>
        <td>
            <input type="text" name="full_name" id="full_name" value="<?php echo esc_attr( get_the_author_meta( 'full_name', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter User Full name to display." , 'investorstrust'); ?></span>
        </td>
    </tr>	
    <tr>
        <th><label for="address"><?php _e("Introducer Code", 'investorstrust'); ?></label></th>
        <td>
            <input type="text" name="introducer_code" id="introducer_code" value="<?php echo esc_attr( get_the_author_meta( 'introducer_code', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter introducer code." , 'investorstrust'); ?></span>
        </td>
    </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    if( isset($_POST['introducer_code']) ){ 
    	update_user_meta( $user_id, 'introducer_code', $_POST['introducer_code'] );
    }
    if( isset($_POST['full_name']) ){ 
    	update_user_meta( $user_id, 'full_name', $_POST['full_name'] );
    }
    
}

function admin_post_list_add_export_button( $which ) {
	/*global $wpdb;
	$sql = 'SELECT * FROM ' . $wpdb->users;
	$users = $wpdb->get_results( $sql, 'ARRAY_A' );
	*/
	?>
	<input type="submit" name="export_all_users" class="button button-primary" value="<?php _e('Export All Users'); ?>" />
	<?php
}
 
add_action( 'restrict_manage_users', 'admin_post_list_add_export_button', 20, 1 );

function func_export_all_users() {
    if(isset($_GET['export_all_users'])) {
        global $wpdb;
		$sql = 'SELECT * FROM ' . $wpdb->users;
		$users = $wpdb->get_results( $sql, 'ARRAY_A' );
		
        if ($users) {
			$date = date('d-m-Y');
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="users-lists-'.$date.'.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
  
            fputcsv($file, array('Introducer Code', 'Full Name', 'Email', 'Language', 'Role'));
  
			foreach ( $users as $user ) {

				$user_id = $user['ID'];
				$user_info = get_userdata($user_id);
				
				$first_name = $user_info->first_name;
				$first_name = !empty( $first_name ) ? $first_name.' ' : '';
				$last_name = $user_info->last_name;
				$last_name = !empty( $last_name ) ? $last_name : '';
				$user_full_custom_name = $first_name.$last_name;			
				$user_fullname = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : $user_full_custom_name;	
				$user_intro_code = get_user_meta( $user_id, 'introducer_code', true);
				$user_intro_code = !empty( $user_intro_code ) ? $user_intro_code : '';
				// $user_language = get_user_locale( $user_id );
				$user_language = (get_user_meta( $user_id, 'current_language', true )) ? (get_user_meta( $user_id, 'current_language', true )) : "en";
				$user_roles = implode(', ', $user_info->roles);
				$user_roles = !empty( $user_roles ) ? $user_roles : '';
				$user_email = !empty( $user_info->user_email ) ? $user_info->user_email : '';
				
				fputcsv($file, array($user_intro_code, $user_fullname, $user_email, $user_language, $user_roles));
			}
			
            exit();
        }
    }
}
 
add_action( 'init', 'func_export_all_users' );

/**
 *  FUNCTION FOR VALIDATE POPUP DATE
 *
 *   @author investorestrust
 */

add_action( 'pre_post_update', 'popup_date_validation', 10 ,1);
function popup_date_validation( $post_id ) {
	global $post;
    if ($post->post_type != 'popup'){
        return;
    }

	global $message_global;
	$current_post_id = get_the_ID();
	$post_meta_start_date = get_acf_key('popup_start_date');
	$post_meta_end_date = get_acf_key('popup_end_date');
	$post_meta_user = get_acf_key('popup_user');
	$current_post_start_date = $_POST['acf'][$post_meta_start_date];
	$current_post_end_date = $_POST['acf'][$post_meta_end_date];
	$current_post_user = $_POST['acf'][$post_meta_user];
	$error_message = array();
	$message = '';

	if ( $current_post_start_date > $current_post_end_date ) {
		$error_message['date_check_error'] = apply_filters( 'wpml_translate_single_string', $message_global['popupdatefieldvalidation']['popup_date_error'], 'popupdatefieldvalidation-msg', 'popup-date-error',$language);
		$message = $error_message;
		set_transient('date_validation', $message);
		add_action('publish_popup', 'check_user_publish', 10, 2);
		return false;
	}

	$args = array(
		'post_type'      => 'popup',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'     => 'popup_user',
		'meta_value'   => $current_post_user,
		'meta_compare' => '=',
		'post__not_in'   =>  array( $current_post_id ),
	);

	$popup = new WP_Query($args);
	if( $popup->have_posts() ) {
		while ( $popup->have_posts() ) {
			$popup->the_post();
			$english_id = get_the_ID();
			$post_id    = apply_filters( 'wpml_object_id', $english_id, 'popup', TRUE, $language);
			$start_date = ( get_field('popup_start_date',$post_id) ) ? get_field('popup_start_date',$post_id) : "";
			$end_date   = ( get_field('popup_end_date',$post_id) ) ? get_field('popup_end_date',$post_id) : "";

			if ( $current_post_start_date >=  (int)$start_date && $current_post_start_date <=  (int)$end_date) {
				$error_message['start_date_error'] = apply_filters( 'wpml_translate_single_string', $message_global['popupstartdatefieldvalidation']['popup_date_error'], 'popupstartdatefieldvalidation-msg', 'popup-date-error',$language);
			}
			if ($current_post_end_date >=  (int)$start_date && $current_post_end_date <=  (int)$end_date) {
				$error_message['end_date_error'] = apply_filters( 'wpml_translate_single_string', $message_global['popupenddatefieldvalidation']['popup_date_error'], 'popupenddatefieldvalidation-msg', 'popup-date-error',$language);
			}
		}
	}
	if ( $current_post_start_date > $current_post_end_date ) {
		$error_message['date_check_error'] = apply_filters( 'wpml_translate_single_string', $message_global['popupdatefieldvalidation']['popup_date_error'], 'popupdatefieldvalidation-msg', 'popup-date-error',$language);
	}
	if ( !empty( $error_message ) ) {
		$message = $error_message;
		set_transient('date_validation', $message);
		add_action('publish_popup', 'check_user_publish', 10, 2);
		return false;
	}
}

function check_user_publish ($post_id, $post) {
	$query = array(
		'ID' => $post_id,
        'post_status' => 'draft',
    );
    wp_update_post( $query, true );
}

if ( get_transient('date_validation') ) {
	add_filter( 'post_updated_messages', 'remove_post_published_message' );
}
function remove_post_published_message( $messages ) {
    unset($messages[post][6]);
	unset($messages[post][10]);
    return $messages;
}

function get_acf_key($field_name){
    global $wpdb;

    return $wpdb->get_var("
        SELECT post_name
        FROM $wpdb->posts
        WHERE post_type='acf-field' AND post_excerpt='$field_name';
    ");
}

add_action( 'admin_notices', 'show_error' );
function show_error() {
	$error = get_transient('date_validation');
	if ( !empty($error['start_date_error']) ) {
		?>
		<div class="error">
			<p><?php echo $error['start_date_error']; ?></p>
		</div>
		<?php
	delete_transient('date_validation');
	}
	if ( !empty($error['end_date_error']) ) {
		?>
		<div class="error">
			<p><?php echo $error['end_date_error']; ?></p>
		</div>
		<?php
	delete_transient('date_validation');
	}
	if ( !empty($error['date_check_error']) ) {
		?>
		<div class="error">
			<p><?php echo $error['date_check_error']; ?></p>
		</div>
		<?php
	delete_transient('date_validation');
	}
}