<?php 
function getLibrary( $request_data ) {

    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = $push  = $type_array = array();
    $json['message'] = '';
    $displayCounter = $catCounter = 0;
    $language = ( isset( $request_data['lang'] ) ) ? $request_data['lang'] : "en";
    $counter = $product_counter = $flyers_counter = $socialpost_counter = $presentation_counter = 0;
    $section_title = "";

    if( empty( $request_data['user_id'] ) ) {
        $guest = 1;
    } else {
        $user_id = $request_data['user_id'];
        $guest = (!get_user_by('id', $user_id)) ? 1 : 0;
    }

    if ( $guest == 1 ) {
        $library_option_field = get_acf_option('library_option', $language);
        if (!empty($library_option_field)) {
            $type_array = array_merge($type_array,$library_option_field);
        }
    } else {
        $type_array = array( 'brochure', 'products', 'flyers', 'socialpost', 'presentation',  'video' );
    }

    foreach ( $type_array as $key => $value ) {
        if( $value == 'brochure' || $value == 'video' )
            $defaultPostsPerPage = 1;
        else if( $value == 'presentation' )
            $defaultPostsPerPage = 6;
        else
            $defaultPostsPerPage = get_option( 'posts_per_page' );

        $args = array(
                  'post_type'   => 'library',
                  'post_status' => 'publish',
                  'posts_per_page' => $defaultPostsPerPage,
                  'meta_query' => array(
                                            array(
                                               'key'       => 'select_type',
                                               'value'     => $value,
                                               'compare'   => 'LIKE'
                                            ),
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key'       => 'hide_post_from_guest_user',
                                                    'value'     => 0,
                                                    'compare'   => '='
                                                ),
                                                array(
                                                    'key'       => 'hide_post_from_guest_user',
                                                    'compare'   => 'NOT EXISTS'
                                                )
                                            ),
                                )
            );
        $library_query = new WP_Query( $args );
        // Counter to loop through posts
        while ( $library_query->have_posts() ) {  //if have post loop the loop
            $library_query->the_post();
            /* LOOP FOR THE CATEGORY  */

            $english_id = get_the_ID();
            // FETCHING TRANSLATION POST ID
            $post_id = apply_filters( 'wpml_object_id', $english_id, 'library', TRUE, $language);
            $library_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
            // INITIALIZE THE VARIABLE
            $social_posts_category = $product_category = $flyer_category = "";
            $short_description     = ( get_field( 'short_description', $post_id ) ) ? get_field('short_description', $post_id ) : "";
            $thumbnail_image       = ( get_field( 'thumbnail', $post_id ) ) ? get_file_path( 'library', $language, $value, get_field( 'thumbnail', $post_id ) ) : "" ;
            $presentation_link     = ( get_field( 'presentation_link', $post_id ) ) ? get_field( 'presentation_link', $post_id ) : "";
            $banner_image          = ( get_field( 'banner_image', $post_id ) ) ? get_field( 'banner_image', $post_id ) : "";
            $video_link            = ( get_field( 'video_url', $post_id ) ) ? get_field( 'video_url', $post_id ) : "" ;
            $image                 = ( get_field( 'image', $post_id ) ) ? get_file_path( 'library', $language, $value, get_field( 'image', $post_id ) ) : "";
            $video_thumbnail       = ( get_field( 'video_thumbnail', $post_id ) ) ? get_field( 'video_thumbnail', $post_id ) : "";
            $sub_title             = ( get_field( 'sub_title', $post_id ) ) ? get_field( 'sub_title', $post_id ) : "";
            //$show                  = ( get_field( 'show_post_to_guest_user', $post_id ) ) ? get_field( 'show_post_to_guest_user', $post_id ) : "";
            $hide                  = ( get_field( 'hide_post_from_guest_user', $post_id ) ) ? get_field( 'hide_post_from_guest_user', $post_id ) : "";
            $file_url = ( get_field( 'file_url', $post_id ) ) ? get_file_path( 'library', $language, $value, get_field( 'file_url', $post_id ) ): "";
            if( get_field( 'product_category', $post_id ) ) {
                $product_category = get_field( 'product_category', $post_id );
                $translated_id = apply_filters( 'wpml_object_id',$product_category->term_id, 'product-category', TRUE, $language);
                $product_category = get_translated_category_name($translated_id,$post_id,'product-category');
            }
            if( get_field( 'social_posts_category', $post_id ) ) {
                $social_posts_category = get_field( 'social_posts_category', $post_id );
                $translated_id = apply_filters( 'wpml_object_id',$social_posts_category->term_id, 'social-post-category', TRUE, $language);
                $social_posts_category = get_translated_category_name($translated_id,$post_id,'social-post-category');
            }
            if( get_field( 'flyer_category', $post_id ) ) {
                $flyer_category = get_field( 'flyer_category', $post_id );
                $translated_id = apply_filters( 'wpml_object_id',$flyer_category->term_id, 'flyer-category', TRUE, $language);
                $flyer_category = get_translated_category_name($translated_id,$post_id,'flyer-category');
            }

            // INITIALIZE THE VARIABLE
                $push = $product = $flyers = $socialpost = $presentation = array(
                                'post_id'  => (int)$english_id,
                                'title'    => $library_title,
                            );
                switch( $value ) {
                    case 'brochure':
                            $push['image'] = $thumbnail_image;
                            $push['short_description'] = $short_description;
                            $push['file_url'] = $file_url;
                    break;
                    case 'flyers':
                            $section_title = (get_acf_option('flyer_section_title',$language)) ? get_acf_option('flyer_section_title',$language) : "";
                            $flyers['category'] = $flyer_category;
                            $flyers['image'] = $thumbnail_image;
                            $flyers['file_url'] = $file_url;
                            $flyers['subTitle'] = $sub_title;
                    break;
                    case 'products':
                            $section_title = (get_acf_option('product_section_title',$language)) ? get_acf_option('product_section_title',$language) : "";
                            $product['category'] = $product_category;
                            $product['image'] = $thumbnail_image;
                            $product['file_url'] = $file_url;
                    break;
                    case 'socialpost':
                        $section_title = (get_acf_option('social_posts_section_title',$language)) ? get_acf_option('social_posts_section_title',$language) : "";
                        $socialpost['subTitle'] = $sub_title;
                        $socialpost['category'] = $social_posts_category;
                        $socialpost['image'] = $image;
                    break;
                    case 'video':
                        $push['short_description'] = $short_description;
                        $push['video_link'] = $video_link;
                        $push['video_thumbnail']   = $video_thumbnail;
                    break;
                    case 'presentation':
                        $section_title = (get_acf_option('presentation_section_title',$language)) ? get_acf_option('presentation_section_title',$language) : "";

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

                if( ( $value == "flyers" || $value == "brochure" || $value == "products" ) && $file_url == "" ){
                    continue;
                }
                $data[$counter]['type'] = $value;
                $data[$counter]['title'] = $section_title;
                if( $value == "products" ){
                    $data[$counter]['data'][$product_counter] = $product;
                    $product_counter++;
                }
                if( $value == "flyers" ) {
                    $data[$counter]['data'][$flyers_counter] = $flyers;
                    $flyers_counter++;
                }
                if( $value == "socialpost" ) {
                    $data[$counter]['data'][$socialpost_counter] = $socialpost;
                    $socialpost_counter++;
                }
                if( $value == "presentation" ) {
                    $data[$counter]['data'][$presentation_counter] = $presentation;
                    $presentation_counter++;
                }
                if( $value == "brochure" || $value == 'video' ) {
                    $push['type'] = $value;
                    $data[$counter] = $push;
                }
            wp_reset_query();
        }
        $counter++;
    }
    if( !empty( $data ) ) {
        $json['data']['posts'] = $data;
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlibrary']['library_listing_success'], 'getlibrary-msg', 'library-listing-success',$language);
        return get_200_success( $json );
    }
    if( empty( $data ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getlibrary']['library_not_found'], 'getlibrary-msg', 'library-not-found',$language);
        return get_200_success( $json );
    }
}

