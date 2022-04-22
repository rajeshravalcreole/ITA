<?php 

require_once '../../../../wp-load.php';

/* Constants used for push notifications */
define('FCM_URL', 'https://fcm.googleapis.com/fcm/send');
define('FCM_KEY', 'AAAA4YOG4zk:APA91bFuLXnoivPrlmjY6REN0IGRxdHLsvm1CAjaZYNxGSQf4YmFhsMtid-NFYM8QJp25SZCAchMZ5BvxjBB5h6B6wFvyr15LK3kBn8ibZwbr_P4_yHW6lPLfKGDrL-bFEqK7MUFuosO');

function library_update_notification() {
    $posts = array();
    global $wpdb;
    $prefix = $wpdb->prefix;    
    $table_notified_posts = $prefix . 'notified_posts';    
    $querystr = "SELECT id, post_id FROM $table_notified_posts WHERE post_type = 'library'";
    $posts = $wpdb->get_results( $querystr );
    $type_title = "";
    if ( !empty( $posts ) )
    {
        $users = users_settings_notification( 'library' );
        if($users) {
            $user_count = count( $users['user_detail'] );
            foreach( $posts as $post ) 
            {         
                $post_id = $post->post_id;                
                for( $j = 0; $j < $user_count; $j++ )
                {
                    $user_id = $users['user_detail'][$j]['user_id'];   

                    //***** CREATING NOTIFICATION ARRAY TO SEND NOTIFICATION ******
                    if( $users['user_detail'][$j]['device_token'] != ""  && $users['user_detail'][$j]['os_type']!="")
                    {
                        // SKIP ITERATION IF POST LANGUAGE DOES NOT MATCH USER'S NOTIFICATION LANGUAGE
                        $notification_language = "en";
                        $acf_field_id = "user_".$user_id;
                        $notification_language = (get_field('notification_language',$acf_field_id)) ? (get_field('notification_language',$acf_field_id)) : "en";
                        
                        $post_language = apply_filters( 'wpml_post_language_details','',$post_id);
                        if(isset($post_language)) {
                            if($post_language['language_code'] != $notification_language)
                                continue;
                        }
                        // SKIP ITERATION IF POST LANGUAGE DOES NOT MATCH USER'S NOTIFICATION LANGUAGE

                        $post_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                        $type = get_field('select_type', $post_id);

                        // condition as we're not going to send auto notification for social posts
                        if($type=="socialpost")
                            continue;
                        
                        $social_posts_category = $product_category = $flyer_category = "";
                        $short_description     = ( get_field( 'short_description', $post_id ) ) ? get_field('short_description', $post_id ) : "";
                        $thumbnail_image       = ( get_field( 'thumbnail', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'thumbnail', $post_id ) ) : "" ;
                        $presentation_link     = ( get_field( 'presentation_link', $post_id ) ) ? get_field( 'presentation_link', $post_id ) : "";
                        $banner_image          = ( get_field( 'banner_image', $post_id ) ) ? get_field( 'banner_image', $post_id ) : "";
                        $video_link            = ( get_field( 'video_url', $post_id ) ) ? get_field( 'video_url', $post_id ) : "" ;
                        $image                 = ( get_field( 'image', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'image', $post_id ) ) : "";
                        $video_thumbnail       = ( get_field( 'video_thumbnail', $post_id ) ) ? get_field( 'video_thumbnail', $post_id ) : "";
                        $file_url = ( get_field( 'file_url', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'file_url', $post_id ) ): "";
                        if( get_field( 'product_category', $post_id ) ) {
                            $product_category = get_field( 'product_category', $post_id );
                            $translated_id = apply_filters( 'wpml_object_id',$product_category->term_id, 'product-category', TRUE, $notification_language);
                            $product_category = get_translated_category_name($translated_id,$post_id,'product-category');
                        }
                        if( get_field( 'social_posts_category', $post_id ) ) {
                            $social_posts_category = get_field( 'social_posts_category', $post_id );
                            $translated_id = apply_filters( 'wpml_object_id',$social_posts_category->term_id, 'social-post-category', TRUE, $notification_language);
                            $social_posts_category = get_translated_category_name($translated_id,$post_id,'social-post-category');
                        }
                        if( get_field( 'flyer_category', $post_id ) ) {
                            $flyer_category = get_field( 'flyer_category', $post_id );
                            $translated_id = apply_filters( 'wpml_object_id',$flyer_category->term_id, 'flyer-category', TRUE, $notification_language);
                            $flyer_category = get_translated_category_name($translated_id,$post_id,'flyer-category');
                        }
                        $push = $product = $flyers = $socialpost = $presentation = array('post_id' => (int)$post_id,'title' => $post_title,'type'=>$type);

                        switch( $type ) {
                            case 'brochure':
                                    $push['image'] = $thumbnail_image;
                                    $push['short_description'] = $short_description;
                                    $push['file_url'] = $file_url;
                            break;
                            case 'flyers':
                                    $section_title = (get_acf_option('flyer_section_title',$notification_language)) ? get_acf_option('flyer_section_title',$notification_language) : "";
                                    $flyers['category'] = $flyer_category;
                                    $flyers['image'] = $thumbnail_image;
                                    $flyers['file_url'] = $file_url;
                            break;
                            case 'products':
                                    $section_title = (get_acf_option('product_section_title',$notification_language)) ? get_acf_option('product_section_title',$notification_language) : "";
                                    $product['category'] = $product_category;
                                    $product['image'] = $thumbnail_image;
                                    $product['file_url'] = $file_url;
                            break;
                            case 'socialpost':
                                $section_title = (get_acf_option('social_posts_section_title',$notification_language)) ? get_acf_option('social_posts_section_title',$notification_language) : "";
                                $socialpost['category'] = $social_posts_category;
                                $socialpost['image'] = $image;
                            break;
                            case 'video':
                                $push['short_description'] = $short_description;
                                $push['video_link'] = $video_link;
                                $push['video_thumbnail']   = $video_thumbnail;
                            break;
                            case 'presentation':
                                $section_title = (get_acf_option('presentation_section_title',$notification_language)) ? get_acf_option('presentation_section_title',$notification_language) : "";

                                if( $file_url != '' ) {
                                    $presentation['image'] = $thumbnail_image;
                                    $presentation['file_url'] = $file_url;
                                } else if( $presentation_link != '' ) {
                                    $presentation['image'] = $thumbnail_image;
                                    $presentation['file_url'] = $presentation_link;
                                }                  
                            break;
                            default:
                            break;
                        }

                        if( ( $type == "flyers" || $type == "brochure" || $type == "products" ) && $file_url == "" ){
                            continue;                        
                        }  
                        $data = array('type' => 'library','post_id'=>$post_id);
                        if( $type == "products" ){
                            $data['post'] = $product;
                            $type_title = "Product";
                        }
                        if( $type == "flyers" ) {
                            $data['post'] = $flyers;
                            $type_title = "Flyer";
                        }
                        if( $type == "socialpost" ) {
                            $data['post'] = $socialpost;    
                            $type_title = "Social post";
                        }
                        if( $type == "presentation" ) {
                            $data['post'] = $presentation;    
                            $type_title = "Presentation";
                        }
                        if( $type == "brochure" || $type == 'video' ) {
                            if($type == "brochure"){
                                $type_title = "Brochure";
                            }else{
                                $type_title = "Video";
                            }
                            $data['post'] = $push;    
                        }
                        $os_type = $users['user_detail'][$j]['os_type'];
                        //Title of the Notification.
                        $title = sprintf(__("%s added in Library","investorstrust"),$type_title);

                        //Body of the Notification.
                        // $body = $post_title.__(" added in Library","investorstrust");
                        $body = $post_title;

                        //Creating the notification array.
                        $notification = array('title' => $title, 'body' => $body);

                        if ( $os_type == 2 ){                            
                            $data['title'] = $title;
                            $data['body'] = $body;
                            $data['sound'] = 1;
                        }
                        else if ( $os_type == 1 ){
                            $notification['sound'] = 1;
                        }
                        // $sendPushIos  = sendPushNotificationAdmin( $notification,$users['user_detail'][$j]['device_token'], $data , $os_type);
                        //if($users['user_detail'][$j]['user_id'] == 26){
                            $sendPushIos  = sendPushNotificationAdmin( $notification,$users['user_detail'][$j]['device_token'], $data , $os_type, $users['user_detail'][$j]['user_id'], $post_id, 'library_notification' );
                        //}
                    }
                }
                $wpdb->delete( $table_notified_posts, array( 'id' => $post->id) );
            }
        }        
    }
}

