<?php 
function generateNewReport( $request_data ){
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = $uploaded_filedata = array();
    $json['message'] = $user_id = $content = $post_id = $attach_id = $message_type = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $json['data']['post']  = array();
    if( !isset( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generatenewreport']['user_id_not_provided'], 'generatenewreport-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generatenewreport']['user_not_found'], 'generatenewreport-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
    }

    if( isset( $request_data['post_id'] )  && $request_data['post_id'] != '') {
        $post_id = $request_data['post_id'];
    }

    if( isset( $request_data['message'] )  && $request_data['message'] != '') {
        $content = $request_data['message'];
        $message_type = 'text';
    }

    if( isset( $_FILES['attachment_image'] ) ) {
        $uploaded_filedata = $request_data->get_file_params();
            // CHECKING IF ANY FILE IS UPLOADED OR NOT
        if( sizeof( $uploaded_filedata ) ) {
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
            }
            $uploadedfile = $uploaded_filedata['attachment_image'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
            
            if ( $movefile && ! isset( $movefile['error'] ) ) {
                $file_loc   =   $movefile['file'];
                $file_name  =   basename($uploadedfile['name']);
                $file_type  =   wp_check_filetype($uploadedfile['type']);
                $attachment = array(
                                        'post_author' => $user_id,
                                        'post_mime_type' => $uploadedfile['type'],
                                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                                        'post_content' => '',
                                        'post_status' => 'inherit',
                                    );

                $attach_id      =   wp_insert_attachment($attachment, $file_loc);
                $attach_data    =   wp_generate_attachment_metadata($attach_id, $file_loc);
                wp_update_attachment_metadata($attach_id, $attach_data);
            }
        }
        $message_type = 'image';
    }
    
    if($content != '' && sizeof( $uploaded_filedata ) ){
        $message_type = 'text_image';
    }

    $user = get_userdata( $user_id );
    $user_name = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : "";
    
    if( $post_id != '' ) {
        
        $time = current_time('mysql');
        $comment = array(
            'comment_post_ID'       => $post_id,
            'comment_author'        => $user_name,
            'comment_author_email'  => $user->user_email,
            'comment_author_url'    => $user->user_url,
            'comment_content'       => $content,
            'user_id'               => $user_id,
            'comment_date'          => $time,
            'comment_approved'      => 1,
            'comment_type'          => $message_type
        );
        $comment_id = wp_insert_comment( $comment );
        if( $attach_id != '' )
            update_field( 'attachment_image', $attach_id, get_comment( $comment_id ) );
            
            $comment_data = get_comment( $comment_id );
            
            $message_type = $comment_image = $comment_content = '';

            if( $comment_data->comment_content != '' )
                $comment_content = $comment_data->comment_content;

            if( get_field('attachment_image',get_comment( $comment_id ) ) )
                $comment_image = get_field( 'attachment_image', get_comment( $comment_id ) );

            if( $comment_content != '' && $comment_image ) {
                $message_type = 'text_image';
            } else if( $comment_content != '' ) {
                $message_type = 'text';
            } else if( $comment_image ) {
                $message_type = 'image';
            }
            $push_comment = array( 
                            'comment_id'       => (int)$comment_id,
                            'author_id'     => (int)$user_id,
                            'author_name'   => $user_name,
                            'comment_text'  => $comment_content,
                            'comment_image' => $comment_image,
                            'comment_type'  => $message_type,
                            'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_comment_date( 'Y-m-d H:i:s', $comment_id ) ) )
                      );
        $json['data']['post_id'] = (int)$post_id;
        $json['data']['post'][] = $push_comment;
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['generatenewreport']['message_sent'], 'generatenewreport-msg', 'message-sent',$language);
        return get_200_success($json);
    } else {
        $user_name = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : "";
        $image = '';
        $new_post_id = wp_insert_post( array (
               'post_type' => 'tickets',
               'post_title' => $user_name.' has generated an issue.',
               'post_content' => $content,
               'post_status' => 'publish',
               'post_author' => $user_id
            ) );
        update_field('ticket_status','active', $new_post_id);
        if( $new_post_id ) {
            
            $user_info = get_userdata($user_id);
            $logo_image = (get_acf_option('logo_image',$language)) ? wp_get_attachment_url(get_acf_option('logo_image',$language)) : get_template_directory_uri()."/images/logo-main-email.png";
            // $sublogo = (get_acf_option('sublogo',$language)) ? wp_get_attachment_url(get_acf_option('sublogo',$language)) : get_template_directory_uri()."/images/logo-subtitle.png";
            $admin_post_url = admin_url( 'post.php?post=' . $new_post_id ) . '&action=edit';
            $headers[] =  'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
            $subject_admin = $user_info->user_email.' posted a comment.';
            $message_admin = '<html><body><table style="border-spacing: 0;max-width: 600px;width: 100%;word-break: break-word;">';
            $message_admin .= '<tr><th style="padding:0 15px;background-color: #fff;text-align: left" colspan="2" class="logo"><a href="javascript:void(0);"><img src="'.$logo_image.'" width="300px"></a></th></tr>';
            $message_admin .= '<tr><td style="padding:30px 20px 0;" colspan="2">';
            $message_admin = 'Hi Admin,';
            $emailBody.= '</td></tr>';
            $message_admin .= '<tr><td style="padding:30px 20px 0;" colspan="2" >You have a new comment from '.$user_info->user_email ;
            $message_admin .= '</td></tr>';
            $message_admin .= '<tr><td style="padding:30px 20px 0;" colspan="2"> To review the comment you can refer this link: '.$admin_post_url;
            $message_admin .= '</td></tr></table></body></html>';
            $admin_email = get_option('admin_email');
            wp_mail( $admin_email , $subject_admin, $message_admin, $headers );

            if($user_info) {
                $emailSubject = get_acf_option('report_an_issue_email_subject',$language);
                $headers[] =  'Content-Type: text/html; charset=UTF-8';
                $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";

                $email_address = $user_info->user_email;
                
                $output = get_acf_option('report_an_issue_email_content',$language);
                $subject = get_acf_option('report_an_issue_email_subject',$language);
               
                $output = str_replace('%USER-NAME%',  $user_name,     $output);
                $output = str_replace('%USER-EMAIL%', $email_address, $output);
                $output = str_replace('%LOGO-IMAGE%', $logo_image,    $output);
                // $output = str_replace('%SUB-LOGO-IMAGE%', $sublogo,   $output);

                $emailBody  = '<html><body>';
                $emailBody .= $output;
                $emailBody .= '</body></html>';

                wp_mail( $email_address, $emailSubject, $emailBody, $headers );
            }

            if( $attach_id != '' )
            update_field('attachment_image', $attach_id, $new_post_id );

            if( get_field( 'attachment_image', $new_post_id ) )
                $image = get_field( 'attachment_image', $new_post_id );

            //PAUSING EXECUTION FOR 1 SECOND SO THAT COMMENT & INITIAL POST TIME HAVE DIFFERENCE OF 1 SECOND
            sleep(1);
            $comment_content = $message_global['generatenewreport']['message_sent'];
            $time = current_time('mysql');
            $comment = array(
                'comment_post_ID'       => $new_post_id,
                'comment_author'        => $user_name,
                'comment_author_email'  => $user->user_email,
                'comment_author_url'    => $user->user_url,
                'comment_content'       => $comment_content,
                'user_id'               => $user_id,
                'comment_date'          => $time,
                'comment_approved'      => 1,
                'comment_type'          => $message_type
            );
            $new_comment_id = wp_insert_comment( $comment );
            $json['data']['post_id'] = (int)$new_post_id;
            update_field('is_system_generated', 1, get_comment($new_comment_id) );
            $json['data']['post'][] = array( 
                       'comment_id'       => (int)$new_comment_id,
                       'author_id'     => (int)$user_id,
                       'author_name'   => $user_name,
                       'comment_text'  => $comment_content,
                       'comment_image' => '',
                       'comment_type'  => 'text',
                       'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_comment_date( 'Y-m-d H:i:s', $new_comment_id ) ) ),
                       'is_system_generated' => 1
                    );
            $json['data']['post'][]  = array( 
                       'author_id'     => (int)$user_id,
                       'author_name'   => $user_name,
                       'comment_text'  => $content,
                       'comment_image' => $image,
                       'comment_type'  => $message_type,
                       'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_the_date( 'Y-m-d H:i:s', $new_post_id ) ) ),
                    );
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generatenewreport']['message_sent'], 'generatenewreport-msg', 'message-sent', $language);
            return get_200_success($json);
        }
        
    }
}

