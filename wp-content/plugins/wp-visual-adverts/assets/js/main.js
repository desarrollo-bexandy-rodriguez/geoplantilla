if (typeof rpadv_settings == 'undefined') {
    var rpadv_settings = {advertCount:[],refreshTime:[],animationSpeed:[],advertCountPage:[],version:[],index:[]};
}
(function($) {  
    $(window).load(function() {
        $('.main-visual-adverts').each(function() {
            $(this).height($(this).find('.visual-adverts.add').outerHeight());
            advertsRefresh($(this).data('id'));            
        });
    });
    
    $(document).ready(function() { 
        $('.main-visual-adverts').each(function() {
            $(this).height($(this).find('.visual-adverts.add').outerHeight());
        });
    });
    
    
    function advertsRefresh(id) {
        var owner = $('[data-id=' + id + ']');

        $(owner).find('.visual-adverts').removeClass('add');  

        var data = {};
        data.action = 'advertsRefresh';
        data.nonce = ajax_rpadv.ajax_nonce;
        data.id = id;
        data.index = rpadv_settings.index[id];
        
        $.ajax({
            url: ajax_rpadv.ajax_url,
            type: 'POST' ,
            data: data,
            dataType: 'html',
            cache: false,
            success: function(data) {
                $(owner).find('.visual-adverts').addClass('remove');
                $(owner).append(data);

                rpadv_settings.index[id] = $(owner).find('.visual-adverts.add').data('index');

                $(owner).find('.visual-adverts.add').fadeIn(rpadv_settings.animationSpeed[id], function () {
                    $(owner).find('.visual-adverts.remove').remove();                    
                    var height = $(this).outerHeight();
                    $(owner).height(height);
                });                    

                $(owner).find('.visual-adverts.remove').fadeOut(rpadv_settings.animationSpeed[id], function() {
                    $(this).remove();  
                });                    

                if (rpadv_settings.refreshTime[id] > 0 && rpadv_settings.advertCount[id] > rpadv_settings.advertCountPage[id] ) {
                    setTimeout(function() {advertsRefresh(id)}, rpadv_settings.refreshTime[id]);   
                }
            },
            error: function (request, status, error) {
                setTimeout(function() {advertsRefresh(id)}, 3000);   
            }
        });                   
    };    
})(jQuery);


