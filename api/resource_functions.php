<?php

function getResources( $request_data ){
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    $data['calculator'][0]['logo']        = (get_acf_option('resources_retirement_calculator_logo',$language)) ? wp_get_attachment_url(get_acf_option('resources_retirement_calculator_logo',$language)) : "";
    $data['calculator'][0]['title']       = (get_acf_option('resources_retirement_calculator_title',$language)) ? get_acf_option('resources_retirement_calculator_title',$language) : "";
    $data['calculator'][0]['subtitle']       = (get_acf_option('resources_retirement_calculator_subtitle',$language)) ? get_acf_option('resources_retirement_calculator_subtitle',$language) : "";
    $data['calculator'][0]['description'] = (get_acf_option('resources_retirement_calculator_description',$language)) ? get_acf_option('resources_retirement_calculator_description',$language) : "";
    $data['calculator'][1]['logo']         = (get_acf_option('resources_education_calculator_logo',$language)) ? wp_get_attachment_url(get_acf_option('resources_education_calculator_logo',$language)) : "";
    $data['calculator'][1]['title']        = (get_acf_option('resources_education_calculator_title',$language)) ? get_acf_option('resources_education_calculator_title',$language) : "";
    $data['calculator'][1]['subtitle']        = (get_acf_option('resources_education_calculator_subtitle',$language)) ? get_acf_option('resources_education_calculator_subtitle',$language) : "";
    $data['calculator'][1]['description']  = (get_acf_option('resources_education_calculator_description',$language)) ? get_acf_option('resources_education_calculator_description',$language) : "";
    $data['calculator'][2]['logo']            = (get_acf_option('resources_saving_calculator_logo',$language)) ? wp_get_attachment_url(get_acf_option('resources_saving_calculator_logo',$language)) : "";
    $data['calculator'][2]['title']           = (get_acf_option('resources_saving_calculator_title',$language)) ? get_acf_option('resources_saving_calculator_title',$language) : "";
    $data['calculator'][2]['subtitle']           = (get_acf_option('resources_saving_calculator_subtitle',$language)) ? get_acf_option('resources_saving_calculator_subtitle',$language) : "";
    $data['calculator'][2]['description']     = (get_acf_option('resources_saving_calculator_description',$language)) ? get_acf_option('resources_saving_calculator_description',$language) : "";

    $data['risk_profile']['banner'] = (get_acf_option('risk_profile_banner',$language)) ? wp_get_attachment_url(get_acf_option('risk_profile_banner',$language)) : "";
    $data['risk_profile']['title'] = (get_acf_option('risk_profile_title',$language)) ? get_acf_option('risk_profile_title',$language) : "";
    $data['risk_profile']['short_desc'] = (get_acf_option('risk_profile_short_Description', $language)) ? get_acf_option('risk_profile_short_Description', $language) : "";
    $data['risk_profile']['category'] = (get_acf_option('risk_profile_category', $language)) ? get_acf_option('risk_profile_category', $language) : "";

    $data['fund_platform']['banner'] = (get_acf_option('fund_profile_banner', $language)) ? wp_get_attachment_url(get_acf_option('fund_profile_banner', $language)) : "";
    $data['fund_platform']['title'] = (get_acf_option('fund_platform_title', $language)) ? get_acf_option('fund_platform_title', $language) : "";
    $data['fund_platform']['short_desc'] = (get_acf_option('fund_platform_short_description', $language)) ? get_acf_option('fund_platform_short_description', $language) : "";
    $data['fund_platform']['category'] = (get_acf_option('fund_platform_category', $language)) ? get_acf_option('fund_platform_category', $language) : "";
    $data['fund_platform']['description'] = (get_acf_option('fund_platform_description', $language)) ? get_acf_option('fund_platform_description', $language) : "";

    $push['banner']     = (get_acf_option('company_timeline_banner',$language)) ? wp_get_attachment_url(get_acf_option('company_timeline_banner',$language)) : "";
    $push['title']      = (get_acf_option('company_timeline_title',$language)) ? get_acf_option('company_timeline_title',$language) : "";
    $push['short_desc'] = (get_acf_option('company_timeline_short_description', $language)) ? get_acf_option('company_timeline_short_description', $language) : "";
    $push['category']   = (get_acf_option('company_timeline_category', $language)) ? get_acf_option('company_timeline_category', $language) : "";

    if(!empty($push['title'])) {
        $data['company_timeline'] = $push;
    }

    $data['portfolio_builder']['banner'] = (get_acf_option('portfolio_builder_banner',$language)) ? wp_get_attachment_url(get_acf_option('portfolio_builder_banner',$language)) : "";
    $data['portfolio_builder']['title'] = (get_acf_option('portfolio_builder_title',$language)) ? get_acf_option('portfolio_builder_title',$language) : "";
    $data['portfolio_builder']['mstar_logo'] = (get_acf_option('portfolio_builder_morning_star_logo',$language)) ? wp_get_attachment_url(get_acf_option('portfolio_builder_morning_star_logo',$language)) : "";

    $json['data'] = $data;
    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['getresources']['resources_listed'], 'getresources-msg', 'resources-listed',$language);
    return get_200_success($json);
}

