// contextual help
hljs.initHighlightingOnLoad();
jQuery(function($){
    var qs = {};
    $.each(location.search.substr(1).split('&'), function(i,v){
        var p = v.split('=');
        qs[p[0]] = p[1];
    });
    if (qs.tab) {
        $('.contextual-help-tabs li').removeClass('active');
        $('#tab-link-' + qs.tab).addClass('active');
    }
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });
});
// make visual adjustment to subheadline metabox
jQuery(function($){
    $('#_nooz_release_metabox').removeClass('postbox');

    $('[data-md-dependency!=""][data-md-dependency]').each(function(i, el) {
        var dep = $(el).data('mdDependency');
        var selector = '#' + dep;
        var value = $(el).data('mdValue').split(',');
        var dep_el = $('#' + dep + ', input[type="checkbox"][name="'+ dep +'"]');
        if (dep_el.is(':checkbox')) {
            if (dep_el.is(':checked')) {
                $(el).show();
            }
            dep_el.on('change', function(e){
                if (dep_el.is(':checked')) {
                    $(el).show('slow');
                } else {
                    $(el).hide('slow');
                }
            });
        }
    });
    // media selection component
    $('.md-media-select').on('click', function(e){
        e.preventDefault();
        var mediaControls = $(this).closest('.md-media-controls');
        var mediaFrame = mediaControls.data('mediaFrame');
        if (mediaFrame) {
            mediaFrame.open();
            return;
        }
        var selectButton = $('.md-media-select', mediaControls);
        var removeButton = $('.md-media-remove', mediaControls);
        var mediaInput = $('.md-media-input', mediaControls);
        var mediaThumb = $('.md-media-thumb', mediaControls);
        var mediaTitle = mediaControls.data('mediaTitle');
        var mediaButton = mediaControls.data('mediaButton');
        mediaFrame = wp.media({
            title: mediaTitle,
            button: {text: mediaButton},
            multiple: false
        });
        mediaFrame.on('select', function(){
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            console.log('selection', attachment);
            mediaInput.val(attachment.id);
            mediaThumb.empty().append('<img src="'+ attachment.url+'">').show();
            selectButton.hide();
            removeButton.show();
        });
        mediaControls.data('mediaFrame', mediaFrame);
        mediaFrame.open();
    });
    $('.md-media-remove').on('click', function(e){
        e.preventDefault();
        var mediaControls = $(this).closest('.md-media-controls');
        var selectButton = $('.md-media-select', mediaControls);
        var removeButton = $('.md-media-remove', mediaControls);
        var mediaInput = $('.md-media-input', mediaControls);
        var mediaThumb = $('.md-media-thumb', mediaControls);
        mediaInput.val('');
        mediaThumb.empty().hide();
        removeButton.hide();
        selectButton.show();
    });
    // press release/coverage post publish option
    $('.md-misc-pub-section').each(function(){
        var section_el = $(this);
        var edit_el = $('.md-misc-pub-section-edit', section_el);
        var cancel_el = $('.md-misc-pub-section-cancel', section_el);
        var ok_el = $('.md-misc-pub-section-save', section_el);
        var content_el = $('.md-misc-pub-section-content', section_el);
        var display_el = $('.md-misc-pub-section-display', section_el);
        var input_els = $('.md-misc-pub-section-input', section_el);
        // remember current/old values
        input_els.each(function(){
            var el = $(this);
            // initial hidden state MUST BE set in the markup
            // specifically looking for display:none;, is(':hidden') is too broad
            el.data({
                'oldValue': el.val(),
                'isHidden': 'none' === el.css('display')
            });
        });
        // edit link
        edit_el.click(function(e){
            e.preventDefault();
            if (content_el.is(':hidden')) {
                content_el.slideDown('fast');
            }
            edit_el.hide();
        });
        // cancel/ok links
        cancel_el.add(ok_el).click(function(e){
            e.preventDefault();
            content_el.slideUp('fast');
            if (cancel_el.get(0) == this) {
                // reset values
                input_els.each(function(){
                    var el = $(this);
                    el.val(el.data('oldValue'));
                    el.data('isHidden') ? el.hide() : el.show();
                });
            }
            edit_el.show().focus();
        });
        // specific to post priority option
        if ('md-post-priority' == section_el.get(0).id) {
            var select_el = $('select', section_el);
            var menu_order_el = $('#menu_order', section_el);
            // select field
            select_el.on('change', function() {
                if ('pinned' == select_el.val()) {
                    menu_order_el.val(0).show();
                } else {
                    menu_order_el.val(0).hide();
                }
            });
            cancel_el.add(ok_el).click(function(e){
                var menu_order = parseInt(menu_order_el.val());
                var menu_order_text = menu_order > 0 ? ' #' + menu_order : '' ;
                display_el.text(select_el.find('option:selected').text() + menu_order_text);
            });
        }
    });
});
