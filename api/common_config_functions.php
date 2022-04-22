<?php
function getConfiguration($request_data) {
	global $message_global;
	$json = $data = array();
    $json['data']  = $data_product_categories = $data_social_categories = $data_flyer_categories = $data_currencies = $data_languages = $data_asset_class = $data_investment_universe = $data_fund_family = array();
    $json['message'] = '';
    $language = ( isset( $request_data['lang'] ) ) ? $request_data['lang'] : "en";

	$product_categories = get_terms(array('taxonomy' => 'product-category','hide_empty' => false));
	if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
		$counter = 0;
		foreach ( $product_categories as $product_category ) {
			$data_product_categories[$counter]['id'] = $product_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$product_category->term_id, 'product-category', TRUE, $language);
			$data_product_categories[$counter]['name'] = get_translated_category($translated_id,'product-category');
			$counter++;
		}
	}

	$social_posts_categories = get_terms(array('taxonomy' => 'social-post-category','hide_empty' => false));
	if ( ! empty( $social_posts_categories ) && ! is_wp_error( $social_posts_categories ) ) {
		$counter = 0;
		foreach ( $social_posts_categories as $social_post_category ) {
			$data_social_categories[$counter]['id'] = $social_post_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$social_post_category->term_id, 'social-post-category', TRUE, $language);
			$data_social_categories[$counter]['name'] = get_translated_category($translated_id,'social-post-category');
			$counter++;
		}
	}

	$flyer_categories = get_terms(array('taxonomy' => 'flyer-category','hide_empty' => false));
	if ( ! empty( $flyer_categories ) && ! is_wp_error( $flyer_categories ) ) {
		$counter = 0;
		foreach ( $flyer_categories as $flyer_category ) {
			$data_flyer_categories[$counter]['id'] = $flyer_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$flyer_category->term_id, 'flyer-category', TRUE, $language);
			$data_flyer_categories[$counter]['name'] = get_translated_category($translated_id,'flyer-category');
			$counter++;
		}
	}

    $asset_class_categories = get_terms(array('taxonomy' => 'assetclass','hide_empty' => false));
    if ( ! empty( $asset_class_categories ) && ! is_wp_error( $asset_class_categories ) ) {
        $counter = 0;
        foreach ( $asset_class_categories as $asset_class_category ) {
            $data_asset_class[$counter]['id'] = $asset_class_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$asset_class_category->term_id, 'assetclass', TRUE, $language);
            $data_asset_class[$counter]['name'] = get_translated_category($translated_id,'assetclass');
            $counter++;
        }
    }

    $investment_universe_categories = get_terms(array('taxonomy' => 'investmentuniverse','hide_empty' => false));
    if ( ! empty( $investment_universe_categories ) && ! is_wp_error( $investment_universe_categories ) ) {
        $counter = 0;
        foreach ( $investment_universe_categories as $investment_universe_category ) {
            $data_investment_universe[$counter]['id'] = $investment_universe_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$investment_universe_category->term_id, 'investmentuniverse', TRUE, $language);
            $data_investment_universe[$counter]['name'] = get_translated_category($translated_id,'investmentuniverse');
            $counter++;
        }
    }

    $fund_family_categories = get_terms(array('taxonomy' => 'fundfamily','hide_empty' => false));
    if ( ! empty( $fund_family_categories ) && ! is_wp_error( $fund_family_categories ) ) {
        $counter = 0;
        foreach ( $fund_family_categories as $fund_family_category ) {
            $data_fund_family[$counter]['id'] = $fund_family_category->term_id;
            $translated_id = apply_filters( 'wpml_object_id',$fund_family_category->term_id, 'fundfamily', TRUE, $language);
            $data_fund_family[$counter]['name'] = get_translated_category($translated_id,'fundfamily');
            $fund_image = (get_field('fund_family_image', $fund_family_category)) ? get_field('fund_family_image', $fund_family_category) : "";
            $data_fund_family[$counter]['image'] = $fund_image;
            $counter++;
        }
    }

    $field = get_field_object('field_5d1edff0549ed');   
    if( !empty( $field['choices'] ) ){
        $counter = 0;
        foreach ($field['choices'] as $key => $value) {
            $data_currencies[$counter]['currency_code'] = $key;
            $data_currencies[$counter]['display_name'] = $value;
            $counter++;
        }
    }    

    $languages = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
    if($languages) {
        $counter = 0;
        foreach ($languages as $key => $value) {
            $data_languages[$counter]['language_code'] = $key;
            $data_languages[$counter]['language'] = get_language_name($key);
            $counter++;
        }
    }
	$json['data']['category']['product'] = $data_product_categories;
	$json['data']['category']['social_post'] = $data_social_categories;
	$json['data']['category']['flyer'] = $data_flyer_categories;
    $json['data']['fund-categories']['asset-class'] = $data_asset_class;
    $json['data']['fund-categories']['investment-universe'] = $data_investment_universe;
    $json['data']['fund-categories']['fund-family'] = $data_fund_family;
    $json['data']['currencies'] = $data_currencies;
    $json['data']['languages'] = $data_languages;

    $risk_profile_help = apply_filters( 'wpml_object_id', 2711, 'page', TRUE, $language);
    $important_disclosure = apply_filters( 'wpml_object_id', 2707, 'page', TRUE, $language);
    $privacy_policy = apply_filters( 'wpml_object_id', 2709, 'page', TRUE, $language);
    $terms_and_conditions = apply_filters( 'wpml_object_id', 3304, 'page', TRUE, $language);
    $json['data']['risk_profile_help'] = get_permalink($risk_profile_help);
    $json['data']['important_disclosure'] = get_permalink($important_disclosure);
    $json['data']['privacy_policy'] = get_permalink($privacy_policy);
    $json['data']['terms_and_conditions'] = get_permalink($terms_and_conditions);
    $json['data']['calculator_max_range_value'] = ( get_acf_option('calculator_max_range_value',$language) ) ? get_acf_option('calculator_max_range_value',$language) : "5000000";

	$json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getconfiguration']['success'], 'getconfiguration-msg', 'success',$language);
	return get_200_success($json);
}

