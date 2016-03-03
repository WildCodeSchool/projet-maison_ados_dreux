<?php

if(!class_exists('TLPwidget')):


    /**
    *
    */
    class TLPwidget extends WP_Widget
    {

        /**
         * TLP TEAM widget setup
         */
        function TLPwidget() {

            $widget_ops = array( 'classname' => 'widget_tlpTeam', 'description' => __('Display the Team.', TLP_TEAM_SLUG) );
            parent::__construct( 'widget_tlpTeam', __('TPL Team', TLP_TEAM_SLUG), $widget_ops);

        }

        /**
         * display the widgets on the screen.
         */
        function widget( $args, $instance ) {
            extract( $args );
            global $TLPteam;

            @$title = ($instance['title'] ? $instance['title'] : __('TPL Team', TLP_TEAM_SLUG));
            @$member = ($instance['member'] ? (int)$instance['member'] : 2);
            @$layout = ($instance['layout'] ? (int)$instance['layout'] : 'grid');

            echo $before_widget;
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            }
                    $args = array(
                        'post_type' => 'team',
                        'post_status'=> 'publish',
                        'posts_per_page' => $member,
                        'orderby' => 'date',
                        'order'   => 'DESC',
                    );
                    $teamQuery = new WP_Query( $args );
                    $html = null;
                    $html .= '<div class="container-fluid tlp-team">';
                    $html .= "<div class='row'>";
                    $html .= "<div class='layout1'>";
                    if ( $teamQuery->have_posts() ) {
                            while ($teamQuery->have_posts()) : $teamQuery->the_post();

                                  if (has_post_thumbnail()){
                                      $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $TLPteam->options['feature_img_size'] );
                                      $img = $image[0];
                                  }else{
                                    $img = $TLPteam->assetsUrl .'images/demo.jpg';
                                  }
                              $bio = get_post_meta( get_the_ID(), 'short_bio', true );
                              $designation = get_post_meta( get_the_ID(), 'designation', true );
                              $tel = get_post_meta( get_the_ID(), 'telephone', true );
                              $loc = get_post_meta( get_the_ID(), 'location', true );
                              $email = get_post_meta( get_the_ID(), 'email', true );
                              $url = get_post_meta( get_the_ID(), 'web_url', true );
                              $grid=12/$member;
                                $html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";
                                $html .= '<div class="single-team-area">';
                                $html .="<div class='tlp-thum'><img src='$img' /></div>                                                                           
                                        <div class='tlp-content'>
                                        <h3><a href='".get_the_permalink()."'>".get_the_title()."</a></h3>";

                                $html .= '<div class="short-bio">';
                                    if($designation){
                                        $html .="<div class='designation'>$designation</div>";
                                    }
                                $html .= "</div>";
                                $html .= "</div>";

                            $html .="</div>";
                            $html .="</div>";
                        
                            endwhile;

                        wp_reset_postdata();
                    }else{
                        $html .= "<p>".__('No member found',TLP_TEAM_SLUG)."</p>";
                    }
                    $html .= "</div>";
                    $html .= "</div>";
                    $html .= "</div>";
                    echo $html;

            echo $after_widget;
        }
        function form( $instance ) {

            $defaults = array(
                'title' => '',
                'member' => 4
            );

            $instance = wp_parse_args( (array) $instance, $defaults ); 

            ?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', TLP_TEAM_SLUG); ?></label>
                <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>

            <p><label for="<?php echo $this->get_field_id( 'member' ); ?>"><?php _e('Number of member to show:', TLP_TEAM_SLUG); ?></label>
                <input type="text" size="2" id="<?php echo $this->get_field_id('member'); ?>" name="<?php echo $this->get_field_name('member'); ?>" value="<?php echo $instance['member']; ?>" /></p>

            
            <?php
        }
        public function update( $new_instance, $old_instance ) {

            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['member'] = ( ! empty( $new_instance['member'] ) ) ? (int)( $new_instance['member'] ) : '';
            $instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? (int)( $new_instance['layout'] ) : '';

            return $instance;
        }


    }


endif;