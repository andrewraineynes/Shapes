<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Genesis Sample' );
define( 'CHILD_THEME_URL', 'https://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.8.0' );

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style(
		'genesis-sample-fonts',
		'//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
		array(),
		CHILD_THEME_VERSION
	);

	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script(
		'genesis-sample-responsive-menu',
		get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js",
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);

	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

	wp_enqueue_script(
		'genesis-sample',
		get_stylesheet_directory_uri() . '/js/genesis-sample.js',
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);

}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'genesis-sample' ),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Adds support for HTML5 markup structure.
add_theme_support( 'html5', genesis_get_config( 'html5' ) );

// Adds support for accessibility.
add_theme_support( 'genesis-accessibility', genesis_get_config( 'accessibility' ) );

// Adds viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Adds custom logo in Customizer > Site Identity.
add_theme_support( 'custom-logo', genesis_get_config( 'custom-logo' ) );

// Renames primary and secondary navigation menus.
add_theme_support( 'genesis-menus', genesis_get_config( 'menus' ) );

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes( $_genesis_admin_settings ) {

	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings' );
/**
 * Removes output of header and front page breadcrumb settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	unset( $config['genesis']['sections']['genesis_breadcrumbs']['controls']['breadcrumb_front_page'] );
	return $config;

}

// Displays custom logo.
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

add_action( 'genesis_entry_content', 'sk_show_featured_image_single_posts', 9 );
/**
 * Display Featured Image floated to the right in single Posts.
 *
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/how-to-display-featured-image-in-single-posts-in-genesis/
 */
function sk_show_featured_image_single_posts() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$image_args = array(
		'size' => 'large',
		'attr' => array(
			'class' => 'left',
		),
	);

	genesis_image( $image_args );
}


// Registers a custom image size for image thumbs on content archives.
add_image_size( 'masonry-image', 500, 0, true );

add_action( 'pre_get_posts', 'sk_change_archives_posts_per_page' );
/**
 * Changes Posts Per Page for Posts page and archives.
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/customize-the-wordpress-query/
 * @param object $query data.
 *
 */
function sk_change_archives_posts_per_page( $query ) {

    if ( $query->is_main_query() && ! is_admin() && ( is_home() || is_archive() ) ) {
        $query->set( 'posts_per_page', '8' );
    }

}

//do not add in opening php tag
 
/**
 * Add a Header Row above the 3 footer widgets in Genesis Theme
 *
 * @package   Add a Header Row above the 3 footer widgets
 * @author    Neil Gee
 * @link      https://wpbeaches.com/add-full-width-row-footer-widgets-genesis-child-theme/
 * @copyright (c)2014, Neil Gee
 */
 
 add_action ( 'widgets_init','genesischild_footerwidgetheader' );
// Extra the Footer Header Widget Area.
function genesischild_footerwidgetheader() {
	genesis_register_sidebar( array(
	'id'          => 'footerwidgetheader',
	'name'        => __( 'Footer Widget Header', 'genesis' ),
	'description' => __( 'This is for the Footer Widget Headline', 'genesis' ),
	) );
	
}


// Action set to a higher priority to fire before footer widgets.
add_action ( 'genesis_before_footer','genesischild_footerwidgetheader_position', 5);
// Position Footer Header Widget.
function genesischild_footerwidgetheader_position ()  {
	echo '<div class="footerwidgetheader-container"><div class="wrap">';
	genesis_widget_area ( 'footerwidgetheader' );
	echo '</div></div>';
    
   
}

// * Relocate titles on Page, Post and other single pages
add_action( 'genesis_after_header', 'relocate_entry_title_singular' );
function relocate_entry_title_singular() {

	if ( ! is_singular() ) {
		return;
	}

	echo '<div class="entry-header-wrapper"><div class="wrap">';
		genesis_do_post_title();
		genesis_post_info();
	echo '</div></div>';

	if ( is_page_template( 'page_blog.php' ) ) {
		return;
	}

	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	if ( ! is_singular( 'post' ) ) {
		return;
	}

	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
}