function getAllVideos( $request_data ) {
    // INITIALIZATION START
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = '';
    $language = ( isset( $request_data['lang'] ) ) ? $request_data['lang'] : "en";
    $defaultPostsPerPage = get_option( 'posts_per_page');

    if( empty( $request_data['user_id'] ) ) {
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}

    if( empty( $request_data['paged'] ) ) {
        $paged = 1;
    }
    else {
        $paged = $request_data['paged'];
    }

    $args = array(
              'post_type'       => 'library',
              'post_status'     => 'publish',
              'posts_per_page'  =>  -1,
              'meta_query'      => array(
                                        array(
                                           'key'       => 'select_type',
                                           'value'     => 'video',
                                           'compare'   => 'LIKE'
                                        )
                            )
        );
    if( $guest ){
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array(
                'key' => 'hide_post_from_guest_user',
                'value' => 0,
                'compare'   => '='
            ),
            array(
                'key' => 'hide_post_from_guest_user',
                'compare'   => 'NOT EXISTS'
            )
        );
    }
    $query = new WP_Query( $args );
    $total_video = $query->post_count; 
      
    $args['posts_per_page'] = $defaultPostsPerPage;
    $args['paged'] = $paged;
    $video_query = new WP_Query( $args );
    $total_pages = ceil( $total_video / $defaultPostsPerPage );
    $json['data']['total_pages'] = $total_pages;
    $json['data']['current_page'] = (int)$paged;
    $json['data']['posts'] = $data;

    if( $video_query->have_posts() ) {
        $videocounter = 0; 
            // Counter to loop through posts
            while ( $video_query->have_posts() ) {  //if have post loop the loop
                $video_query->the_post();    
                // INITIALIZE THE VARIABLE
                $short_description = $video_link = $video_thumbnail ="" ;
                $english_id = get_the_ID();
                $post_id = apply_filters( 'wpml_object_id', $english_id, 'library', TRUE, $language);
                $short_description     = ( get_field( 'short_description', $post_id ) ) ? get_field('short_description', $post_id ) : "";
                $video_link            = ( get_field( 'video_url', $post_id ) ) ? get_field( 'video_url', $post_id ) : "" ;
                $video_thumbnail       = ( get_field( 'video_thumbnail', $post_id ) ) ? get_field( 'video_thumbnail', $post_id ) : "";
                // INITIALIZE THE VARIABLE
                $push = array (
                            'post_id'           => (int)$english_id,
                            'title'             => html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8'),
                            'video_link'        => $video_link,
                            'video_thumbnail'   => $video_thumbnail,
                            'short_description' => $short_description,
                            'date'              => get_the_date(),
                    );
                $data[$videocounter] =  $push;
                $videocounter++;
            }
            wp_reset_query();
            $json['data']['posts'] = $data;
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getallvideos']['video_listing_success'], 'getallvideos-msg', 'video-listing-success',$language);
            return get_200_success( $json );
        }
        else{
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['getallvideos']['video_not_found'], 'getallvideos-msg', 'video-not-found',$language);
            return get_200_success( $json );
        }
}