function hide_publishing_actions(){
    global $post;
    if($post->post_type == 'tickets'){
        $status = get_field('ticket_status',$post->ID);
        if($status == 'closed')
            echo '<style type="text/css">#submitdiv{display:none;}</style>';
    }
}
add_action('admin_head-post.php', 'hide_publishing_actions');


function getListReport( $request_data ) {
    $json = array();
    global $message_global;
    $json['data'] = $data = $push = array();
    $json['message'] = '';
    $user_id = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( !isset( $request_data['user_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlistreport']['user_id_not_provided'], 'getlistreport-msg', 'user-id-not-provided', $language);
        return get_400_error( $json );
    } else {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlistreport']['user_not_found'], 'getlistreport-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
    }
    $user_info = get_userdata($user_id);
    $args = array(
        'author' => $user_id,
        'post_type' => 'tickets',
        'posts_per_page' => -1,
        'meta_query' => array(
                                array(
                                   'key'       => 'ticket_status',
                                   'value'     => 'active',
                                   'compare'   => '='
                                )
                    )
            );

    $admin_name = (get_acf_option('admin_name',$language)) ? get_acf_option('admin_name',$language) : __('ITA App Team','investorstrust');
    $admin_profile_image = (get_acf_option('admin_profile_image',$language)) ? wp_get_attachment_url(get_acf_option('admin_profile_image',$language)) : get_template_directory_uri()."/images/investorstrust-admin.png";
    $admin_me = apply_filters( 'wpml_translate_single_string', $message_global['getlistreport']['admin_text_me'], 'getlistreport-msg', 'admin-text-me', $language);
    $ticket_query = new WP_Query( $args );
    if( $ticket_query->have_posts() ){
        $counter = 0;
        while ( $ticket_query->have_posts() ) {
            $ticket_query->the_post();
            $post_id = get_the_ID();
                // FETCHING LATEST COMMENT DATA
                $args = array( 'number' => '1', 'post_id' => $post_id );
                $comments = get_comments($args);
                foreach($comments as $comment) :
                    if(get_field('is_system_generated', get_comment($comment->comment_ID) ) ){
                        $push = array(
                            'post_id'           => (int)$post_id,
                            "author_id"         => $user_id,
                            "author_name"       => get_user_meta($user_id, 'full_name',true),
                            "comment_title"     => 'Support ticket #'.(int)$post_id,
                            'date'              => get_the_date('Y-m-d H:i:s', $post_id),
                            'comment'           => get_the_content(),
                            "admin_name"        => $admin_me.", ".$admin_name,
                            "admin_image"       => $admin_profile_image
                        );
                    } else {
                        $author_id = $comment->user_id;
                        $user_info = get_userdata( $author_id );
                        $user_roles = implode(', ', $user_info->roles);
                        if(strpos($user_roles,"administrator")===0) {
                            $label = $admin_name.", ".$admin_me;
                        } else {
                            $label = $admin_me.", ".$admin_name;
                        }

                        $push = array(
                            'post_id'           => (int)$post_id,
                            "author_id"         => $comment->user_id,
                            "author_name"       => get_user_meta($comment->user_id, 'full_name',true),
                            "comment_title"     => 'Support ticket #'.(int)$post_id,
                            'date'              => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_comment_date( 'Y-m-d H:i:s', $comment->comment_ID ) ) ),
                            'comment'           => $comment->comment_content,
                            "admin_name"        => $label,
                            "admin_image"       => $admin_profile_image
                        );
                    }
                endforeach;
                // FETCHING LATEST COMMENT DATA                
                $data[$counter] =  $push;
                $counter++;
            }
            wp_reset_query();
            $json['data']['posts'] = $data;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlistreport']['ticket_list_generated'], 'getlistreport-msg', 'ticket-list-generated', $language);
            return get_200_success($json);
    }
    else{
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlistreport']['ticket_not_found'], 'getlistreport-msg', 'ticket-not-found', $language);
        return get_200_success( $json );
    }
}

