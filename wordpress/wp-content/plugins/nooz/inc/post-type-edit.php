<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function mdnooz_add_post_type_priority_option() {
    global $post;
    if ( in_array( $post->post_type, mdnooz()->get_post_types() ) ) {
        $post_type_object = get_post_type_object( $post->post_type );
        $can_publish = current_user_can( $post_type_object->cap->publish_posts );
        $option_icon = version_compare( get_bloginfo( 'version' ), '3.8', '>=') ? 'dashicons-randomize' : '';
        $option_icon = version_compare( get_bloginfo( 'version' ), '4.3', '>=') ? 'dashicons-sticky' : $option_icon ;
        ?><div id="md-post-priority" class="misc-pub-section md-misc-pub-section<?php echo ' ' . $option_icon; ?>">
            <span class="md-misc-pub-section-name"><?php _e( 'Priority:', 'mdnooz' ); ?></span>
            <span class="md-misc-pub-section-display">
            <?php
                $priority = get_post_meta( $post->ID, '_mdnooz_post_priority', true );
                $priority = $priority ?: 'auto';
                $priority_text = ( 'pinned' === $priority ) ? __( 'Pinned', 'mdnooz' ) : __( 'Auto', 'mdnooz' ) ;
            ?>
            <?php echo esc_html( $priority_text ); echo $post->menu_order ? ' #' . $post->menu_order : null ; ?></span>
            <?php if ( $can_publish ) { ?>
                <a href="#md-post-priority" class="md-misc-pub-section-edit hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit', 'mdnooz' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit order', 'mdnooz' ); ?></span></a>
                <div class="md-misc-pub-section-content hide-if-js">
                    <?php wp_nonce_field( 'mdnooz_post_priority', 'mdnooz_post_priority_nonce' ); ?>
                    <select id="mdnooz_post_priority" class="md-misc-pub-section-input" name="mdnooz_post_priority">
                        <option<?php selected( $priority, 'auto' ); ?> value="auto"><?php _e( 'Auto', 'mdnooz' ); ?></option>
                        <option<?php selected( $priority, 'pinned' ); ?> value="pinned"><?php _e( 'Pinned', 'mdnooz' ); ?></option>
                    </select>
                    <input id="menu_order" class="md-misc-pub-section-input"<?php echo 'auto' == $priority ? ' style="display:none;"' : '' ; ?> name="menu_order" value="<?php echo $post->menu_order; ?>" type="number" min="0">
                    <p>
                        <a href="#md-post-priority" class="md-misc-pub-section-save hide-if-no-js button"><?php _e( 'OK', 'mdnooz' ); ?></a>
                        <a href="#md-post-priority" class="md-misc-pub-section-cancel hide-if-no-js button-cancel"><?php _e( 'Cancel', 'mdnooz' ); ?></a>
                    </p>
                </div>
            <?php } ?>
        </div><?php
    }
}
// see /wp-admin/includes/meta-boxes.php
add_action( 'post_submitbox_misc_actions', 'mdnooz_add_post_type_priority_option' );

function mdnooz_save_post_type_priority_option( $post_id ) {
    if ( ! isset( $_POST['post_type'] ) ) {
        return $post_id;
    }
    if ( ! in_array( $_POST['post_type'], mdnooz()->get_post_types() ) ) {
        return $post_id;
    }
    if ( ! wp_verify_nonce( $_POST['mdnooz_post_priority_nonce'], 'mdnooz_post_priority' ) ) {
        return $post_id;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    if ( ! isset( $_POST['mdnooz_post_priority'] ) ) {
        return $post_id;
    }
    update_post_meta( $post_id, '_mdnooz_post_priority', trim( $_POST['mdnooz_post_priority'] ), get_post_meta( $post_id, '_mdnooz_post_priority', true ) );
}
// wp2.0
add_action( 'save_post', 'mdnooz_save_post_type_priority_option' );

function mdnooz_post_priority_state( $post_states, $post ) {
    if ( in_array( $post->post_type, mdnooz()->get_post_types() ) ) {
        $post_priority = get_post_meta( $post->ID, '_mdnooz_post_priority', true );
        if ( 'pinned' === $post_priority ) {
            $post_states['pinned'] = __( 'Pinned', 'mdnooz' );
            if ( $post->menu_order > 0 ) {
                $post_states['pinned'] .= ' #' . $post->menu_order;
            }
        }
    }
    return $post_states;
}
// wp2.8
add_filter( 'display_post_states', 'mdnooz_post_priority_state', 10, 2 );