function newsUpdateNotification() {
    $posts = array();
    global $wpdb;
    $prefix = $wpdb->prefix;
    
    $table_notified_posts = $prefix . 'notified_posts';
    
    $querystr = "SELECT id, post_id FROM $table_notified_posts WHERE post_type = 'discover' AND is_notified = 0";
    $posts = $wpdb->get_results( $querystr );
    if ( !empty ( $posts ) )
    {
        $users = users_settings_notification('news');
        if($users) {
            $user_count = count( $users['user_detail'] );
            foreach( $posts as $post )
            {           
                $post_id = $post->post_id;      
                for( $j = 0; $j < $user_count; $j++ )
                {
                    $user_id = $users['user_detail'][$j]['user_id'];   
                    if($users['user_detail'][$j]['device_token'] != "" && $users['user_detail'][$j]['os_type']!="")
                    {
                        // SKIP ITERATION IF POST LANGUAGE DOES NOT MATCH USER'S NOTIFICATION LANGUAGE
                        $notification_language = "en";
                        $acf_field_id = "user_".$user_id;
                        $notification_language = (get_field('notification_language',$acf_field_id)) ? (get_field('notification_language',$acf_field_id)) : "en";
                        
                        $post_language = apply_filters( 'wpml_post_language_details','',$post_id);
                        if(isset($post_language)) {
                            if($post_language['language_code'] != $notification_language)
                                continue;
                        }
                        // SKIP ITERATION IF POST LANGUAGE DOES NOT MATCH USER'S NOTIFICATION LANGUAGE

                        $os_type = $users['user_detail'][$j]['os_type'];
                        $post_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');

                        //Title of the Notification.
                        $title = __("News added in Discover","investorstrust");

                        //Body of the Notification.
                        // $body = $post_title.__(" added in Discover","investorstrust");
                        $body = $post_title;
                        //Creating the notification array.
                        $notification = array('title' => $title, 'body' => $body);

                        $data = array('type' => 'discover', 'post_id' => $post_id, 'post' => array());

                        if ( $os_type == 2 ){
                            $data['title'] = $title;
                            $data['body'] = $body;
                            $data['sound'] = 1;
                        }
                        else if ( $os_type == 1 ){
                            $notification['sound'] = 1;                            
                        }
                        // $sendPushIos  = sendPushNotificationAdmin( $notification,$users['user_detail'][$j]['device_token'], $data , $os_type);
                        //if($users['user_detail'][$j]['user_id'] == 26){
                        $sendPushIos  = sendPushNotificationAdmin( $notification,$users['user_detail'][$j]['device_token'], $data , $os_type, $users['user_detail'][$j]['user_id'], $post_id,'news_notification');
                        //}
                    }
                }
                $wpdb->delete( $table_notified_posts, array( 'id' => $post->id) );
            }
        }        
    }
}