function getSearchData( $request_data ) {

    // INITIALIZATION START
    $json = array();
    global $wpdb, $message_global;
    $json['data'] = $data = $type_array = array();
    $json['message'] = '';
    $displayCounter = $catCounter = $counter = 0;
    $language = ( isset( $request_data['lang'] ) ) ? $request_data['lang'] : "en";
    $search_string = $user_id = '' ;

    if(isset($request_data['search_string']) && $request_data['search_string'] != ''){
        $search_string = $request_data['search_string'];
    }
    
    if( empty( $request_data['user_id'] ) ) {
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}

    if ( $guest == 1 ) {
        $library_option_field = get_acf_option('library_option', $language);
        if (!empty($library_option_field)) {
            $type_array = array_merge($type_array,$library_option_field);
        }
    } else {
        $type_array = array( 'brochure', 'products', 'flyers', 'socialpost', 'presentation',  'video' );
    }

    foreach ( $type_array as $key => $value ) {
        $args = array(
                        'post_type'   => 'library',
                        'post_status' => 'publish',
                        's' => $search_string,
                        'language_code' => $language,
                        'meta_query' => array(
                                            array(
                                                'key'       => 'select_type',
                                                'value'     => $value,
                                                'compare'   => 'LIKE'
                                            )
                        )
            );
        if( $guest ){
            $args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key' => 'hide_post_from_guest_user',
                    'value' => 0,
                    'compare'   => '='
                ),
                array(
                    'key' => 'hide_post_from_guest_user',
                    'compare'   => 'NOT EXISTS'
                )
            );
        }
        $library_query = new WP_Query( $args );

        // Counter to loop through posts
        $favorites  = $user_id ? get_user_meta( $user_id, 'favorite',true ) : '';
        if(!$favorites)
            $favorites = array();
        if( $library_query->have_posts() ) {
            while ( $library_query->have_posts() ) {  //if have post loop the loop
                $library_query->the_post();    
                /* LOOP FOR THE CATEGORY  */
                if($language!="en") {
                    $english_id = apply_filters( 'wpml_object_id', get_the_ID(), 'library', TRUE,"en");
                } else {
                    $english_id = (int)get_the_ID();    
                }
                $post_id = apply_filters( 'wpml_object_id', $english_id, 'library', TRUE, $language);

                if(in_array_r($english_id, $data)) {
                    continue;
                }               

                $library_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                // INITIALIZE THE VARIABLE
                $file_size = '';
                $select_type = get_field('select_type', $post_id);
                $short_description     = ( get_field( 'short_description', $post_id ) ) ? get_field('short_description', $post_id ) : "";
                $thumbnail_image       = ( get_field( 'thumbnail', $post_id ) ) ? get_file_path( 'library', $language, $select_type, get_field( 'thumbnail', $post_id ) ) : "" ;
                $file_url              = ( get_field( 'file_url', $post_id ) ) ? get_file_path( 'library',$language,$select_type,get_field( 'file_url', $post_id ) ): "";

                $file_size = 0;
                $file_name = (get_field('file_url', $post_id )) ? get_field('file_url', $post_id ) : "";
                if( $file_url != '' ) {
                    if( file_exists( ABSPATH.'/wp-content/library/'.$language.'/'.$select_type.'/'.$file_name ) ){
                        $file_size = file_formatsize( filesize( ABSPATH.'wp-content/library/'.$language.'/'.$select_type.'/'.$file_name ) );
                    } else if( file_exists( ABSPATH.'/wp-content/library/en/'.$select_type.'/'.$file_name ) ){
                        $file_size = file_formatsize( filesize( ABSPATH.'wp-content/library/en/'.$select_type.'/'.$file_name ) );
                    }
                }
                $presentation_link     = ( get_field( 'presentation_link', $post_id ) ) ? get_field( 'presentation_link', $post_id ) : "";
                $banner_image          = ( get_field( 'banner_image', $post_id ) ) ? get_field( 'banner_image', $post_id ) : "";
                $video_link            = ( get_field( 'video_url', $post_id ) ) ? get_field( 'video_url', $post_id ) : "" ;
                $image                 = ( get_field( 'image', $post_id ) ) ? get_file_path( 'library', $language, $select_type, get_field( 'image', $post_id ) ) : "";
                $video_thumbnail       = ( get_field( 'video_thumbnail', $post_id ) ) ? get_field( 'video_thumbnail', $post_id ) : "";
                
                // INITIALIZE THE VARIABLE
                if( $select_type == 'video' ) {
                    $push = array(
                                    'post_id'           => $english_id,
                                    'type'              => $select_type,
                                    'title'             => $library_title,
                                    'file_url'          => $video_link,
                                    'image'             => $video_thumbnail,
                                    'file_type'         => $select_type
                            );
                } else if( $select_type == 'socialpost' ) {
                    if($image!="") {
                        $site_url = esc_url(get_site_url());
                        $absolute_path = ABSPATH;
                        $file_size = file_formatsize(filesize(str_replace($site_url, $absolute_path, $image)));
                    }
                    $push = array(
                                    'post_id'           => $english_id,
                                    'type'              => $select_type,
                                    'title'             => $library_title,
                                    'image'             => $image,
                                    'file_size'         => $file_size,
                                    'file_type'         => __("IMAGE","investorstrust")
                            );
                } else {
                    $file_type = __("PDF","investorstrust");
                    if($select_type == "presentation"  && $file_url == "") {
                        $file_url = ( get_field( 'presentation_link', $post_id ) ) ? get_field( 'presentation_link', $post_id ) : "";
                        $file_type = __("URL","investorstrust");
                    }

                    if( ( $select_type == "flyers" || $select_type == "brochure" || $select_type == "products" || $select_type == "presentation" ) && $file_url == "" ){
                        continue;                        
                    }
                    
                    $push = array(
                                    'post_id'           => $english_id,
                                    'type'              => $select_type,
                                    'title'             => $library_title,
                                    'image'             => $thumbnail_image,
                                    'file_url'          => $file_url,
                                    'file_size'         => $file_size,
                                    'file_type'         => $file_type
                            );
                }
                if( in_array( $english_id, $favorites ) ){
                    $push['is_favourite'] = 1;
                } else {
                    $push['is_favourite'] = 0;
                }
                $data[$counter] =  $push;
                $counter++;
            }
            wp_reset_query();   

        }
    }

    if ( !empty( $data ) ) {
        $json['data']['posts'] = $data;
        $json['data']['favorites'] = $favorites;
        $json['message'] = $message_global['getlibrary']['library_listing_success'];
        return get_200_success($json);
    } else {
        $json['message'] = $message_global['getlibrary']['library_not_found'];
        return get_200_success( $json );
    }
}