function getSearchFund($request_data) {
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    // $post_per_page = (int)get_option( 'posts_per_page');
    $post_per_page = -1;
    $paged = (empty($request_data['paged'])) ? 1 : (int)$request_data['paged'];
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['currency_code'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['currency_code_not_provided'], 'getsearchfund-msg', 'currency-code-not-provided',$language);
        return get_400_error($json);
    } else {
        $currency_code =  $request_data['currency_code'];
        $currency_codes = get_field_object('field_5d1edff0549ed');
        if(sizeof($currency_codes)) {
            if(array_key_exists('choices',$currency_codes)) {
                $choices = $currency_codes['choices'];
                if(!array_key_exists($currency_code, $choices)){
                    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['wrong_currency_code'], 'getsearchfund-msg', 'wrong-currency-code',$language);
                    return get_400_error($json);
                }
            }
        }
    }

    $assetclass = "";
    if(!empty($request_data['asset_class'])) {
        $assetclass = explode(",",$request_data['asset_class']); 
    }

    $investmentuniverse = "";
    if(!empty($request_data['investment_universe'])) {
        $investmentuniverse = explode(",",$request_data['investment_universe']); 
    }

    $fundfamily = "";
    if(!empty($request_data['fund_family'])) {
        $fundfamily = explode(",",$request_data['fund_family']); 
    }

    $search_string = "";
    if(!empty($request_data['search_string'])) {
        $search_string = $request_data['search_string']; 
    }

    $args = array(
                  'post_type'       => 'funds',
                  'post_status'     => 'publish',
                  'posts_per_page'  => -1,                  
                  'order'           => 'ASC',
                  'meta_query'      => array(
                                            'relation' => 'AND',
                                            array('key' => 'select_currency',
                                                'compare' => '=',
                                                'value' => $currency_code 
                                            )
                                       ));    


    if($assetclass!="" || $investmentuniverse!="" || $fundfamily !="") {
        $tax_query = array( 'relation' => 'AND' );
        if(is_array($assetclass)) {
            $tax_query[] = array(
                                    'taxonomy' => 'assetclass',
                                    'field'    => 'id',
                                    'terms'    => $assetclass,
                                    'operator' => 'IN'
                                );
        }
        if(is_array($investmentuniverse)) {
            $tax_query[] = array(
                                    'taxonomy' => 'investmentuniverse',
                                    'field'    => 'id',
                                    'terms'    => $investmentuniverse,
                                    'operator' => 'IN'
                                );
        }
        if(is_array($fundfamily)) {
            $tax_query[] = array(
                                    'taxonomy' => 'fundfamily',
                                    'field'    => 'id',
                                    'terms'    => $fundfamily,
                                    'operator' => 'IN'
                                );
        }
        $args['tax_query'] = $tax_query;
    }

    if($search_string!="") {
        if(get_isin_posts($search_string)) {
            $args['meta_query'][] = array(
                'key'   => 'isin',
                'value' => $search_string,
                'compare' => 'LIKE'
            );    
        } else {
            $args['s'] = $search_string;
        }      
    }

    $query = new WP_Query($args);
    $total_funds = $query->post_count;

    $args['posts_per_page'] = $post_per_page;
    $args['paged'] = $paged;

    $funds = new WP_Query($args);
    if( $funds->have_posts() ) {
        $fund_counter = 0; // Counter to loop through posts
        while ( $funds->have_posts() ) {  //if have post loop the loop
            $funds->the_post(); 
            $data[$fund_counter]['title'] = (get_the_title()) ? html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8') : '';

            $fund_image = "";
            $fund_family = get_the_terms( get_the_ID(), 'fundfamily' ); 
            if($fund_family) {
                foreach ($fund_family as $term) {
                    $fund_image = (get_field('fund_family_image', $term)) ? get_field('fund_family_image', $term) : "";
                }
            }

            $data[$fund_counter]['image'] = $fund_image;
            $data[$fund_counter]['isin'] = (get_field('isin')) ? get_field('isin') : '';
            $data[$fund_counter]['fund_code'] = (get_field('fund_code')) ? get_field('fund_code') : '';
            $file_url = "";
            if( get_field('file_name') ){
                $file_url = get_fund_path('funds','en', get_field('file_name'));
            }
            $data[$fund_counter]['pdf'] = $file_url;
            $data[$fund_counter]['assetclass'] = join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'assetclass' ), 'name'));
            $data[$fund_counter]['investmentuniverse'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'investmentuniverse' ), 'name'));
            $data[$fund_counter]['fundfamily'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'fundfamily' ), 'name'));           
            $fund_counter++;
        }
    }

    $total_pages = ceil(((int)($total_funds)) / $post_per_page);
    $json['data']['total_pages'] = $total_pages;
    $json['data']['current_page'] = $paged;
    $json['data']['posts'] = $data;
    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['no_data'], 'getsearchfund-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['success'], 'getsearchfund-msg', 'success',$language);
    }
    return get_200_success($json);
}

