<?php
/**
 * Custom template part for content archives in masonry layout.
 */

// Forces full width content.
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_filter( 'body_class', 'sk_mansory_body_class' );
/**
 * Adds custom body class to the head.
 *
 * @param array $classes Existing body classes.
 *
 * @return array Modified body classes.
 */
function sk_mansory_body_class( $classes ) {

    $classes[] = 'masonry-page';

    return $classes;

}

// Repositions breadcrumbs.
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

// Repositions custom headline and / or description.
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_before_content', 'genesis_do_taxonomy_title_description', 15 );
remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
add_action( 'genesis_before_content', 'genesis_do_author_title_description', 15 );
remove_action( 'genesis_before_loop', 'genesis_do_author_box_archive', 15 );
add_action( 'genesis_before_content', 'genesis_do_author_box_archive', 15 );
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
add_action( 'genesis_before_content', 'genesis_do_cpt_archive_title_description' );
remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );
add_action( 'genesis_before_content', 'genesis_do_date_archive_title' );
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );
add_action( 'genesis_before_content', 'genesis_do_posts_page_heading' );

/**
 * Removes all actions from entry hooks.
 */

$hooks = array(
    'genesis_entry_header',
    'genesis_entry_content',
    'genesis_entry_footer',
);

foreach ( $hooks as $hook ) {
    remove_all_actions( $hook );
}

add_action( 'genesis_loop', 'sk_add_masonry_grid_sizer', 7 );
/**
 * Adds grid sizer and gutter sizer divs for responsive masonry.
 */
function sk_add_masonry_grid_sizer() {

    echo '<div class="grid-sizer"></div><div class="gutter-sizer"></div>';

}

/**
 * Entry Header (Image).
 */
add_action( 'genesis_entry_header', 'genesis_entry_header_markup_open' );
add_action( 'genesis_entry_header', 'sk_masonry_block_post_image' );
/**
 * Outputs featured image with a fallback.
 */
function sk_masonry_block_post_image() {
    if ( has_post_thumbnail() ) {
        $img = genesis_get_image( array(
            'format' => 'url',
            'size' => 'masonry-image',
        ));
    } else {
        $img = '//lorempixel.com/500/375/';
    }

    printf( '<a href="%s" title="%s"><img src="%s" class="masonry-image" /></a>', esc_url( get_permalink() ), the_title_attribute( 'echo=0' ), $img );
}
add_action( 'genesis_entry_header', 'genesis_entry_header_markup_close' );

/**
 * Entry Content (Title and Content).
 */
add_action( 'genesis_entry_content', 'genesis_do_post_title' );
add_action( 'genesis_entry_content', 'genesis_do_post_content' );

/**
 * Entry Footer (Post Info and Entry Meta).
 */
add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open' );
add_action( 'genesis_entry_footer', 'genesis_post_info' );
add_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close' );

add_filter( 'genesis_post_info', 'sp_post_info_filter' );
/**
 * Customizes post info.
 *
 * @return string Modified post info.
 */
function sp_post_info_filter() {
    $today = date("d/m/Y"); 
    return $today;

}

add_filter( 'excerpt_length', 'sk_excerpt_length' );
/**
 * Modifies the length of post excerpts.
 *
 * @return int Number of desired words in post excerpts (default: 55).
 */
function sk_excerpt_length() {

    return 0; // pull first 20 words.

}

add_filter( 'excerpt_more', 'sk_excerpt_more' );
/**
 * Replace the normal "[...]" in excerpts with an empty string.
 *
 * @return string empty string.
 */
function sk_excerpt_more() {

    return ' <a class="button more-link" href="' . get_permalink() . '">Continue Reading</a>';

}

/**
 * Repositions Archive Pagination.
 * Moves .archive-pagination from under main.content to adjacent to it.
 */
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
add_action( 'genesis_after_content', 'genesis_posts_nav' );

add_action( 'wp_enqueue_scripts', 'sk_enqueue_masonry' );
/**
 * Loads and initialize Masonry.
 */
function sk_enqueue_masonry() {

    wp_enqueue_script(
        'masonry-init',
        get_stylesheet_directory_uri() . '/js/masonry-init.js',
        array( 'jquery', 'masonry' ),
        '1.0',
        true
    );

}