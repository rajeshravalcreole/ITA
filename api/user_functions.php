<?php 
/**
 *  User realated API use after authentication.
 *  Purpose: Password Reset For the user.  
 *  @link api/auth_functions.php
 *    
 */

function changePassword( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $old_password = $new_password =  $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END
    
    if( empty( $request_data['old_password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['old_password_not_provided'], 'changepassword-msg', 'old-password-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['new_password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['new_password_not_provided'], 'changepassword-msg', 'new-password-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
    }

    $old_password = $request_data['old_password'];
    $new_password = $request_data['new_password'];
    if(($old_password == $new_password)){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['same_password'], 'changepassword-msg', 'same-password',$language);
        return get_400_error( $json );
    }

    $user = get_user_by( 'id', $user_id );
    if ( ! empty( $user ) ) {
        $check_user_pass = wp_check_password( $old_password, $user->data->user_pass, $user_id );
        if( ! wp_check_password( $old_password, $user->data->user_pass, $user_id ) ) { // VALIDATE THE USER PASSWORD
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['incorrect_password'], 'changepassword-msg', 'incorrect-password',$language);  // password not match with database.
            return get_400_error( $json );
        }
        wp_set_password( $new_password, $user_id ); //password reset
       // storing user information in array
        $data['user_id'] = (int)$user->data->ID;
        $data['email_address'] = $user->data->user_email;
        $json['data']  = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['success'], 'changepassword-msg', 'success',$language);
        return get_200_success( $json );
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changepassword']['user_not_found'], 'changepassword-msg', 'user-not-found',$language);
        return get_400_error( $json );
    }
}
/**
 *  Purpose: To update nioght mode settings
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  REQUEST @param  = $request_data['nightmode_status'] (int) (require)
 *  
 */
function updateNightMode( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $nightmode_status = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

   if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenightmode']['user_not_found'], 'updatenightmode-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $acf_field_id = "user_".$user_id; 
    }

    if(!isset($request_data['nightmode_status'])){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenightmode']['status_not_provided'], 'updatenightmode-msg', 'status-not-provided',$language);
        return get_400_error( $json );
    } 

    $nightmode_status = $request_data['nightmode_status'];
    if($nightmode_status<0 || $nightmode_status>1) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    }
    update_field('night_mode',$nightmode_status,$acf_field_id);

    if($nightmode_status == 1)
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenightmode']['night_mode_turned_on'], 'updatenightmode-msg', 'night-mode-turned-on',$language);
    else
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenightmode']['night_mode_turned_off'], 'updatenightmode-msg', 'night-mode-turned-off',$language);
    $data = get_user_obj($user_id);
    $json['data']  = $data;
    return get_200_success( $json );
}
/**
 *  Purpose: To update notification settings
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  REQUEST @param  = $request_data['library_status'] (int) (require)
 *  
 */
function updateNotification( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $news_status = $library_status =  $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenotification']['user_not_found'], 'updatenotification-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $acf_field_id = "user_".$user_id; 
    }
    if( !isset( $request_data['news_status'] ) ){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenotification']['news_status_not_provided'], 'updatenotification-msg', 'news-status-not-provided',$language);
        return get_400_error( $json );
    } else {
        $news_status = $request_data['news_status']; 
        if($news_status < 0 || $news_status > 1) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
            return get_400_error( $json );
        }
    }
    if(!isset($request_data['library_status'])){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenotification']['library_status_not_provided'], 'updatenotification-msg', 'library-status-not-provided',$language);
        return get_400_error( $json );
    }  else {
        $library_status = $request_data['library_status']; 
        if($library_status < 0 || $library_status > 1) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
            return get_400_error( $json );
        }
    }
    update_field('news_update_notification',$news_status,$acf_field_id);
    update_field('library_update_notification',$library_status,$acf_field_id);
    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['updatenotification']['push_notification_success'], 'updatenotification-msg', 'push-notification-success',$language);
    $data = get_user_obj($user_id);
    $json['data']  = $data;
    return get_200_success( $json );
}

/**
 *  Purpose: To add or remove to favorite post
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['post_id'] (int) (require)
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  
 */