/**
 * Default Category Title
 *
 * @author Bill Erickson
 * @url http://www.billerickson.net/default-category-and-tag-titles
 *
 * @param string $headline
 * @param object $term
 * @return string $headline
 */
function be_default_category_title( $headline, $term ) {
	if( ( is_category() || is_tag() || is_tax() ) && empty( $headline ) )
		$headline = $term->name;

	return $headline;
}
add_filter( 'genesis_term_meta_headline', 'be_default_category_title', 10, 2 );

//* Relocate titles on category / tag / taxonomy archive pages
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description' );

//* Relocate titles on author archive pages
remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
add_action( 'genesis_after_header', 'genesis_do_author_title_description' );

//* Relocate titles on relevant custom post type archive pages
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
add_action( 'genesis_after_header', 'genesis_do_cpt_archive_title_description' );

//* Relocate titles on date archive pages
remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );
add_action( 'genesis_after_header', 'genesis_do_date_archive_title' );

//* Relocate titles on search results pages
add_action( 'genesis_header', 'sk_search_title' );
function sk_search_title() {

	// if we are not on a search results page, abort.
	if ( ! is_search() ) {
		return;
	}

	remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
	add_action( 'genesis_after_header', 'genesis_do_search_title' );

}

add_filter( 'wp_nav_menu_items', 'sk_menu_extras', 10, 2 );
/**
 * Filter menu items, appending a a search icon at the end.
 *
 * @param string   $menu HTML string of list items.
 * @param stdClass $args Menu arguments.
 *
 * @return string Amended HTML string of list items.
 */
function sk_menu_extras( $menu, $args ) {

    //* Add HTML to the menu in primary location
    if ( 'primary' == $args->theme_location ) {
        $menu .= '<li class="menu-item alignright"><a id="trigger-overlay" class="search-icon" href="#"><div class="search-text">Search</div> <i class="fa fa-search"></i></a></li>';
    }

    return $menu;

}

// Load Font Awesome, Sticky Kit and files needed for full screen overlay
add_action( 'wp_enqueue_scripts', 'sk_enqueue_stuff' );
function sk_enqueue_stuff() {

    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );

    wp_enqueue_style( 'style3', get_stylesheet_directory_uri() . '/css/style3.css' );

    wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/js/modernizr.custom.js' );

    wp_enqueue_script( 'classie', get_stylesheet_directory_uri() . '/js/classie.js', '', '', true );
    wp_enqueue_script( 'demo1', get_stylesheet_directory_uri() . '/js/demo1.js', '', '', true );

    // if being viewed on a tablet or mobile, abort.
    if ( wp_is_mobile() ) {
        return;
    }

    wp_enqueue_script( 'sticky-kit', get_stylesheet_directory_uri() . '/js/jquery.sticky-kit.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

    wp_enqueue_script( 'non-handhelds', get_stylesheet_directory_uri() . '/js/non-handhelds.js', array( 'sticky-kit' ), CHILD_THEME_VERSION, true );

}

//* Customize search form input button text
add_filter( 'genesis_search_button_text', 'sk_search_button_text' );
function sk_search_button_text( $text ) {

    return esc_attr( '&#xf002;' );

}

//* Overlay content
add_action( 'genesis_after', 'sk_search' );
function sk_search() {

    echo '<div class="overlay overlay-slidedown">';
        echo '<button type="button" class="overlay-close">Close</button>';
        get_search_form();
    echo '</div>';

}

add_action( 'genesis_after_entry', 'sk_adjacent_entry_nav' );

add_action( 'get_header', 'remove_titles_all_single_pages' );
function remove_titles_all_single_pages() {
    if ( is_singular('page') ) {
        remove_action( 'genesis_after_entry', 'sk_adjacent_entry_nav' );
    }
}
/**
 * Display visual links to previous and next entry.
 *
 * @author Sridhar Katakam
 *
 * @return null Return early if not singular.
 */