function get_library_post($user_id, $post_id) {
    $notification_language = "en";
    $acf_field_id = "user_".$user_id;
    $notification_language = (get_field('notification_language',$acf_field_id)) ? (get_field('notification_language',$acf_field_id)) : "en";

    $post_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
    $type = get_field('select_type', $post_id);
    $social_posts_category = $product_category = $flyer_category = "";
    $short_description     = ( get_field( 'short_description', $post_id ) ) ? get_field('short_description', $post_id ) : "";
    $thumbnail_image       = ( get_field( 'thumbnail', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'thumbnail', $post_id ) ) : "" ;
    $presentation_link     = ( get_field( 'presentation_link', $post_id ) ) ? get_field( 'presentation_link', $post_id ) : "";
    $banner_image          = ( get_field( 'banner_image', $post_id ) ) ? get_field( 'banner_image', $post_id ) : "";
    $video_link            = ( get_field( 'video_url', $post_id ) ) ? get_field( 'video_url', $post_id ) : "" ;
    $image                 = ( get_field( 'image', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'image', $post_id ) ) : "";
    $video_thumbnail       = ( get_field( 'video_thumbnail', $post_id ) ) ? get_field( 'video_thumbnail', $post_id ) : "";
    $file_url = ( get_field( 'file_url', $post_id ) ) ? get_file_path( 'library', $notification_language, $type, get_field( 'file_url', $post_id ) ): "";

    if( get_field( 'product_category', $post_id ) ) {
        $product_category = get_field( 'product_category', $post_id );
        $translated_id = apply_filters( 'wpml_object_id',$product_category->term_id, 'product-category', TRUE, $notification_language);
        $product_category = get_translated_category_name($translated_id,$post_id,'product-category');
    }
    if( get_field( 'social_posts_category', $post_id ) ) {
        $social_posts_category = get_field( 'social_posts_category', $post_id );
        $translated_id = apply_filters( 'wpml_object_id',$social_posts_category->term_id, 'social-post-category', TRUE, $notification_language);
        $social_posts_category = get_translated_category_name($translated_id,$post_id,'social-post-category');
    }
    if( get_field( 'flyer_category', $post_id ) ) {
        $flyer_category = get_field( 'flyer_category', $post_id );
        $translated_id = apply_filters( 'wpml_object_id',$flyer_category->term_id, 'flyer-category', TRUE, $notification_language);
        $flyer_category = get_translated_category_name($translated_id,$post_id,'flyer-category');
    }
    $push = $product = $flyers = $socialpost = $presentation = array('post_id' => (int)$post_id,'type'=>$type,'title' => $post_title);
    switch( $type ) {
        case 'brochure':
                $push['image'] = $thumbnail_image;
                $push['short_description'] = $short_description;
                $push['file_url'] = $file_url;
        break;
        case 'flyers':
                $section_title = (get_acf_option('flyer_section_title',$notification_language)) ? get_acf_option('flyer_section_title',$notification_language) : "";
                $flyers['category'] = $flyer_category;
                $flyers['image'] = $thumbnail_image;
                $flyers['file_url'] = $file_url;
        break;
        case 'products':
                $section_title = (get_acf_option('product_section_title',$notification_language)) ? get_acf_option('product_section_title',$notification_language) : "";
                $product['category'] = $product_category;
                $product['image'] = $thumbnail_image;
                $product['file_url'] = $file_url;
        break;
        case 'socialpost':
            $section_title = (get_acf_option('social_posts_section_title',$notification_language)) ? get_acf_option('social_posts_section_title',$notification_language) : "";
            $socialpost['category'] = $social_posts_category;
            $socialpost['image'] = $image;
        break;
        case 'video':
            $push['short_description'] = $short_description;
            $push['video_link'] = $video_link;
            $push['video_thumbnail']   = $video_thumbnail;
        break;
        case 'presentation':
            $section_title = (get_acf_option('presentation_section_title',$notification_language)) ? get_acf_option('presentation_section_title',$notification_language) : "";
            if( $file_url != '' ) {
                $presentation['image'] = $thumbnail_image;
                $presentation['file_url'] = $file_url;
            } else if( $presentation_link != '' ) {
                $presentation['image'] = $thumbnail_image;
                $presentation['file_url'] = $presentation_link;
            }                  
        break;
        default:
        break;
    }
    $data = array();
    if( ( $type == "flyers" || $type == "brochure" || $type == "products" ) && $file_url == "" ){
        return $data;
    }  
    $data = array('type' => 'library','post_id'=>$post_id);
    if( $type == "products" ){
        $data['post'] = $product;
    }
    if( $type == "flyers" ) {
        $data['post'] = $flyers;
    }
    if( $type == "socialpost" ) {
        $data['post'] = $socialpost;
    }
    if( $type == "presentation" ) {
        $data['post'] = $presentation;
    }
    if( $type == "brochure" || $type == 'video' ) {
        $data['post'] = $push;    
    }
    return $data;
}