function addRemoveBookmark( $request_data ) {
    global $wpdb;
    global $message_global;
    $json = $data = array();
    $json['data']  = $favorite = array();
    $json['message'] = $user_id = $favorites = $key_of_fevorite ='';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['user_id_not_provided'], 'addremovebookmark-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['user_id'] = $user_id = intval( $request_data['user_id'] );
        if( !get_user_by('ID', $user_id) ) {    
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['user_not_exsist'], 'addremovebookmark-msg', 'user-not-exsist',$language);
            return get_400_error( $json );
        }
    }

    if( empty( $request_data['post_id'] ) ) {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['post_id_not_provided'], 'addremovebookmark-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['post_id'] = $post_id = intval( $request_data['post_id'] ); 
    }   

    // THIS CHECK IS ADDED TO ALLOW ONLY ENLISH POST ADDITION TO BOOKMARKS
    $english_post_id = 0;
    $english_post_id = icl_object_id($post_id, 'library', false);
    if($english_post_id) {
        if($english_post_id!=$post_id) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['can_not_be_added'], 'addremovebookmark-msg', 'can-not-be-added',$language);
            return get_400_error( $json );
        }
    }

    $english_post_id = 0;
    $english_post_id = icl_object_id($post_id, 'discover', false);
    if($english_post_id) {
        if($english_post_id!=$post_id) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['can_not_be_added'], 'addremovebookmark-msg', 'can-not-be-added',$language);
            return get_400_error( $json );
        }
    }
    // THIS CHECK IS ADDED TO ALLOW ONLY ENLISH POST ADDITION TO BOOKMARKS

    $postget = get_post( $post_id );
    $post_type = $postget->post_type;
    $post_status = $postget->post_status;
    $type = (get_field('select_type',$post_id)) ? get_field('select_type',$post_id) : '';
    $supported_types = array("flyer","flyers","brochure","products","presentation","video","socialpost");
    $supported_post_types = array("discover","library");  
    if((!in_array($post_type,$supported_post_types)) || (!in_array($type,$supported_types))) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['can_not_be_added'], 'addremovebookmark-msg', 'can-not-be-added',$language);
        return get_400_error( $json );
    }

    if($post_status!="publish") {
        $favorite_posts = array();
        $favorite_posts = get_user_meta( $user_id, 'favorite',true);
        $favourite = array_search( $post_id, $favorite_posts, true); // true for the strict search
        if( is_int($favourite) ) {
            array_splice( $favorite_posts, $favourite, 1 );    
            update_user_meta( $user_id, 'favourite', $favorite_posts );
        }
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['post_status_error'], 'addremovebookmark-msg', 'post-status-error',$language);
        return get_400_error( $json );   
    }
       
    $favorite_posts  = array();
    if(get_user_meta( $user_id, 'favorite', true )) {
        $favorite_posts = get_user_meta( $user_id, 'favorite', true );
        // removing fom array if it exist
        if (in_array($post_id, $favorite_posts)) {
            $element_position = array_search($post_id,$favorite_posts);
            if($element_position ==0) {
                array_shift($favorite_posts); 
            } else {
                array_splice($favorite_posts,$element_position,1); 
            }
            update_user_meta( $user_id, 'favorite', $favorite_posts );
            $json['data'] = $data;
            $json['data']['favorites'] = $favorite_posts;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['remove_success'], 'addremovebookmark-msg', 'remove-success',$language);
            return get_200_success( $json );
        }
        // adding it to array if not exist
        array_unshift( $favorite_posts, $post_id );    
    } else {
        $favorite_posts[] = $post_id;
    }                

    update_user_meta( $user_id, 'favorite', $favorite_posts );
    $json['data'] = $data;
    $json['data']['favorites'] = $favorite_posts;
    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['added_success'], 'addremovebookmark-msg', 'added-success',$language);
    return get_200_success( $json );
}

/**
 *  Purpose: To multiple remove from favorite post
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['post_id'] (Comma separated string) (require)
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  
 */
