<?php
/* Name: Hardika Satasiya
 * Function :checkIntroducerCode
 * Purpose: To check introducer code and email address
 */

function checkIntroducerCode( $request_data ) {
    // INITIALIZATION START
    $json = array(); 
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_meta = $user = $introducer_code = $email_address = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

    if( empty( $request_data['client_name'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['client_name_not_provided'], 'checkintroducercode-msg', 'client-name-not-provided',$language);
        return get_400_error( $json );
    }
    else {
        $client_name = $request_data['client_name'];
    }

    if( empty( $request_data['email_address'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['email_not_provided'], 'checkintroducercode-msg', 'email-not-provided',$language);
        return get_400_error( $json );
    }
    else {
        $email_address = $request_data['email_address'];
    }

    if( empty( $request_data['introducer_code'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['introducercode_not_provided'], 'checkintroducercode-msg', 'introducercode-not-provided',$language);
        return get_400_error( $json );
    }
    else {
        $introducer_code = $request_data['introducer_code'];
    }
    
    //FETCHING USER DETAILS BY EMAIL ADDRESS
    $user = get_user_by( 'email', $email_address );
    if( !$user ){
       $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['email_not_found'], 'checkintroducercode-msg', 'email-not-found',$language);
        return get_400_error( $json );
    } 

    $user_meta = get_user_meta( $user->ID, 'introducer_code', true );
    if( isset( $user_meta ) && $user_meta == $introducer_code ) {
        //TO CHECK WHETHER USER ACCOUNT IS ALREADY ACTIVATED
        $activation_code = get_user_meta( $user->ID, 'user_activation_status', true );
        if( $activation_code == 2 ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['account_already_activated'], 'checkintroducercode-msg', 'account-already-activated',$language);
            return get_400_error( $json );
        }

        // RETURNING USER DATA ON SUCCESS
        update_user_meta( $user->ID, 'full_name', $client_name );
        $data['user_id'] = (int)$user->data->ID;
        $data['email_address'] = $user->data->user_email;
        $json['data'] = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['verification_success'], 'checkintroducercode-msg', 'verification-success',$language);
        return get_200_success( $json );                    
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducercode']['verification_failure'], 'checkintroducercode-msg', 'verification-failure',$language);
        return get_400_error( $json );
    }        
}

/* Name: Hardika Satasiya
 * Function :newPasswordSetup
 * Purpose: To set password for verified users.
 */

