<?php
/**
 * Investors Trust functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Investors_Trust
 * @since 1.0.0
 */

/**
 * Investors Trust only works in WordPress 4.7 or later.
 */

if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

require_once 'api/message_constants.php';

if ( ! function_exists( 'investorstrust_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function investorstrust_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Investors Trust, use a find and replace
		 * to change 'investorstrust' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'investorstrust', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'investorstrust' ),
				'footer' => __( 'Footer Menu', 'investorstrust' ),
				'social' => __( 'Social Links Menu', 'investorstrust' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 190,
				'width'       => 190,
				'flex-width'  => false,
				'flex-height' => false,
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'investorstrust' ),
					'shortName' => __( 'S', 'investorstrust' ),
					'size'      => 19.5,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'investorstrust' ),
					'shortName' => __( 'M', 'investorstrust' ),
					'size'      => 22,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'investorstrust' ),
					'shortName' => __( 'L', 'investorstrust' ),
					'size'      => 36.5,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'investorstrust' ),
					'shortName' => __( 'XL', 'investorstrust' ),
					'size'      => 49.5,
					'slug'      => 'huge',
				),
			)
		);

		// Editor color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'investorstrust' ),
					'slug'  => 'primary',
					'color' => investorstrust_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 33 ),
				),
				array(
					'name'  => __( 'Secondary', 'investorstrust' ),
					'slug'  => 'secondary',
					'color' => investorstrust_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 23 ),
				),
				array(
					'name'  => __( 'Dark Gray', 'investorstrust' ),
					'slug'  => 'dark-gray',
					'color' => '#111',
				),
				array(
					'name'  => __( 'Light Gray', 'investorstrust' ),
					'slug'  => 'light-gray',
					'color' => '#767676',
				),
				array(
					'name'  => __( 'White', 'investorstrust' ),
					'slug'  => 'white',
					'color' => '#FFF',
				),
			)
		);

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
	}