function versionController( $request_data ){
	$json = array();
    global $message_global;
    $json['data'] = array();
    $json['message'] = '';
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['version'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['versioncontroller']['version_not_provided'], 'versioncontroller-msg', 'version-not-provided',$language);
        return get_400_error( $json );
    } else{
        $version = $request_data['version'];
    }

    if( empty( $request_data['os_type'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['versioncontroller']['os_type_not_provided'], 'versioncontroller-msg', 'os-type-not-provided',$language);
        return get_400_error( $json );
    } else{
        $os_type = $request_data['os_type'];
    }

    if( $os_type == 1 || $os_type == 2 ) { 
    	if($os_type==1) {
    		//NON-TRANSLATEABLE OPTIONS
            $ios_min_version = get_acf_option('ios_min_version','en');
    		$ios_current_version = get_acf_option('ios_current_version','en');
            $ios_app_link = get_acf_option('ios_app_link','en');
            //TRANSLATEABLE OPTIONS
    		$ios_hard_update_message = get_acf_option('ios_hard_update_message',$language);
			$ios_soft_update_message = get_acf_option('ios_soft_update_message',$language);

			$json['data']['app_link'] = $ios_app_link;
    		if($version < $ios_min_version) {
    			$json['data']['is_soft'] = false;
    			$json['data']['is_update'] = true;
    			$json['data']['message'] = $ios_hard_update_message;
    			$json['message'] = $ios_hard_update_message;
                $json['data'] = array();
    			return get_200_success( $json );
    		} else if($ios_min_version <= $version && $version < $ios_current_version) {
    			$json['data']['is_soft'] = true;
    			$json['data']['is_update'] = true;
    			$json['data']['message'] = $ios_soft_update_message;
    			$json['message'] = $ios_soft_update_message;
                $json['data'] = array();
    			return get_200_success( $json );
    		} else if($version >= $ios_current_version) {
    			$json['data']['is_soft'] = '';
    			$json['data']['is_update'] = false;
    			$json['data']['message'] = '';
                $json['data'] = array();
    			return get_200_success( $json );
    		}

    	} else {
            //NON-TRANSLATEABLE OPTIONS
    		$android_min_version = get_acf_option('android_min_version', 'en');
    		$android_current_version = get_acf_option('android_current_version', 'en');
            $android_app_link = get_acf_option('android_app_link','en');
            //TRANSLATEABLE OPTIONS
    		$android_hard_update_message = get_acf_option('android_hard_update_message',$language);
			$android_soft_update_message = get_acf_option('android_soft_update_message',$language);

			$json['data']['app_link'] = $android_app_link;
    		if($version < $android_min_version) {
    			$json['data']['is_soft'] = false;
    			$json['data']['is_update'] = true;
    			$json['data']['message'] = $android_hard_update_message;
    			$json['message'] = $android_hard_update_message;
                $json['data'] = array();
    			return get_200_success( $json );
    		} else if($android_min_version <= $version && $version < $android_current_version) {
    			$json['data']['is_soft'] = true;
    			$json['data']['is_update'] = true;
    			$json['data']['message'] = $android_soft_update_message;
    			$json['message'] = $android_soft_update_message;
                $json['data'] = array();
    			return get_200_success( $json );
    		} else if($version >= $android_current_version) {
    			$json['data']['is_soft'] = '';
    			$json['data']['is_update'] = false;
    			$json['data']['message'] = '';
                $json['data'] = array();
    			return get_200_success( $json );
    		}

    	}
    } else {
    	$json['message'] = $message_global['generic_error'];
        return get_400_error( $json );
    }
} 
function sessionManagement( $request_data ) {
	$json = array();
    global $message_global;
    $json['data'] = array();
    $json['message'] = $device_info = $current_version = '';
    $user_id = $session_count = 0;

    if( empty( $request_data['user_id'] ) ) {
        $json['message'] = $message_global['sessionmanagement']['user_id_not_provided'];
        return get_400_error( $json );
    } else{
        $user_id = $request_data['user_id'];
    }

    if( empty( $request_data['os_type'] ) ) {
        $json['message'] = $message_global['sessionmanagement']['os_type_not_provided'];
        return get_400_error( $json );
    } else{
        $os_type = $request_data['os_type'];
    }

    if( empty( $request_data['session_data'] ) ) {
        $json['message'] = $message_global['sessionmanagement']['session_data_not_provided'];
        return get_400_error( $json );
    } else{
        $session_data = $request_data['session_data'];
    }

    if( !empty( $request_data['device_info'] ) ) {
    	$device_info = $request_data['device_info'];
    }

    if( !empty( $request_data['current_version'] ) ) {
    	$current_version = $request_data['current_version'];
    }

    if( $os_type == 1 || $os_type == 2 ) { 
    	global $wpdb;
    	$prefix = $wpdb->prefix;
        $tablename = $prefix.'session_management';
    	$session_count = $wpdb->get_var( "SELECT COUNT(user_id) FROM $tablename where user_id = ". $user_id );
        if($session_count > 9) {
        	$min_id = $wpdb->get_var( "SELECT MIN(id) FROM $tablename WHERE user_id = ". $user_id );
        	$wpdb->delete( $tablename, array( 'id' => $min_id ) );
        }
        $query_args = array( 
                        'user_id' => (int)$user_id, 
                        'current_version' => $current_version,
                        'os_type' => $os_type,
                        'device_info' => $device_info,
                        'session_data' => $session_data
                    );
        $wpdb->insert( $tablename, $query_args );
        $json['message'] = $message_global['sessionmanagement']['session_data_save_success'];
        return get_200_success( $json );
    } else {
    	$json['message'] = $message_global['generic_error'];
        return get_400_error( $json );
    }
}

