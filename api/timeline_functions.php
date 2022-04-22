<?php
/**
 *  Purpose: list of timeline.  
 *  @link api/timeline_functions.php
 *  
 *  Method = "POST"
 *  $request_data['user_id'] int ( optional ), ['order'] string.
 * 
 */
function getTimelineList( $request_data ) {
	$json = array();
	global $wpdb;
	global $message_global;
	$json['data']     = $data = $page_data = array();
	$json['message']  = '';
	$language         = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
	$user_id          = $guest = 0;
	$type             = 'timeline-image';
	$order            = (isset($request_data['order'])) ? $request_data['order'] : "DESC";

	if( empty( $request_data['user_id'] ) ) { 
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}
	
	$args = array(
		'post_type'     => 'timeline',
		'post_status'   => 'publish',
		'meta_key'      => 'timeline_date',
		'orderby'       => 'meta_value_num',
		'order'         =>  $order,
		'posts_per_page' => -1,

	);
	
	$query = new WP_Query($args);
	$timeline = new WP_Query($args); 

	// GET timeline post data
	if( $timeline->have_posts() ) {  
		$timeline_counter = 0; 
		while ( $timeline->have_posts() ) {
			$timeline->the_post(); 

			$english_id = get_the_ID();
			// FETCHING TRANSLATION POST ID
			$post_id             = apply_filters( 'wpml_object_id', $english_id, 'timeline', TRUE, $language);
			$push['post_id']     = $post_id;
			$push['title']       = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
			$push['date']        = ( get_field('timeline_date',$post_id) ) ? get_field('timeline_date',$post_id) : "";

			$data[$timeline_counter] =  $push;
            $timeline_counter++;
		}
		wp_reset_query();
	}
	
	$page_data['title']       = ( get_acf_option('timeline_page_title', $language) ) ? get_acf_option('timeline_page_title', $language) : '';
	$page_data['sub_title']   = ( get_acf_option('timeline_page_subtitle', $language) ) ? get_acf_option('timeline_page_subtitle', $language) : '';
	$page_data['description'] = ( get_acf_option('timeline_page_description', $language) ) ? get_acf_option('timeline_page_description', $language) : '';

	$json['data']['page_data'] = $page_data;
	$json['data']['posts'] = $data;
    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['gettimelinelist']['no_data_found'], 'gettimelinelist-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['gettimelinelist']['success'], 'gettimelinelist-msg', 'success',$language);
    }
	return get_200_success( $json );
}
/**
 * 	Show popup with single timeline details.
 *  Purpose: Single timeline popup.  
 *  @link api/timeline_functions.php
 *  
 *  Method = "POST"
 *  $request_data['post_id'] int
 * 
 */
function getTimelineDetail( $request_data ) {
	$json = array();
	global $wpdb;
	global $message_global;
	$json['data']     = $data = $content = array();
	$json['message']  = '';
	$language         = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
	$user_id          = $guest = 0;
	$type             = 'timeline-image';

	if( !isset( $request_data['post_id'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['gettimelinedetail']['post_id_not_provided'], 'gettimelinedetail-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $english_id = $request_data['post_id'];
    }
    
    $post_id = apply_filters( 'wpml_object_id', $english_id, 'timeline', TRUE, $language);
    $single_post = get_post( $post_id );
    if( !isset( $single_post ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['gettimelinedetail']['timeline_not_found'], 'gettimelinedetail-msg', 'timeline-not-found',$language);
        return get_400_error( $json );
    } else {
        if($single_post->post_type != "timeline" || $single_post->post_status != "publish") {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['gettimelinedetail']['timeline_not_found'], 'gettimelinedetail-msg', 'timeline-not-found',$language);
            return get_400_error( $json );
        }
    }
	
	// Get single timeline post data
	$push['title']       = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
	$push['date']        = ( get_field('timeline_date',$post_id) ) ? get_field('timeline_date',$post_id) : "";
	$push['description'] = ( get_field('timeline_description',$post_id) ) ? get_field('timeline_description',$post_id) : "";
	$push['image']       = ( get_field('timeline_image',$post_id) ) ? get_file_path('timeline',$language,$type,get_field('timeline_image',$post_id)) : "";

    $json['data'] = $push;
    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['gettimelinedetail']['timeline_detail_success'], 'gettimelinedetail-msg', 'timeline-detail-success',$language);
    return get_200_success($json);
}