function RemoveMultipleFavorite( $request_data ) {
    global $wpdb;
    global $message_global;
    $json = $data = array();
    $json['data']  = $favorite = array();
    $json['message'] = $user_id = $favorites = $key_of_fevorite ='';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['user_id_not_provided'], 'addremovebookmark-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['user_id'] = $user_id = intval( $request_data['user_id'] );
        if( !get_user_by('ID', $user_id) ) {    
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['user_not_exsist'], 'addremovebookmark-msg', 'user-not-exsist',$language);
            return get_400_error( $json );
        }
    }

    if( empty( $request_data['post_id'] ) ) {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['post_id_not_provided'], 'addremovebookmark-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $posts_id = explode(',', $request_data['post_id']);
        $data['post_id'] = $posts_id =array_map('intval', $posts_id); 
    }   
    foreach ($posts_id as $post_id) {
       
        $favorite_posts  = array();
        if(get_user_meta( $user_id, 'favorite', true )) {
            $favorite_posts = get_user_meta( $user_id, 'favorite', true );
            // removing fom array if it exist
            if (in_array($post_id, $favorite_posts)) {
                $element_position = array_search($post_id,$favorite_posts);
                if($element_position ==0) {
                    array_shift($favorite_posts); 
                } else {
                    array_splice($favorite_posts,$element_position,1); 
                }
                update_user_meta( $user_id, 'favorite', $favorite_posts );
                $json['data'] = $data;
                $json['data']['favorites'] = $favorite_posts;
            }
        }               
    }
    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addremovebookmark']['remove_success'], 'addremovebookmark-msg', 'remove-success',$language);
    return get_200_success( $json );
}

/**
 *  Purpose: Reorder fevorite list
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['post_id'] (comma separated string) (require)
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  
 */

function ReorderFavorite( $request_data ) {

    global $wpdb;
    global $message_global;
    $json = $data = array();
    $json['data']  = $favorite = array();
    $json['message'] = $user_id = $favorites = $key_of_fevorite ='';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['reorderfavorite']['user_id_not_provided'], 'reorderfavorite-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['user_id'] = $user_id = intval( $request_data['user_id'] );
        if( !get_user_by('ID', $user_id) ) {    
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['reorderfavorite']['user_not_exsist'], 'reorderfavorite-msg', 'user-not-exsist',$language);
            return get_400_error( $json );
        }
    }

    if( empty( $request_data['post_id'] ) ) {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['reorderfavorite']['post_id_not_provided'], 'reorderfavorite-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $posts_id = explode(',', $request_data['post_id']);
        $data['post_id'] = $posts_id =array_map('intval', $posts_id); 
        update_user_meta( $user_id, 'favorite', $posts_id );
        $json['data']['favorites'] = $posts_id;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['reorderfavorite']['reorder_success'], 'reorderfavorite-msg', 'reorder-success',$language);
        return get_200_success( $json );
    }   
}

/**
 *  Purpose: get user Recent fevorite count = 10 
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['post_id'] (int) (require)
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  
 *  REQUEST @param  = $request_data['product_count'] (int) (optional)
 */