function admin_notifications(){
    // wp_mail('hardika@creolestudios.com','testing','live testing body');
    $languages = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
    $subfields = array('notification_text','notification_time','notification_type','select_post','external_url','fund_post','library_post');
    $values = array();
    date_default_timezone_set('Hongkong');
    // place to put the repeater values
    if( $languages ) {
        $counter = 0;
        foreach ( $languages as $key => $val ) {
            if( $key == 'en')   
                $repeater = 'options_notification_details';
            else
                $repeater = 'options_'.$key.'_notification_details';
            
            $count = intval( get_option( $repeater, 0 ) );
            for ( $i=0; $i< $count; $i++ ) {
                foreach ( $subfields as $subfield ) {
                    $value[$subfield] = get_option( $repeater.'_'.$i.'_'.$subfield, '' );
                    $value['lang'] = $key;
                } // end foreach $subfield
                $values[] = $value;
            }
        }
        foreach ( $values as $value ) {
            $current_time = date("Y-m-d H:i");
            $old_date = $value['notification_time'];
            $post_id = 0;
            $external_url = "";
            if(isset($value['notification_type'])) {
                $notification_type = $value['notification_type'];

                if($notification_type=="discover_post"){
                    if(isset($value['select_post']) && $value['select_post']!="") {
                        $post_id = $value['select_post'];
                        if(is_array($post_id)){
                            $post_id = $post_id[0];    
                        }
                    }         
                } else if($notification_type=="library_post"){
                    if(isset($value['library_post']) && $value['library_post']!="") {
                        $post_id = $value['library_post'];
                        if(is_array($post_id)){
                            $post_id = $post_id[0];    
                        }
                    }         
                } else if($notification_type=="fund_post"){
                    if(isset($value['fund_post']) && $value['fund_post']!="") {
                        $post_id = $value['fund_post'];
                        if(is_array($post_id)){
                            $post_id = $post_id[0];    
                        }
                    }         
                } else if($notification_type=="external_url"){
                    if(isset($value['external_url']) && $value['external_url']!="") {
                        $external_url = $value['external_url'];
                    }   
                }

                $old_date_timestamp = strtotime($old_date);
                $new_date = date('Y-m-d H:i', $old_date_timestamp);
                if( $new_date ==  $current_time ) {
                    $users      = users_settings_notification($value['lang']);
                    if($users){
                        $user_count = count( $users['user_detail'] );
                        for( $j = 0; $j < $user_count; $j++ )
                        {
                            $user_id = $users['user_detail'][$j]['user_id'];   
                            if($users['user_detail'][$j]['device_token'] != "" && $users['user_detail'][$j]['os_type']!="")
                            {
                                $os_type = $users['user_detail'][$j]['os_type'];
                                //Title of the Notification.
                                $title = __( "ITA Connect","investorstrust" );
                                //Body of the Notification.
                                $body = $value['notification_text'];
                                //Creating the notification array.
                                $notification = array( 'title' => $title, 'body' => $body);

                                $data = array('type' => 'no_link','url'=>'');
                                if($post_id) {                                    
                                    if ( get_post_type($post_id) == 'discover' ) {
                                        if($discover_post_type!="poll" && $discover_post_type!="survey"){
                                            $title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                                            $discover_post_type = get_field('select_type', $post_id);
                                            if( get_field( 'discover_category_field', $post_id ) ) {
                                                $discover_category = get_field( 'discover_category_field', $post_id );
                                                if(!empty($discover_category)){
                                                    $translated_id = apply_filters( 'wpml_object_id',$discover_category->term_id, 'discover-category', TRUE, $language);                
                                                    $categories = get_translated_category_name($translated_id, $post_id,'discover-category','name');
                                                }
                                            }
                                            $push = array('post_id'=> $post_id,'type' => $discover_post_type, 'category' => $categories, 'title' => $title);
                                            $short_description = ( get_field('short_description',$post_id) ) ? get_field('short_description',$post_id) : "";
                                            $thumbnail = ( get_field('thumbnail_image',$post_id) ) ? get_file_path('discover',$language,$type,get_field('thumbnail_image',$post_id)) : "";
                                            $video_link = ( get_field('video_link',$post_id) ) ? get_field('video_link',$post_id) : "";
                                            $video_thumbnail = ( get_field('video_thumbnail',$post_id) ) ? get_field('video_thumbnail',$post_id) : "";
                                            $file_url = ( get_field('file_url',$post_id) ) ? get_file_path('discover',$language,$type,get_field('file_url',$post_id)) : "";
                                            switch( $discover_post_type ){
                                                case 'flyer':
                                                    $push['image'] = $thumbnail;
                                                    $push['file_url'] = $file_url;
                                                    $push['short_description'] = $short_description;
                                                    break;
                                                case 'video':                   
                                                    $push['short_description'] = $short_description;
                                                    $push['video_thumbnail'] = $video_thumbnail;
                                                    $push['video_link'] = $video_link;
                                            }                                        
                                            $data = array('type' => 'discover','post_id'=>$post_id,'post'=>$push);
                                        }
                                    } else if ( get_post_type($post_id) == 'library' ) {
                                        $data =  get_library_post($user_id, $post_id);
                                        if( sizeof($data) == 0 ) {
                                            continue;
                                        }
                                    } else if ( get_post_type($post_id) == 'funds' ) {
                                        $pdf_Url = "";
                                        if( get_field('file_name',$post_id) ){
                                            $pdf_Url = get_fund_path('funds','en', get_field('file_name',$post_id));
                                        }                                     
                                        $data = array('type' => 'resource','url'=>$pdf_Url);    
                                    }                                                                
                                } else if($external_url!="") {
                                    $data = array('type' => 'admin','url'=> $external_url);    
                                }
                                
                                if ( $os_type == 2 ){
                                    $data['title'] = $title;
                                    $data['body'] = $body;
                                    $data['sound'] = 1;
                                }
                                else if ( $os_type == 1 ){
                                    $notification['sound'] = 1;                            
                                }
                                //if($users['user_detail'][$j]['user_id'] == 26){
                                    $sendPushIos = sendPushNotification( $notification, $users['user_detail'][$j]['device_token'],$data, $users['user_detail'][$j]['user_id'],'automated_notifications' );
                                //}
                            }
                        }
                    }
                }
            }            
        }
    }
}