function getRiskQuestion( $request_data ) {
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    $args = array('post_type'       => 'resource',
                  'post_status'     => 'publish',
                  'posts_per_page'  => 1,                  
                  'order'           => 'DESC');

    $resource = new WP_Query($args);
    if( $resource->have_posts() ) {
        while ( $resource->have_posts() ) {
            $resource->the_post();
            $english_id = (int)get_the_ID();
            // FETCHING TRANSLATION POST ID
            $post_id = apply_filters( 'wpml_object_id', $english_id, 'resource', TRUE, $language);
            $data['resource_id'] = $english_id;

            $counter = 0;
            if( have_rows('question_and_answer_field',$post_id) ):
                // loop through the rows of data
                while ( have_rows('question_and_answer_field',$post_id) ) : the_row();
                    $data['questions'][$counter]['question'] = (get_sub_field('question')) ? get_sub_field('question') : '';
                    $data['questions'][$counter]['options_display'] = (get_sub_field('options_view')) ? get_sub_field('options_view') : '';
                    $answer_counter = 0;
                    $answers_data = array();
                    $answers = get_sub_field('answers');
                    if($answers) {
                        foreach($answers as $answer) {
                            $answers_data[$answer_counter]['answer'] = $answer['answer'];
                            $answers_data[$answer_counter]['weightage'] = (int)$answer['value'];
                            $answer_counter++;
                        }
                    }
                    $data['questions'][$counter]['answers'] = $answers_data;
                    $counter++;
                endwhile;
            endif;           
        }
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskquestion']['success'], 'getriskquestion-msg', 'success',$language);
        wp_reset_postdata();
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskquestion']['no_data'], 'getriskquestion-msg', 'no-data',$language);
    }
    $json['data'] = $data;
    return get_200_success($json);
}


function getRiskDetail($request_data) {
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( isset( $request_data['total_score'] ) && $request_data['total_score']!=NULL ){ 
        $total_score = (int)$request_data['total_score'];
    } else {
        $total_score = 0;
        /*$json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskdetail']['total_score_not_provided'], 'getriskdetail-msg', 'total-score-not-provided',$language);
        return get_400_error($json);*/
    }

    if( empty( $request_data['resource_id'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskdetail']['no_resource_id'], 'getriskdetail-msg', 'no-resource-id',$language);
        return get_400_error($json);
    } else {
        $post_id = $request_data['resource_id'];
    }

    $post = get_post( $post_id );
    if( !isset( $post ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskdetail']['post_id_not_found'], 'getriskdetail-msg', 'post-id-not-found',$language);
        return get_400_error( $json );
    } else {
        if($post->post_type != "resource") {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskdetail']['post_id_not_found'], 'getriskdetail-msg', 'post-id-not-found',$language);
            return get_400_error( $json );
        }
    }

    $english_id = $post->ID;
    $resource_id = apply_filters( 'wpml_object_id', $english_id, 'resource', TRUE, $language);
    $range_counter = 0;
    if( have_rows('score_breakdown',$resource_id) ):
        while ( have_rows('score_breakdown',$resource_id) ) : the_row();
            $minimum_range = (get_sub_field('minimum_range')) ? (int)get_sub_field('minimum_range') : (int)0;
            $maximum_range = (get_sub_field('maximum_range')) ? (int)get_sub_field('maximum_range') : (int)0;
            if($total_score>=$minimum_range && $total_score<=$maximum_range) {
                $data['profile_details'][$range_counter]['profile_name'] = (get_sub_field('profile')) ? get_sub_field('profile') : "";
                $data['profile_details'][$range_counter]['profile_description'] = (get_sub_field('profile_description')) ? get_sub_field('profile_description') : "";
                $funds_data = array();
                $fund_counter = 0;
                $funds = get_sub_field('funds');
                if($funds) {
                    foreach($funds as $fund) {
                        $obj = $fund['fund'];
                        $fund_id = $obj->ID;
                        $funds_data[$fund_counter]['fund_detail']['fund_id'] = (int)$fund_id;
                        $funds_data[$fund_counter]['fund_detail']['title'] = html_entity_decode(get_the_title($fund_id), ENT_COMPAT, 'UTF-8');
                        $fund_image = "";
                        $fund_family = get_the_terms( $fund_id, 'fundfamily' ); 
                        if($fund_family) {
                            foreach ($fund_family as $term) {
                                $fund_image = (get_field('fund_family_image', $term)) ? get_field('fund_family_image', $term) : "";
                            }
                        }
                        $funds_data[$fund_counter]['fund_detail']['image'] = $fund_image;
                        $funds_data[$fund_counter]['fund_detail']['isin'] = (get_field('isin',$fund_id)) ? get_field('isin',$fund_id) : '';
                        $funds_data[$fund_counter]['fund_detail']['fund_code'] = (get_field('fund_code',$fund_id)) ? get_field('fund_code',$fund_id) : '';
                        $file_url = "";
                        if( get_field('file_name',$fund_id) ){
                            $file_url = get_fund_path('funds','en', get_field('file_name',$fund_id));
                        }
                        $funds_data[$fund_counter]['fund_detail']['pdf'] = $file_url;
                        $funds_data[$fund_counter]['fund_detail']['fundfamily'] = strip_tags( get_the_term_list($fund_id, 'fundfamily', '', ','));
                        $funds_data[$fund_counter]['fund_detail']['investmentuniverse'] = strip_tags( get_the_term_list($fund_id, 'investmentuniverse', '', ','));
                        $funds_data[$fund_counter]['fund_detail']['assetclass'] = strip_tags( get_the_term_list($fund_id, 'assetclass', '', ','));
                        $funds_data[$fund_counter]['weightage'] = (int)$fund['value'];
                        $fund_counter++;
                    }
                }
                $data['profile_details'][$range_counter]['funds'] = $funds_data;
                break; 
            }
        endwhile;
    endif;

    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getriskdetail']['no_profile_found'], 'getriskdetail-msg', 'no-profile-found',$language);
    }
    $json['data'] = $data;
    return get_200_success($json);
}

function uploadImage($request_data) {
    $json = array();
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";

    if(!isset( $_FILES['img'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['uploadimage']['no_image_provided'], 'uploadimage-msg', 'no-image-provided',$language);
        return get_400_error($json);
    } else {
        $img = $request_data['img'];
    }

    if( isset( $_FILES['img'] ) ) {
        $uploaded_filedata = $request_data->get_file_params();
            // CHECKING IF ANY FILE IS UPLOADED OR NOT
        if( sizeof( $uploaded_filedata ) ) {
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
            }
            $uploadedfile = $uploaded_filedata['img'];
            $upload_overrides = array( 'test_form' => false );

            // Register our path override.
            add_filter( 'upload_dir', 'investors_trust_custom_upload_dir' );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            // Set everything back to normal.
            remove_filter( 'upload_dir', 'investors_trust_custom_upload_dir' );
            if ( $movefile && ! isset( $movefile['error'] ) ) {
                $json['data'] = $movefile;
               $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['uploadimage']['success'], 'uploadimage-msg', 'success',$language);
                return get_200_success($json);                
            } else {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['uploadimage']['error_uploading_file'], 'uploadimage-msg', 'error-uploading-file',$language);
                return get_400_error($json);
            }
        }
    }
}