function getMessageData( $request_data ) {
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = '';
    $post_id = $message_type = $last_message_date = ''; 
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    if( !isset( $request_data['post_id'] ) ) { 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmessagedata']['post_id_not_provided'], 'getmessagedata-msg', 'post-id-not-provided', $language);
        return get_400_error( $json );
    } else {
        $post_id = $request_data['post_id'];
        $post_exist = get_post( $post_id );
        if ( empty( $post_exist ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmessagedata']['post_id_not_founded'], 'getmessagedata-msg', 'post-id-not-founded', $language);
            return get_400_error( $json );
        }       
    }

    $content = $image = $comment_image = '';
    $content = get_post_field('post_content', $post_id);
    
    if( get_field( 'attachment_image',$post_id ) )
        $image = get_field('attachment_image', $post_id );
    
    $author_id    = get_post_field ('post_author', $post_id);
    $display_name = get_the_author_meta( 'display_name' , $author_id );
    
    if( $content != '' && $image ) {
        $message_type = 'text_image';
    } else if( $content != '' ) {
        $message_type = 'text';
    } else if( $image ) {
        $message_type = 'image';
    }

    
    $args = array(
         'post_id' => $post_id,
         'orderby' => 'date',
         'order' => 'DESC');
    if( isset( $request_data['last_message_date'] ) && $request_data['last_message_date'] != "" ) { 
        $last_message_date = $request_data['last_message_date'];
        $args['date_query'] = array(
                array(
                'after' => $last_message_date,
                  )
               );

    }
    $comments       = get_comments( $args );
    if( $comments ) {
        $comments_count = get_comments( array( 'post_id' => $post_id , 'count' => true ) );
        if( $comments_count >= 1 ) {
            // $counter = 0;
                
                foreach ( $comments as $comment ) :
                    $message_type = $comment_image = $comment_content = $is_system_generated = '';

                    if( $comment->comment_content != '' )
                        $comment_content = $comment->comment_content;

                    if( get_field( 'attachment_image',get_comment( $comment->comment_ID ) ) )
                        $comment_image = get_field( 'attachment_image',get_comment( $comment->comment_ID ) );

                    if( $comment_content != '' && $comment_image ) {
                        $message_type = 'text_image';
                    } else if( $comment_content != '' ) {
                        $message_type = 'text';
                    } else if( $comment_image ) {
                        $message_type = 'image';
                    }

                    if(get_field( 'is_system_generated', get_comment( $comment->comment_ID ) ) ){
                        $is_system_generated = 1;
                    }
                    $data[] = array( 
                                    'comment_id'    => (int)$comment->comment_ID,
                                    'author_id'     => (int)$comment->user_id,
                                    'author_name'   => $comment->comment_author,
                                    'comment_text'  => $comment_content,
                                    'comment_image' => $comment_image,
                                    'comment_type'  => $message_type,
                                    'is_system_generated'  => $is_system_generated,
                                    'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_comment_date( 'Y-m-d H:i:s', $comment->comment_ID ) ) )
                            );
                   
                    // $data[$counter] = $push ;
                    // $counter++;
                endforeach;
                if( !isset( $request_data['last_message_date'] ) ) { 
                    $data[] = array ( 
                                   'author_id'     => (int)$author_id,
                                   'author_name'   => $display_name,
                                   'comment_text'  => $content,
                                   'comment_image' => $image,
                                   'comment_type'  => $message_type,
                                   'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_the_date( 'Y-m-d H:i:s', $post_id ) ) )
                                );
                }
            $json['data']['posts'] = $data;
            $json['data']['post_id'] = (int)$post_id;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmessagedata']['message_data_found'], 'getmessagedata-msg', 'message-data-found', $language);
            return get_200_success( $json );
        } else {
            if( !isset( $request_data['last_message_date'] ) ) { 
                    $data[] = array ( 
                                   'author_id'     => (int)$author_id,
                                   'author_name'   => $display_name,
                                   'comment_text'  => $content,
                                   'comment_image' => $image,
                                   'comment_type'  => $message_type,
                                   'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_the_date( 'Y-m-d H:i:s', $post_id ) ) )
                                );
                }
            $json['data']['posts']  = $data;
            $json['data']['post_id'] = (int)$post_id;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmessagedata']['message_data_found'], 'getmessagedata-msg', 'message-data-found', $language);
            return get_200_success( $json );
        }
    } else {
        if( !isset( $request_data['last_message_date'] ) ) { 
                    $data[] = array ( 
                                   'author_id'     => (int)$author_id,
                                   'author_name'   => $display_name,
                                   'comment_text'  => $content,
                                   'comment_image' => $image,
                                   'comment_type'  => $message_type,
                                   'date'          => local_date_i18n( 'Y-m-d H:i:s', strtotime( get_the_date( 'Y-m-d H:i:s', $post_id ) ) )
                                );
                }
        $json['data']['posts']  = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getmessagedata']['message_data_not_found'], 'getmessagedata-msg', 'message-data-not-found', $language );
        return get_200_success( $json );
    }
}

function local_date_i18n($format, $timestamp) {
    $timezone_str = 'UTC';
    $timezone = new \DateTimeZone($timezone_str);

    // The date in the local timezone.
    $date = new \DateTime(null, $timezone);
    $date->setTimestamp($timestamp);
    $date_str = $date->format('Y-m-d H:i:s');

    // Pretend the local date is UTC to get the timestamp
    // to pass to date_i18n().
    $utc_timezone = new \DateTimeZone('UTC');
    $utc_date = new \DateTime($date_str, $utc_timezone);
    $timestamp = $utc_date->getTimestamp();
    return date_i18n('Y-m-d H:i:s', $timestamp, true);
}

?>