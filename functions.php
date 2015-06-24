<?php
/**
 * Wait and Eat functions and definitions
 *
 * @package Wait and Eat
 */

if ( ! function_exists( 'wait_and_eat_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wait_and_eat_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Wait and Eat, use a find and replace
	 * to change 'wait-and-eat' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wait-and-eat', get_template_directory() . '/languages' );

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'wait-and-eat' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wait_and_eat_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // wait_and_eat_setup
add_action( 'after_setup_theme', 'wait_and_eat_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wait_and_eat_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wait_and_eat_content_width', 640 );
}
add_action( 'after_setup_theme', 'wait_and_eat_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function wait_and_eat_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wait-and-eat' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'wait_and_eat_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wait_and_eat_scripts() {
	wp_enqueue_style( 'wait-and-eat-style', get_stylesheet_uri() );

	wp_enqueue_script( 'wait-and-eat-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'wait-and-eat-api-editor', get_template_directory_uri() . '/js/wait-and-eat-api-editor.js', array( 'jquery' ), '20150720', true );

	wp_enqueue_script( 'wait-and-eat-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'wait-and-eat-api-editor', 'WAIT_AND_EAT', array(
		'url'   => rest_url('wp/v2'),
		'nonce' => wp_create_nonce( 'wp_json' ),
		'successMessage'    => __( 'Post Created Successfully', 'wait-and-eat' ),
		'failureMessage'    => __( 'An Error has occurred', 'wait-and-eat' ),
		'userID'            => get_current_user_id()
	) );
}
add_action( 'wp_enqueue_scripts', 'wait_and_eat_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function wait_and_eat_register_guest_post_type() {
	$singular = 'Guest';
	$plural = 'Guests';

	$labels = array(
		'name' 			=> $plural,
		'singular_name' 	=> $singular,
		'add_new' 		=> 'Add New',
		'add_new_item'  	=> 'Add New ' . $singular,
		'edit'		        => 'Edit',
		'edit_item'	        => 'Edit ' . $singular,
		'new_item'	        => 'New ' . $singular,
		'view' 			=> 'View ' . $singular,
		'view_item' 		=> 'View ' . $singular,
		'search_term'   	=> 'Search ' . $plural,
		'parent' 		=> 'Parent ' . $singular,
		'not_found' 		=> 'No ' . $plural .' found',
		'not_found_in_trash' 	=> 'No ' . $plural .' in Trash'
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'menu_position'       => 10,
		'menu_icon'           => 'dashicons-editor-paste-text',
		'can_export'          => false,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => false,
		'query_var'           => true,
		'capability_type'     => 'post',
		'rewrite'             => array(
			'slug' => strtolower( $singular ),
		),
		'supports'            => array(
			'title',
		),
		'show_in_rest'        => true,
		'rest_base'           => strtolower( $plural ),
		'rest_controller_class' => 'WP_REST_Posts_Controller'
	);
	register_post_type( strtolower( $singular ), $args );
}
add_action( 'init', 'wait_and_eat_register_guest_post_type' );

function wait_and_eat_register_meta_box() {
	add_meta_box(
		'wait_and_eat_guest',
		__( 'Guest Info' ),
		'wait_and_eat_guest_callback',
		'guest',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wait_and_eat_register_meta_box' );

function wait_and_eat_guest_callback( $post ) {
	wp_create_nonce( basename( __FILE__ ), 'wait_and_eat_nonce');
	$wait_and_eat_stored_meta = get_post_meta( $post->ID );
	?>

	<label for="phone-number"><?php _e( 'Phone Number', 'wait-and-eat' ); ?></label>
	<input type="text"
	       name="phone-number"
	       id="phone-number"
	       value="<?php if ( isset ( $wait_and_eat_stored_meta['phone-number'] ) ) echo esc_attr( $wait_and_eat_stored_meta['phone-number'][0] ); ?>"
	/>

	<label for="guest-count"><?php _e( 'Guest Count', 'wait-and-eat' ); ?></label>
	<input type="number"
	       name="guest-count"
	       id="guest-count"
	       min="1"
	       max="20"
	       value="<?php if ( isset ( $wait_and_eat_stored_meta['guest-count'] ) ) echo esc_attr( $wait_and_eat_stored_meta['guest-count'][0] ); ?>"
		/>

	<?php
}

function wait_and_eat_save_post_meta( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'hrm_nonce' ] ) && wp_verify_nonce( $_POST[ 'hrm_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'phone-number' ] ) ) {
		update_post_meta( $post_id, 'phone-number', sanitize_text_field( $_POST[ 'phone-number' ] ) );
	}

	if( isset( $_POST[ 'guest-count' ] ) ) {
		update_post_meta( $post_id, 'guest-count', sanitize_text_field( $_POST[ 'guest-count' ] ) );
	}
}
add_action( 'save_post', 'wait_and_eat_save_post_meta' );

function wait_and_eat_register_meta_fields() {
	$schema = array(
		'type'  => 'text',
		'description' => 'Custom Meta fields for the Guest Post Type',
		'context' => array( 'view', 'edit' ),
	);

	register_api_field( 'guest', 'guest_meta', array(
		'schema'    => $schema,
		'get_callback'  => 'wait_and_eat_guest_phone_get_callback',
		'update_callback' => 'wait_and_eat_guest_phone_update_callback'
	) );
}
add_action( 'rest_api_init', 'wait_and_eat_register_meta_fields' );

function wait_and_eat_guest_phone_get_callback( $post_data ) {
	$guest_meta = get_post_meta( $post_data['id'] );

	$guest_meta = array_slice( $guest_meta, 2 );

	return $guest_meta;
}

function wait_and_eat_guest_phone_update_callback( $value, $post ) {
	if ( ! is_string( $value ) ) {
		return new WP_Error( 'rest_meta_guest_phone_invalid',
			__( 'The Phone Number value is expected to be a string.' ),
			array( 'status' => 403 )
		);
	}

	$value = sanitize_text_field( $value );

	$update = update_post_meta( $post->ID, 'phone-number', $value );

	return $update;
}

function wait_and_eat_post_edit_form() {

	$form = '<form id="editor">';
	$form .= '<input type="text" name="title" id="title" value="Hello There"/>';
	$form .= '<input type="text" name="phone-number" id="phone-number" value=""/>';
	$form .= '<input type="number" name="guest-count" id="guest-count" min="1" max="20" value=""/>';
	$form .= '<input type="submit" id="submit" value="' . __( "Submit" ) . '"/>';
	$form .= '</form>';
	$form .= '<div id="results"></div>';

	if ( is_user_logged_in() ) {
		if ( user_can( get_current_user_id(), 'edit_posts' ) ) {
			return $form;
		} else {
			return __( 'You do not have permissions to edit posts', 'wait-and-eat' );
		}
	} else {
		return sprintf( '<a href="%1s" title="Login">%2s</a>', wp_login_url( get_permalink( get_queried_object_id() ) ), __( 'You must be logged in to edit posts, please click here to log in.', 'wait-and-eat') );
	}
}
add_shortcode( 'WAIT-AND-EAT', 'wait_and_eat_post_edit_form' );