function investors_trust_custom_upload_dir( $dir ) {
    return array(
        'path'   => $dir['basedir'] . '/riskprofiles',
        'url'    => $dir['baseurl'] . '/riskprofiles',
        'subdir' => '/riskprofiles',
    ) + $dir;
}

function getPortfolioDetail($request_data){
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    $data['title'] = ( get_acf_option('portfolio_builder_title',$language) ) ? get_acf_option('portfolio_builder_title',$language) : "";
    $data['description'] = ( get_acf_option('portfolio_builder_description',$language) ) ? get_acf_option('portfolio_builder_description',$language) : "";
    $data['full_name'] = ( get_acf_option('portfolio_builder_name_text',$language) ) ? get_acf_option('portfolio_builder_name_text',$language) : "";
    $data['currency'] = ( get_acf_option('portfolio_builder_currency_text',$language) ) ? get_acf_option('portfolio_builder_currency_text',$language) : "";
    $data['total_amount'] = ( get_acf_option('portfolio_builder_total_amount_text',$language) ) ? get_acf_option('portfolio_builder_total_amount_text',$language) : "";
    $data['total_amount_tooltip_title'] = ( get_acf_option('portfolio_builder_total_amount_text_tooltip_title',$language) ) ? get_acf_option('portfolio_builder_total_amount_text_tooltip_title',$language) : "";
    $data['total_amount_tooltip_description'] = ( get_acf_option('portfolio_builder_total_amount_text_tooltip_description',$language) ) ? get_acf_option('portfolio_builder_total_amount_text_tooltip_description',$language) : "";
    $data['show_prepared_by'] = ( get_acf_option('portfolio_builder_show_prepared_by_text',$language) ) ? get_acf_option('portfolio_builder_show_prepared_by_text',$language) : "";
    $data['show_prepared_by_tooltip_title'] = ( get_acf_option('portfolio_builder_show_prepared_by_text_tooltip_title',$language) ) ? get_acf_option('portfolio_builder_show_prepared_by_text_tooltip_title',$language) : "";
    $data['show_prepared_by_tooltip_description'] = ( get_acf_option('portfolio_builder_show_prepared_by_text_tooltip_description',$language) ) ? get_acf_option('portfolio_builder_show_prepared_by_text_tooltip_description',$language) : "";
    $data['funds_contribution'] = ( get_acf_option('portfolio_builder_funds_contribution_text',$language) ) ? get_acf_option('portfolio_builder_funds_contribution_text',$language) : "";
    $data['funds_contribution_tooltip_title'] = ( get_acf_option('portfolio_builder_funds_contribution_text_tooltip_title',$language) ) ? get_acf_option('portfolio_builder_funds_contribution_text_tooltip_title',$language) : "";
    $data['funds_contribution_tooltip_description'] = ( get_acf_option('portfolio_builder_funds_contribution_text_tooltip_description',$language) ) ? get_acf_option('portfolio_builder_funds_contribution_text_tooltip_description',$language) : "";

    if ( !empty($data['title']) ) {
        $json['data'] = $data;
    }
    else {
        $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['no_text_listed'], 'getportfoliodetail-msg', 'no-text-listed',$language);
        return get_400_error( $json );
    }

    $json['message'] =  apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['text_listed'], 'getportfoliodetail-msg', 'text-listed',$language);
    return get_200_success($json);
}

