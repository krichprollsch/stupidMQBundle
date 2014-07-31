$(document).ready(function() {
    // To show / hide message content.
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

    // To duplicate a message that failed.
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

    // Update dynamic content.

    // Set durations.
    $('span.messageDuration').each(function() {
        var createdAt = moment($(this).parent().parent().next().find('em.messageCreatedAt').attr('title'), 'YYYY-MM-DD HH:mm:ss');
        $(this).text(moment($(this).text(), 'YYYY-MM-DD HH:mm:ss').from(createdAt, true));
    });

    function updateContent() {
        // Update sent date.
        $('em.messageCreatedAt').each(function() {
            var date = $(this).attr('title');
            $(this).find('span').text(moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow());
        });

        setTimeout(updateContent, 10000);
    }

    updateContent();
});