$(function(){
    $('a[href^="#top"]').click(function(){
        var speed = 500;
        var href= $(this).attr("href");
        var target = $(href == "#top" || href == "" ? 'html' : href);
        //var target = $(href == "" ? 'html' : href);
        var position = target.offset().top;
        var body = 'body';
        if (navigator.userAgent.match(/MSIE/)){
            body = 'html';
        }
        $(body).animate({scrollTop:position}, speed, 'swing');
        return false;
    });
});

