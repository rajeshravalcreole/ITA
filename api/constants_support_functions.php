<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('STATUS_CODE_400', 400); // BAD REQUEST
define('STATUS_CODE_200', 200); // SUCCESS
define('POST_ADD_UPDATE_INTERVAL', 15); // post added interval
define('NOTIFICATION_LIMIT', 10);

/* Name: Hardika Satasiya
 * Function :get_400_error
 * Purpose: to set status code 4000
 */

function get_400_error($json) {
    $error = new WP_REST_Response($json);
    $error->set_status(400);
    return $error;
}

function get_200_success($json) {
    $error = new WP_REST_Response($json);
    $error->set_status(200);
    return $error;
}

/* Author: Ankita Tanti
 * Purpose: To get user object with required settings
 */
function get_user_obj($user_id) {
	$acf_field_id = "user_".$user_id; 
	$user_arr = array();
	$user_arr['user_id'] = (int)$user_id;
	$user_arr['user_name'] = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : "";
	$user_arr['night_mode'] = (get_user_meta( $user_id, 'night_mode', true )) ? ((int)get_user_meta( $user_id, 'night_mode', true )) : 0;
	$user_arr['news_update_notification'] = (get_user_meta( $user_id, 'news_update_notification', true )) ? ((int)get_user_meta( $user_id, 'news_update_notification', true )) : 0;
	$user_arr['library_update_notification'] = (get_user_meta( $user_id, 'library_update_notification', true )) ? ((int)get_user_meta( $user_id, 'library_update_notification', true )) : 0;
	$user_arr['current_language'] = (get_user_meta( $user_id, 'current_language', true )) ? (get_user_meta( $user_id, 'current_language', true )) : "en";
	$user_arr['notification_language'] = (get_user_meta( $user_id, 'notification_language', true )) ? (get_user_meta( $user_id, 'notification_language', true )) : "en";
	return $user_arr;
}

function file_formatsize( $size ) {
    $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    if ( $size != 0 ) 
        return ( round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . $sizes[$i] );
}

function  get_file_path( $post_type, $language, $type, $file_name){
    $file_url = '';
    if( file_exists( ABSPATH.'/wp-content/'.$post_type.'/'.$language.'/'.$type.'/'.$file_name ) ){
        $file_url = site_url().'/wp-content/'.$post_type.'/'.$language.'/'.$type.'/'.$file_name;
    } else {
    	if( file_exists( ABSPATH.'/wp-content/'.$post_type.'/en/'.$type.'/'.$file_name ) ){
	        $file_url = site_url().'/wp-content/'.$post_type.'/en/'.$type.'/'.$file_name;
	    }	
    }

    if($file_url=="") {
    	//IMAGE CHECK
    	if(!strpos($file_name, ".pdf")) {
    		if($post_type=="discover"){
    			$file_url =	get_template_directory_uri()."/images/discover-placeholder.jpg";
    		} if($post_type=="library"){
    			if($type=="products" || $type=="flyers"){
    				$file_url =	get_template_directory_uri()."/images/library-placeholder.jpg";
    			}
    			else if($type=="socialpost"){
    				$file_url =	get_template_directory_uri()."/images/socialpost-placeholder.jpg";
    			}
    			else if($type=="presentation"){
    				$file_url =	get_template_directory_uri()."/images/presentation-placeholder.jpg";
    			}
    		} if($post_type=="media-center"){
    			$file_url =	get_template_directory_uri()."/images/socialpost-placeholder.jpg";
    		}  if($post_type=="timeline"){
    			$file_url =	get_template_directory_uri()."/images/socialpost-placeholder.jpg";
    		}
    	}
    }

    return $file_url;
}

function  get_fund_path( $post_type, $language, $file_name){
    $file_url = '';
    if( file_exists( ABSPATH.'/wp-content/'.$post_type.'/'.$language.'/'.$file_name ) ){
        $file_url = site_url().'/wp-content/'.$post_type.'/'.$language.'/'.$file_name;
    } else {
    	if( file_exists( ABSPATH.'/wp-content/'.$post_type.'/en/'.$file_name ) ){
	        $file_url = site_url().'/wp-content/'.$post_type.'/en/'.$file_name;
	    }	
    }
    return $file_url;
}

