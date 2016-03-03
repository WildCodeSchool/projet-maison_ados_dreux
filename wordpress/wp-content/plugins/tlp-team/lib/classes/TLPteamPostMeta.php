<?php
if (!class_exists('TLPteamPostMeta')):

    /**
     *
     */
    class TLPteamPostMeta
    {

        function __construct() {
            add_action('add_meta_boxes', array($this, 'team_meta_boxs'));
            add_action('save_post', array($this, 'save_team_meta_data'), 10, 3);
            add_action('admin_print_scripts-post-new.php', array($this, 'tpl_team_script'), 11);
            add_action('admin_print_scripts-post.php', array($this, 'tpl_team_script'), 11);
            add_action( 'edit_form_after_title', array($this, 'team_after_title') );
        }
        function team_after_title($post){
            global $TLPteam;
            if( $TLPteam->post_type !== $post->post_type) {
                return;
            }
            $html = null;
            $html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
            $html .= '<p style="text-align: center;"><a style="color: red; text-decoration: none; font-size: 14px;" href="https://radiustheme.com/tlp-team-pro-for-wordpress/" target="_blank">Please check the pro features</a></p>';
            $html .= '</div></div>';

            echo $html;
        }

        function team_meta_boxs() {
            add_meta_box(
                'tlp_team_meta',
                __('Member Info', TLP_TEAM_SLUG ),
                array($this,'tlp_team_meta'),
                'team',
                'normal',
                'high');
        }

        function tlp_team_meta($post){
                global $TLPteam;
                wp_nonce_field( $TLPteam->nonceText(), 'tlp_nonce' );
                $meta = get_post_meta( $post->ID );
            ?>
            <div class="member-field-holder">

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="short_bio"><?php _e('Short Bio:', TLP_TEAM_SLUG); ?></label>
                    </div>
                    <div class="tlp-field">
                        <textarea name="short_bio" rows="5" class="tlpfield" value=""><?php echo (@$meta['short_bio'][0] ? @$meta['short_bio'][0] : null) ?></textarea>
                        <span class="desc"><?php _e('Add some short bio', TLP_TEAM_SLUG); ?></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="designation"><?php _e('Designations', TLP_TEAM_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="designation" class="tlpfield" value="<?php echo (@$meta['designation'][0] ? @$meta['designation'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>


                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="email"><?php _e('Email', TLP_TEAM_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="email" name="email" class="tlpfield" value="<?php echo (@$meta['email'][0] ? @$meta['email'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="web_url"><?php _e('Personal Web URL', TLP_TEAM_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="url" name="web_url" class="tlpfield" value="<?php echo (@$meta['web_url'][0] ? @$meta['web_url'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="url"><?php _e('Telephone', TLP_TEAM_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="telephone" class="tlpfield" value="<?php echo (@$meta['telephone'][0] ? @$meta['telephone'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="location"><?php _e('Location', TLP_TEAM_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="location" class="tlpfield" value="<?php echo (@$meta['location'][0] ? @$meta['location'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>
            <h2 class="hndle ui-sortable-handle"><?php _e('Social Links', TLP_TEAM_SLUG); ?></h2>
            <?php
            $s = unserialize(get_post_meta( $post->ID, 'social' , true));
                foreach($TLPteam->socialLink() as $id => $label){
                ?>
                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="location"><?php echo $label; ?></label>
                    </div>
                    <div class="tlp-field">
                        <input type="url" name="social[<?php echo $id; ?>]" class="tlpfield" value="<?php echo (@$s[$id] ? @$s[$id] : null) ?>">
                    </div>
                </div>
            <?php } ?>

            </div>
            <?php
        }
        function save_team_meta_data($post_id, $post, $update) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

            global $TLPteam;

            if ( !wp_verify_nonce( @$_REQUEST['tlp_nonce'], $TLPteam->nonceText() ) )return;

            // Check permissions
            if ( $_GET['post_type'] )
            {
                if ( !current_user_can( 'edit_'.$_GET['post_type'], $post_id ) ) return;
            }

            if ( 'team' != $post->post_type ) return;

            if ( isset( $_REQUEST['short_bio'] ) ) {
                update_post_meta( $post_id, 'short_bio', sanitize_text_field( $_REQUEST['short_bio'] ) );
            }

            if ( isset( $_REQUEST['email'] ) ) {
                update_post_meta( $post_id, 'email', sanitize_text_field( $_REQUEST['email'] ) );
            }


            if ( isset( $_REQUEST['designation'] ) ) {
                update_post_meta( $post_id, 'designation', sanitize_text_field( $_REQUEST['designation'] ) );
            }

            if ( isset( $_REQUEST['web_url'] ) ) {
                update_post_meta( $post_id, 'web_url', sanitize_text_field( $_REQUEST['web_url'] ) );
            }

            if ( isset( $_REQUEST['telephone'] ) ) {
                update_post_meta( $post_id, 'telephone', sanitize_text_field( $_REQUEST['telephone'] ) );
            }

            if ( isset( $_REQUEST['location'] ) ) {
                update_post_meta( $post_id, 'location', sanitize_text_field( $_REQUEST['location'] ) );
            }

            if( isset($_REQUEST['social'])){
                $s = array_filter($_REQUEST['social']);
                update_post_meta( $post_id, 'social', serialize($s) );
            }

        }

        function tpl_team_script() {
            global $post_type,$TLPteam;
            if($post_type == $TLPteam->post_type){
                $TLPteam->tlp_style();
                $TLPteam->tlp_script();
            }
        }
    }
endif;
