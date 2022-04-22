<?php
/**
 *  Discover section show for the Both logged in or non logged in
 *  Purpose: Discover list of all posts.  
 *  @link api/discover_functions.php
 *  
 *  Method = "POST"
 *  $request_data['user_id'] int ( optional )
 *  $request_data['paged'] int (if set we can use paging) ( require to pagination) 
 *  
 */

function getDiscoverList( $request_data ) {

    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = $categories = array();
    $json['message'] = '';
    $user_id = $paged = $guest = $total_discover_posts = 0;
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $default_language = wpml_get_default_language(); // will return 'en'

    $paged = (isset($request_data['paged'])) ? (int)$request_data['paged'] : 1;
    $post_per_page = (int)get_option( 'posts_per_page');

    if( empty( $request_data['user_id'] ) ) { 
        $guest = 1;
    } else {
        $user_id = $request_data['user_id'];
        $guest = (!get_user_by('id', $user_id)) ? 1 : 0;
    }
    
    $field = get_acf_option('discover_option', $language);
    if( empty( $field ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverlist']['show_option_disabled'], 'getdiscoverlist-msg', 'options-disabled',$language);
        return get_400_error( $json );
    }
    
    $args = array( 'post_type' => 'discover', 'post_status' => 'publish', 'posts_per_page' => -1 );
    if($guest == 1){
        $args['meta_query'] = array( 'relation' => 'AND', 
                                array('key' => 'select_type', 'compare' => 'IN', 'value' => $field ),
                                array( 'key' => 'show_to_guest_users','compare' => '=', 'value' =>  1)
                        );
    } else {
        $field[]='poll';
        $field[]='survey';
        $args['meta_query'] = array( 'relation' => 'AND', 
                                array('key' => 'select_type', 'compare' => 'IN', 'value' => $field )
                        );   
    }
    

    $query = new WP_Query($args);

    /*print_r($query);*/

    $total_discover_posts = $query->post_count;

    $args['posts_per_page'] = $post_per_page;
    $args['paged'] = $paged;

    $discover_posts = new WP_Query($args); 
    if( $discover_posts->have_posts() ) {
        $post_counter = 0;    
        while ( $discover_posts->have_posts() ) {
            $discover_posts->the_post(); 

            $english_id = get_the_ID();
            // FETCHING TRANSLATION POST ID
            $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
            $type = get_field('select_type',$post_id);
            $product_category = "";
             if( get_field( 'discover_category_field', $post_id ) ) {
                $discover_category = get_field( 'discover_category_field', $post_id );
                if(!empty($discover_category)){
                    $translated_id = apply_filters( 'wpml_object_id',$discover_category->term_id, 'discover-category', TRUE, $language);                
                    $categories = get_translated_category_name($translated_id, $post_id,'discover-category','name');
                }
            }
            $title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
            $answer = $poll_expiry = "";
            $current_users_answer_index = $matched_value = "";
            $short_description = ( get_field('short_description',$post_id) ) ? get_field('short_description',$post_id) : "";
            $long_description  = ( get_field('long_description',$post_id) ) ? get_field('long_description',$post_id) : "";
            $long_description_nightmode  = ( get_field('long_description_nightmode',$post_id) ) ? get_field('long_description_nightmode',$post_id) : "";
            $banner_image      = ( get_field('banner_image',$post_id) ) ? get_field('banner_image',$post_id) : "";
            $thumbnail         = ( get_field('thumbnail_image',$post_id) ) ? get_file_path('discover',$language,$type,get_field('thumbnail_image',$post_id)) : "";
            $video_link        = ( get_field('video_link',$post_id) ) ? get_field('video_link',$post_id) : "";
            $video_thumbnail   = ( get_field('video_thumbnail',$post_id) ) ? get_field('video_thumbnail',$post_id) : "";
            $file_url          = ( get_field('file_url',$post_id) ) ? get_file_path('discover',$language,$type,get_field('file_url',$post_id)) : "";
            $answer_type       = ( get_field('answer_type',$post_id) ) ? get_field('answer_type',$post_id) : "";
            $poll_expiry_date  = ( get_field('poll_expiry_date',$post_id) ) ? get_field('poll_expiry_date',$post_id) : "";

	        if( $poll_expiry_date != '') {
                $current_time = strtotime( date("Y-m-d") );
                $poll_expiry_date_str = strtotime( $poll_expiry_date );
                if( $poll_expiry_date_str >= $current_time ) {
                    if( $type == "poll") {
                        $poll_expiry =  apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverlist']['poll_end_text'], 'getdiscoverlist-msg', 'poll-end-text',$language) . $poll_expiry_date;
                    }
                    if( $type == "survey"){
                        $poll_expiry =  apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverlist']['survey_end_text'], 'getdiscoverlist-msg', 'survey-end-text',$language) . $poll_expiry_date;
                    }
                }
            }

            if( $answer_type != "" ) {
                $answer = ($answer_type == 'text') ? get_field('text_answers',$post_id) : get_field('image_answers',$post_id);
            }

            $english_version = icl_object_id($post_id, 'discover', true, $default_language);
            if( $guest != 1 && $type == 'poll' ) {
                $matched_value = "";
                $current_users_answer_index = ( get_post_meta( $english_version , 'users_answer_'.$user_id, true) ) ? get_post_meta( $english_version , 'users_answer_'.$user_id, true) : "";

                if( $current_users_answer_index != "" ){
                    $count = 1;
                    foreach ( $answer as $value ) {
                        if($count == $current_users_answer_index ) {
                            $matched_value = (isset($value['image'])) ? $value['image'] : $value['text'];
                            $matched = 1;
                               break;
                        }
                        $count++;
                    }
                }
                $percent_answer = get_poll_answer_precent($english_version);
            }

            if( $guest != 1 && $type == 'survey' ) {
                $matched_value = "";
                $current_users_answer_index = ( get_post_meta( $english_version , 'users_survey_answer_'.$user_id, true) ) ? get_post_meta( $english_version , 'users_survey_answer_'.$user_id, true) : "";

                if( $current_users_answer_index != "" && $current_users_answer_index >= 1){
                   $count = 1;
                   foreach ($answer as $value) {
                       if($count == $current_users_answer_index ) {
                           $matched_value = (isset($value['image'])) ? $value['image'] : $value['text'];
                           $matched = 1;
                               break;
                       }
                       $count++;
                   }
               }
                $percent_answer = get_survey_answer_precent($english_version);
            }
            
            if( is_array ($answer) && is_array ($percent_answer) ){
                foreach ($answer as $key_com => $value) {
                    foreach ($percent_answer as $key_answer => $value_answer) {
                        if($key_com == $key_answer -1){
                            $answer[$key_com]['percentage'] = (int)$value_answer;
                        }
                    }
                }    
            }
            if( is_array ($answer) && empty($percent_answer) ){
            foreach ($answer as $key_com => $value) {
                        $answer[$key_com]['percentage'] = 0;
                }
            }    
            

            $push = array('post_id'=> $english_id,'type' => $type, 'category' => $categories, 'title' => $title);
            switch( $type ){
                case 'news':
                    $push['image']             = $banner_image;
                    $push['short_description'] = $short_description;
                    $push['long_description']  = $long_description;
                    $push['long_description_nightmode']  = $long_description_nightmode;
                    $push['shareable_link']  = urldecode(get_the_permalink($post_id));
                    break;
                case 'flyer':
                    $push['image']             = $thumbnail;
                    $push['file_url']          = $file_url;
                    $push['short_description'] = $short_description;
                    break;
                case 'poll':
                    $push['image']             = $banner_image;
                    $push['short_description'] = $short_description;
                    $push['poll_question']     = ( get_field('poll_question',$post_id) ) ? get_field('poll_question', $post_id) : '';
                    $push['question']     = ( get_field('poll_question',$post_id) ) ? get_field('poll_question', $post_id) : '';
                    $push['answers_type']      = $answer_type;
                    $push['poll_answers']      = $answer;
                    $push['answers']      = $answer;
                    $push['users_answer']      = $matched_value;
                    $push['expiry_date']       = $poll_expiry;
                    break;
                case 'survey':
                    $push['image']             = $banner_image;
                    $push['short_description'] = $short_description;
                    $push['question']     = ( get_field('poll_question',$post_id) ) ? get_field('poll_question', $post_id) : '';
                    $push['answers_type']      = $answer_type;
                    $push['answers']           = $answer;
                    $push['users_answer']      = $matched_value;
                    $push['expiry_date']       = $poll_expiry;
                    break;
                case 'video':                   
                    $push['short_description'] = $short_description;
                    $push['video_thumbnail']   = $video_thumbnail;
                    $push['video_link']        = $video_link;
                break;
                default:
                    break;
            }
            if($type == "flyer" && $file_url == "")
                continue;
            if(($type == "poll" && $guest == 1) || ($type == "poll" && $poll_expiry == ""))
                continue;
            if(($type == "survey" && $guest == 1) || ($type == "survey" && $poll_expiry == ""))
                continue;
            $data[$post_counter] =  $push;
            $post_counter++;
        }
        wp_reset_query();
    }

    $total_post_count = (int)($total_discover_posts);
    $total_pages = ceil($total_post_count / $post_per_page);
    $json['data']['total_pages'] = $total_pages;
    $json['data']['current_page'] = $paged;
    $json['data']['posts'] = $data;
    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverlist']['no_data_found'], 'getdiscoverlist-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverlist']['success'], 'getdiscoverlist-msg', 'success',$language);
    }
    return get_200_success( $json );
}

