(function($) {  
    $(document).ready(function() { 
        $('.widgets-sortables .rpadv-color-picker').wpColorPicker();
        
        $(window).click(function (event) {
            event = event || window.event;
            if ($(event.target).closest('.wcp-visual-adverts-field-settings-notice').length > 0 && ( $(event.target).hasClass('dashicons-editor-help') || $(event.target).hasClass('dashicons-warning'))) {
                var el = $(event.target).closest('.wcp-visual-adverts-field-settings-notice').children('.description');
                if ($(event.target).closest('.wcp-visual-adverts-field-settings-notice').hasClass('open')) {
                    el.fadeOut(100);                
                    $(event.target).closest('.wcp-visual-adverts-field-settings-notice').removeClass('open');
                } else {
                    $('.wcp-visual-adverts-field-settings-notice').each(function() {
                       $(this).removeClass('open');
                       $(this).children('.description').fadeOut(100);
                    }); 
                    el.fadeIn(100);                
                    $(event.target).closest('.wcp-visual-adverts-field-settings-notice').addClass('open');
                }                
            } else if ($(event.target).closest('.wcp-visual-adverts-field-settings-notice').length > 0 && $(event.target).hasClass('description')) {
                return;
            } else {
                $('.wcp-visual-adverts-field-settings-notice').each(function() {
                   $(this).removeClass('open');
                   $(this).children('.description').fadeOut(100);
                });                                                 
            }
        });
    });
})(jQuery);