function get_language_name($code=''){
	global $sitepress;
	$details = $sitepress->get_language_details($code);
	$language_name = $details['native_name'];
	return $language_name;
} 

function get_acf_option($option_name,$language = "en") {
	$translated_option = "options_".$language."_".$option_name;
	$en_option = "options_".$option_name;
	$option_value = get_option($translated_option);
	return ($option_value) ? $option_value : get_option($en_option);
}

function get_translated_category_name($taxonomy_id,$post_id,$taxonomy,$return='name') {
	global $wpdb;
    $posts = $wpdb->prefix . 'posts';
    $terms = $wpdb->prefix . 'terms';
    $term_relationship = $wpdb->prefix . 'term_relationships';
    $term_taxonomy = $wpdb->prefix . 'term_taxonomy';

	$sql = "SELECT wt.* FROM ".$posts." p INNER JOIN ".$term_relationship." r ON r.object_id=p.ID INNER JOIN ".$term_taxonomy." t ON t.term_taxonomy_id = r.term_taxonomy_id INNER JOIN ".$terms." wt on wt.term_id = t.term_id WHERE p.ID=".$post_id." AND t.taxonomy='".$taxonomy."'";
	$translated_taxonomies = $wpdb->get_results($sql);
	if($translated_taxonomies) {
		foreach ( $translated_taxonomies as $taxonomy ) 
		{	
			if($return=="name")
				return html_entity_decode($taxonomy->name, ENT_COMPAT, 'UTF-8');
			if($return=="term_id")
				return $taxonomy->term_id;
			if($return=="slug")
				return $taxonomy->slug;
			if($return=="term_group")
				return $taxonomy->term_group;
			if($return=="term_order")
				return $taxonomy->term_order;
		}
	} else {
		return "";
	}
}

function get_translated_category($taxonomy_id,$taxonomy,$return='name') {
	global $wpdb;
    $posts = $wpdb->prefix . 'posts';
    $terms = $wpdb->prefix . 'terms';
    $term_relationship = $wpdb->prefix . 'term_relationships';
    $term_taxonomy = $wpdb->prefix . 'term_taxonomy';

	$sql = "SELECT wt.* FROM ".$terms." wt INNER JOIN ".$term_taxonomy." t on wt.term_id = t.term_id WHERE t.taxonomy='".$taxonomy."' AND wt.term_id=".$taxonomy_id;
	$translated_taxonomies = $wpdb->get_results($sql);
	if($translated_taxonomies) {
		foreach ( $translated_taxonomies as $taxonomy ) 
		{	
			if($return=="name")
				return html_entity_decode($taxonomy->name, ENT_COMPAT, 'UTF-8');
			if($return=="term_id")
				return $taxonomy->term_id;
			if($return=="slug")
				return $taxonomy->slug;
			if($return=="term_group")
				return $taxonomy->term_group;
			if($return=="term_order")
				return $taxonomy->term_order;
		}
	} else {
		return "";
	}
}

function get_isin_posts($isin) {
	global $wpdb;
    $postmeta = $wpdb->prefix . 'postmeta';
    $count = 0;
	$sql = "SELECT count(pm.post_id) as post_count FROM ".$postmeta." pm WHERE pm.meta_key='isin' AND pm.meta_value like '%".$isin."%'";
	$post_count = $wpdb->get_results($sql);
	if($post_count) {
		return $post_count[0]->post_count;
	} else {
		return $count;
	}
}

function get_fund_code_posts($fund_code) {
	global $wpdb;
    $postmeta = $wpdb->prefix . 'postmeta';
    $count = 0;
	$sql = "SELECT count(pm.post_id) as post_count FROM ".$postmeta." pm WHERE pm.meta_key='fund_code' AND pm.meta_value like '%".$fund_code."%'";
	$post_count = $wpdb->get_results($sql);
	if($post_count) {
		return $post_count[0]->post_count;
	} else {
		return $count;
	}
}