function addPollAnswers( $request_data ){
    global $wpdb, $message_global;
    $json = array();
    $json['message'] = '';
    $json['data'] = $data['post'] = $push = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $default_language = wpml_get_default_language(); // will return 'en'
    $user_id = $text = "";
    if( !isset( $request_data['poll_id'] ) || $request_data['poll_id'] == "" ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['poll_id_not_provided'], 'addpollanswers-msg', 'poll-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $english_id = $request_data['poll_id'];
        $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
        $english_version = icl_object_id($post_id, 'discover', true, $default_language);
        $post_type  = get_post_type($post_id);
        $selected   = get_field('select_type', $post_id);
        if($post_type != 'discover' && $selected != 'poll' ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['poll_not_found'], 'addpollanswers-msg', 'poll-not-found',$language);
            return get_400_error( $json );
        }
    }

    if( isset( $request_data['user_id'] )  && $request_data['user_id'] != '' ) { 
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['user_not_found'], 'addpollanswers-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $user_name = $user->data->user_login;
        if( $user_name == "" ) {
            $user_name = $user->data->user_email;
        }
    }else{
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['user_id_not_provided'], 'addpollanswers-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }

    if( !isset( $request_data['text'] ) || $request_data['text'] == "" ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['answer_not_provided'], 'addpollanswers-msg', 'answer-not-provided',$language);
        return get_400_error( $json );
    } else {
        $text = $request_data['text'];
    }

    $current_users_answer = ( get_post_meta( $english_version , 'users_answer_'.$user_id, true) ) ? get_post_meta( $english_version , 'users_answer_'.$user_id, true) : "";
    if( $current_users_answer != "" && $current_users_answer >= 1 ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['already_submitted_answer'], 'addpollanswers-msg', 'already-submitted-answer', $language );
        return get_400_error( $json ); 
    }
    $answer_type = ( get_field('answer_type',$post_id ) ) ? get_field('answer_type',$post_id) : "";
    if($answer_type != ""){
        $answer = ( $answer_type == 'text' ) ? get_field( 'text_answers', $post_id ) : get_field( 'image_answers', $post_id );
    }
    $matched = 0;
    $matched_index = "";
    $poll_answers_options      = $answer;
    $count = 1;
    foreach ($poll_answers_options as $value) {
        if( ( isset($value['image'] ) && strcmp(trim($value['image']), trim($text)) == 0 ) ||  ( isset($value['text'] ) && strcmp($value['text'], $text) == 0 ) ) {
            $matched = 1;
            $matched_index = $count;
            break;
        }
        $count++;
    }


    if($matched) {
        $new_post_id = wp_insert_post( array (
               'post_type' => 'poll-answer',
               'post_title' => $user_name.' has submitted an answer',
               'post_content' => $text,
               'post_status' => 'publish',
               'post_author' => $user_id
            ) );
            if( $new_post_id ) {
                update_post_meta( $english_version, 'users_answer_'.$user_id, $matched_index); 
                update_post_meta( $post_id, 'users_answer_'.$user_id, $matched_index); 
                update_post_meta( $new_post_id, 'poll_answer', $text);
                update_post_meta( $new_post_id, 'poll_answer_index', $matched_index);
                update_post_meta( $new_post_id, 'answer_poll_id', $post_id); 
                update_post_meta( $new_post_id, 'answer_user_id', $user_id); 
            }
            $percent_answer = get_poll_answer_precent($english_version);
            $poll_answer = array();
            if( !empty( $percent_answer ) ){
                foreach ($poll_answers_options as $key => $value) {
                    foreach ($percent_answer as $key_answer => $value_answer) {
                        if($key == $key_answer -1){
                            $poll_answers_options[$key]['percentage'] = (int)$value_answer;
                        }
                    }
                }    
            }
            $json['data']['post_id'] = (int)$english_id;
            $json['data']['users_answer'] = $text;
            $json['data']['user_id'] = (int)$user_id;
            $json['data']['poll_answers']  = $poll_answers_options;
            $json['data']['answers']  = $poll_answers_options;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['answer_submitted'], 'addpollanswers-msg', 'answer-submitted', $language);
            return get_200_success($json); 
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addpollanswers']['add_proper_answer'], 'addpollanswers-msg', 'add-proper-answer',$language);
            return get_400_error( $json );
    }
}

