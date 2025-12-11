<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>ITE311-DIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background:#f8f9fa; }
        .navbar { box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        .container-main { max-width: 980px; }
    </style>
</head>
<body>
    <?= $this->include('templates/header') ?>

    <main class="container container-main py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notification System Script -->
    <script>
        // Wait for jQuery and DOM to be ready
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded!');
        } else {
            console.log('jQuery version:', jQuery.fn.jquery);
        }
        
        jQuery(document).ready(function($) {
            console.log('Notification system initialized');
            
            // Verify notification elements exist
            if ($('#notificationBadge').length === 0) {
                console.error('Notification badge element not found!');
            }
            if ($('#notificationItems').length === 0) {
                console.error('Notification items container not found!');
            }
            
            // Function to fetch and update notifications
            function fetchNotifications() {
                const notificationUrl = '<?= base_url('notifications') ?>';
                console.log('Fetching notifications from:', notificationUrl);
                
                $.ajax({
                    url: notificationUrl,
                    type: 'GET',
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        console.log('Notifications response:', response);
                        if (response && response.success) {
                            // Update badge count
                            const unreadCount = response.unread_count || 0;
                            const badge = $('#notificationBadge');
                            console.log('Unread count:', unreadCount);
                            
                            if (unreadCount > 0) {
                                badge.text(unreadCount).show();
                                console.log('Badge shown with count:', unreadCount);
                            } else {
                                badge.hide();
                                console.log('Badge hidden (no unread notifications)');
                            }
                            
                            // Update notification list
                            const itemsContainer = $('#notificationItems');
                            if (response.notifications && response.notifications.length > 0) {
                                console.log('Found', response.notifications.length, 'notifications');
                                let html = '';
                                response.notifications.forEach(function(notification) {
                                    const isRead = notification.is_read == 1;
                                    const readClass = isRead ? 'alert-secondary' : 'alert-info';
                                    const readText = isRead ? 'Read' : 'Mark as Read';
                                    const timeAgo = getTimeAgo(notification.created_at);
                                    
                                    html += `
                                        <div class="alert ${readClass} alert-dismissible fade show mb-2" role="alert" data-notification-id="${notification.id}">
                                            <div class="small">${escapeHtml(notification.message)}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">${timeAgo}</div>
                                            ${!isRead ? `<button type="button" class="btn btn-sm btn-outline-primary mt-2 mark-read-btn" data-id="${notification.id}">${readText}</button>` : ''}
                                        </div>
                                    `;
                                });
                                itemsContainer.html(html);
                            } else {
                                console.log('No notifications found');
                                itemsContainer.html('<div class="text-muted small">No notifications</div>');
                            }
                        } else {
                            console.error('Invalid response format:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch notifications:', status, error);
                        console.error('Status Code:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        // Show error in notification items if request fails
                        const itemsContainer = $('#notificationItems');
                        if (xhr.status === 401) {
                            itemsContainer.html('<div class="text-danger small">Please login to view notifications.</div>');
                        } else if (xhr.status === 403) {
                            itemsContainer.html('<div class="text-danger small">Access denied.</div>');
                        } else {
                            itemsContainer.html('<div class="text-muted small">Failed to load notifications. Please refresh the page.</div>');
                        }
                    }
                });
            }
            
            // Function to mark notification as read
            function markAsRead(notificationId) {
                // Get CSRF token from meta tag
                const csrfToken = $('meta[name="csrf-token"]').attr('content') || '';
                const csrfTokenName = '<?= config('Security')->tokenName ?>';
                
                $.ajax({
                    url: '<?= base_url('notifications/mark_read') ?>/' + notificationId,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: csrfToken ? {
                        [csrfTokenName]: csrfToken
                    } : {},
                    success: function(response) {
                        if (response && response.success) {
                            // Remove the notification from the list or update its appearance
                            const notificationItem = $(`[data-notification-id="${notificationId}"]`);
                            notificationItem.removeClass('alert-info').addClass('alert-secondary');
                            notificationItem.find('.mark-read-btn').remove();
                            
                            // Refresh notifications to update count
                            fetchNotifications();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to mark notification as read:', status, error);
                        console.error('Response:', xhr.responseText);
                        // Try to parse error response
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                console.error('Error message:', errorResponse.message);
                            }
                        } catch (e) {
                            // Not JSON response
                        }
                    }
                });
            }
            
            // Helper function to get cookie value
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return null;
            }
            
            // Helper function to escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
            
            // Helper function to get time ago
            function getTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
                if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
                if (diffInSeconds < 604800) return Math.floor(diffInSeconds / 86400) + ' days ago';
                return date.toLocaleDateString();
            }
            
            // Event delegation for mark as read buttons
            $(document).on('click', '.mark-read-btn', function() {
                const notificationId = $(this).data('id');
                markAsRead(notificationId);
            });
            
            // Manual refresh button
            $(document).on('click', '#refreshNotifications', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Manual refresh triggered');
                $('#notificationItems').html('<div class="text-muted small">Refreshing...</div>');
                fetchNotifications();
            });
            
            // Initial fetch with small delay to ensure page is fully loaded
            setTimeout(function() {
                console.log('Starting initial notification fetch...');
                fetchNotifications();
            }, 500);
            
            // Optional: Refresh notifications every 60 seconds
            setInterval(function() {
                console.log('Auto-refreshing notifications...');
                fetchNotifications();
            }, 60000);
        });
    </script>
</body>
</html>
