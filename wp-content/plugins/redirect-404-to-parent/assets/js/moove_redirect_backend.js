
(function($){
    $(document).ready(function(){

        $('#moove-redirect-base').on('keyup',function(){
            $('.moove-base-url').text($(this).val());
        });
        $('.moove-show-stat-btn').on('click',function(e){
            e.preventDefault();
            $(this).parent().find('.moove-redirect-stat').slideToggle(function(){
                $(this).toggleClass('moove-stats-open');
                if ( $(this).hasClass('moove-stats-open') ) {
                    $(this).parent().find('.moove-show-stat-btn').text('Hide redirect statistics');
                } else {
                    $(this).parent().find('.moove-show-stat-btn').text('Show redirect statistics');
                }
            });
        });
        $('.moove-download-stat-btn').on('click',function(e){
           e.preventDefault();
           window.location.href = $(this).attr('data-href');
        });
        $('.moove-redirect-keep-settings').on('change','#moove-redirect-activate',function(){
            if ( $(this).is(':checked') ) {
                $(this).val('true').attr('checked','checked');
                $('#moove-redirect-activate-val').val('true');
            } else {
                $(this).val('false').removeAttr('checked');
                $('#moove-redirect-activate-val').val('false');
            }
        });
    });

})(jQuery);