function getPortfolioFund($request_data) {
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    // $post_per_page = (int)get_option( 'posts_per_page');
    $post_per_page = -1;
    $paged = (empty($request_data['paged'])) ? 1 : (int)$request_data['paged'];
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";
    
    if( empty( $request_data['currency_code'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['currency_code_not_provided'], 'getportfoliodetail-msg', 'currency-code-not-provided',$language);
        return get_400_error($json);
    } else {
        $currency_code =  $request_data['currency_code'];
        $currency_codes = get_field_object('field_5d1edff0549ed');
        if(sizeof($currency_codes)) {
            if(array_key_exists('choices',$currency_codes)) {
                $choices = $currency_codes['choices'];
                if(!array_key_exists($currency_code, $choices)){
                    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['wrong_currency_code'], 'getportfoliodetail-msg', 'wrong-currency-code',$language);
                    return get_400_error($json);
                }
            }
        }
    }

    $assetclass = "";
    if(!empty($request_data['asset_class'])) {
        $assetclass = explode(",",$request_data['asset_class']); 
    }

    $investmentuniverse = "";
    if(!empty($request_data['investment_universe'])) {
        $investmentuniverse = explode(",",$request_data['investment_universe']); 
    }

    $fundfamily = "";
    if(!empty($request_data['fund_family'])) {
        $fundfamily = explode(",",$request_data['fund_family']); 
    }

    $search_string = "";
    if(!empty($request_data['search_string'])) {
        $search_string = $request_data['search_string']; 
    }

    $args = array(
                  'post_type'       => 'funds',
                  'post_status'     => 'publish',
                  'posts_per_page'  => -1,                  
                  'order'           => 'ASC',
                  'meta_query'      => array(
                                            'relation' => 'AND',
                                            array('key' => 'select_currency',
                                                'compare' => '=',
                                                'value' => $currency_code 
                                            )
                                       ));    


    if($assetclass!="" || $investmentuniverse!="" || $fundfamily !="") {
        $tax_query = array( 'relation' => 'AND' );
        if(is_array($assetclass)) {
            $tax_query[] = array(
                                    'taxonomy' => 'assetclass',
                                    'field'    => 'id',
                                    'terms'    => $assetclass,
                                    'operator' => 'IN'
                                );
        }
        if(is_array($investmentuniverse)) {
            $tax_query[] = array(
                                    'taxonomy' => 'investmentuniverse',
                                    'field'    => 'id',
                                    'terms'    => $investmentuniverse,
                                    'operator' => 'IN'
                                );
        }
        if(is_array($fundfamily)) {
            $tax_query[] = array(
                                    'taxonomy' => 'fundfamily',
                                    'field'    => 'id',
                                    'terms'    => $fundfamily,
                                    'operator' => 'IN'
                                );
        }
        $args['tax_query'] = $tax_query;
    }

    if($search_string!="") {
        if(get_isin_posts($search_string)) {
            $args['meta_query'][] = array(
                                        'key'   => 'isin',
                                        'value' => $search_string,
                                        'compare' => 'LIKE'
                                    );    
        } elseif(get_fund_code_posts($search_string)) {
            $args['meta_query'][] = array(
                                        'key'   => 'fund_code',
                                        'value' => $search_string,
                                        'compare' => 'LIKE'
                                    );    
        }
         else {
            $args['s'] = $search_string;
        }      
    }
    
    $query = new WP_Query($args);
    $total_funds = $query->post_count;

    $args['posts_per_page'] = $post_per_page;
    $args['paged'] = $paged;

    $funds = new WP_Query($args);
    if( $funds->have_posts() ) {
        $fund_counter = 0; // Counter to loop through posts
        while ( $funds->have_posts() ) {  //if have post loop the loop
            $funds->the_post(); 
            $data[$fund_counter]['fund_id'] = get_the_ID();
            $data[$fund_counter]['title'] = (get_the_title()) ? html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8') : '';

            $fund_image = "";
            $fund_family = get_the_terms( get_the_ID(), 'fundfamily' ); 
            if($fund_family) {
                foreach ($fund_family as $term) {
                    $fund_image = (get_field('fund_family_image', $term)) ? get_field('fund_family_image', $term) : "";
                }
            }

            $data[$fund_counter]['image'] = $fund_image;
            $data[$fund_counter]['isin'] = (get_field('isin')) ? get_field('isin') : '';
            $data[$fund_counter]['fund_code'] = (get_field('fund_code')) ? get_field('fund_code') : '';
            // $file_url = "";
            // if( get_field('file_name') ){
            //     $file_url = get_fund_path('funds','en', get_field('file_name'));
            // }
            // $data[$fund_counter]['pdf'] = $file_url;
            $data[$fund_counter]['assetclass'] = join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'assetclass' ), 'name'));
            $data[$fund_counter]['investmentuniverse'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'investmentuniverse' ), 'name'));
            $data[$fund_counter]['fundfamily'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'fundfamily' ), 'name'));           
            $fund_counter++;
        }
    }

    //$total_pages = ceil(((int)($total_funds)) / $post_per_page);
    $total_pages = 1;
    $json['data']['total_funds'] = $total_funds;
    $json['data']['total_pages'] = $total_pages;
    $json['data']['current_page'] = $paged;
    $json['data']['posts'] = $data;
    
    $select_funds_title = ( get_acf_option('portfolio_builder_select_funds_title',$language) ) ? get_acf_option('portfolio_builder_select_funds_title',$language) : "";
    $select_funds_desc = ( get_acf_option('portfolio_builder_select_funds_description',$language) ) ? get_acf_option('portfolio_builder_select_funds_description',$language) : "";
    
    $json['data']['select_funds_title'] = $select_funds_title;
    $json['data']['select_funds_desc'] = $select_funds_desc;

    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['no_data'], 'getsearchfund-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['success'], 'getsearchfund-msg', 'success',$language);
    }
    return get_200_success($json);
}

