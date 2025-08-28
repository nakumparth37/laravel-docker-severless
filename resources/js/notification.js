
window.markAsRead = (notificationId) => {
    $.ajax({
        url: '/notifications/' + notificationId + '/mark-read',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // Ensure CSRF token is in the meta tag
        },
        success: function(response) {
            // Hide the toast notification after marking as read
            let count = parseInt($(`#notification_count`).text());
            $(`#notification-${notificationId}`).fadeOut();
            if (count != 0) {
                count -= 1;
                $("#notification_count").text(count);
                if (count == 0) {
                    $("#notification_count").text('');
                    $(`#notificationList`).append('<li class="list-group-item">No notifications yet</li>')
                    $(`#make-all-read-btn`).addClass('disabled');
                }
            }
        },
        error: function(error) {
            console.error('Error:', error);
            alert('Error marking notification as read');
        }
    });
}

window.makeAllRead = () => {
    let notificationIds = []
   $('.notification-card').each(function() {
        notificationIds.push($(this).attr('id').replace('notification-', ''));
    });

    $.ajax({
        url: '/notifications/mark-all-read',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            ids : notificationIds
        },
        success: function(response) {
            // Hide the toast notification after marking as read
            notificationIds.forEach(element => {
                let count = parseInt($(`#notification_count`).text());
                $(`#notification-${element}`).fadeOut();
                if (count != 0) {
                    count -= 1;
                    $("#notification_count").text(count);
                    if (count == 0) {
                        $("#notification_count").text('');
                        $(`#notificationList`).append('<li class="list-group-item">No notifications yet</li>')
                        $(`#make-all-read-btn`).addClass('disabled');
                    }
                }
            });
        },
        error: function(error) {
            console.error('Error:', error);
            alert('Error marking notification as read');
        }
    });
}