function viewalldata( $request_data ) {
    $json = array();
    global $wpdb,$message_global;
    $json['data'] = $data = array();
    $json['message'] = $product_category = $social_post_category = $flyer_category = $search_string = '';
    $post_per_page = (int)get_option( 'posts_per_page');
    $paged = (empty($request_data['paged'])) ? 1 : (int)$request_data['paged'];
    $language = ( isset( $request_data['lang'] ) ) ? $request_data['lang'] : "en";
    $counter = $cover_post = 0;

    if( empty( $request_data['user_id'] ) ) {
		$guest = 1;
	} else {
		$user_id = $request_data['user_id'];
		$guest = (!get_user_by('id', $user_id)) ? 1 : 0;
	}

    if( empty($request_data['category_id'] ) ) {
        $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['category_not_provided'], 'viewalldata-msg', 'category-not-provided',$language);
        return get_400_error( $json );
    } else {
        $category_id          = $request_data['category_id'];
        $product_category     = get_term_by('id', $category_id, 'product-category');
        $social_post_category = get_term_by('id', $category_id, 'social-post-category');
        $flyer_category       = get_term_by('id', $category_id, 'flyer-category');
    }

    if( $product_category == "" && $social_post_category == "" && $flyer_category == "" ) {
       $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['invalid_category'], 'viewalldata-msg', 'invalid-category',$language);
       return get_400_error($json);
    } else {
        if( $product_category != "" ) {
            $type = "products";
            $key = "product_category";
        } else if( $social_post_category != "" ) {
            $type = "socialpost";
            $key = "social_posts_category";
        } else if( $flyer_category != "" ) {
            $type = "flyers";
            $key = "flyer_category";
        }

        $args = array(
                        'post_type'      => 'library',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'meta_query'     => array(
                                                'relation' => 'AND',
                                                array(
                                                    'key'       => 'select_type',
                                                    'value'     => $type,
                                                    'compare'   => '='
                                                ),
                                                array(
                                                    'key'       => $key,
                                                    'value'     => $category_id,
                                                    'compare'   => '='
                                                ),
                                            )
                    );
        
        if( $guest ){
            $args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key' => 'hide_post_from_guest_user',
                    'value' => 0,
                    'compare'   => '='
                ),
                array(
                    'key' => 'hide_post_from_guest_user',
                    'compare'   => 'NOT EXISTS'
                )
            );
        }

        /* commented as searching by category is not required
        if( isset( $request_data['search_string'] ) && $request_data['search_string'] != '' ){
            $search_string = $request_data['search_string'];
            $args['s'] = $search_string;
        }*/

        $query = new WP_Query($args);
        $total_posts = $query->post_count;
        wp_reset_query();

        $args['posts_per_page'] = $post_per_page;
        $args['paged'] = $paged;

        $library_query = new WP_Query( $args );
        if( $library_query->have_posts() ) {
            while ( $library_query->have_posts() ) {
                $library_query->the_post();
                $english_id = get_the_ID();
                // FETCHING TRANSLATION POST ID
                $post_id = apply_filters( 'wpml_object_id', $english_id, 'library', TRUE, $language);
                $post_title = html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8');
                $file_url = (get_field('file_url',$post_id)) ? get_file_path('library',$language,$type,get_field('file_url',$post_id)) : "";
                $image = (get_field('image',$post_id)) ? get_file_path( 'library', $language, $type, get_field( 'image', $post_id ) ) : "";
                $thumbnail_image = (get_field('thumbnail',$post_id)) ? get_file_path( 'library', $language, $type, get_field( 'thumbnail', $post_id ) ) : "";
                
                if( get_field('is_featured_image') )
                    $cover_post = 1;
                
                $push = array( 'post_id'  => (int)$english_id, 'title' => $post_title );
                if($type == "products" || $type == "flyers") {
                    if( $file_url != '') {
                        $push['file_url'] = $file_url;
                        $push['thumbnail_image'] = $thumbnail_image;
                        $data[$counter] =  $push;
                        $counter++;
                    }
                } else if( $type=="socialpost" ) {
                    $push['image'] = $image;
                    $push['is_cover_post'] = $cover_post;
                    $data[$counter] =  $push;
                    $counter++;
                }
            }
            wp_reset_query();
        }
        $total_pages = ceil(((int)$total_posts ) / $post_per_page );
        $json['data']['total_pages'] = $total_pages;
        $json['data']['current_page'] = $paged;
        $json['data']['posts'] = $data;
        if( empty( $data ) ) {
            /* commented as searching by category is not required
            if( $type == "products" ) {
                if( $search_string == "" ) {
                    $json['message'] = $message_global['viewalldata']['product_search_cat_fail'];
                } else {
                    $json['message'] = $message_global['viewalldata']['product_search_fail'];
                }
            } else if( $type == "socialpost" ) {
                if( $search_string == "" ) {
                    $json['message'] = $message_global['viewalldata']['social_post_search_cat_fail'];
                } else {
                    $json['message'] = $message_global['viewalldata']['social_post_search_fail'];
                }
            } else if( $type == "flyers" ) {
                if( $search_string == "" ) {
                    $json['message'] = $message_global['viewalldata']['flyer_search_cat_fail'];
                } else {
                    $json['message'] = $message_global['viewalldata']['flyer_search_fail'];
                }
            } */

            if( $type == "products" ) {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['no_products'], 'viewalldata-msg', 'no-products',$language);
            } else if( $type == "socialpost" ) {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['no_social_post'], 'viewalldata-msg', 'no-flyer',$language);
            } else if( $type == "flyers" ) {
                $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['no_flyer'], 'viewalldata-msg', 'no-social-post',$language);
            } 
        } else {
            $json['message'] = apply_filters( 'wpml_translate_single_string', $message_global['viewalldata']['success'], 'viewalldata-msg', 'success',$language);
        }
        return get_200_success( $json );
    }
}