/**
 *  Discover section show for the Both logged in or non logged in
 *  Purpose: Discover Details of all posts.
 *  argument Needed for this 'post_id', 
 *  @link api/discover_functions.php
 *
 */
function getDiscoverDetail( $request_data ) {
    // INITIALIZATION START
    global $wpdb, $message_global;
    $json = array();
    $modified_content = $json['message'] = '';
    $categories = $json['data'] = $data['post'] = $push = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( !isset( $request_data['post_id'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverdetail']['post_id_not_provided'], 'getdiscoverdetail-msg', 'post-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $english_id = $request_data['post_id'];
    }
    
    $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
    $single_post = get_post( $post_id );
    if( !isset( $single_post ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverdetail']['post_not_found'], 'getdiscoverdetail-msg', 'post-not-found',$language);
        return get_400_error( $json );
    } else {
        if($single_post->post_type != "discover" || $single_post->post_status != "publish") {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverdetail']['post_not_found'], 'getdiscoverdetail-msg', 'post-not-found',$language);
            return get_400_error( $json );
        }
    }

    /** GETTING THE FIELD OF THE CURRUNT POST ID  **/
    $type  =  get_field('select_type' , $post_id );
    if( get_field( 'discover_category_field', $post_id ) ) {
        $discover_category = get_field( 'discover_category_field', $post_id );
        $translated_id = apply_filters( 'wpml_object_id',$discover_category->term_id, 'discover-category', TRUE, $language);                
        $categories = get_translated_category_name($translated_id, $post_id,'discover-category','name');
    }
    $title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');

    $short_description = (get_field('short_description',$post_id)) ? get_field('short_description',$post_id) : "";
    $long_description = (get_field('long_description',$post_id)) ? get_field('long_description',$post_id) : "";
    $long_description_nightmode = (get_field('long_description_nightmode',$post_id)) ? get_field('long_description_nightmode',$post_id) : "";
    $banner_image = (get_field('banner_image',$post_id)) ? get_field('banner_image',$post_id) : "";
    $thumbnail = (get_field('thumbnail_image',$post_id)) ? get_file_path('discover',$language,$type,get_field('thumbnail_image',$post_id)) : "";
    $video_link = (get_field('video_link',$post_id)) ? get_field('video_link',$post_id) : "";
    $video_thumbnail = (get_field('video_thumbnail',$post_id)) ? get_field('video_thumbnail',$post_id) : "";
    $file_url = ( get_field('file_url',$post_id) ) ? get_file_path('discover',$language,$type,get_field('file_url',$post_id)) : "";
    $answer_type = (get_field('answer_type',$post_id)) ? get_field('answer_type',$post_id) : "";
    if($answer_type!=""){
        $answer = ($answer_type == 'text') ? get_field('text_answers',$post_id) : get_field('image_answers',$post_id);
    }

    $push = array( 'post_id' => (int)$english_id, 'type' => $type, 'category' => $categories, 'title' => $title);
   
    switch( $type ){
        case 'news':
            $original_time = get_the_time('U', $post_id);
            $modified_time = get_the_modified_time('U', $post_id);
            if ($modified_time >= $original_time + 86400) {
                $updated_time = get_the_modified_time('h:i a', $post_id);
                $updated_day = get_the_modified_time('F jS, Y', $post_id);
                $modified_content = 'Updated at '.$updated_time.' on '. $updated_day;
            } else {
                $updated_time = get_the_time('h:i a', $post_id);
                $updated_day = get_the_time('F jS, Y', $post_id);
                $modified_content = 'Updated at '.$updated_time.' on '. $updated_day;
            }
            $push['image'] = $banner_image;
            $push['last_updated_date'] = $modified_content;
            $push['short_description'] = $short_description;
            $push['long_description'] = $long_description;
            $push['long_description_nightmode'] = $long_description_nightmode;
            $push['shareable_link']  = urldecode(get_the_permalink($post_id));
        break;
        case 'flyer':
            $push['image'] = $thumbnail;
            $push['file_url'] = $file_url;
            $push['short_description'] = $short_description;
        break;
        case 'poll':
            $push['banner_image'] = $banner_image;
            $push['short_description'] = $short_description;
            $push['poll_question'] = ( get_field('poll_question', $post_id) ) ? get_field('poll_question', $post_id) : '';
            $push['poll_answers'] = $answer;
        break;
        case 'survey':
            $push['banner_image'] = $banner_image;
            $push['short_description'] = $short_description;
            $push['survey_question'] = ( get_field('poll_question', $post_id) ) ? get_field('poll_question', $post_id) : '';
            $push['survey_answers'] = $answer;
        break;
        case 'video':
            $push['short_description'] = $short_description;
            $push['video_thumbnail']  = $video_thumbnail;
            $push['video_link'] = $video_link;
        break;
        case 'pdf':
            $push['image'] = $banner_image;
            $push['file_url'] = $file_url;
            $push['short_description'] = $short_description;
        break;
        default:
        break;
    }   
    $data = $push;
    $json['data'] = $data;
    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['getdiscoverdetail']['discover_detail_success'], 'getdiscoverdetail-msg', 'discover-detail-success',$language);
    return get_200_success($json);
}

function delete_associated_media( $id ) {

    global $post_type;   
    if($post_type !== 'poll-answer') return;
    $answer_poll_id = get_post_meta( $id , 'answer_poll_id' , true );
    $user_id        = get_post_meta( $id , 'answer_user_id' , true );
    delete_post_meta( $answer_poll_id, 'users_answer_'.$user_id );
}
add_action( 'before_delete_post', 'delete_associated_media' );

function my_trash_action( $post_id ) {
   if ( 'poll-answer' != get_post_type( $post_id )) return;
     //Do Stuff....
    $answer_poll_id = get_post_meta( $post_id , 'answer_poll_id' , true );
    $user_id        = get_post_meta( $post_id , 'answer_user_id' , true );
    delete_post_meta( $answer_poll_id, 'users_answer_'.$user_id );
}
add_action( 'trashed_post', 'my_trash_action' );

function addSurveyAnswers( $request_data ){
    global $wpdb, $message_global;
    $json = array();
    $json['message'] = '';
    $json['data'] = $data['post'] = $push = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $default_language = wpml_get_default_language(); // will return 'en'
    $user_id = $text = "";
    if( !isset( $request_data['survey_id'] ) || $request_data['survey_id'] == "" ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['survey_id_not_provided'], 'addsurveyanswers-msg', 'survey-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $english_id = $request_data['survey_id'];
        $post_id = apply_filters( 'wpml_object_id', $english_id, 'discover', TRUE, $language);
        $english_version = icl_object_id($post_id, 'discover', true, $default_language);
        $post_type  = get_post_type($post_id);
        $selected   = get_field('select_type', $post_id);
        if($post_type != 'discover' && $selected != 'survey' ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['survey_not_found'], 'addsurveyanswers-msg', 'survey-not-found',$language);
            return get_400_error( $json );
        }
    }

    if( isset( $request_data['user_id'] )  && $request_data['user_id'] != '' ) { 
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['user_not_found'], 'addsurveyanswers-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $user_name = $user->data->user_login;
        if( $user_name == "" ) {
            $user_name = $user->data->user_email;
        }
    }else{
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['user_id_not_provided'], 'addsurveyanswers-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }

    if( !isset( $request_data['text'] ) || $request_data['text'] == "" ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['answer_not_provided'], 'addsurveyanswers-msg', 'answer-not-provided',$language);
        return get_400_error( $json );
    } else {
        $text = $request_data['text'];
    }

    $current_users_answer = ( get_post_meta( $english_version , 'users_survey_answer_'.$user_id, true) ) ? get_post_meta( $english_version , 'users_survey_answer_'.$user_id, true) : "";
    if( $current_users_answer >= 1 && $current_users_answer != "" ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['already_submitted_answer'], 'addsurveyanswers-msg', 'already-submitted-answer', $language );
        return get_400_error( $json ); 
    }
    $answer_type = ( get_field('answer_type',$post_id ) ) ? get_field('answer_type',$post_id) : "";
    if($answer_type != ""){
        $answer = ( $answer_type == 'text' ) ? get_field( 'text_answers', $post_id ) : get_field( 'image_answers', $post_id );
    }
    $matched = 0;
    $matched_index = "";
    $survey_answers_options      = $answer;
    $count = 1;
    foreach ($survey_answers_options as $value) {
        if( ( isset($value['image'] ) && strcmp(trim($value['image']), trim($text)) == 0 ) ||  ( isset($value['text'] ) && strcmp($value['text'], $text) == 0 ) ) {
            $matched = 1;
            $matched_index = $count;
            break;    
        }
        $count++;
    }
    if($matched) {
        $new_post_id = wp_insert_post( array (
               'post_type' => 'survey-answer',
               'post_title' => $user_name.' has submitted an answer',
               'post_content' => $text,
               'post_status' => 'publish',
               'post_author' => $user_id
            ) );
            if( $new_post_id ) {
                update_post_meta( $english_version, 'users_survey_answer_'.$user_id, $matched_index); 
                update_post_meta( $post_id, 'users_survey_answer_'.$user_id, $matched_index); 
                update_post_meta( $new_post_id, 'survey_answer', $text);
                update_post_meta( $new_post_id, 'survey_answer_index', $matched_index);
                update_post_meta( $new_post_id, 'answer_survey_id', $post_id); 
                update_post_meta( $new_post_id, 'answer_user_id', $user_id); 
            }
            $survey_answer = get_survey_answer_precent($english_version);
            $poll_answer = array();
            if( !empty($survey_answer ) ){
                foreach ($survey_answers_options as $key => $value) {
                    foreach ($survey_answer as $key_answer => $value_answer) {
                        if($key == $key_answer -1){
                            $survey_answers_options[$key]['percentage'] = (int)$value_answer;
                        }
                    }
                }    
            }
            $json['data']['post_id'] = (int)$english_id;
            $json['data']['users_answer'] = $text;
            $json['data']['user_id'] = (int)$user_id;
            $json['data']['answers']  = $survey_answers_options;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['answer_submitted'], 'addsurveyanswers-msg', 'answer-submitted', $language);
            return get_200_success($json); 
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['addsurveyanswers']['add_proper_answer'], 'addsurveyanswers-msg', 'add-proper-answer',$language);
            return get_400_error( $json );
    }
}

