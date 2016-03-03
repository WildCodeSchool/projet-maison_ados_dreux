<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
function get_wp_tsas_showcase_slider( $atts, $content = null ){
    // setup the query
            extract(shortcode_atts(array(
		"limit" => '',	
		"category" => '',		
		"design" => '',
		"slides_column" => '',
		"slides_scroll" => '',
		"dots" => '',
		"arrows" => '',
		"autoplay" => '',
		"autoplay_interval" => '',
		"speed" => '',
		"popup" => '',
	), $atts));
	
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}
	
	if( $design ) { 
		$teamdesign = $design; 
	} else {
		$teamdesign = 'design-1';
	}	
	
	if( $slides_column ) { 
		$slidesColumn = $slides_column; 
	} else {
		$slidesColumn = '3';
	}	
	
	if( $slides_scroll ) { 
		$slidesScroll = $slides_scroll; 
	} else {
		$slidesScroll = '1';
	}	
	if( $autoplay ) { 
		$slidesautoplay = $autoplay; 
	} else {
		$slidesautoplay = 'true';
	}	
	if( $dots ) { 
		$sliderdots = $dots; 
	} else {
		$sliderdots = 'true';
	}	
	if( $arrows ) { 
		$sliderarrows = $arrows; 
	} else {
		$sliderarrows = 'true';
	}	
	if( $autoplay_interval ) { 
		$autoplayInterval = $autoplay_interval; 
	} else {
		$autoplayInterval = '3000';
	}
	if( $speed ) { 
		$sliderspeed = $speed; 
	} else {
		$sliderspeed = '500';
	}	

if( $popup ) { 
		$teampopup = $popup; 
	} else {
		$teampopup = 'true';
	}		
	
	
	ob_start();
	
	$unique			= wp_tsas_get_unique();
	$post_type 		= 'team_showcase_post';
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				 
        $args = array ( 
            'post_type'      => $post_type, 
            'orderby'        => $orderby, 
            'order'          => $order,
            'posts_per_page' => $posts_per_page,  
          
            );
	if($cat != ""){
            	$args['tax_query'] = array( array( 'taxonomy' => 'tsas-category', 'field' => 'term_id', 'terms' => $cat) );
            }        
      $query = new WP_Query($args);
	  global $post;
      $post_count = $query->post_count;
          $count = 0;		 
			$i = 1;
	if ( $query->have_posts() ) {		?> 
		  
		  <div class="wp-tsas-slider-<?php echo $unique; ?> wp_teamshowcase_slider <?php echo $teamdesign; ?>">
		   
		  
			<?php  			 
		  
            while ( $query->have_posts() ) : $query->the_post();
             $count++;
              
                $css_class="team-slider";               
				$class = '';		
				
				switch ($teamdesign) {
				 case "design-1":
					include('designs/design-1.php');
					break;
				 case "design-2":
					include('designs/design-2.php');
					break;	
				 default:					 
						include('designs/design-1.php');
					}		
				
						   
			
			$i++;
            endwhile; 
			 ?>
			</div>
			
		<?php
		if($teampopup == "true") {
			$j = 1;
	while ($query->have_posts()) : $query->the_post();
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$member_designation = get_post_meta($post->ID, '_member_designation', true);
		$member_department = get_post_meta($post->ID, '_member_department', true); 
		$skills = get_post_meta($post->ID, '_skills', true);
		$member_experience = get_post_meta($post->ID, '_member_experience', true); 
		$facebook_link = get_post_meta($post->ID, '_facebook_link', true);
		$google_link = get_post_meta($post->ID, '_google_link', true); 
		$likdin_link = get_post_meta($post->ID, '_likdin_link', true);
		$twitter_link = get_post_meta($post->ID, '_twitter_link', true); 
	?>	
	
	<div id="popup<?php echo $j; ?>" class="wp-modal-box">
			  <header> 			 
				<div class="wp-modal-header ng-scope" style="background:url(<?php echo $feat_image ?>) center top no-repeat;">
					 <a href="javascript:void(0)" class="wp-modal-close close">X</a>
						<div class="member-popup-info">
						<div class="member-name"><?php the_title(); ?></div>	
						
						<?php if($member_designation != '' || $member_department!= ''){ ?>
							<div class="member-job"> 
								<?php echo ($member_designation != '' ? $member_designation : '');
								echo ($member_designation != '' && $member_department != '' ? ' - ' : '');
								echo ($member_department != '' ? $member_department : ''); ?>
							</div>
							<?php } ?>
						</div>
						</div>
			  </header>
			  <div class="wp-modal-body">
			  <?php if($skills != '' || $member_experience != '') { ?>	
					<div class="other-info">
					<?php echo ($skills != '' ? $skills : '');
						  echo ($skills != '' && $member_experience != '' ? ' - ' : '');
						  echo ($member_experience != '' ? $member_experience : ''); ?>
					</div>		
				<?php } 
				if($facebook_link != '' || $likdin_link != '' || $twitter_link != '' || $google_link != '') { ?>
					<div class="contact-content">
					<?php if($facebook_link != '') { ?><a href="<?php echo $facebook_link; ?>" target="_blank"><i class="fa fa-facebook"></i></a> <?php }						
							if($likdin_link != '') { ?><a target="_blank" href="<?php echo $likdin_link; ?>"><i class="fa fa-linkedin"></i></a> <?php } 
							if($twitter_link != '') {?><a target="_blank" href="<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a> <?php }
							if($google_link != '') { ?><a target="_blank" href="<?php echo $google_link; ?>"><i class="fa fa-google-plus"></i></a> <?php } ?>
					</div>	
					<?php } 
					the_content(); ?>
				
			  </div>
			  <div class="text-center "> <a href="javascript:void(0)" class="link wp-modal-close">-- Done --</a> </div>
			 
		</div>
		<?php 
		$j++;
		endwhile;
	} } ?>
			
			 
			 <script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery('.wp-tsas-slider-<?php echo $unique; ?>').slick({
			dots: <?php echo $sliderdots; ?>,
			infinite: true,
			arrows: <?php echo $sliderarrows; ?>,
			speed: <?php echo $sliderspeed; ?>,
			autoplay: <?php echo $slidesautoplay; ?>,						
			autoplaySpeed: <?php echo $autoplayInterval; ?>,
			slidesToShow: <?php echo $slidesColumn; ?>,
			slidesToScroll: <?php echo $slidesScroll; ?>,
			responsive: [
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 641,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 481,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
		});
	});
	</script>
             <?php  
			  wp_reset_query(); 
             return ob_get_clean();
	}

add_shortcode( 'wp-team-slider', 'get_wp_tsas_showcase_slider' );
?>