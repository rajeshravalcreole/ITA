<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Investors_Trust
 * @since 1.0.0
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php 
	if ( is_single() && 'discover' == get_post_type(get_the_ID()) ) {
   		$post_id = get_the_ID();
		$selected_type = get_field('select_type',$post_id);
		if($selected_type == 'news'){
			$sharing_title = $image = "";
			$sharing_title = get_the_title()." | ".get_bloginfo( 'name' );
			$image = get_field('banner_image',$post_id);
			$long_description = wp_strip_all_tags(get_field('long_description',$post_id));
			?>
			<!-- Open Graph data -->
			
			<meta property="fb:app_id" content="321579074980363" />
			<meta property="og:title" content="<?php echo $sharing_title; ?>" />
			<meta property="og:type" content="article" />
			<meta property="og:url" content="<?php echo get_the_permalink($post_id); ?>" />
			<meta property="og:image" content="<?php echo $image; ?>" />			
			<meta property="og:description" content="<?php echo $long_description; ?>" />
			
			<!-- Twitter Card data -->
			<meta name="twitter:card" content="summary_large_image" />
			<meta name="twitter:title" content="<?php echo $sharing_title; ?>" />
			<meta name="twitter:description" content="<?php echo $long_description; ?>">
			<meta name="twitter:image" content="<?php echo $image; ?>" />

			<!-- Schema.org markup for Google+ -->
			<meta itemprop="name" content="<?php echo $sharing_title; ?>">
			<meta itemprop="description" content="<?php echo $long_description; ?>">
			<meta itemprop="image" content="<?php echo $image; ?>"> 
			
			<meta name="weibo:article:image" content="<?php echo $image; ?>" />
			<meta name="weibo:article:url" content="<?php echo get_the_permalink($post_id); ?>" />
			<meta name="weibo:article:title" content="<?php echo $sharing_title; ?>" />
			<meta name="weibo:article:description" content="<?php echo $long_description; ?>" />
			<?php			
		}
	}
wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header>
			<div class="container">
				<nav class="navbar navbar-expand-lg navbar-light">
				<?php
				$logo_image = (get_acf_option('logo_image','en')) ? wp_get_attachment_url(get_acf_option('logo_image','en')) : get_template_directory_uri()."/images/logo-main.png";
                $sublogo = (get_acf_option('sublogo','en')) ? wp_get_attachment_url(get_acf_option('sublogo','en')) : get_template_directory_uri()."/images/logo-subtitle.png";
	          	?>
					<a class="navbar-brand" href="javacript:void(0);">
						<img src="<?php if($logo_image){ echo $logo_image; } ?>" class="w-100">
						<img src="<?php if($sublogo){ echo $sublogo; } ?>" class="sublogo">
					</a>
					<div class="dropdown lang-section">
						<?php dynamic_sidebar( 'language-sw' ); ?>
					</div>
				</nav>
			</div>
		</header>
	<div id="content" class="site-content">
		<?php
		/*function get_poll_answer_precent($post_id) {
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
		}*/
		?>