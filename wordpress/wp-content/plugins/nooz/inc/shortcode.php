<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function mdnooz_shortcode_pre_query() {
    add_filter( 'posts_join', 'mdnooz_shortcode_posts_join' );
    add_filter( 'posts_groupby', 'mdnooz_shortcode_posts_groupby' );
    add_filter( 'posts_orderby', 'mdnooz_shortcode_posts_orderby' );
}
// @since 0.8
add_action( 'nooz_shortcode_pre_query', 'mdnooz_shortcode_pre_query' );

function mdnooz_shortcode_post_query() {
    remove_filter( 'posts_join', 'mdnooz_shortcode_posts_join' );
    remove_filter( 'posts_groupby', 'mdnooz_shortcode_posts_groupby' );
    remove_filter( 'posts_orderby', 'mdnooz_shortcode_posts_orderby' );
}
// @since 0.8
add_action( 'nooz_shortcode_post_query', 'mdnooz_shortcode_post_query' );

function mdnooz_shortcode_posts_join( $sql = '' ) {
    global $wpdb;
    return $sql . " LEFT JOIN {$wpdb->postmeta} AS mdnooz_postmeta ON {$wpdb->posts}.ID = mdnooz_postmeta.post_id AND mdnooz_postmeta.meta_key = '_mdnooz_post_priority'";
}

function mdnooz_shortcode_posts_groupby( $sql = '' ) {
    global $wpdb;
    return $sql . "{$wpdb->posts}.ID";
}

function mdnooz_shortcode_posts_orderby( $sql = '' ) {
    return "mdnooz_postmeta.meta_value DESC, " . $sql;
}