function errorHandling( $request_data ) {
    $json = array();
    global $message_global;
    $json['data'] = array();
    $json['message'] = $email_body = "";
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    $email_address = 'nirmalsinh@creolestudios.com';
    $email_body .= "<html><body>";
    $email_body = __('Hello Dev,' ,'investorstrust');
    $email_body .= '<br/><br/>';
    $email_body .= __('Exception generated on Investors Trust. Please find below details : ' ,'investorstrust');
    $parameters = $request_data->get_params();
    if(is_array( $parameters )){
        $counter = 1;
        $email_body .= '<table style="font-family: arial, sans-serif; border-collapse: collapse; margin-top:20px;">';
        foreach ($parameters as $key => $value) {
            $style = "";
            if($counter%2==0) {
                $style = "background-color: #dddddd;";
            }
            $email_body .= "<tr style='".$style."'><td style='border: 1px solid #dddddd;text-align: left;padding: 8px;'>".$key." : </td><td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>".$value."</td>";
            $counter++;
        }
        $email_body .= '</table>';
    }
    $email_body .= "</body></html>";
    $emailSubject = __('Investors Trust : Mobile Exception');
    $headers[] =  'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";    
    if (wp_mail( $email_address, $emailSubject, $email_body, $headers )) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['errorhandling']['success'], 'errorhandling-msg', 'success',$language);
        $message_global['errorhandling']['success'];
        return get_200_success( $json );
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['errorhandling']['failure'], 'errorhandling-msg', 'failure',$language);
        return get_400_error( $json );
    }    
}
?>