function getfavorite( $request_data ) {
    global $message_global;
    $json = $data = array();
    $json['data'] = array();
    $json['message'] ='';
    $post_per_page = (int)get_option( 'posts_per_page');
    $total_favorites = $user_id = 0;
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_id_not_provided'], 'getbookmark-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    $user_id = $request_data['user_id'];

    $user_id = intval( $request_data['user_id'] );
    if( !get_user_by('ID', $user_id) ) {    
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_not_exsist'], 'getbookmark-msg', 'user-not-exsist',$language);
        return get_400_error( $json );
    }    

    if(empty($request_data['paged']))
        $offset = 0;
    else if(isset($request_data['paged']) && $request_data['paged']==1)
        $offset = 0;
    else
        $offset = (((int)$request_data['paged'] - 1) * $post_per_page);

    $favorites = $sliced_arr = array();
    $favorites = get_user_meta($user_id, 'favorite',false);
    if($favorites) {
        if(sizeof( $favorites )) {
            $favorites  = array_shift($favorites);//get favorites from the database
            $total_favorites = count($favorites);
            if(($offset+1)>$total_favorites) {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['no_more_data'], 'getbookmark-msg', 'no-more-data',$language);
                $data['favorite_posts'] = array();
            } else {
                $sliced_arr = array_slice( $favorites, $offset , $post_per_page);
                if($sliced_arr) {
                    foreach($sliced_arr as $english_id ) {
                        // we'll skip iteration if post status is not publish
                        if(get_post_status($english_id)!="publish")
                            continue;
                        $post_type = get_post_type($english_id);
                        $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
                        $type = (get_field('select_type',$post_id)) ? get_field('select_type',$post_id) : '';
                        $supported_types = array("flyer","flyers","brochure","products","presentation","video","socialpost");
                        $supported_post_types = array("discover","library");  
                        if((in_array($post_type,$supported_post_types)) && (in_array($type,$supported_types))) {
                            $title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                            $push = array('post_id' => $english_id,'type'=>$type,'title'=>$title);
                            if($type == "video"){
                                $video_url = ($post_type=="discover") ? get_field('video_link',$post_id) : get_field('video_url',$post_id);
                                $video_thumbnail = (get_field('video_thumbnail',$post_id)) ? get_field('video_thumbnail',$post_id) : "";
                                $push['video_link'] = $video_url;
                                $push['video_thumbnail'] = $video_thumbnail;
                                $data['favorite_posts'][] =  $push;
                            } else if($post_type=="discover" && $type == "flyer") {
                                $push['image'] = (get_field('thumbnail_image',$post_id)) ? get_file_path('discover',$language,$type,get_field('thumbnail_image',$post_id)) : '';
                                $push['file_url'] = (get_field('file_url',$post_id)) ? get_file_path('discover',$language,$type, get_field('file_url',$post_id)) : '';
                                if($push['file_url']!="")
                                    $data['favorite_posts'][] = $push;
                            } else {
                                $push['image'] = (get_field('thumbnail',$post_id)) ? get_file_path('library',$language,$type, get_field('thumbnail',$post_id)) : '';
                                $file_url = (get_field('file_url',$post_id)) ? get_file_path('library',$language,$type, get_field('file_url',$post_id)) : '';
                                if($type == "flyers" || $type == "brochure" || $type == "products"){
                                    $push['file_url'] = $file_url;
                                    if($file_url!="")
                                        $data['favorite_posts'][] =  $push;
                                } else if($type == "presentation") {
                                    $presentation_link = (get_field('presentation_link',$post_id)) ? get_field('presentation_link',$post_id) : "";
                                    $push['file_url'] = ($file_url!="") ? $file_url : $presentation_link;
                                    $data['favorite_posts'][] = $push;
                                } else if($type == "socialpost") {
                                    $push['image'] = (get_field('image',$post_id)) ? get_file_path('library',$language,$type, get_field('image',$post_id)) : '';
                                    $data['favorite_posts'][] = $push;
                                }
                            }
                        } 
                    }
                }     
            }        
        }    
    }    
    else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['no_data'], 'getbookmark-msg', 'no-data',$language);
    }
    $json['data'] = $data;
    if(sizeof(($data['favorite_posts']))){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['success'], 'getbookmark-msg', 'success',$language);   
    }
    $json['data']['total_pages'] = ($total_favorites<$post_per_page) ? 1 : ceil(($total_favorites / $post_per_page));
    $json['data']['current_page'] = $offset + 1;
    return get_200_success( $json );
}

