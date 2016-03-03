<?php

if(!class_exists('TPLshortCode')):

	/**
	*
	*/
	class TPLshortCode
	{

		function __construct()
		{
			add_shortcode( 'tlpteam', array( $this, 'team_shortcode' ) );

		}

		function team_shortcode($atts , $content = ""){

			$col_A = array(1,2,3,4,6);

			global $TLPteam;
			$atts = shortcode_atts( array(
					'member' => 4,
					'col' => 3,
					'orderby' => 'date',
					'order'	=> 'DESC',
					'layout'	=> 1
				), $atts, 'tlpteam' );

			if(!in_array($atts['col'], $col_A)){
				$atts['col'] = 3;
			}
			if(!in_array($atts['layout'], array(1,2,3,'isotope'))){
				$atts['layout'] = 1;
			}

			$html = null;

			$args = array(
					'post_type' => 'team',
					'post_status'=> 'publish',
					'posts_per_page' => $atts['member'],
					'orderby' => $atts['orderby'],
					'order'   => $atts['order']
				);


			$teamQuery = new WP_Query( $args );

			   if ( $teamQuery->have_posts() ) {
			   		$html .= '<div class="container-fluid tlp-team">';
				   if($atts['layout'] == 'isotope') {
					   $html .= '<div class="button-group sort-by-button-group">
									<button data-sort-by="original-order" class="selected">Default</button>
									<button data-sort-by="name">Name</button>
									  <button data-sort-by="designation">Designation</button>
								  </div>';
					   $html .= '<div class="tlp-team-isotope">';
				   }
				   if($atts['layout'] != 'isotope') {
					   $html .= '<div class="row layout' . $atts['layout'] . '">';
				   }
				    while ($teamQuery->have_posts()) : $teamQuery->the_post();
			
				      		$title = get_the_title();
				      		$pLink = get_permalink();
				      		$short_bio = get_post_meta( get_the_ID(), 'short_bio', true );
				      		$designation = get_post_meta( get_the_ID(), 'designation', true );

				      		if (has_post_thumbnail()){
				      			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $TLPteam->options['feature_img_size']);
				      			$imgSrc = $image[0];
				      		}else{
								$imgSrc = $TLPteam->assetsUrl .'images/demo.jpg';
				      		}
                            $grid=12/$atts['col'];

                            if($atts['col']==2){
						          $image_area="tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-6 tlp-col-xs-12 ";
						          $content_area="tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-6 tlp-col-xs-12 ";
						      }else{
						          $image_area="tlp-col-lg-3 tlp-col-md-3 tlp-col-sm-6 tlp-col-xs-12 ";
						          $content_area="tlp-col-lg-9 tlp-col-md-9 tlp-col-sm-6 tlp-col-xs-12 ";
						      }

				      		$sLink = unserialize(get_post_meta( get_the_ID(), 'social' , true));

							if($atts['layout'] != 'isotope') {
								$html .= "<div class='tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height'>";
							}
								switch ($atts['layout']) {
									case 1:
										$html .= $this->layoutOne($title, $pLink, $imgSrc, $designation, $short_bio, $sLink);
									break;

									case 2:
										$html .= $this->layoutTwo($title, $pLink, $imgSrc, $designation, $short_bio, $sLink,$image_area,$content_area);
									break;

									case 3:
										$html .= $this->layoutThree($title, $pLink, $imgSrc, $designation, $short_bio, $sLink,$image_area,$content_area);
									break;

									case 'isotope':
										$html .= $this->layoutIsotope($title, $pLink, $imgSrc, $designation,$grid);
									break;

									default:
										# code...
									break;
								}
							if($atts['layout'] != 'isotope') {
								$html .= '</div>'; //end col
								
							}
							
				      endwhile;
				   if($atts['layout'] != 'isotope') {
					   $html .= '</div>'; // End row
				   }
				       wp_reset_postdata();
				     // end row
				   if($atts['layout'] == 'isotope') {
					   $html .= '</div>'; // end tlp-team-isotope
				   }
				   $html .= '</div>'; // end container
			   }else{
			   	$html .= "<p>".__('No member found',TLP_TEAM_SLUG)."</p>";
			   }
			return $html;
		}

		function layoutOne($title, $pLink, $imgSrc, $designation, $short_bio, $sLink){
			global $TLPteam;
			$settings = get_option($TLPteam->options['settings']);
			$html = null;
			$html .= '<div class="single-team-area">';
			  	$html .='<img class="img-responsive" src="'.$imgSrc.'" alt="'.$title.'"/>';
			  		$html .= '<div class="tlp-content">';
					if($settings['link_detail_page'] == 'no') {
						$html .= '<h3 class="name">'. $title . '</h3>';
					}else{
					$html .= '<h3 class="name"><a title="' . $title . '" href="' . $pLink . '">' . $title . '</a></h3>';
					}
					if($designation){
						$html .= '<div class="designation">'.$designation.'</div>';
					}
	                $html .= '</div>';
	                 	$html .= '<div class="short-bio">';
	    				if($short_bio){
	    					$html .= '<p>'.$short_bio.'</p>';
	    				}
				$html .= '</div>';
        			$html .= '<div class="tpl-social">';
        			if($sLink){
        				foreach ($sLink as $id => $link) {
        						$html .= "<a href='{$sLink[$id]}' title='$id' target='_blank'><i class='fa fa-$id'></i></a>";
        				}
        			}
        		$html .= '</div>';
        	$html .= '</div>';
			return $html;
		}
		function layoutTwo($title, $pLink, $imgSrc, $designation, $short_bio, $sLink,$image_area,$content_area){
			global $TLPteam;
			$settings = get_option($TLPteam->options['settings']);
			$html = null;
				$html .='<div class="single-team-area">';
					$html .='<div class="'. $image_area.'">';
						$html .='<img class="img-responsive" src="'.$imgSrc.'" alt="'.$title.'"/>';
					$html .= '</div>';

					$html .='<div class="'. $content_area.'">';
						if($settings['link_detail_page'] == 'no') {
							$html .= '<h3 class="tlp-title">'.$title.'</h3>';
						}else{
                            $html .= '<h3 class="tlp-title"><a title="'.$title.'" href="'.$pLink.'">'.$title.'</a></h3>';
						}
						$html .='<div class="designation">'.$designation.'</div>';
						$html .='<div class="short-bio">
							    	<p>'.$short_bio.'</p>
							    </div>';
						$html .= '<div class="tpl-social">';
								if ($sLink) {
									foreach ($sLink as $id => $link) {
										$html .= "<a href='{$sLink[$id]}' title='$id' target='_blank'><i class='fa fa-$id'></i></a>";
									}
								}
								$html .= '</div>';

					$html .= '</div>';
				$html .= '</div>';
			return $html;
		}
		function layoutThree($title, $pLink, $imgSrc, $designation, $short_bio, $sLink,$image_area,$content_area){
			global $TLPteam;
			$settings = get_option($TLPteam->options['settings']);
			$html = null;
			$html .='<div class="single-team-area">';
				$html .='<div class="'. $image_area.' round-img">';
					$html .='<img class="img-responsive" src="'.$imgSrc.'" alt="'.$title.'"/>';
				$html .= '</div>';

				$html .='<div class="'. $content_area.'">';
					if($settings['link_detail_page'] == 'no') {
						$html .= '<h3 class="tlp-title">'.$title.'</h3>';
					}else{
                        $html .= '<h3 class="tlp-title"><a title="'.$title.'" href="'.$pLink.'">'.$title.'</a></h3>';
					}
					$html .='<div class="designation">'.$designation.'</div>';
					$html .='<div class="short-bio">
						    	<p>'.$short_bio.'</p>
						    </div>';
					$html .= '<div class="tpl-social">';
							if ($sLink) {
								foreach ($sLink as $id => $link) {
									$html .= "<a href='{$sLink[$id]}' title='$id' target='_blank'><i class='fa fa-$id'></i></a>";
								}
							}
							$html .= '</div>';

				$html .= '</div>';
			$html .= '</div>';
			return $html;
		}

		function layoutIsotope($title, $pLink, $imgSrc, $designation,$grid){
			global $TLPteam;
			$settings = get_option($TLPteam->options['settings']);
			$html = null;
			$html .= "<div class='team-member tlp-col-lg-{$grid} tlp-col-md-{$grid} tlp-col-sm-6 tlp-col-xs-12 tlp-equal-height '>";
				$html .= '<div class="single-team-area">';
			  		$html .='<img class="img-responsive" src="'.$imgSrc.'" alt="'.$title.'"/>';
			  		$html .= '<div class="tlp-content">';
						if($settings['link_detail_page'] == 'no') {
							$html .= '<h3 class="name">'. $title . '</h3>';
						}else{
							$html .= '<h3 class="name"><a title="' . $title . '" href="' . $pLink . '">' . $title . '</a></h3>';
						}
						if($designation){
							$html .= '<div class="designation">'.$designation.'</div>';
						}
	                $html .= '</div>';
				$html .= '</div>';
        	$html .= '</div>';
			return $html;
		}


	}

endif;