function get_poll_answer_precent($post_id) {
    if(isset($post_id)) {
        $total_options = 0;
        $answer_type = ( get_field('answer_type',$post_id) ) ? get_field('answer_type',$post_id) : "";
        if( $answer_type != "" ) {
            $options = ($answer_type == 'text') ? get_field('text_answers',$post_id) : get_field('image_answers',$post_id);
            $total_options = count($options);
            if($total_options){
                $option_arr = array_fill(1, $total_options,0);
                global $wpdb;
                $results = $wpdb->get_results("SELECT meta_key,meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE 'users_answer_%' AND post_id=$post_id ORDER BY meta_value ASC");
                if(($results)) {
                    $total_answers = count($results);
                    $total_percent = 100;
                    $each_answer_weightage = $total_percent / $total_answers;
                    foreach ($results as $result) {
                        $meta_key = $result->meta_value;
                        $option_arr[$meta_key] = ++$option_arr[$meta_key];
                    }
                    foreach( $option_arr as &$val ){ $val *= $each_answer_weightage; }
                    return $option_arr;
                }
            }
        }               
    }
}
function get_survey_answer_precent($post_id) {
    if(isset($post_id)) {
        $total_options = 0;
        $answer_type = ( get_field('answer_type',$post_id) ) ? get_field('answer_type',$post_id) : "";
        if( $answer_type != "" ) {
            $options = ($answer_type == 'text') ? get_field('text_answers',$post_id) : get_field('image_answers',$post_id);
            $total_options = count($options);
            if($total_options){
                $option_arr = array_fill(1, $total_options,0);
                global $wpdb;
                $results = $wpdb->get_results("SELECT meta_key,meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE 'users_survey_answer_%' AND post_id=$post_id ORDER BY meta_value ASC");
                if(($results)) {
                    $total_answers = count($results);
                    $total_percent = 100;
                    $each_answer_weightage = $total_percent / $total_answers;
                    foreach ($results as $result) {
                        $meta_key = $result->meta_value;
                        $option_arr[$meta_key] = ++$option_arr[$meta_key];
                    }
                    foreach( $option_arr as &$val ){ $val *= $each_answer_weightage; }
                    return $option_arr;
                }
            }
        }               
    }
}