endif;
add_action( 'after_setup_theme', 'investorstrust_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function investorstrust_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'investorstrust' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'investorstrust' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

    register_sidebar(
        array(
            'name'          => __( 'Header Language switcher', 'links' ),
            'id'            => 'language-sw',
            'description'   => __( 'Add widgets here to appear in your header.', 'links' ),
            'before_widget' => '<div class="head-lang">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        )
    );

}
add_action( 'widgets_init', 'investorstrust_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function investorstrust_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'investorstrust_content_width', 640 );
}
add_action( 'after_setup_theme', 'investorstrust_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function investorstrust_scripts() {
	wp_enqueue_style( 'investorstrust-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'bootstarp-styles', get_template_directory_uri() . '/css/bootstrap.css', array() );
    wp_enqueue_style( 'custom-styles', get_template_directory_uri() . '/css/styles.css', array() );
    wp_enqueue_script( 'jquery-scripts', get_template_directory_uri() . '/js/jquery.min.js', array(), '', true );
    wp_enqueue_script( 'bootstrap-scripts', get_template_directory_uri() . '/js/bootstrap.js', array(), '', true );

	wp_style_add_data( 'investorstrust-style', 'rtl', 'replace' );

	if ( has_nav_menu( 'menu-1' ) ) {
		wp_enqueue_script( 'investorstrust-priority-menu', get_theme_file_uri( '/js/priority-menu.js' ), array(), '1.1', true );
		wp_enqueue_script( 'investorstrust-touch-navigation', get_theme_file_uri( '/js/touch-keyboard-navigation.js' ), array(), '1.1', true );
	}

	wp_enqueue_style( 'investorstrust-print-style', get_template_directory_uri() . '/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'investorstrust_scripts' );

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function investorstrust_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'investorstrust_skip_link_focus_fix' );

/**
 * Enqueue supplemental block editor styles.
 */
function investorstrust_editor_customizer_styles() {

	wp_enqueue_style( 'investorstrust-editor-customizer-styles', get_theme_file_uri( '/style-editor-customizer.css' ), false, '1.1', 'all' );

	if ( 'custom' === get_theme_mod( 'primary_color' ) ) {
		// Include color patterns.
		require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
		wp_add_inline_style( 'investorstrust-editor-customizer-styles', investorstrust_custom_colors_css() );
	}
}
add_action( 'enqueue_block_editor_assets', 'investorstrust_editor_customizer_styles' );

/**
 * Display custom color CSS in customizer and on frontend.
 */
function investorstrust_colors_css_wrap() {

	// Only include custom colors in customizer or frontend.
	if ( ( ! is_customize_preview() && 'default' === get_theme_mod( 'primary_color', 'default' ) ) || is_admin() ) {
		return;
	}

	require_once get_parent_theme_file_path( '/inc/color-patterns.php' );

	$primary_color = 199;
	if ( 'default' !== get_theme_mod( 'primary_color', 'default' ) ) {
		$primary_color = get_theme_mod( 'primary_color_hue', 199 );
	}
	?>

	<style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . absint( $primary_color ) . '"' : ''; ?>>
		<?php echo investorstrust_custom_colors_css(); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'investorstrust_colors_css_wrap' );

/**
 * SVG Icons class.
 */
require get_template_directory() . '/classes/class-investorstrust-svg-icons.php';

/**
 * Custom Comment Walker template.
 */
require get_template_directory() . '/classes/class-investorstrust-walker-comment.php';

/**
 * Enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * SVG Icons related functions.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * Custom template tags for the theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Registerd the custom post types for the Investores trust admin panel.
 */



/*******************************************************************************************************************
 *							CUSTOM POST STARTS
 *******************************************************************************************************************/
function custom_post_type() {
	/**
     * CUSTOM POST TYPE DISCOVER
     */ 
    $labels = array(
        'name'                => _x( 'Discover', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Discover', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Discover', 'investorstrust' ),
        'all_items'           => __( 'All Discover', 'investorstrust' ),
        'view_item'           => __( 'View Discover', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Discover', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Discover', 'investorstrust' ),
        'update_item'         => __( 'Update Discover', 'investorstrust' ),
        'search_items'        => __( 'Search Discover', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Discover', 'investorstrust' ),
        'description'         => __( 'Discover ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'menu_icon' 		  => 'dashicons-megaphone',
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'Discover', $args );
    /**
     * CUSTOM POST TYPE LIBRARY
     */ 
    $labels = array(
        'name'                => _x( 'Library', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Library', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Library', 'investorstrust' ),
        'all_items'           => __( 'All Items', 'investorstrust' ),
        'view_item'           => __( 'View Library', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Item', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Library', 'investorstrust' ),
        'update_item'         => __( 'Update Library', 'investorstrust' ),
        'search_items'        => __( 'Search Library', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Library', 'investorstrust' ),
        'description'         => __( 'Library ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-media-interactive',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'Library', $args );  
    /**
     * CUSTOM POST TYPE Resources
     */ 
    $labels = array(
        'name'                => _x( 'Resource', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Resource', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Resource', 'investorstrust' ),
        'all_items'           => __( 'All Items', 'investorstrust' ),
        'view_item'           => __( 'View Resource', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Item', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Resource', 'investorstrust' ),
        'update_item'         => __( 'Update Resource', 'investorstrust' ),
        'search_items'        => __( 'Search Resource', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Resource', 'investorstrust' ),
        'description'         => __( 'Resource ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-groups',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'Resource', $args );
    /**
     * CUSTOM POST TYPE Fund Platform
     */ 
    $labels = array(
        'name'                => _x( 'Funds', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Fund', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Funds', 'investorstrust' ),
        'all_items'           => __( 'All Funds', 'investorstrust' ),
        'view_item'           => __( 'View Fund', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Fund', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Fund', 'investorstrust' ),
        'update_item'         => __( 'Update Fund', 'investorstrust' ),
        'search_items'        => __( 'Search Funds', 'investorstrust' ),
        'not_found'           => __( 'No Fund Found.', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not Fund Found in Trash.', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Funds', 'investorstrust' ),
        'description'         => __( 'Fund', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-media-document',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'Funds', $args ); 
    /**
     * CUSTOM POST TYPE Fund Platform
     */ 
    $labels = array(
        'name'                => _x( 'Poll Answers', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Poll Answer', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Poll Answers', 'investorstrust' ),
        'all_items'           => __( 'All Poll Answers', 'investorstrust' ),
        'view_item'           => __( 'View Poll Answer', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Poll Answer', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Poll Answer', 'investorstrust' ),
        'update_item'         => __( 'Update Poll Answer', 'investorstrust' ),
        'search_items'        => __( 'Search Poll Answers', 'investorstrust' ),
        'not_found'           => __( 'No Poll Answer Found.', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not Poll Answer Found in Trash.', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Poll Answers', 'investorstrust' ),
        'description'         => __( 'Poll Answer', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-media-document',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'poll-answer', $args ); 
    /**
     * CUSTOM POST TYPE Fund Platform
     */ 
    $labels = array(
        'name'                => _x( 'Survey Answers', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Survey Answer', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Survey Answers', 'investorstrust' ),
        'all_items'           => __( 'All Survey Answers', 'investorstrust' ),
        'view_item'           => __( 'View Survey Answer', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Survey Answer', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Survey Answer', 'investorstrust' ),
        'update_item'         => __( 'Update Survey Answer', 'investorstrust' ),
        'search_items'        => __( 'Search Survey Answers', 'investorstrust' ),
        'not_found'           => __( 'No Survey Answer Found.', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not Survey Answer Found in Trash.', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Survey Answers', 'investorstrust' ),
        'description'         => __( 'Survey Answer', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-media-document',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'survey-answer', $args ); 
     /**
     * CUSTOM POST TYPE  Tickets
     */ 
    $labels = array(
        'name'                => _x( 'Tickets', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Tickets', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Tickets', 'investorstrust' ),
        'all_items'           => __( 'All Items', 'investorstrust' ),
        'view_item'           => __( 'View Tickets', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Item', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Tickets', 'investorstrust' ),
        'update_item'         => __( 'Update Tickets', 'investorstrust' ),
        'search_items'        => __( 'Search Tickets', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Tickets', 'investorstrust' ),
        'description'         => __( 'Tickets ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'author', 'comments', 'custom-fields' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon' 		  => 'dashicons-tickets',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'Tickets', $args );
	 /**
     * CUSTOM POST TYPE Media Center
     */ 
    $labels = array(
        'name'                => _x( 'Media Center', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Media Center', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Media Center', 'investorstrust' ),
        'all_items'           => __( 'All Media Center', 'investorstrust' ),
        'view_item'           => __( 'View Media Center', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Media Center', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Media Center', 'investorstrust' ),
        'update_item'         => __( 'Update Media Center', 'investorstrust' ),
        'search_items'        => __( 'Search Media Center', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Media Center', 'investorstrust' ),
        'description'         => __( 'Media Center ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'menu_icon' 		  => 'dashicons-images-alt',
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'media-center', $args );
	 /**
     * CUSTOM POST TYPE TIMELINE
     */ 
    $labels = array(
        'name'                => _x( 'Timeline', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Timeline', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Timeline', 'investorstrust' ),
        'all_items'           => __( 'All Timeline', 'investorstrust' ),
        'view_item'           => __( 'View Timeline', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Timeline', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Timeline', 'investorstrust' ),
        'update_item'         => __( 'Update Timeline', 'investorstrust' ),
        'search_items'        => __( 'Search Timeline', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Timeline', 'investorstrust' ),
        'description'         => __( 'Timeline ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'menu_icon' 		  => 'dashicons-clock',
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'timeline', $args );
	 /**
     * CUSTOM POST TYPE TIMELINE
     */ 
    $labels = array(
        'name'                => _x( 'Popup', 'Post Type General Name', 'investorstrust' ),
        'singular_name'       => _x( 'Popup', 'Post Type Singular Name', 'investorstrust' ),
        'menu_name'           => __( 'Popup', 'investorstrust' ),
        'all_items'           => __( 'All Popup', 'investorstrust' ),
        'view_item'           => __( 'View Popup', 'investorstrust' ),
        'add_new_item'        => __( 'Add New Popup', 'investorstrust' ),
        'add_new'             => __( 'Add New', 'investorstrust' ),
        'edit_item'           => __( 'Edit Popup', 'investorstrust' ),
        'update_item'         => __( 'Update Popup', 'investorstrust' ),
        'search_items'        => __( 'Search Popup', 'investorstrust' ),
        'not_found'           => __( 'Not Found', 'investorstrust' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'investorstrust' ),
    );
    $args = array(
        'label'               => __( 'Popup', 'investorstrust' ),
        'description'         => __( 'Popup ', 'investorstrust' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', ),
        'menu_icon' 		  => 'dashicons-pressthis',
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'popup', $args );
    if(is_user_logged_in()){
    	$user = get_current_user_id();
    	$data  = get_userdata( $user );
	    $roles = $data->roles;
	    if ( !in_array(  'administrator' ,$roles) ) {
			add_filter('show_admin_bar', '__return_false');
	    }
    }
}
add_action( 'init', 'custom_post_type', 0 );

/*******************************************************************************************************************
 *							CUSTOM POST ENDS....
 *******************************************************************************************************************/

//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'investmenttrust_taxonomy_registration', 0 );
function investmenttrust_taxonomy_registration() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

/***
 *		FUND PLATFORM CUSTOM TAXONOMY FOR THE FILTERS STARTS
 **/
  $labels = array(
    'name' 				=> _x( 'Asset Class', 'taxonomy general name', 'investorstrust' ),
    'singular_name' 	=> _x( 'Asset Class', 'taxonomy singular name', 'investorstrust' ),
    'search_items' 		=> __( 'Search Asset Class', 'investorstrust' ),
    'all_items' 		=> __( 'All Asset Class', 'investorstrust'),
    'parent_item' 		=> __( 'Parent Asset class', 'investorstrust' ),
    'parent_item_colon' => __( 'Parent Asset class:' ,'investorstrust' ),
    'edit_item' 		=> __( 'Edit Asset class' , 'investorstrust'), 
    'update_item' 		=> __( 'Update Asset class', 'investorstrust' ),
    'add_new_item' 		=> __( 'Add New Asset class', 'investorstrust' ),
    'new_item_name' 	=> __( 'New Asset class Name', 'investorstrust' ),
    'menu_name' 		=> __( 'Asset Class', 'investorstrust' ),
  );    
 
// Now register the taxonomy Aseet Class
 
  register_taxonomy('assetclass',array('funds'), array(
    'hierarchical'  	=> true,
    'labels' 			=> $labels,
    'show_ui' 			=> true,
    'show_admin_column' => true,
    'query_var' 		=> true,
    'rewrite' 			=> array( 'slug' => 'assetclass' ),
  ));

/*
*   Taxonomy for the Investment Universe
*/
  $labels = array(
    'name' 				=> _x( 'Investment Universe', 'taxonomy general name', 'investorstrust' ),
    'singular_name' 	=> _x( 'investment Universe', 'taxonomy singular name', 'investorstrust' ),
    'search_items' 		=> __( 'Search Investment Universe', 'investorstrust' ),
    'all_items' 		=> __( 'All Investment Universe', 'investorstrust'),
    'parent_item' 		=> __( 'Parent Investment Universe', 'investorstrust' ),
    'parent_item_colon' => __( 'Parent Investment Universe:' ,'investorstrust' ),
    'edit_item' 		=> __( 'Edit Investment Universe' , 'investorstrust'), 
    'update_item' 		=> __( 'Update Investment Universe', 'investorstrust' ),
    'add_new_item' 		=> __( 'Add New Investment Universe', 'investorstrust' ),
    'new_item_name' 	=> __( 'New Investment Universe Name', 'investorstrust' ),
    'menu_name' 		=> __( 'Investment Universe', 'investorstrust' ),
  );    
 
// Now register the taxonomy Invewstment universe
 
  register_taxonomy('investmentuniverse',array('funds'), array(
    'hierarchical' 		=> true,
    'labels' 			=> $labels,
    'show_ui' 			=> true,
    'show_admin_column' => true,
    'query_var' 		=> true,
    'rewrite' 			=> array( 'slug' => 'investmentuniverse' ),
  ));

/**
 *  Taxonomy for the Fund Family
 */
	$labels = array(
		'name' 				=> _x( 'Fund Family', 'taxonomy general name', 'investorstrust' ),
		'singular_name' 	=> _x( 'Fund Family', 'taxonomy singular name', 'investorstrust' ),
		'search_items' 		=> __( 'Search Fund Family', 'investorstrust' ),
		'all_items' 		=> __( 'All Fund Family', 'investorstrust'),
		'parent_item' 		=> __( 'Parent Fund Family', 'investorstrust' ),
		'parent_item_colon' => __( 'Parent Fund Family:' ,'investorstrust' ),
		'edit_item' 		=> __( 'Edit Fund Family' , 'investorstrust'), 
		'update_item' 		=> __( 'Update Fund Family', 'investorstrust' ),
		'add_new_item' 		=> __( 'Add New Fund Family', 'investorstrust' ),
		'new_item_name' 	=> __( 'New Fund Family Name', 'investorstrust' ),
		'menu_name' 		=> __( 'Fund Family', 'investorstrust' ),
	);    
 
	// Now register the taxonomy Invewstment universe
 
  register_taxonomy('fundfamily',array('funds'), array(
    'hierarchical' 		=> true,
    'labels' 			=> $labels,
    'show_ui' 			=> true,
    'show_admin_column' => true,
    'query_var' 		=> true,
    'rewrite'		    => array( 'slug' => 'fundfamily' ),
  ));

	/**
	*  Taxonomy for library
	*/
	$common_values = array(
		'hierarchical' 		=> false,
		'show_ui' 			=> true,
		'show_admin_column' => true,
		'query_var' 		=> true,
	);

	$labels = array(
		'name' 				=> _x( 'Categories', 'taxonomy general name', 'investorstrust' ),
		'singular_name' 	=> _x( 'Category', 'taxonomy singular name', 'investorstrust' ),
		'search_items' 		=> __( 'Search Categories', 'investorstrust' ),
		'all_items' 		=> __( 'All Categories', 'investorstrust'),
		'parent_item' 		=> __( 'Parent Category', 'investorstrust' ),
		'parent_item_colon' => __( 'Parent Category:' ,'investorstrust' ),
		'edit_item' 		=> __( 'Edit Category' , 'investorstrust'), 
		'update_item' 		=> __( 'Update Category', 'investorstrust' ),
		'add_new_item' 		=> __( 'Add New Category', 'investorstrust' ),
		'new_item_name' 	=> __( 'New Category Name', 'investorstrust' ),
		'menu_name' 		=> __( 'Categories', 'investorstrust' ),
	);    
	$common_values['labels'] = $labels;
	register_taxonomy('discover-category',array('discover'),$common_values);

	$labels = array(
		'name' 				=> _x( 'Product Categories', 'taxonomy general name', 'investorstrust' ),
		'singular_name' 	=> _x( 'Product Category', 'taxonomy singular name', 'investorstrust' ),
		'search_items' 		=> __( 'Search Categories', 'investorstrust' ),
		'all_items' 		=> __( 'All Categories', 'investorstrust'),
		'parent_item' 		=> __( 'Parent Category', 'investorstrust' ),
		'parent_item_colon' => __( 'Parent Category:' ,'investorstrust' ),
		'edit_item' 		=> __( 'Edit Category' , 'investorstrust'), 
		'update_item' 		=> __( 'Update Category', 'investorstrust' ),
		'add_new_item' 		=> __( 'Add New Category', 'investorstrust' ),
		'new_item_name' 	=> __( 'New Product Category Name', 'investorstrust' ),
		'menu_name' 		=> __( 'Product Categories', 'investorstrust' ),
	);    
	$common_values['labels'] = $labels;
	register_taxonomy('product-category',array('library'),$common_values);

	$labels = array(
		'name' 				=> _x( 'Social Post Categories', 'taxonomy general name', 'investorstrust' ),
		'singular_name' 	=> _x( 'Social Post Category', 'taxonomy singular name', 'investorstrust' ),
		'search_items' 		=> __( 'Search Categories', 'investorstrust' ),
		'all_items' 		=> __( 'All Categories', 'investorstrust'),
		'parent_item' 		=> __( 'Parent Category', 'investorstrust' ),
		'parent_item_colon' => __( 'Parent Category:' ,'investorstrust' ),
		'edit_item' 		=> __( 'Edit Category' , 'investorstrust'), 
		'update_item' 		=> __( 'Update Category', 'investorstrust' ),
		'add_new_item' 		=> __( 'Add New Category', 'investorstrust' ),
		'new_item_name' 	=> __( 'New Social Post Category Name', 'investorstrust' ),
		'menu_name' 		=> __( 'Social Post Categories', 'investorstrust' ),
	); 
	$common_values['labels'] = $labels; 
	register_taxonomy('social-post-category',array('library'), $common_values);

	$labels = array(
		'name' 				=> _x( 'Flyer Categories', 'taxonomy general name', 'investorstrust' ),
		'singular_name' 	=> _x( 'Flyer Category', 'taxonomy singular name', 'investorstrust' ),
		'search_items' 		=> __( 'Search Categories', 'investorstrust' ),
		'all_items' 		=> __( 'All Categories', 'investorstrust'),
		'parent_item' 		=> __( 'Parent Category', 'investorstrust' ),
		'parent_item_colon' => __( 'Parent Category:' ,'investorstrust' ),
		'edit_item' 		=> __( 'Edit Category' , 'investorstrust'), 
		'update_item' 		=> __( 'Update Category', 'investorstrust' ),
		'add_new_item' 		=> __( 'Add New Category', 'investorstrust' ),
		'new_item_name' 	=> __( 'New Flyer Category Name', 'investorstrust' ),
		'menu_name' 		=> __( 'Flyer Categories', 'investorstrust' ),
	); 
	$common_values['labels'] = $labels; 
	register_taxonomy('flyer-category',array('library'), $common_values);
}

add_action('admin_head', 'hide_category_box_from_sidebar');
function hide_category_box_from_sidebar() {
  echo '<style>#tagsdiv-product-category, #tagsdiv-social-post-category, #tagsdiv-flyer-category {display:none !important;}</style>';
}

/**
 *  CUSTOM THEME OPTIONS PAGE FOR THE THEME SETTINGS 
 *  to get value from this page @link : https://www.advancedcustomfields.com/resources/get-values-from-an-options-page/
 *  
 *	@note : We have required the ACF plugin to see options on admin menu......
 */

if( function_exists('acf_add_options_page') ) {
	//acf_add_options_page();
	acf_add_options_page(array(
		'page_title' 	=> _x('Theme General options', 'investorstrust' ),
		'menu_title'	=> _x('Theme Options', 'investorstrust' ),
		'menu_slug' 	=> 'acf-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'App Version Control',
		'menu_title'	=> 'Version Control Settings',
		'parent_slug'	=> 'acf-options',
	));	
}
// Hardika Satasiya

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> _x('Library & Resource Settings', 'investorstrust' ),
		'menu_title'	=> _x('Translateable options', 'investorstrust' ),
		'menu_slug' 	=> 'translateable-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Version Control Messages', 'investorstrust' ),
		'menu_title'	=> _x('Version Control Messages', 'investorstrust' ),
		'parent_slug'	=> 'translateable-options',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Notification Settings', 'investorstrust' ),
		'menu_title'	=> _x('Notification Settings', 'investorstrust' ),
		'parent_slug'	=> 'translateable-options',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Email Template Configuration', 'investorstrust' ),
		'menu_title' 	=> 'Email Templates',
		'parent_slug' 	=> 'translateable-options',
	));
    
	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Education Calculator Details', 'investorstrust' ),
		'menu_title' 	=> 'Education Calculator',
		'parent_slug' 	=> 'translateable-options',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Retirement Calculator Details', 'investorstrust' ),
		'menu_title' 	=> 'Retirement Calculator',
		'parent_slug' 	=> 'translateable-options',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> _x('Savings Calculator Details', 'investorstrust' ),
		'menu_title' 	=> 'Savings Calculator',
		'parent_slug' 	=> 'translateable-options',
	));
    acf_add_options_sub_page(array(
		'page_title' 	=> _x('Portfolio Builder Options', 'investorstrust' ),
		'menu_title' 	=> 'Portfolio Builder',
		'parent_slug' 	=> 'translateable-options',
	));
}

add_action('admin_init', 'remove_acf_options_page', 99);
function remove_acf_options_page() {
	global $sitepress;
	if($sitepress->get_current_language()!="en"){
		remove_menu_page('acf-options');	
	}   
}

//INCLUDED TO ADD OUR OWN API CONSTANTS//
require_once 'api/constants_support_functions.php';
//INCLUDED TO REGISTER OUR OWN USER API ROUTES//
require_once 'api/route_functions.php';
//INCLUDED TO ADD OUR OWN AUTH API FUNCTIONS//
require_once 'api/auth_functions.php';
//INCLUDED TO ADD OUR OWN USER API FUNCTIONS//
require_once 'api/user_functions.php';
//DISCOVER SECTION FUNCTIONS
require_once 'api/discover_functions.php';
//LIBREARY SECTION FUNCTIONS
require_once 'api/library_functions.php';
//RESOURCE SECTION FUNCTIONS
require_once 'api/resource_functions.php';
//USER AND ADMIN FUNCTIONS
require_once 'inc/admin-functions.php';
//CONFIGURATION FUNCTIONS
require_once 'api/common_config_functions.php';
//REPORT AN ISSUE API FUNCTIONS
require_once 'api/report_issues_functions.php';
// require_once 'crons/cron_functions.php';
//MEDIA CENTER SECTION FUNCTIONS
require_once 'api/media_center_functions.php';
//TIMELINE SECTION FUNCTIONS
require_once 'api/timeline_functions.php';
//POPUP SECTION FUNCTIONS
require_once 'api/popup_functions.php';
//CALCULATOR SECTION FUNCTIONS
require_once 'api/calculator_functions.php';

add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
function title_like_posts_where( $where, $wp_query ) 
{
    global $wpdb;
    if(isset($wp_query->query['post_type']) && $wp_query->query['post_type']=='library') {
        if(isset($wp_query->query['language_code']) && $wp_query->query['language_code']!='') {
        	if($wp_query->query['language_code']!='en') {
        		$language_code = $wp_query->query['language_code'];
	        	/*$find = "wpml_translations.language_code = 'en' OR 0";
	        	$replace = "wpml_translations.language_code = '".$language_code."' OR 0";*/
	        	$find = "( wpml_translations.language_code = 'en' OR 0 ) AND ";
	        	$replace = "( wpml_translations.language_code = 'en' OR wpml_translations.language_code = '".$language_code."' OR 0 ) AND ";
	        	$where = str_replace($find,$replace,$where);	
        	}        	
        }	
    }

    if(isset($wp_query->query['post_type']) && $wp_query->query['post_type']=='funds')
    {
        $post_title_like = "";
        if(isset($wp_query->query['post_title_like']) && $wp_query->query['post_title_like']!='')
        {
            $post_title_like = $wp_query->query['post_title_like'];
            $where .= ' OR ((' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\' OR ' . $wpdb->posts . '.post_content LIKE \'%' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\' ) AND ' . $wpdb->posts . '.post_type = "funds" AND ' . $wpdb->posts . '.post_status = "publish")';
        }
    }
    return $where;
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

/**
 *  DISCOVER POST FILTER BY THE TYPE LIKE PDF FLYER POLL VIDEO
 */
add_filter( 'manage_discover_posts_columns', 'investorstrust_discover_post_column' );
function investorstrust_discover_post_column( $columns ) {
	//$columns['discover_type'] = __('Type','investorstrust');	
	unset($columns['date']);
	return array_merge( $columns, 
        array( 'discover_type' => __( 'Type', 'investorstrust' ), 'date' => __( 'Date', 'investorstrust' )  ) );
}

add_action( 'manage_discover_posts_custom_column', 'investorstrust_discover_post_column_content', 10, 2);
function investorstrust_discover_post_column_content( $column, $post_id ) {
  	if ( 'discover_type' === $column ) {
    	echo ucfirst(get_field('select_type', $post_id));
  	}
}
add_action( 'restrict_manage_posts', 'investorstrust_discover_type_column_custom_filters' ); 
function investorstrust_discover_type_column_custom_filters(){
    $type = 'discover';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];

        if($type=="discover") {
        	$types_list = array('flyer' => __('Flyer','investorstrust'),'news' => __('News','investorstrust'),'video'=> __('Video','investorstrust'),'poll'=> __('Poll','investorstrust'));
        	?>
	        	<select name="filter_by_type">
					<option value=""><?php _e('All types', 'investorstrust'); ?></option>
	        		<?php $current_value = isset($_GET['filter_by_type'])? $_GET['filter_by_type']:''; 
	        		foreach ($types_list as $key => $value) {
	                	printf('<option value="%s"%s>%s</option>',$key,$key == $current_value? ' selected="selected"':'',$value);
	                }
	        		?>
	        	</select>
        	<?php
        }
    }  
}
add_filter( 'parse_query', 'investoresturst_filter_of_type_in_discover_post' ); 
function investoresturst_filter_of_type_in_discover_post( $query )
{
    global $pagenow;
    if(is_admin() && $pagenow=='edit.php') {
    	$types_list_meta = array();
    	if (isset($_GET['filter_by_type']) && $_GET['filter_by_type'] != '') {
    		$types_list_meta = array('key' => 'select_type', 'compare' => '=', 'value' => $_GET['filter_by_type']);    
    		$query->query_vars['meta_query'] = array('relation'=>'AND',$types_list_meta);		
    	}
    }
}
/*****************  END   *******************************************/

/**
 *  LIBRARY POST FILTER BY THE TYPE LIKE PDF FLYER POLL VIDEO
 */
add_filter( 'manage_library_posts_columns', 'investorstrust_library_post_column' );
function investorstrust_library_post_column( $columns ) {
	//$columns['library_type'] = __('Type','investorstrust');	
	unset($columns['date']);
	return array_merge( $columns, 
        array( 'library_type' => __( 'Type', 'investorstrust' ), 'date' => __( 'Date', 'investorstrust' )  ) );
}

add_action( 'manage_library_posts_custom_column', 'investorstrust_library_post_column_content', 10, 2);
function investorstrust_library_post_column_content( $column, $post_id ) {
  	if ( 'library_type' === $column ) {
    	echo ucfirst(get_field('select_type', $post_id));
  	}
}
add_action( 'restrict_manage_posts', 'investorstrust_library_type_column_custom_filters' ); 
function investorstrust_library_type_column_custom_filters(){
    $type = 'library';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];

        if($type=="library") {
        	$types_list = array('brochure' => __('brochure','investorstrust'),'products' => __('products','investorstrust'),'flyers' => __('flyers','investorstrust'),'socialpost'=> __('socialpost','investorstrust'),'presentation'=> __('presentation','investorstrust'), 'video'=> __('video','investorstrust'));
        	?>
	        	<select name="filter_by_type">
					<option value=""><?php _e('All types', 'investorstrust'); ?></option>
	        		<?php $current_value = isset($_GET['filter_by_type'])? $_GET['filter_by_type']:''; 
	        		foreach ($types_list as $key => $value) {
	                	printf('<option value="%s"%s>%s</option>',$key,$key == $current_value? ' selected="selected"':'',$value);
	                }
	        		?>
	        	</select>
        	<?php
        }
    }  
}
add_filter( 'parse_query', 'investoresturst_filter_of_type_in_library_post' ); 
function investoresturst_filter_of_type_in_library_post( $query )
{
    global $pagenow;
    if(is_admin() && $pagenow=='edit.php') {
    	$types_list_meta = array();
    	if (isset($_GET['filter_by_type']) && $_GET['filter_by_type'] != '') {
    		$types_list_meta = array('key' => 'select_type', 'compare' => '=', 'value' => $_GET['filter_by_type']);    
    		$query->query_vars['meta_query'] = array('relation'=>'AND',$types_list_meta);		
    	}
    }
}
/*****************  END   *******************************************/
function font_awesome_admin_style() {
    wp_register_style( 'datatables-css',  get_template_directory_uri().'/DataTables/datatables.min.css' );
    wp_register_script( 'datatables',  get_template_directory_uri().'/DataTables/datatables.min.js', array('jquery'));
    wp_register_script( 'responsive',  get_template_directory_uri().'/DataTables/dataTables.responsive.min.js', array('jquery','datatables'));
    wp_register_style( 'dt-responsive',  get_template_directory_uri().'/DataTables/responsive.dataTables.min.css');
    wp_register_script( 'custom-admin',  get_template_directory_uri().'/js/custom-admin.js', array('jquery'));
    wp_register_style( 'custom',  get_template_directory_uri().'/css/custom-admin.css');
}
add_action( 'admin_enqueue_scripts', 'font_awesome_admin_style' );

add_action('add_meta_boxes', 'poll_answer_metabox');
function poll_answer_metabox() {
	global $post;
	if(isset($post) && $post->post_type=="discover") {
		$type = get_field('select_type', $post->ID);
		if(isset($type) && $type=="poll") {
			add_meta_box('poll-attempts-metabox', __( 'Poll Atempts', 'investorstrust' ), 'poll_attempts_metabox', 'discover', 'normal', 'low'); 	
		}    	
	}
}
function poll_attempts_metabox($post) {
	if(isset($post) && $post->post_type=="discover") {

	wp_enqueue_style('datatables-css');
    wp_enqueue_style('dt-responsive');
    wp_enqueue_script('datatables');
    wp_enqueue_script('responsive');
    wp_enqueue_script('custom-admin');
    wp_enqueue_style('custom');


		$type = get_field('select_type', $post->ID);
		if(isset($type) && $type=="poll") {
			$args = array ('post_type' => 'poll-answer','post_status' => 'publish','posts_per_page'=>-1,
							'meta_query'=>array('relation'=> 'AND',array('key'=>'answer_poll_id','compare' =>'==','value'=>$post->ID)));
		
				echo '<div id="poll-answers-datatable-wrapper">';
		        echo '<table id="poll-answers-table" class="responsive display compact" cellspacing="0" border="0" width="100%">';
		            echo '<thead>';
		                echo '<tr role="row">';
		                    echo '<th class="all">Submitted By</th>';
		                    echo '<th class="all">Answer</th>';
		                    echo '<th class="min-tablet-l" width="150px">Posted On</th>';
		                echo '</tr>';
		            echo '</thead>';
		            echo '<tbody>';
		            	$poll_answers = new WP_Query($args); 
    					if( $poll_answers->have_posts() ) { 
    						while ( $poll_answers->have_posts() ) {
	        					$poll_answers->the_post();
	        					$post_id = get_the_ID();

	        					$user_id = 0;
	        					$user_id = ( get_post_meta($post_id, 'answer_user_id', true) ) ? get_post_meta($post_id, 'answer_user_id',true) : "";
	        					$poll_answer = ( get_post_meta($post_id, 'poll_answer',true) ) ? get_post_meta($post_id , 'poll_answer',true) : "";
	        					if($user_id!=0) {
	        						$user_name = (get_user_meta( $user_id, 'full_name', true )) ? (get_user_meta( $user_id, 'full_name', true )) : " - ";

	        						echo '<tr><td>';
				                        echo '<strong>'.$user_name.'</strong>';
				                    echo '</td>';
				                    echo '<td>';
				                    	if(filter_var($poll_answer, FILTER_VALIDATE_URL)) {
				                    		echo "<img src='".$poll_answer."' width='150'>";
				                    	} else {
				                    		echo $poll_answer;
				                    	}				                        
				                    echo '</td>';
				                    echo '<td>';
				                        echo get_the_date( 'D M j' );
				                    echo '</td></tr>';
	        					}
	        				}
	        				wp_reset_query();
    					}		            	
		            echo '</tbody>';
		        echo '</table>';
	    	echo '</div>';
		}
	}
}

function push_notification($post_id) 
{ 
  	$select_type = get_post_meta($post_id,'select_type',true); 
  	$is_notification_send = get_post_meta($post_id,'send_notification_to_users',true); 
  	$post_type = get_post_type( $post_id );
  	global $wpdb;
	$prefix = $wpdb->prefix;
	$tablenotification = $prefix . 'notified_posts';
	$post = $post_id;
	$post_exist = "";
	if('publish' == get_post_status( $post )){
		$post_exist = $wpdb->get_row( "SELECT * FROM $tablenotification WHERE post_id = ". $post ." AND post_type='".$post_type."'");
		if($post_exist == null)
		{
			if($is_notification_send){
				if($select_type == 'news' && $post_type == 'discover')  { 
				    $data = array(
				                    'post_id' => $post,
				                    'post_type' => $post_type,
				                    'created_date' => date('Y-m-d H:i:s')
				                );
				    $wpdb->insert( $tablenotification, $data, $format = null );
				}
				if( $post_type == 'library' && $select_type != 'socialpost') { 
				    $post = $post_id;
				    $data = array(
				                    'post_id' => $post,
				                    'post_type' => $post_type,
				                    'created_date' => date('Y-m-d H:i:s')
				                );
				    $wpdb->insert( $tablenotification, $data, $format = null );
				}
			}
		}
	}
} 
add_action('save_post','push_notification',11,1);

function save_post_meta($post_id, $post)
{
	if(isset($_POST['select_type'])){
   		$ref_id = $_POST['select_type'];
   		update_post_meta($post_id,'select_type',$ref_id);
	}
}
add_action('save_post','save_post_meta',10,2);
function no_notification()
{
  remove_action('save_post','push_notification',11,1);
}
add_action('publish_to_publish','no_notification');

/* Constants used for push notifications */
define('FCM_URL_NEW', 'https://fcm.googleapis.com/fcm/send');
define('FCM_KEY_NEW', 'AAAA4YOG4zk:APA91bFuLXnoivPrlmjY6REN0IGRxdHLsvm1CAjaZYNxGSQf4YmFhsMtid-NFYM8QJp25SZCAchMZ5BvxjBB5h6B6wFvyr15LK3kBn8ibZwbr_P4_yHW6lPLfKGDrL-bFEqK7MUFuosO');
add_action( 'comment_post', 'show_message_function', 10, 2 );
function show_message_function( $comment_ID, $comment_approved ) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$device_token_table = $prefix . 'device_token';
	$comment_id = get_comment( $comment_ID );
	$content    = $comment_id->comment_content;
    $post_id    = $comment_id->comment_post_ID;
    $post_type  =  get_post_type( $post_id );
    if ( $post_type == 'tickets' ) {
	    $author_id  = get_post_field ( 'post_author', $post_id );
	    $devicetoken_qry     = "SELECT * FROM $device_token_table WHERE user_id =".$author_id;
	    $device_token_results = $wpdb->get_results( $devicetoken_qry );
	    if ( 1 === $comment_approved  ) {
	    	if ( is_admin() ) {
				if($device_token_results) {
	                foreach ( $device_token_results as $device_token_result ) {
				        //Title of the Notification.
				        $device_token = $device_token_result->device_token;
				        $os_type = $device_token_result->os_type;
				        $title = "Admin has replied to your report" ;
				        //Body of the Notification.
				        $body  = $content;
				        //Creating the notification array.
				       
                        if ( $os_type == 2 ){
                        	$notification = array( 'title' =>$title, 'body' => $body );
				        	$data = array('type' => 'report', 'post_id' => $post_id, 'post' => array(),  'title' =>$title, 'body' => $body, 'sound' => 1  );
					        // $sendPushIos  = sendPushNotificationAdmin( $notification, $device_token, $data , $os_type);
					        $sendPushIos  = sendPushNotificationAdmin( $notification, $device_token, $data , $os_type,  $author_id, '', 'Comment_posted_from_admin');
                        }
                        if ( $os_type == 1 ){
                        	$notification = array( 'title' =>$title, 'text' => $body, 'sound' => 1 );
				        	$data = array('type' => 'report', 'post_id' => $post_id, 'post' => array() );
					        // $sendPushIos  = sendPushNotificationAdmin( $notification, $device_token, $data, $os_type);
					        $sendPushIos  = sendPushNotificationAdmin( $notification, $device_token, $data , $os_type,  $author_id,'', 'Comment_posted_from_admin');
                        }
				    }
				}
			}
		}
	}
}

function sendPushNotificationAdmin($notification, $deviceToken, $data, $os_type, $user_id="",$post_id="", $notification_type="" ) {
    $ch = curl_init(FCM_URL_NEW);
    if($os_type == 2){
	    $arrayToSend  = array('to' =>  $deviceToken, 'notification' => $notification, 'collapse_key'=>'type_a', 'priority' => 'high', 'data' => $data);
    }
    if($os_type == 1){
	    $arrayToSend  = array('to' =>  $deviceToken, 'notification' => $notification, 'priority' => 'high', 'data' => $data);
    }

    //Generating JSON encoded string form the above array.
    $json = json_encode($arrayToSend);
    //Setup headers:
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='.FCM_KEY_NEW; // key here

    //Setup curl, add headers and post parameters.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);  
    // curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);     

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
                        'post_id' => $post_id,
                        'device_token' => $deviceToken ,
                        'sent_flag' => $sentFlag,
                        'notification_type' => $notification_type,
                        'created_on' => date('Y-m-d H:i:s')
                    );
        $SendMessageinsert = $wpdb->insert($table_notifications, $insertArr);
    }
    //Close request
    curl_close($ch);
    return $response;
}

/**
 * Searching User Meta Data in Admin
 */
add_action('pre_user_query','yoursite_pre_user_search');
function yoursite_pre_user_search($user_search) {
    global $wpdb, $pagenow; 

    if ( is_admin() && 'users.php' == $pagenow && isset( $_GET['s'] )){
	    //Enter Your Meta Fields To Query
	    $search_array = array("introducer_code","user_login","user_email","first_name","last_name");
	    $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id AND (";
	    for($i=0;$i<count($search_array);$i++) {
	        if ($i > 0) $user_search->query_from .= " OR ";
	            $user_search->query_from .= "{$wpdb->usermeta}.meta_key='" . $search_array[$i] . "'";
	        }
	    $user_search->query_from .= ")";        
	    $custom_where = $wpdb->prepare("{$wpdb->usermeta}.meta_value LIKE '%s'", "%" . $_GET['s'] . "%");
	    $user_search->query_where = str_replace('WHERE 1=1 AND (', "WHERE 1=1 AND ({$custom_where} OR ",$user_search->query_where);
    }else{
    	 return;
    }
}

add_filter( 'manage_popup_posts_columns', 'get_date_column' );
function get_date_column( $columns ) {
    $columns['start_date'] = 'Start Date';
    $columns['end_date'] = 'End Date';
    $columns['user'] = 'User';
    return $columns;
 }

 add_action( 'manage_popup_posts_custom_column', 'get_date_column_content', 5, 2 );
 // Add date & user column content
 function get_date_column_content( $column, $post_id ) {
    if ( 'start_date' === $column ) {
        $start_date = ( get_field('popup_start_date',$post_id) ) ? get_field('popup_start_date',$post_id) : "";
        $start_date = date('Y-m-d',strtotime($start_date));
        echo $start_date;
    }
    if ( 'end_date' === $column ) {
        $end_date = ( get_field('popup_end_date',$post_id) ) ? get_field('popup_end_date',$post_id) : "";
        $end_date = date('Y-m-d',strtotime($end_date));
        echo $end_date;
    }
    if ( 'user' === $column ) {
        $user = ( get_field('popup_user',$post_id) ) ? get_field('popup_user',$post_id) : "";
        echo $user;
    }
 }

 add_action( 'admin_enqueue_scripts', 'enqueue_custom_sortable_script' );
 function enqueue_custom_sortable_script() {
     $current_screen = get_current_screen();
    if ($current_screen->id === 'edit-media-center') {
        wp_dequeue_script( 'hicpojs' );
        wp_enqueue_script( 'sortable-scripts', get_template_directory_uri() . '/js/custom_sortable.js', array(), '', true );
        wp_localize_script( 'sortable-scripts', 'custom_localize_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'lang_code' => ICL_LANGUAGE_CODE,
        ));
    }

}

add_action( 'wp_ajax_update-custom-menu-order', 'action_callback' );
function action_callback() {

    global $wpdb;

    parse_str( $_POST['order'], $data );
    
    if ( !is_array( $data ) ) return false;
        
    // get objects per now page
    $id_arr = array();
    foreach( $data as $key => $values ) {
        foreach( $values as $position => $id ) {
            $id_arr[] = $id;
        }
    }
    
    // get menu_order of objects per now page
    $menu_order_arr = array();
    foreach( $id_arr as $key => $id ) {
        $results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = ".intval( $id ) );
        foreach( $results as $result ) {
            $menu_order_arr[] = $result->menu_order;
        }
    }
    
    // maintains key association = no
    sort( $menu_order_arr );
    
    foreach( $data as $key => $values ) {
        foreach( $values as $position => $id ) {
            $wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[$position] ), array( 'ID' => intval( $id ) ) );
        }
    }

    $query = new WP_Query( array( 'post_type' => 'media-center', 'suppress_filters' => true, 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => -1 ) );
    $posts = $query->posts;
    
    foreach($posts as $post) {
        $id = $post;
        $postid = icl_object_id($id,'post',false,'en');
        
        if ( $id !== $postid ) {
            $result = $wpdb->get_results( "
            SELECT menu_order
            FROM $wpdb->posts 
            WHERE post_type = 'media-center' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') AND ID = $postid
            " );
            $new_order = $result[0]->menu_order;

            $result = $wpdb->get_results( "
            UPDATE $wpdb->posts
            SET menu_order = $new_order
            WHERE ID = $id" );
        }
    }
}