function sk_adjacent_entry_nav() {

    if ( ! is_singular() ) {
        return;

        
    }



    // http://codex.wordpress.org/Function_Reference/get_adjacent_post
    // get_adjacent_post( $in_same_term, $excluded_terms, $previous, $taxonomy )

    // get the previous post object
    $prev_post = get_adjacent_post( false, '', true );

    // get the next post object
    $next_post = get_adjacent_post( false, '', false );


    genesis_markup( array(
        'html5'   => '<div %s>',
        'xhtml'   => '<div class="navigation">',
        'context' => 'adjacent-entry-pagination',
    ) );

        echo '<div class="pagination-previous alignleft">';
                previous_post_link( '%link', '<div class="post-navigation-content"><div class="previous-next previous">« Older Post</div><span class="post-navigation-title">%title</span></div>' );
        echo '</div>';

        echo '<div class="pagination-next alignright">';
                next_post_link( '%link', '<div class="post-navigation-content"><div class="previous-next next">Newer Post »</div><span class="post-navigation-title">%title</span></div>' );
        echo '</div>';

    echo '</div>';

}


//* Populate Related Posts in Genesis based on Category // https://sridharkatakam.com/related-posts-with-thumbnails-in-genesis-reloaded/
add_action( 'genesis_after_entry', 'sk_related_posts', 12 );

function sk_related_posts() { global $do_not_duplicate;

 if ( ! is_singular ( 'post' ) ) { return; }

 $count = 0; $related = ''; $do_not_duplicate = array(); $cats = wp_get_post_categories( get_the_ID() );


 // If we have some categories and less than 5 posts, run the cat query.
 if ( $cats && $count <= 4 ) { $query = sk_related_cat_query( $cats, $count ); $related .= $query['related']; $count = $query['count'];}

 // End here if we don't have any related posts.
 if ( ! $related ) { return; }

 // Display the related posts section.
 echo '<div class="related-posts">';
 echo '<h3 class="related-title">Related Posts</h3>';
 echo '<div class="related-posts-list" data-columns>' . $related . '</div>';
 echo '</div>';

}

function sk_related_cat_query( $cats, $count ) {

 global $do_not_duplicate;

 if ( ! $cats ) {
 return;
 }

 $postIDs = array_merge( array( get_the_ID() ), $do_not_duplicate );

 $catIDs = array();

 foreach ( $cats as $cat ) {
 if ( 3 == $cat ) {
 continue;
 }
 $catIDs[] = $cat;
 }

 $showposts = 3 - $count;

 $tax_query = array(
 array(
 'taxonomy' => 'post_format',
 'field' => 'slug',
 'terms' => array( 'post-format-link', 'post-format-status', 'post-format-aside', 'post-format-quote' ),
 'operator' => 'NOT IN'
 )
 );
 $args = array(
 'category__in' => $catIDs, 'post__not_in' => $postIDs, 'showposts' => $showposts, 'ignore_sticky_posts' => 1, 'orderby' => 'rand', 'tax_query' => $tax_query,
 );

 $related = '';

 $cat_query = new WP_Query( $args );

 if ( $cat_query->have_posts() ) {
 while ( $cat_query->have_posts() ) {
 $cat_query->the_post();

 $count++;

 /*$title = genesis_truncate_phrase( get_the_title(), 35 );*/
 $title = get_the_title();

 $related .= '<div class="one-third">';
 $related .= '<a class="related-image" href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to ' . $title . '">' . genesis_get_image( array( 'size' => 'related' ) ) . '</a>';
 $related .= '<div class="one-copy">';
 $related .= '<a class="related-post-title" href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to ' . $title . '">' . $title . '</a>';
 $related .= '</div>';
 $related .= '</div>';

 }
 }

 wp_reset_postdata();

 $output = array( 'related' => $related, 'count' => $count );

 return $output;

}