function newPasswordSetup( $request_data ) {
    // INITIALIZATION START
    $json = array();
    $user_id = $password = $confim_password = '';
    global $wpdb;
    global $message_global;
    $json['data'] = array();
    $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END
    
    if( empty( $request_data['password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['newpasswordsetup']['password_not_provided'], 'newpasswordsetup-msg', 'password-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['confirm_password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['newpasswordsetup']['confirmpassword_not_provided'], 'newpasswordsetup-msg', 'confirmpassword-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    }

    $password = $request_data['password'];
    $confim_password = $request_data['confirm_password'];
    $user_id = $request_data['user_id'];

    if( $password != $confim_password ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['newpasswordsetup']['password_not_matching'], 'newpasswordsetup-msg', 'password-not-matching',$language);
        return get_400_error( $json );
    }    
    $user = get_user_by( 'id', $user_id );
    if( $user ) {
        wp_set_password( $password, $user_id ); //password reset
        update_user_meta( $user_id, 'user_activation_status', 2 ); //user activation status        
        $json['data']          = get_user_obj($user->data->ID);
        $json['message']       = apply_filters( 'wpml_translate_single_string', $message_global['newpasswordsetup']['success'], 'newpasswordsetup-msg', 'success',$language);
        return get_200_success( $json );        
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['newpasswordsetup']['user_not_found'], 'newpasswordsetup-msg', 'user-not-found',$language);
        return get_400_error( $json );
    }
}

/* Name: Hardika Satasiya
 * Function :newPasswordSetup
 * Purpose: To change password for verified users.
 */

function login( $request_data ) {
    // INITIALIZATION START
    $json = array();
    $json['message'] = $user_id = $email_address = $password = $device_token = $os_type = '';
    global $wpdb, $message_global;
    $json['data'] = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    $user_signon_error_code = '';    
    // INITIALIZATION END
    
    if( empty( $request_data['email_address'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['email_not_provided'], 'login-msg', 'email-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['password_not_provided'], 'login-msg', 'password-not-provided',$language);
        return get_400_error( $json );
    }

    $email_address = $request_data['email_address'];
    $password = $request_data['password'];
    $info['user_login'] = $email_address;
    $info['user_password'] = $password;
    $login_user = get_user_by( 'email', $email_address );
    $exists = email_exists( $info['user_login'] );
    if ( $exists ){
            $check_user_active = get_user_meta( $login_user->ID, 'user_activation_status', true );
            if ( $check_user_active != 2){
                $user_signon_error_code = 'check_user_active';
            } else {
                $user_signon = wp_signon( $info, '' );
                if( is_wp_error( $user_signon ) ) {
                    $user_signon_error_code = $user_signon->get_error_code();
                }                       
            }
    } else{
        $user_signon_error_code = 'email_address_not_exist';
    }

    if( $user_signon_error_code != '') {
        switch ( $user_signon_error_code ) {
            case 'incorrect_password':
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['incorrect_password'], 'login-msg', 'incorrect-password',$language);
                return get_400_error( $json );
                break;
            case 'invalid_email':
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['email_not_found'], 'login-msg', 'email-not-found',$language);
                return get_400_error( $json );
                break;
            case 'check_user_active':
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['user_not_verified'], 'login-msg', 'user-not-verified',$language);
                return get_400_error( $json );
                break;
            case 'email_address_not_exist':
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['email_not_found'], 'login-msg', 'email-not-found',$language);
                return get_400_error( $json );
                break;
            default:
                break;
        }
    } else {
        //get logged in user data in response
        if( $user_signon->ID ){
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['login']['user_login'], 'login-msg', 'user-login',$language);
            $json['data'] = get_user_obj($user_signon->ID );
            return get_200_success( $json );
        }else{
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
            return get_400_error( $json );
        }
    }    
}

/* Name: Hardika Satasiya
 * Function :checkIntroducerForgot
 * Purpose: To check introducer code for forgot password request
 */

function checkIntroducerForgot( $request_data ) {
    // INITIALIZATION START
    $json = array();
    $json['message'] = $introducer_code = '';
    global $wpdb, $message_global;
    $json['data'] = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END    

    if( $request_data['introducer_code']=='' ) {      
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducerforgot']['introducercode_not_provided'], 'checkintroducerforgot-msg', 'introducercode-not-provided',$language);
        return get_400_error( $json );
    }
    else 
    {
        $forgot_introducer_code = $request_data['introducer_code'];
        $args = array(  
                'meta_key'     => 'introducer_code',
                'meta_value'   => $forgot_introducer_code,
                'meta_compare'       => '='
        );
        
        $get_users = get_users( $args );
        if( empty( $get_users ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducerforgot']['introducer_code_invalid'], 'checkintroducerforgot-msg', 'introducer-code-invalid',$language);
            return get_400_error( $json );
        } else {
                $email_address = $get_users[0]->data->user_email;
                $check_user_active = get_user_meta( $get_users[0]->data->ID, 'user_activation_status', true );
                if ( $check_user_active != 2){
                   $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducerforgot']['user_not_verified'], 'checkintroducerforgot-msg', 'user-not-verified',$language);
                   return get_400_error( $json );
                }
                /* Random code generating*/
                $characters = '123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 6; $i++) {
                    $randomString .= $characters[rand( 0, $charactersLength - 1 )];
                }
                $pin = $randomString;
                /*Save meta value*/
                $current_time = current_time('timestamp');
                $user_id = $get_users[0]->ID;
                update_user_meta( $user_id, 'password_reset_code', $pin );
                update_user_meta( $user_id, 'password_reset_validatetime', current_time( 'timestamp' ) );
                $current_language = (get_user_meta( $user_id, 'current_language', true )) ? (get_user_meta( $user_id, 'current_language', true )) : "en";
                //SEND EMAIL WITH CONFIRMATION CODE TO USER'S EMAIL ADDRESS//
                $logo_image = (get_acf_option('logo_image',$current_language)) ? wp_get_attachment_url(get_acf_option('logo_image',$current_language)) : get_template_directory_uri()."/images/logo-main-email.png";
                // $sublogo = (get_acf_option('sublogo',$current_language)) ? wp_get_attachment_url(get_acf_option('sublogo',$current_language)) : get_template_directory_uri()."/images/logo-subtitle.png";
                
                $output = get_acf_option('forgot_password_content',$current_language);
                $emailSubject = get_acf_option('forgot_password_email_subject',$current_language);
                $emailSubject = str_replace('%USER-CODE%', $pin, $emailSubject);
               
                $user_name = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : "";
                $output = str_replace('%USER-NAME%',  $user_name,     $output);
                $output = str_replace('%USER-EMAIL%', $email_address, $output);
                $output = str_replace('%USER-CODE%', $pin, $output);
                $output = str_replace('%LOGO-IMAGE%', $logo_image,    $output);
                // $output = str_replace('%SUB-LOGO-IMAGE%', $sublogo,   $output);

                $emailBody  = '<html><body>';
                $emailBody .= $output;
                $emailBody .= '</body></html>';
                $headers[] =  'Content-Type: text/html; charset=UTF-8';
                $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
                wp_mail( $email_address, $emailSubject, $emailBody, $headers );
                //END//
                /*Save meta value*/
                $json['data']['user_id'] = $user_id;
                $json['data']['email_address'] = $email_address;
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['checkintroducerforgot']['emai_sent_success'], 'checkintroducerforgot-msg', 'emai-sent-success',$language);
                return get_200_success( $json );
        }
    }   
}