function getPortfolioFundAllocation($request_data){
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['fund_ids'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['fund_ids_not_provided'], 'getportfoliodetail-msg', 'fund-ids-not-provided',$language);
        return get_400_error($json);
    }

    $fund_ids = explode(',', $request_data['fund_ids']);
    $args = array(
        'post_type'       => 'funds',
        'post_status'     => 'publish',
        'posts_per_page'  => -1,                  
        'post__in'        => $fund_ids, 
        'orderby'         => 'post__in'
    ); 
    $funds = new WP_Query($args);
    if( $funds->have_posts() ) {
        $fund_counter = 0; // Counter to loop through posts
        while ( $funds->have_posts() ) {
            $funds->the_post(); 
            $data[$fund_counter]['fund_id'] = get_the_ID();
            $data[$fund_counter]['title'] = (get_the_title()) ? html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8') : '';

            $fund_image = "";
            $fund_family = get_the_terms( get_the_ID(), 'fundfamily' ); 
            if($fund_family) {
                foreach ($fund_family as $term) {
                    $fund_image = (get_field('fund_family_image', $term)) ? get_field('fund_family_image', $term) : "";
                }
            }

            $data[$fund_counter]['image'] = $fund_image;
            $data[$fund_counter]['isin'] = (get_field('isin')) ? get_field('isin') : '';
            $data[$fund_counter]['fund_code'] = (get_field('fund_code')) ? get_field('fund_code') : '';
            $data[$fund_counter]['assetclass'] = join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'assetclass' ), 'name'));
            $data[$fund_counter]['investmentuniverse'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'investmentuniverse' ), 'name'));
            $data[$fund_counter]['fundfamily'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'fundfamily' ), 'name'));           
            $fund_counter++;
        }
    }

    if(empty($data)) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['no_funds'], 'getportfoliodetail-msg', 'no-data',$language);
    } else {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['success'], 'getportfoliodetail-msg', 'success',$language);
    }

    $allocation_title = ( get_acf_option('portfolio_builder_funds_allocation_title',$language) ) ? get_acf_option('portfolio_builder_funds_allocation_title',$language) : "";
    $allocation_desc = ( get_acf_option('portfolio_builder_funds_allocation_description',$language) ) ? get_acf_option('portfolio_builder_funds_allocation_description',$language) : "";

    $json['data']['posts'] = $data;
    $json['data']['allocation_title'] = $allocation_title;
    $json['data']['allocation_desc'] = $allocation_desc;

    return get_200_success($json);

}

