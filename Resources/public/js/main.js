$(document).ready(function() {
    $('.title-area').click(function() {
        var next = $(this).next();
        if (next.hasClass('opened')) {
            next.hide();
            next.removeClass('opened');
            $(this).find('div.pull-right span').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            next.show();
            next.addClass('opened');
            $(this).find('div.pull-right span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });
});