function getRecent($request_data) {
    global $wpdb, $message_global;
    $json = $data = array();
    $json['data'] = array();
    $json['message'] ='';
    $user_id = 0;
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getrecent']['user_id_not_provided'], 'getrecent-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    $user_id = $request_data['user_id'];

    $user_id = intval( $request_data['user_id'] );
    if( !get_user_by('ID', $user_id) ) {    
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getrecent']['user_not_exsist'], 'getrecent-msg', 'user-not-exsist',$language);
        return get_400_error( $json );
    }    

    $recent_posts = $sliced_arr = array();
    $recent_posts = get_user_meta($user_id, 'recent_posts',true);
    if($recent_posts) {
        if(sizeof( $recent_posts )) {
            foreach ($recent_posts as $english_id) {
                // we'll skip iteration if post status is not publish
                if(get_post_status($english_id)!="publish")
                    continue;
                $post_type = get_post_type($english_id);
                $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
                $type = (get_field('select_type',$post_id)) ? get_field('select_type',$post_id) : '';
                $supported_types = array("flyer","flyers","brochure","products","presentation");
                $supported_post_types = array("discover","library");  
                if((in_array($post_type,$supported_post_types)) && (in_array($type,$supported_types))) {

                    $title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                    $push = array('post_id' => $post_id,"type"=>$type,"title"=>$title);
                    if($post_type=="discover") {
                        if($type == "flyer")
                            $push['image'] = (get_field('thumbnail_image',$post_id)) ? get_file_path('discover',$language,$type,get_field('thumbnail_image',$post_id)) : '';
                        else
                            $push['image'] = (get_field('banner_image',$post_id)) ? get_field('banner_image',$post_id) : '';

                        $push['file_url'] = (get_field('file_url',$post_id)) ? get_file_path('discover',$language,$type,get_field('file_url',$post_id)) : '';
                        if($push['file_url']!="")
                            $data[] = $push;
                    } else {
                        $push['image'] = (get_field('thumbnail',$post_id)) ? get_file_path('library',$language,$type,get_field('thumbnail',$post_id)): '';
                        if($type == "flyers" || $type == "brochure" || $type == "products"){                            
                            $push['file_url'] = (get_field('file_url',$post_id)) ? get_file_path('library',$language,$type,get_field('file_url',$post_id)) : '';
                            if($push['file_url']!="")
                                $data[] =  $push;
                        } else if($type == "presentation"){
                            $presentation_link = (get_field('presentation_link',$post_id)) ? get_field('presentation_link',$post_id) : "";
                            $file_url = (get_field('file_url',$post_id)) ? get_file_path('library',$language,$type,get_field('file_url',$post_id)) : '';
                            $push['file_url'] = ($file_url!="") ? $file_url : $presentation_link;
                            $data[] =  $push;
                        }
                    }
                } 
            }
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getrecent']['success'], 'getrecent-msg', 'success',$language);
        }
    }
    else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getrecent']['no_data'], 'getrecent-msg', 'no-data',$language);
    }
    $json['data'] = $data;
    return get_200_success( $json );
}


/**
 *  Purpose: to delete multiple recent
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['post_id'] (Comma separated string) (require)
 *  REQUEST @param  = $request_data['user_id'] (int) (required)
 */

function DeleteRecent( $request_data ){

    global $wpdb, $message_global;
    $json = $data = array();
    $json['data'] = array();
    $json['message'] ='';
    $user_id = 0;
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['deleterecent']['user_id_not_provided'], 'deleterecent-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    $user_id = $request_data['user_id'];

    $user_id = intval( $request_data['user_id'] );
    if( !get_user_by('ID', $user_id) ) {    
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['deleterecent']['user_not_exsist'], 'deleterecent-msg', 'user-not-exsist',$language);
        return get_400_error( $json );
    }   

    if( empty( $request_data['post_id'] ) ) {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['deleterecent']['post_id_not_provided'], 'deleterecent-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $posts_id = explode(',', $request_data['post_id']);
        $data['post_id'] = $posts_id =array_map('intval', $posts_id); 
    }  

    
    
    foreach ($posts_id as $post_id) {
        $recent_posts = $sliced_arr = array();
        
        if(get_user_meta( $user_id, 'recent_posts', true )) {
            $recent_posts = get_user_meta($user_id, 'recent_posts',true);
            // removing fom array if it exist
            if (in_array($post_id, $recent_posts)) {
                $element_position = array_search($post_id,$recent_posts);
                if($element_position ==0) {
                    array_shift($recent_posts); 
                } else {
                    array_splice($recent_posts,$element_position,1); 
                }
                update_user_meta( $user_id, 'recent_posts', $recent_posts );
                $json['data'] = $data;
                $json['data']['recent_posts'] = $recent_posts;
            }
        }               
    }

    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['deleterecent']['delete_success'], 'deleterecent-msg', 'delete-success',$language);
    return get_200_success( $json );
}

/**
 *  Purpose: to set devicetoken 
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['user_id'] (int) (required)
 *  REQUEST @param  = $request_data['device_token'] (string) (required)
 *  
 *  REQUEST @param  = $request_data['os_type'] (int) (required)
 */