function getPortfolioFundReport($request_data){
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = "";
    
    $language = (isset($request_data['lang'])) ? $request_data['lang'] : "en";

    if( empty( $request_data['currency_code'] ) ){ 
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['currency_code_not_provided'], 'getportfoliodetail-msg', 'currency-code-not-provided',$language);
        return get_400_error($json);
    } else {
        $currency_code =  $request_data['currency_code'];
        $currency_codes = get_field_object('field_5d1edff0549ed');
        if(sizeof($currency_codes)) {
            if(array_key_exists('choices',$currency_codes)) {
                $choices = $currency_codes['choices'];
                if(!array_key_exists($currency_code, $choices)){
                    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getsearchfund']['wrong_currency_code'], 'getportfoliodetail-msg', 'wrong-currency-code',$language);
                    return get_400_error($json);
                }
            }
        }
    }

    $holdings = $request_data['holdings'];
    if(empty($holdings)){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['holdings_not_provided'], 'getportfoliodetail-msg', 'holdings-not-provided',$language);
        return get_400_error($json);
    } else {
        $holdings_json = json_decode($holdings, true); 
        $holdings_weight = 0;
        foreach( $holdings_json as $holding ){
            $holdings_weight += (int)$holding['Weight'];
        }
        if( $holdings_weight > 100){
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['fund_weight_exceeded'], 'getportfoliodetail-msg', 'fund-weight-exceeded',$language);
            return get_400_error($json);
        }
    } 

    $name = $request_data['name'];
    if(empty($name)){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['name_not_provided'], 'getportfoliodetail-msg', 'name-not-provided',$language);
        return get_400_error($json);
    }

    $amount = $request_data['amount'];
    if(empty($amount)){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['amount_not_provided'], 'getportfoliodetail-msg', 'amount-not-provided',$language);
        return get_400_error($json);
    }

    $user_id = $request_data['user_id'];
    if(empty($user_id)){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['user_id_not_provided'], 'getportfoliodetail-msg', 'user-id-not-provided',$language);
        return get_400_error($json);
    } else if(get_userdata( $user_id ) == false){
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['user_id_not_valid'], 'getportfoliodetail-msg', 'user-id-not-valid',$language);
        return get_400_error($json);
    }

    $show_prepared_by = $request_data['show_prepared_by'];
    $show_prepared_by = isset($show_prepared_by) && $show_prepared_by == 'yes' ? true : false;

    $advisor_data = get_userdata( $user_id );
    $advisor_name = get_user_meta($user_id, 'full_name', true) ? get_user_meta($user_id, 'full_name', true) : $advisor_data->display_name;
    $advisor_email = $advisor_data->user_email;

    /* Generate Access Token for Morning Star API - Start */
    $username = get_field('morning_star_api_username', 'option') ? get_field('morning_star_api_username', 'option') : '';
	$password = get_field('morning_star_api_password', 'option') ? get_field('morning_star_api_password', 'option') : '';

  	$ch = curl_init();

    $sand_box = true;
    
    if( $sand_box == true ) {
        $mstar_api = 'https://www.us-uat-api.morningstar.com';
    }
    else {
        $mstar_api = 'https://www.us-api.morningstar.com';
    }
    
    $auth_url = $mstar_api.'/token/oauth';
	curl_setopt($ch, CURLOPT_URL, $auth_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
	$api_response = curl_exec($ch);
	curl_close($ch);
    /* Generate Access Token for Morning Star API - End */

    $api_response_decoded = json_decode( $api_response, true ); // ["access_token":"","expires_in":"","token_type":"Bearer"]

    if( !array_key_exists('access_token',$api_response_decoded) ){ // no access_token means error on generating it
        $json['api_response'] = $api_response_decoded;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['mstar_api_error'], 'getportfoliodetail-msg', 'mstar-api-error',$language);
        return get_400_error($json);
    }


    $ch_2 = curl_init();

    switch($currency_code){
        case 'usd':
            $lang_cult = 'en-US';
            $currency = 'USD';
        break;
        case 'gbp':
            $lang_cult = 'en-GB';
            $currency = 'GBP';
        break;
        case 'eur':
            $lang_cult = 'en-US';
            $currency = 'USD';
        break;
    }

    $report_url = $mstar_api.'/portfolioanalysis/v1/report';
    $api_url = sprintf('%s?langcult=%s', $report_url ,$lang_cult);

	curl_setopt($ch_2, CURLOPT_URL, $api_url);
	curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch_2, CURLOPT_POST, 1);
	curl_setopt($ch_2, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

	$headers = array(
		"Authorization: Bearer ".$api_response_decoded['access_token'],
		"Content-Type: application/json",
		"Accept: application/pdf",
	);
	curl_setopt($ch_2, CURLOPT_HTTPHEADER,$headers);

    if($show_prepared_by == true) {
        $data = '{
            "RequestSettings": {
                "OutputCurrency": "'.$currency.'",
                "ReportSettings": {
                    "ReportPages": [
                        "portfolioXRay",
                        "portfolioSnapshot",
                        "investmentDetail"
                    ],
                    "CoverPage": {
                        "ReportTitle": "Portfolio Analysis",
                        "PreparedFor": {
                            "Name": "'.$request_data['name'].'"
                        },
                        "PreparedBy": {
                            "Advisors": [
                                {
                                    "Name": "'.$advisor_name.'",
                                    "Email": "'.$advisor_email.'"
                                }
                            ]
                        }
                    }
                }
            },
            "Config": {
                "Id": "PDF"
            },
            "View": {
                "Id": "PDF"
            },
            "portfolios": [
                {
                    "Name": "Proposed Portfolio",
                    "TotalValue": '.(int)$amount.',
                    "Currency": "'.$currency.'",
                    "Holdings": '.$holdings.'
                }
            ]
        }';
    } else {
        $data = '{
            "RequestSettings": {
                "OutputCurrency": "'.$currency.'",
                "ReportSettings": {
                    "ReportPages": [
                        "portfolioXRay",
                        "portfolioSnapshot",
                        "investmentDetail"
                    ],
                    "CoverPage": {
                        "ReportTitle": "Portfolio Analysis",
                        "PreparedFor": {
                            "Name": "'.$request_data['name'].'"
                        }
                    }
                }
            },
            "Config": {
                "Id": "PDF"
            },
            "View": {
                "Id": "PDF"
            },
            "portfolios": [
                {
                    "Name": "Proposed Portfolio",
                    "TotalValue": '.(int)$amount.',
                    "Currency": "'.$currency.'",
                    "Holdings": '.$holdings.'
                }
            ]
        }';
    }
	
	curl_setopt($ch_2, CURLOPT_POSTFIELDS, $data);
	$result_2 = curl_exec($ch_2);
	curl_close($ch_2);

    $api_response_decoded_2 = json_decode($result_2, true);

    /**
     * If $api_response_decoded_2 contains an array then it means there is error in the response
     * else the receieved response is PDF blob and we have to save it in the pdf file 
     */

    if( is_array($api_response_decoded_2) ){ 
        $json['api_response'] = $api_response_decoded_2;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['mstar_api_error'], 'getportfoliodetail-msg', 'mstar-api-error',$language);
        return get_400_error($json);
    }

    $upload_dir   = wp_upload_dir();
	$custom_dir   = $upload_dir['basedir'] . '/portfolio_ananlysis_reports/';

    $time = date("Y-m-d-h-i-s",time());
    $file_name    = sprintf('portfolio_analysis_%s.pdf',  $time); 
	
    if(!file_put_contents( $custom_dir . $file_name, $result_2)){
        $json['api_response'] = 'File upload error';
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['mstar_api_error'], 'getportfoliodetail-msg', 'mstar-api-error',$language);
        return get_400_error($json);
    }

    $pdf_url = $upload_dir['baseurl'] . '/portfolio_ananlysis_reports/'.$file_name;
    $json['data']['pdf_url'] = $pdf_url;
    
    $json['data']['title'] = ( get_acf_option('portfolio_builder_title',$language) ) ? get_acf_option('portfolio_builder_title',$language) : "";
    $json['data']['desc'] = ( get_acf_option('portfolio_builder_generate_report_description',$language) ) ? get_acf_option('portfolio_builder_generate_report_description',$language) : "";

    $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getportfoliodetail']['report_generated'], 'getportfoliodetail-msg', 'report-generated',$language);
    return get_200_success($json);

}

 /** 
 * getFund API functions
 * request typre POST
 * $request_data['paged'] (optional)
 * $request_data['value'] = currency type value which we have provided with getFundCategory api 
 *
function getFund( $request_data ){
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = '';
    $total_funds = 0;
    $post_per_page = (int)get_option( 'posts_per_page');

    if( empty($request_data['currency_code'])){
        $json['message'] = $message_global['getfund']['provide_type'];
        return get_400_error($json);
    } else {
        $currency_code =  $request_data['currency_code'];
        $currency_codes = get_field_object('field_5d1edff0549ed');
        if(sizeof($currency_codes)) {
            if(array_key_exists('choices',$currency_codes)) {
                $choices = $currency_codes['choices'];
                if(!array_key_exists($currency_code, $choices)){
                    $json['message'] = $message_global['getfund']['wrong_currency_code'];
                    return get_400_error($json);
                }
            }
        }
    }

    $paged = (empty($request_data['paged'])) ? 1 : (int)$request_data['paged'];
    $args = array(
                  'post_type'       => 'funds',
                  'post_status'     => 'publish',
                  'posts_per_page'  => -1,                  
                  'order'           => 'ASC',
                  'meta_query'      => array(
                                            array('key' => 'select_currency',
                                                'compare' => '=',
                                                'value' => $currency_code 
                                            )
                                       ));

    $query = new WP_Query($args);
    $total_funds = $query->post_count;

    $args['posts_per_page'] = $post_per_page;
    $args['paged'] = $paged;

    $funds = new WP_Query($args);
    if( $funds->have_posts() ) {
        $fund_counter = 0; // Counter to loop through posts
        while ( $funds->have_posts() ) {  //if have post loop the loop
            $funds->the_post(); 
            $data[$fund_counter]['title'] = (get_the_title( )) ? get_the_title( ) : '';
            $data[$fund_counter]['image'] = (get_field('fund_image')) ? get_field('fund_image') : '';
            $data[$fund_counter]['isin'] = (get_field('isin')) ? get_field('isin') : '';
            $data[$fund_counter]['fund_code'] = (get_field('fund_code')) ? get_field('fund_code') : '';
            $file_url = "";
            if( get_field('file_name') ){
                $file_url = get_fund_path('funds','en', get_field('file_name'));
            }
            $data[$fund_counter]['pdf'] = $file_url;
            $data[$fund_counter]['assetclass'] = join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'assetclass' ), 'name'));
            $data[$fund_counter]['investmentuniverse'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'investmentuniverse' ), 'name'));
            $data[$fund_counter]['fundfamily'] = join(', ', wp_list_pluck( get_the_terms( get_the_ID(), 'fundfamily' ), 'name'));           
            $fund_counter++;
        }
    }

    $total_pages = ceil(((int)($total_funds)) / $post_per_page);
    $json['data']['total_pages'] = $total_pages;
    $json['data']['current_page'] = $paged;
    $json['data']['posts'] = $data;
    if(empty($data)) {
        $json['message'] = $message_global['getfund']['no_data'];
    } else {
        $json['message'] = $message_global['getfund']['success'];
    }
    return get_200_success($json);
}

/**
 *  REQUEST TYPE ( GET ) (WP_REST_Server::READABLE)
 *  purpose : to get currency list
 *
function getFundCurrency( ){

    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = array();
    $json['message'] = '';

    $field = get_field_object('field_5d1edff0549ed');   
    if( empty( $field['choices'] ) ){
        $json['data'] = $data ;
        $json['message'] = $message_global['getfundcurrency']['not_available'];         
        return get_400_error($json);
    }

    $choicesCounter = 0;
    foreach ($field['choices'] as $key => $value) {
        $data[$choicesCounter]['currency_code'] =  $key;
        $data[$choicesCounter]['display_name'] = $value;
        $choicesCounter++;
    }

    $json['data'] = $data ;
    $json['message'] = $message_global['getfundcurrency']['success'];    
    return get_200_success($json);
}
*/