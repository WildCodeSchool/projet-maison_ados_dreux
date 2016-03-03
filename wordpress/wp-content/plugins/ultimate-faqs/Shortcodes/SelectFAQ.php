<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Display_Select_FAQs($atts) {
	$current_url = $_SERVER['REQUEST_URI'];
	$Custom_CSS = get_option("EWD_UFAQ_Custom_CSS");
	$FAQ_Toggle = get_option("EWD_UFAQ_Toggle");
	$FAQ_Accordion = get_option("EWD_UFAQ_FAQ_Accordion");
	$Hide_Categories = get_option("EWD_UFAQ_Hide_Categories");
	$Hide_Tags = get_option("EWD_UFAQ_Hide_Tags");
	$Reveal_Effect = get_option("EWD_UFAQ_Reveal_Effect");
	$Group_By_Category = get_option("EWD_UFAQ_Group_By_Category");
	$Group_By_Order_By = get_option("EWD_UFAQ_Group_By_Order_By");
	$Group_By_Order = get_option("EWD_UFAQ_Group_By_Order");
	$Order_By_Setting = get_option("EWD_UFAQ_Order_By");
	$Order_Setting = get_option("EWD_UFAQ_Order");
	$Include_Permalink = get_option("EWD_UFAQ_Include_Permalink");
	$Permalink_Type = get_option("EWD_UFAQ_Permalink_Type");
	$Display_Style = get_option("EWD_UFAQ_Display_Style");
	$Color_Block_Shape = get_option("EWD_UFAQ_Color_Block_Shape");
	$Pretty_Permalinks = get_option("EWD_UFAQ_Pretty_Permalinks");
	$Display_All_Answers = get_option("EWD_UFAQ_Display_All_Answers");
	$Scroll_To_Top = get_option("EWD_UFAQ_Scroll_To_Top");
    $Socialmedia_String = get_option("EWD_UFAQ_Social_Media");
    $Socialmedia = explode(",", $Socialmedia_String);
    $Display_Author = get_option("EWD_UFAQ_Display_Author");
    $Display_Date = get_option("EWD_UFAQ_Display_Date");
    $Display_Back_To_Top = get_option("EWD_UFAQ_Display_Back_To_Top");

	$ReturnString = "";
	$Back_To_Top_Label = get_option("EWD_UFAQ_Back_To_Top_Label");
	if ($Back_To_Top_Label == "") {$Back_To_Top_Label = __("Back to Top", 'EWD_UFAQ');}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
									'faq_name' => "",
									'faq_slug' => "",
									'faq_id' => ""),
									$atts
		)
	);

	$name_array = explode(",", $faq_name);
	$slug_array = explode(",", $faq_slug);
	$id_array = explode(",", $faq_id);

	foreach ($name_array as $post_name) {
		$single_post = get_page_by_title($post_name, "OBJECT", "ufaq");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($slug_array as $post_slug) {
		$single_post = get_page_by_path($post_slug, "OBJECT", "ufaq");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($id_array as $post_id) {
		$post_id_array[] = $post_id;
	}

	$params = array(
					'posts_per_page' => -1,
					'post_type' => 'ufaq',
					'include' => $post_id_array
				);
	$faqs = get_posts($params);

	if ($Custom_CSS != "") {$ReturnString .= "<style>" . $Custom_CSS . "</style>";}
	$ReturnString .= EWD_UFAQ_Add_Modified_Styles();
		
	$ReturnString .= "<script language='JavaScript' type='text/javascript'>";
	if ($FAQ_Accordion == "Yes") {$ReturnString .= "var faq_accordion = true;";}
	else {$ReturnString .= "var faq_accordion = false;";}
	if ($Scroll_To_Top == "Yes") {$ReturnString .= "var faq_scroll = true;";}
		else {$ReturnString .= "var faq_scroll = false;";}
	$ReturnString .= "var reveal_effect = '" . $Reveal_Effect . "';";
	$ReturnString .= "</script>";

	$ReturnString .= "<div class='ufaq-faq-list' id='ufaq-faq-list'>";
	$Counter = 0;
	foreach ($faqs as $faq) {
		$Category_Terms = wp_get_post_terms($faq->ID, 'ufaq-category');
				$Tag_Terms = wp_get_post_terms($faq->ID, 'ufaq-tag');

				if ($Permalink_Type == "IndividualPage") {
					$FAQ_Permalink = get_permalink($faq->ID);
				}
				else {
					if ($Pretty_Permalinks == "Yes") {$FAQ_Permalink = get_the_permalink() . "single-faq/" . $faq->post_name;}
					else {$FAQ_Permalink = get_the_permalink() . "?Display_FAQ=" . $faq->ID;}
				}

				if ($Display_FAQ_ID == $faq->ID) {
					$ReturnString = str_replace("%Counter_Placeholder%", $Counter, $ReturnString);
					$Display_FAQ_ID = "";
				}

				$TitlesArray[] = json_encode($faq->post_title);
				$HeaderString .= "<div class='ufaq-faq-header-title'><a href='' class='ufaq-faq-header-link'  data-postid='" . $faq->ID . "-" . $Counter  . "'>" . $faq->post_title . "</a></div>";
		
				$ReturnString .= "<div class='ufaq-faq-div ufaq-faq-display-style-" . $Display_Style . " id='ufaq-post-" . $faq->ID . "-" . $Counter  . "' data-postid='" . $faq->ID . "-" . $Counter . "'>";	
		
				$ReturnString .= "<div class='ufaq-faq-title";
				if ($FAQ_Toggle != "No") {$ReturnString .= " ufaq-faq-toggle";}
				$ReturnString .= "' id='ufaq-title-" . $faq->ID . "' data-postid='" . $faq->ID . "-" . $Counter  . "'>";
				$ReturnString .= "<a class='ewd-ufaq-post-margin'  href='" . get_permalink($faq->ID) . "'><div class='ewd-ufaq-post-margin-symbol " . $Color_Block_Shape . "' id='ewd-ufaq-post-margin-symbol-" . $faq->ID . "-" . $Counter  . "'><span id='ewd-ufaq-post-symbol-" . $faq->ID . "-" . $Counter;
				if ($Display_All_Answers == "Yes") {$ReturnString .= "'> - </span></div>";}
				else {$ReturnString .= "'> + </span></div>";}
				$ReturnString .= "<div class='ufaq-faq-title-text'><h4>" .$faq->post_title . "</h4></div><div class='ewd-ufaq-clear'></div></a>";
				$ReturnString .= "</div>";

				if (strlen($faq->post_excerpt) > 0) {$ReturnString .= "<div class='ufaq-faq-excerpt' id='ufaq-excerpt-" . $faq->ID . "'>" . apply_filters('the_content', html_entity_decode($faq->post_excerpt)) . "</div>";}
				$ReturnString .= "<div class='ufaq-faq-body ufaq-body-" . $faq->ID;
				if ($Display_All_Answers != "Yes") {$ReturnString .= " ewd-ufaq-hidden";}
				$ReturnString .= "' id='ufaq-body-" . $faq->ID . "-" . $Counter . "'>";

				if ($Display_Author == "Yes"  or $Display_Date == "Yes") {
					$Display_Author_Value = get_post_meta($faq->ID, "EWD_UFAQ_Post_Author", true);
					$Display_Date_Value = get_the_date("", $faq->ID);
					$ReturnString .= "<div class='ewd-ufaq-author-date'>";
					$ReturnString .= $Posted_Label . " " ;
					if ($Display_Author == "Yes" and $Display_Author_Value != "") {$ReturnString .= $By_Label . " <span class='ewd-ufaq-author'>" . $Display_Author_Value . "</span> ";}
					if ($Display_Date == "Yes") {$ReturnString .= $On_Label . " <span class='ewd-ufaq-date'>" . $Display_Date_Value . "</span> ";}
					$ReturnString .= "</div>";
				}

				$ReturnString .= "<div class='ewd-ufaq-post-margin ufaq-faq-post' id='ufaq-post-" . $faq->ID . "'>" . apply_filters('the_content', html_entity_decode($faq->post_content)) . "</div>";
		
				if ($Hide_Categories == "No") {
					$ReturnString .= "<div class='ufaq-faq-categories' id='ufaq-categories-" . $faq->ID . "'>";
					if ($Category_Label == ""){
						if (sizeOf($Category_Terms) > 1) {$ReturnString .= "Categories: ";}
						else {$ReturnString .= "Category: ";}}
					else {$ReturnString .= $Category_Label . ": ";}
					foreach ($Category_Terms as $Category_Term) {
						if ($Pretty_Permalinks == "Yes") {$Category_URL = $current_url . "faq-category/" . $Category_Term->slug . "/";}
						else {$Category_URL = $current_url . "?include_category=" . $Category_Term->slug;}
						$ReturnString .= "<a  href='" . $Category_URL ."'>" .$Category_Term->name . "</a>, ";
					}
					if (sizeOf($Category_Terms) > 0) {$ReturnString = substr($ReturnString, 0, strlen($ReturnString)-2);}
					$ReturnString .= "</div>";
				}

				if ($Hide_Tags == "No") {
					$ReturnString .= "<div class='ufaq-faq-tags' id='ufaq-tags-" . $faq->ID . "'>";
					if ($Tag_Label == ""){
						if (sizeOf($Tag_Terms) > 1) {$ReturnString .= "Tags: ";}
						else {$ReturnString .= "Tag: ";}}
					else {$ReturnString .= $Tag_Label . ": ";}
					foreach ($Tag_Terms as $Tag_Term) {
						if ($Pretty_Permalinks == "Yes") {$Tag_URL = $current_url . "faq-tag/" . $Tag_Term->slug . "/";}
						else {$Tag_URL = $current_url . "?include_tag=" . $Tag_Term->slug;}
						$ReturnString .= "<a  href='" . $Tag_URL . "'>" .$Tag_Term->name . "</a>, ";
					}
					if (sizeOf($Tag_Terms) > 0) {$ReturnString = substr($ReturnString, 0, strlen($ReturnString)-2);}
					$ReturnString .= "</div>";
				}
		
				if ($Socialmedia[0] != "Blank" and $Socialmedia[0] != "") {
					$ReturnString .= "<div class='ufaq-social-links'>Share: ";
					$ReturnString .= "<ul class='rrssb-buttons'>";
				}
			    if(in_array("Facebook", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Facebook", $FAQ_Permalink, $faq->post_title);}
			    if(in_array("Google", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Google", $FAQ_Permalink, $faq->post_title);}
			    if(in_array("Twitter", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Twitter", $FAQ_Permalink, $faq->post_title);}
			    if(in_array("Linkedin", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Linkedin", $FAQ_Permalink, $faq->post_title);}
			    if(in_array("Pinterest", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Pinterest", $FAQ_Permalink, $faq->post_title);}
			    if(in_array("Email", $Socialmedia)) {$ReturnString .= EWD_UFAQ_Add_Social_Media_Buttons("Email", $FAQ_Permalink, $faq->post_title);}
			    if ($Socialmedia[0] != "Blank" and $Socialmedia[0] != "") {
			    	$ReturnString .= "</ul>";
			    	$ReturnString .= "</div>";
			    }	

			    if ($Include_Permalink == "Yes" and $ajax == "No") {
			    	$ReturnString .= "<div class='ufaq-permalink'>Permalink: ";
			    	$ReturnString .= "<a href='" . $FAQ_Permalink . "'>";
			    	$ReturnString .= "<div class='ufaq-permalink-image'></div>";
			    	$ReturnString .= "</a>";
			    	$ReturnString .= "</div>";
			    }

			    if ($Display_Back_To_Top == "Yes") {
			    	$ReturnString .= "<div class='ufaq-back-to-top'>";
			    	$ReturnString .= "<a class='ufaq-back-to-top-link'>";
			    	$ReturnString .= $Back_To_Top_Label;
			    	$ReturnString .= "</a>";
			    	$ReturnString .= "</div>";
			    }
				
				$ReturnString .= "</div>";
				$ReturnString .= "</div>";

			$Counter++;
	}
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode("select-faq", "Display_Select_FAQs");