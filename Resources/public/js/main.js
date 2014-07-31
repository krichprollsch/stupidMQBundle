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

    $('.duplicateMessage').click(function() {
        var queue = $('ul.nav > li.active > a').text().trim();
        var button = $(this);
        var id = button.attr('id');

        button.text('Now duplicating...');
        button.addClass('disabled');

        $.ajax({
            url: Routing.generate('cog_stupid_duplicate', { id: id, queue: queue })
        }).done(function(data) {
            if('id' in data) {
                button.text('Sucessfully duplicated (#' + data.id + ')');
            } else {
                button.text('Duplicating error !');
            }
        });
    })
});