/* Name: Hardika Satasiya
 * Function :resendConfirmCode
 * Purpose: To resend introducer code for forgot password request
 */

function resendConfirmCode( $request_data ) {
    // INITIALIZATION START
    $json = array();
    $json['message'] = $introducer_code = '';
    global $wpdb, $message_global;
    $json['data'] = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END    

    if( $request_data['user_id'] == '' ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['resendconfirmcode']['user_id_not_provided'], 'resendconfirmcode-msg', 'user-id-not-provided',$language);
        return get_400_error( $json );
    }
    else 
    {
        $user_id = $request_data['user_id'];
        $user = get_user_by( 'id', $user_id );
        if ( empty( $user ) ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['resendconfirmcode']['user_not_found'], 'resendconfirmcode-msg', 'user-not-found',$language);
            return get_400_error( $json );
        }
        $user = get_user_by( 'id', $user_id );
        $email_address = $user->data->user_email;
        $user_code = get_user_meta( $user_id, 'password_reset_code', true );
        if( $user_code ) {
            $date = get_user_meta( $user_id, 'password_reset_validatetime', true );
            $current_time = current_time( 'timestamp' );
            $min_minutes = 10 * 60;
            $date_diff = $current_time - $date;
            if($date_diff <= $min_minutes) {
                //SEND EMAIL WITH CONFIRMATION CODE TO USER'S EMAIL ADDRESS//
                $headers[] =  'Content-Type: text/html; charset=UTF-8';
                $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
                $user_name = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : "";
                $logo_image = (get_acf_option('logo_image','en')) ? wp_get_attachment_url(get_acf_option('logo_image','en')) : get_template_directory_uri()."/images/logo-main-email.png";
                // $sublogo = (get_acf_option('sublogo','en')) ? wp_get_attachment_url(get_acf_option('sublogo','en')) : get_template_directory_uri()."/images/logo-subtitle.png";
                $current_language = (get_user_meta( $user_id, 'current_language', true )) ? (get_user_meta( $user_id, 'current_language', true )) : "en";
                $output = get_acf_option('forgot_password_content',$current_language);
                $subject = get_acf_option('forgot_password_email_subject',$current_language);
                $subject = str_replace('%USER-CODE%', $user_code, $subject);               
                $output = str_replace('%USER-NAME%',  $user_name,     $output);
                $output = str_replace('%USER-EMAIL%', $email_address, $output);
                $output = str_replace('%USER-CODE%', $user_code, $output);
                $output = str_replace('%LOGO-IMAGE%', $logo_image,    $output);
                // $output = str_replace('%SUB-LOGO-IMAGE%', $sublogo,   $output);
                $emailBody  = '<html><body>';
                $emailBody .= $output;
                $emailBody .= '</body></html>';
                wp_mail( $email_address, $subject, $emailBody, $headers );
                //END//
                /*Save meta value*/
                $json['data'] = get_user_obj( $user_id );
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['resendconfirmcode']['emai_sent_success'], 'resendconfirmcode-msg', 'emai-sent-success',$language );
                return get_200_success( $json );
            } else {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['resendconfirmcode']['confirmation_code_time_limit'], 'resendconfirmcode-msg', 'confirmation-code-time-limit',$language);
                return get_400_error( $json );
            }
        } else {
            $json['data'] = "";
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['resendconfirmcode']['request_for_code'], 'resendconfirmcode-msg', 'request-for-code',$language);
            return get_400_error( $json ); 
        }
        
    }   
}

