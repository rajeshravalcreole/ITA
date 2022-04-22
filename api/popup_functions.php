<?php
/**
 *  Purpose: popup list.
 *  @link api/popup_functions.php
 *
 *  Method = "POST"
 *  $request_data['user_id'] int ( optional )
 *
 */
function getPopupDetail( $request_data ) {
	$json = array();
	global $wpdb;
	global $message_global;
	$json['data']     = $data = $content = array();
	$json['message']  = '';
	$language         = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
	$user_id          = $guest = 0;
	$today_date       = date("Ymd");

	if( empty( $request_data['user_id'] ) ) {
		$guest = 1;
		$user  = 'guest';
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
		$user  = (!get_user_by('id', $user_id)) ? 'guest' : 'agent';
	}
	if ( $guest == 0  ) {
		$close_popup_id_list = get_user_meta( $user_id, 'shown_popup' );
	}

	$args = array(
		'post_type'      => 'popup',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_key'       => 'popup_user',
		'meta_value'     => $user,
		'meta_compare'   => '=',
		'post__not_in'   => $close_popup_id_list[0],
		'meta_query'     => array(
			'relation' => 'AND',
			 array(
				'key'     => 'popup_start_date',
				'value'   => $today_date,
				'compare' => '<=',
			),
			 array(
				'key'     => 'popup_end_date',
				'value'   => $today_date,
				'compare' => '>=',
			),
		),
	);

	$popup = new WP_Query($args);
	if( $popup->have_posts() ) {
		while ( $popup->have_posts() ) {
			$popup->the_post();

			$english_id = get_the_ID();
			// FETCHING TRANSLATION POST ID
            $post_id                  = apply_filters( 'wpml_object_id', $english_id, 'popup', TRUE, $language);
			$push['popup_id']         = $post_id;
            $push['popup_title']      = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
            $push['popup_url']        = ( get_field('popup_url',$post_id) ) ? get_field('popup_url',$post_id) : "";
            $push['popup_start_date'] = ( get_field('popup_start_date',$post_id) ) ? get_field('popup_start_date',$post_id) : "";
            $push['popup_end_date']   = ( get_field('popup_end_date',$post_id) ) ? get_field('popup_end_date',$post_id) : "";
            $push['popup_image']      = ( get_field('popup_image',$post_id) ) ? get_field('popup_image',$post_id) : "";
			$push['popup_start_date'] = date('Y-m-d',strtotime($push['popup_start_date']));
			$push['popup_end_date']   = date('Y-m-d',strtotime($push['popup_end_date']));
		}
		wp_reset_query();
	}

	$json['data']= $push;
    if(empty($push)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getpopuplist']['no_data_found'], 'getpopuplist-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getpopuplist']['success'], 'getpopuplist-msg', 'success',$language);
    }
	return get_200_success( $json );
}

/**
 *  Purpose: Close popup list.
 *  @link api/popup_functions.php
 *
 *  Method = "POST"
 *  $request_data['user_id'], [popup_id] int ( required )
 *
 */
function closepopup( $request_data ) {
	$json = array();
	$popup_id_array=array();
	$message='';
	$error = '';
	$user_id=$guest=0;

	if( empty( $request_data['user_id'] ) ) {
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}

	if ( $guest == 0 ) {
		$add_popup_id   = $request_data['popup_id'];
		if ( !empty($add_popup_id) ) {
			$meta_popup_id_array = ( get_user_meta( $user_id, 'shown_popup' ))? get_user_meta( $user_id, 'shown_popup' , true ) : $popup_id_array;
			if (in_array($add_popup_id, $meta_popup_id_array)) {
				$error = 'popup_id already exit in close list';
			} else {
				array_push($meta_popup_id_array, $add_popup_id);
				update_user_meta( $user_id, 'shown_popup', $meta_popup_id_array );
				$message = 'popup_id added in shown popup list';
			}
			}
		else {
			$error = 'Please enter popup_id';
		}
	}
	else {
		$error = 'Please enter valid user_id';
	}

	if ( !empty($error) ) {
		$json['message'] = $error;
		return get_400_error( $json );
	}
	$json['message'] = $message;
	return get_200_success( $json );
}