function setDeviceToken( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $message_global, $wpdb;
    $json['data'] = array();
    $user_id = $device_token = $os_type = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setdevicetoken']['user_id_not_provided'], 'setdevicetoken-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['device_token'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setdevicetoken']['device_token_not_provided'], 'setdevicetoken-msg', 'device-token-not-provided',$language);
        return get_400_error( $json );
    }
    
    if( empty( $request_data['os_type'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setdevicetoken']['os_type_not_provided'], 'setdevicetoken-msg', 'os-type-not-provided',$language);
        return get_400_error( $json );
    }

    $user_id = $request_data['user_id'];
    $device_token = $request_data['device_token'];
    $os_type = $request_data['os_type'];
    if( !empty( $request_data['device_token'] ) )
    {
        $prefix = $wpdb->prefix;
        $tablename = $prefix.'device_token';
        $token_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename where device_token = '". $device_token ."'" );
        if($token_count > 0) {
            $wpdb->delete( $tablename, array( 'device_token' => $device_token ) );
        }
        $query_args = array( 
                        'user_id' => (int)$user_id, 
                        'device_token' => $device_token,
                        'os_type' => $os_type
                    );
        $wpdb->insert( $tablename, $query_args );
        $json['data']['user_id'] = (int)$user_id;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setdevicetoken']['success'], 'setdevicetoken-msg', 'success',$language);
        return get_200_success( $json );
    }
}

/**
 *  Purpose: to set devicetoken 
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  REQUEST @param  = $request_data['device_token'] (string) (optional)
 *  
 */

function signOut($request_data) {
    // INITIALIZATION START
    $json = array();
    global $message_global;
    $json['data'] = array();
    $json['message'] = '';
    $user_id = $device_token = $os_type = '';
   
    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string',$message_global['signout']['user_id_not_provided'], 'signout-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['device_token'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string',$message_global['signout']['device_token_not_provided'], 'signout-msg', 'device-token-not-provided',$language);
        return get_200_success( $json );
    }
    
    if( !empty( $request_data['device_token'] ) ) {
        $user_id = $request_data['user_id'];
        global $wpdb;
        $prefix = $wpdb->prefix;
        $tablename = $prefix.'device_token';
        $device_token = $request_data['device_token'];
        $token_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename where device_token = '". $device_token ."' AND user_id=".$user_id );
        if($token_count > 0)
        {
            $wpdb->delete( $tablename, array( 'device_token' => $device_token,'user_id' => $user_id ) );
        }
        $json['message'] = $message_global['signout']['user_logout'];
        return get_200_success( $json );
    }
}

function setRecentFlyer( $request_data ) {
    global $wpdb, $message_global;
    $json = $data = array();
    $json['data']  = array();
    $json['message'] = $user_id = $recents ='';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['user_id_not_provided'], 'setrecentflyer-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['user_id'] = $user_id = intval( $request_data['user_id'] );
        if( !get_user_by('ID', $user_id) ) {    
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['user_not_exsist'], 'setrecentflyer-msg', 'user-not-exsist',$language);
            return get_400_error( $json );
        }
    }

    if( empty( $request_data['post_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['post_id_not_provided'], 'setrecentflyer-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $data['post_id'] = $post_id = intval( $request_data['post_id'] ); 
    }   

    // THIS CHECK IS ADDED TO ALLOW ONLY ENLISH POST ADDITION TO BOOKMARKS
    $english_post_id = 0;
    $english_post_id = icl_object_id($post_id, 'library', false);
    if($english_post_id) {
        if($english_post_id!=$post_id) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['can_not_be_added'], 'setrecentflyer-msg', 'can-not-be-added',$language);
            return get_400_error( $json );
        }
    }

    $english_post_id = 0;
    $english_post_id = icl_object_id($post_id, 'discover', false);
    if($english_post_id) {
        if($english_post_id!=$post_id) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['can_not_be_added'], 'setrecentflyer-msg', 'can-not-be-added',$language);
            return get_400_error( $json );
        }
    }
    // THIS CHECK IS ADDED TO ALLOW ONLY ENLISH POST ADDITION TO BOOKMARKS

    $postget = get_post( $post_id );
    if(!$postget) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['post_not_exsist'], 'setrecentflyer-msg', 'post-not-exsist',$language);
        return get_400_error( $json );
    }

    $type = get_field('select_type' , $post_id );
    $post_type = $postget->post_type;
    $post_status = $postget->post_status;
    $supported_types = array("flyer","flyers","brochure","products","presentation");
    $supported_post_types = array("discover","library");  
    if((!in_array($post_type,$supported_post_types)) || (!in_array($type,$supported_types))) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['can_not_be_added'], 'setrecentflyer-msg', 'can-not-be-added',$language);
        return get_400_error( $json );
    }

    if($post_status!="publish") {
        $recent_posts = array();
        $recent_posts = get_user_meta( $user_id, 'recent_posts',true);
        $recents = array_search( $post_id, $recent_posts, true); // true for the strict search
        if( is_int($recents) ) {
            array_splice( $recent_posts, $recents, 1 );    
            update_user_meta( $user_id, 'recent_posts', $recent_posts );
        }
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['post_status_error'], 'setrecentflyer-msg', 'post-status-error',$language);
        return get_400_error( $json );   
    }
            
    $recent_posts  = array();
    if(get_user_meta( $user_id, 'recent_posts', true )) {
        $recent_posts = get_user_meta( $user_id, 'recent_posts', true );
        if (in_array($post_id, $recent_posts)) {
            unset($recent_posts[array_search($post_id,$recent_posts)]);
        }
        if(sizeof($recent_posts)>11) {
            array_pop($recent_posts);
        }
        array_unshift( $recent_posts, $post_id );    
    } else {
        $recent_posts[] = $post_id;
    }                
    update_user_meta( $user_id, 'recent_posts', $recent_posts );
    $json['data'] = $data;
    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['setrecentflyer']['added_success'], 'setrecentflyer-msg', 'added-success',$language);
    return get_200_success( $json );
}