function users_settings_notification( $notification_type ) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $user_settings_table = $prefix . 'usermeta';
    $device_token_table = $prefix . 'device_token';
    if( $notification_type == 'news' ) {
        $args  = array(
                'meta_key' => 'news_update_notification', //any custom field name
                'meta_value' => 1 //the value to compare against
            );
        $user_query = new WP_User_Query( $args );
        $users_setting = $user_query->get_results();
    } else if($notification_type == 'library' ) {
        $args  = array(
                'meta_key' => 'library_update_notification', //any custom field name
                'meta_value' => 1 //the value to compare against
            );
        $user_query = new WP_User_Query( $args );
        $users_setting = $user_query->get_results();
    } else{
        if($notification_type=="en") {
            $args = array( 'relation' => 'OR',
                            array(
                                'key' => 'notification_language',
                                'value' => '',
                                'compare' => 'NOT EXISTS',
                            ),
                            array(
                                'key' => 'notification_language',
                                'value' => $notification_type,
                                'compare' => '=',
                            ));
        } else {
            $args  = array(
                'meta_key' => 'notification_language', //any custom field name
                'meta_value' => $notification_type //the value to compare against
            );    
        }        
        $user_query = new WP_User_Query( $args );
        $users_setting = $user_query->get_results();
    }
    $return_val = array();
    // Get the results
    if ( !empty( $users_setting ) ) 
    {
        foreach ( $users_setting as $user )
        {
            $user_id = $user->data->ID;
            $user_details['user_id'] = $user->data->ID;
            
            $devicetoken_qry = "SELECT * FROM $device_token_table WHERE user_id=".$user_id;
            $device_token_results = $wpdb->get_results($devicetoken_qry);
            
            if($device_token_results) {
                foreach ( $device_token_results as $device_token_result ) {                    
                    $user_details['token_id']     = $device_token_result->id;
                    $user_details['device_token'] = $device_token_result->device_token;
                    $user_details['os_type']      = $device_token_result->os_type;
                    $return_val['user_detail'][] = $user_details;
                }
            }
        }
    }
    return $return_val;
}

