(function($){

	var $isotop = $('.tlp-team-isotope').imagesLoaded( function() {
	
	  $isotop.isotope({
	        getSortData: {
	            name: '.name',
	            designation: '.designation',
	        },
	        sortAscending : true,
	        itemSelector: '.team-member',
	    });
	});

    
    $('.sort-by-button-group').on( 'click', 'button', function() {
        var sortByValue = $(this).attr('data-sort-by');
        $isotop.isotope({ sortBy: sortByValue });
        $(this).parent().find('.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    $(window).resize(  HeightResize );
    $(window).load( HeightResize );

})(jQuery);

function HeightResize(){

    jQuery(".tlp-team").each(function(){
        var tlpMaxH = 0;
        jQuery(this).children('div').children(".tlp-equal-height").height("auto");
        jQuery(this).children('div').children(".tlp-equal-height").each(function(){
            var $thisH = jQuery(this).outerHeight();
            if($thisH > tlpMaxH){
                tlpMaxH = $thisH;
            }
        });
        jQuery(this).children('div').children(".tlp-equal-height").height(tlpMaxH + "px");
    });


    var tlpMaxH = 0;
    jQuery(".tlp-team-isotope > div > .tlp-equal-height").height("auto");
    jQuery(".tlp-team-isotope > div > .tlp-equal-height").each(function(){
        var $thisH = jQuery(this).outerHeight();
        if($thisH > tlpMaxH){
            tlpMaxH = $thisH;
        }
    });
    jQuery(".tlp-team-isotope > div > .tlp-equal-height").height(tlpMaxH + "px");
}