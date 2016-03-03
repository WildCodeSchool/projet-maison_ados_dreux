jQuery(function(){

var appendthis =  ("<div class='wp-modal-overlay wp-modal-close'></div>");

	jQuery('a[tsas-data-modal-id]').click(function(e) {
		e.preventDefault();
    jQuery("body").append(appendthis);
    jQuery(".wp-modal-overlay").fadeTo(500, 0.7);
   
		var modalBox = jQuery(this).attr('tsas-data-modal-id');
		jQuery('#'+modalBox).fadeIn(jQuery(this).data());
	});  
  
  
jQuery(".wp-modal-close, .wp-modal-overlay").click(function() {
    jQuery(".wp-modal-box, .wp-modal-overlay").fadeOut(500, function() {
        jQuery(".wp-modal-overlay").remove();
    });
 
});
 
jQuery(window).resize(function() {
    jQuery(".wp-modal-box").css({        
        left: (jQuery(window).width() - jQuery(".wp-modal-box").outerWidth()) / 2
    });
});
 
jQuery(window).resize();
 
});