/* CURRENTLY WE ARE NOT USING THE BELOW APIS 
   BELOW APIS ARE GETLIBCATEGORY AND GETDATA FOR LIBRARY*/

/*function getLibCategory( $request_data ) {
    global $wpdb;
    global $message_global;
    $json = $data = array();

    $json['data'] = array();
    $json['message'] = $type = '';

    if( empty($request_data['type']) ) {
        $json['message'] = $message_global['getlibcategory']['provide_type'];
        return get_400_error($json);
    }

    $data['categories'] = array();
    $type = $request_data['type'];
    $args = array(
                  'post_type'   => 'library',
                  'post_status' => 'publish',
                  'meta_query' => array(
                                            array(
                                               'key'       => 'select_type',
                                               'value'     => $type,
                                            )
                                )
            );
    $new_query = new WP_Query( $args );

    if( $new_query->have_posts() ):
        while ( $new_query->have_posts() ) {
            $new_query->the_post();
            $categories = get_the_category();
            $post_categories = wp_get_post_categories( get_the_ID() );
            foreach($post_categories as $c){
                $cat = get_category( $c );
                $data['categories'][] = array( 'cat_id' => $cat->cat_ID , 'cat_name' => $cat->cat_name );
            }
            $data['categories'] = array_unique($data['categories'] , SORT_REGULAR);
        }

    else:
        $json['data'] = array();
        $json['message'] = $message_global['getlibcategory']['selected_type_not_found'];
        return get_400_error($json);

    endif;
        $json['data'] = $data;
        $json['message'] = $message_global['getlibcategory']['success_get_category'];
    return get_200_success($json);

 }

 function getDetail( $request_data ){
    // INITIALIZATION START
    $json = array();
    global $wpdb;
    global $message_global;
    $json['data'] = $data = array();
    $json['message'] = '';
    $post_id = '';
    
    if( !isset( $request_data['post_id'] ) ){ 
        $json['message'] = $message_global['getdetail']['post_id_not_provided'];
        return get_400_error( $json );
    } else {
        $post_id = $request_data['post_id'];
    }

    $single_post = get_post( $post_id );
    if( !isset( $single_post ) ) {
        $json['message'] = $message_global['getdetail']['post_not_found'];
        return get_400_error( $json );
    }

    $file_url = $image = $presentation_link = '' ;
    if( get_field('file_url', $post_id) ) {
        $file_url = get_file_path('library','en', get_field('file_url', $post_id));
    }
    if( get_field('image',$post_id) ) {
        $image = get_field('image', $post_id);
    }
    if( get_field('presentation_link', $post_id ) ) {
        $presentation_link = get_field('presentation_link', $post_id );
    }
    $title = ( get_the_title( $post_id ) ) ? get_the_title( $post_id ) : '';
    $type = get_field('select_type', $post_id);

    $push =  array(
                        'post_id'           => (int)$post_id,
                        'type'              => $type,
                        'title'             => $title,
                );
    switch( $type ) {
        case 'brochure':
            $push['file_url'] = $file_url;
            $data =  $push;
        break;
        case 'products':
            $push['file_url'] = $file_url;
            $data =  $push;           
        break;
        case 'flyers':
            $push['file_url'] = $file_url;
            $data =  $push;
        break;
        case 'socialpost':
            $push['image'] = $image;
            $data =  $push;
        break;
        case 'video':
            $push['video_link'] = $video_link;
            $data =  $push;
        break;
        case 'presentation':
        if( $file_url != '' ) {
            $push['file_url'] = $file_url;
        }
        else if( $presentation_link != '' ) {
            $push['file_url'] = $presentation_link;
        }                   
        $data =  $push;
        break;
        default:
        break;
    }
    $json['data']    = $data;
    $json['message'] = $message_global['getdetail']['get_detail_success'];
    return get_200_success($json);  
}
*/