function sendPushNotification($notification, $deviceToken, $data, $user_id="", $Notification_type="") {
    $ch = curl_init(FCM_URL);
 
    //The device token.
   
    //This array contains, the token and the notification. The 'to' attribute stores the token.
    $arrayToSend  = array('to' =>  $deviceToken, 'notification' => $notification, 'priority' => 'high', 'data' => $data);

    //Generating JSON encoded string form the above array.
    $json = json_encode($arrayToSend);
    //Setup headers:
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='.FCM_KEY; // key here

    //Setup curl, add headers and post parameters.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

    //Send the request
    $response = curl_exec($ch);
    $responseArray = json_decode($response, true);
    
    if($user_id != ""){
        if($responseArray['failure'] == 1){
            $sentFlag = 2;
        }else{
            $sentFlag = 1;
        }
        global $wpdb;
        $prefix = $wpdb->prefix;
        $table_notifications = $prefix . 'notifications_log';
        
        $insertArr = array( 
                        'user_id' => $user_id,
                        'device_token' => $deviceToken ,
                        'sent_flag' => $sentFlag,
                        'notification_type' => $Notification_type,
                        'created_on' => date('Y-m-d H:i:s')
                    );
        $SendMessageinsert = $wpdb->insert($table_notifications, $insertArr);
    }
    //Close request
    curl_close($ch);
}
function staticPushNotification($notification="", $deviceToken="", $data=array()) {
    $ch = curl_init(FCM_URL);
 
    //The device token.
    $deviceToken = "fCmrS8Y06iA:APA91bEx5WEHMzH_tuTcQZBVLlzr2oxCdCYzjgbfkdqQHNV9S0Eiq3cDsGHyolkBMD0XOENo2zyurRQ0To8SgazpFtHw6_kfxRkbafllkFr3R7aNvloaZ8s_kPeksVQXJr1t7vGLtOW3"; //token here
    $notification = array('title' =>"title", 'text' =>"notification body text", 'sound' => 1);
    $data = array("dummy_text" => "ABC","dummy_text_para" => "PQR");

    //This array contains, the token and the notification. The 'to' attribute stores the token.
    $arrayToSend  = array('to' =>  $deviceToken, 'notification' => $notification, 'priority' => 'high', 'data' => $data);

    //Generating JSON encoded string form the above array.
    $json = json_encode($arrayToSend);
    //Setup headers:
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='.FCM_KEY; // key here

    //Setup curl, add headers and post parameters.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

    //Send the request
    $response = curl_exec($ch);
    //Close request
    curl_close($ch);
}
admin_notifications();
library_update_notification();
newsUpdateNotification();
?>