/**
 *  Purpose: To store current language setting of a user
 *  Request type : POST  
 *
 *  REQUEST @param  = $request_data['user_id'] (int) (require)
 *  REQUEST @param  = $request_data['language_code'] (int) (require)
 *  
 */
function changeLanguage( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $language_code = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

   if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['user_not_found'], 'changelanguage-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $acf_field_id = "user_".$user_id; 
    }

    if(!isset($request_data['language_code'])){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['language_code_not_provided'], 'changelanguage-msg', 'language-code-not-provided',$language);
        return get_400_error( $json );
    }

    $language_code = $request_data['language_code'];
    $allowed_languages = array('zh-hans','zh-hant','en','ja','ko','pt-pt','ru','es');
    if(in_array($language_code, $allowed_languages)) {
        update_field('current_language',$language_code,$acf_field_id);
        $data = get_user_obj($user_id);
        $json['data']  = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['success'], 'changelanguage-msg', 'success',$language);
        return get_200_success( $json );
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['invalid_language_code_provided'], 'changelanguage-msg', 'invalid-language-code-provided',$language);
        return get_400_error( $json );
    }
}

function getFavoriteList( $request_data ) {
    global $message_global;
    $json = array();
    $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_id_not_provided'], 'getbookmark-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    $user_id = $request_data['user_id'];
    $user_id = intval( $request_data['user_id'] );
    if( !get_user_by('ID', $user_id) ) {    
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_not_exsist'], 'getbookmark-msg', 'user-not-exsist',$language);
        return get_400_error( $json );
    }
    $favorites = get_user_meta( $user_id, 'favorite',true );
    if(!$favorites)
        $favorites = array();
    $json['data']['favorites'] = $favorites;
    return get_200_success( $json );
}

function getUserStatus($request_data) {
    global $message_global;
    $json = array();
    $json['message'] = '';
    $json['data'] = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_id_not_provided'], 'getbookmark-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    $user_id = $request_data['user_id'];
    $user_id = intval( $request_data['user_id'] );
    if( !get_user_by('ID', $user_id) ) {    
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getbookmark']['user_not_exsist'], 'getbookmark-msg', 'user-not-exsist',$language);
        return get_400_error( $json );
    } else {
        $json['data'] = get_user_obj($user_id);
        return get_200_success( $json );
    }
}


function changeNotificationLanguage( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $language_code = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

   if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['user_not_found'], 'changelanguage-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $acf_field_id = "user_".$user_id; 
    }

    if(!isset($request_data['language_code'])){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['language_code_not_provided'], 'changelanguage-msg', 'language-code-not-provided',$language);
        return get_400_error( $json );
    }

    $language_code = $request_data['language_code'];
    $allowed_languages = array('zh-hans','zh-hant','en','ja','ko','pt-pt','ru','es');
    if(in_array($language_code, $allowed_languages)) {
        update_field('notification_language',$language_code,$acf_field_id);
        $data = get_user_obj($user_id);
        $json['data']  = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['success'], 'changelanguage-msg', 'success',$language);
        return get_200_success( $json );
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['changelanguage']['invalid_language_code_provided'], 'changelanguage-msg', 'invalid-language-code-provided',$language);
        return get_400_error( $json );
    }
}