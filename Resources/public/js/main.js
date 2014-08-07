$(document).ready(function() {
    var queue = $('ul.nav > li.active > a').text().trim();

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

    function getStateClass(state) {
        var stateClass = 'warning';
        switch (state) {
            case 'new':
                stateClass = 'info';
                break;
            case 'pending':
            case 'running':
            case 'canceled':
                stateClass = 'warning';
                break;
            case 'error':
                stateClass = 'danger';
                break;
            case 'done':
                stateClass = 'success';
                break;
        }
        return stateClass;
    }

    function updateLine(message) {
        var line = $('li.list-group-item').has('span.messageId:contains(' + message.id + ')');

        var messageState = line.find('span.messageState');
        messageState.text(message.state);
        messageState.removeClass();
        messageState.addClass('label label-' + getStateClass(message.state) + ' messageState');

        line.removeClass();
        line.addClass('list-group-item list-group-item-' + getStateClass(message.state));

        if (message.feedback) {
            line.find('div.messageFeedback').text(message.feedback.substr(0, 40) + (message.feedback.length > 40 ? '...' : ''));
            line.find('code.messageFeedbackFull').text(message.feedback);
        }

        var createdAt = moment(line.find('em.messageCreatedAt').attr('title'), 'YYYY-MM-DD HH:mm:ss');
        line.find('span.messageDuration').text(moment(message.updated_at, 'YYYY-MM-DD HH:mm:ss').from(createdAt, true));

        console.log(message);
    }

    function updateContent() {
        // Update sent date.
        $('em.messageCreatedAt').each(function() {
            var date = $(this).attr('title');
            $(this).find('span').text(moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow());
        });

        // Retrieve ids of messages in the queue.
        var ids = $.map($('li.list-group-item-info span.messageId, li.list-group-item-warning span.messageId'), function(val, i) {
            return $(val).text();
        });

        if (ids.length > 0) {
            $.ajax({
                url: Routing.generate('cog_stupidmq_cget', { queue: queue, 'ids[]': ids })
            }).done(function(messages) {
                for (var i=0; i<messages.length; i++) {
                    updateLine(messages[i]);
                }
            });
        }

        setTimeout(updateContent, 10000);
    }

    updateContent();
});