/* Name: Hardika Satasiya
 * Function :confirmCode
 * Purpose: To veirfy confirm code .
 */

function confirmCode( $request_data ) {
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $confirmation_code = $introducer_code = $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['confirmation_code'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['confirmcode']['confirmation_code_not_provided'], 'confirmcode-msg', 'confirmation-code-not-provided',$language);
        return get_400_error( $json );
    }

    $user_id   = $request_data['user_id'];
    $confirmation_code = $request_data['confirmation_code'];
    $user_code         = get_user_meta( $user_id, 'password_reset_code', true );
    $date              = get_user_meta( $user_id, 'password_reset_validatetime', true );
    $current_time      = current_time( 'timestamp' );
    $min_minutes       = 10 * 60;
    $date_diff = $current_time - $date;

    if($date_diff <= $min_minutes) {
        if( $user_code != $confirmation_code ) {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['confirmcode']['invalid_confirmation_code'], 'confirmcode-msg', 'invalid-confirmation-code',$language);
            return get_400_error( $json );
        } else {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['confirmcode']['confirmation_code_valid'], 'confirmcode-msg', 'confirmation-code-valid',$language);
            $json['data']['user_id'] = (int)$user_id ;
            return get_200_success( $json );
        }
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['confirmcode']['confirmation_code_time_limit'], 'confirmcode-msg', 'confirmation-code-time-limit',$language);
        return get_400_error( $json );
    }    
}

/* Name: Hardika Satasiya
 * Function :forgotPassword
 * Purpose: To reset forgot password with new password .
 */

function forgotPassword( $request_data ){
   
    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = array();
    $user_id = $new_password = $confim_password =  $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    // INITIALIZATION END
    
    if( empty( $request_data['new_password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['forgotPassword']['new_password_not_provided'], 'forgotpassword-msg', 'new-password-not-provided',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['confirm_password'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['forgotPassword']['confirmpassword_not_provided'], 'forgotpassword-msg', 'confirmpassword-not-provided',$language);
        return get_400_error( $json );
    }

    $new_password = $request_data['new_password'];
    $confim_password = $request_data['confirm_password'];

    if( $new_password != $confim_password ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['forgotPassword']['password_not_matching'], 'forgotpassword-msg', 'password-not-matching',$language);
        return get_400_error( $json );
    }

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['generic_error'], 'api-msg', 'generic-error',$language);
        return get_400_error( $json );
    }
    
    $user_id = $request_data['user_id'];
    $user = get_user_by( 'id', $user_id );
    if( $user ) {
       wp_set_password( $new_password, $user_id ); //password reset
        $data['user_id'] = (int)$user->data->ID;
        $data['email_address'] = $user->data->user_email;
        $json['data']  = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['forgotPassword']['password_change_success'], 'forgotpassword-msg', 'password-change-success',$language);
        return get_200_success( $json );
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['forgotPassword']['user_not_found'], 'forgotpassword-msg', 'user-not-found',$language);
        return get_400_error( $json );
    }
}