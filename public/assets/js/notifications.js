/**
 * Shared notification bell logic — used on Admin, POS and Customer layouts.
 * Polls every 25s and renders an attractive, color-coded dropdown list.
 */
(function () {
    const ICONS = {
        info: 'fa-circle-info text-primary',
        success: 'fa-circle-check text-success',
        warning: 'fa-triangle-exclamation text-warning',
        danger: 'fa-circle-exclamation text-danger',
    };

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function notificationEndpoint(attribute) {
        const bell = document.querySelector('.notification-bell');
        const endpoint = bell ? safeInternalUrl(bell.getAttribute(attribute)) : '';
        if (!endpoint) {
            console.error('Notification endpoint is missing or invalid:', attribute);
        }
        return endpoint;
    }

    function notificationReadEndpoint(id) {
        const template = notificationEndpoint('data-notification-read-url-template');
        if (!template || !template.includes('__notification_id__')) {
            console.error('Notification read URL template is invalid.');
            return '';
        }
        return template.replace('__notification_id__', encodeURIComponent(id));
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.innerText = str ?? '';
        return div.innerHTML;
    }

    function escapeAttribute(str) {
        return String(str ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function safeInternalUrl(value) {
        if (!value) return '';
        try {
            const url = new URL(value, window.location.origin);
            return url.origin === window.location.origin ? url.pathname + url.search + url.hash : '';
        } catch (_) {
            return '';
        }
    }

    let notificationBaselineLoaded = false;
    const seenNotificationIds = new Set();

    function showToast(notification) {
        let stack = document.getElementById('notification-toast-stack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'notification-toast-stack';
            stack.className = 'position-fixed top-0 end-0 p-3';
            stack.style.zIndex = '1090';
            stack.style.width = 'min(100vw, 380px)';
            document.body.appendChild(stack);
        }

        const toast = document.createElement('div');
        const toastType = ['success', 'warning', 'danger'].includes(notification.type) ? notification.type : 'info';
        toast.className = 'alert alert-' + toastType + ' shadow d-flex justify-content-between align-items-start gap-3 mb-2';
        toast.setAttribute('role', 'status');
        const copy = document.createElement('div');
        const title = document.createElement('strong');
        title.className = 'd-block';
        title.textContent = notification.title || 'New notification';
        const message = document.createElement('span');
        message.className = 'small';
        message.textContent = notification.message || '';
        copy.append(title, message);

        const dismiss = document.createElement('button');
        dismiss.type = 'button';
        dismiss.className = 'btn-close';
        dismiss.setAttribute('aria-label', 'Dismiss notification');
        dismiss.addEventListener('click', function () { toast.remove(); });
        toast.append(copy, dismiss);

        const url = safeInternalUrl(notification.url);
        if (url) {
            toast.style.cursor = 'pointer';
            toast.tabIndex = 0;
            toast.setAttribute('role', 'link');
            toast.addEventListener('click', function (event) {
                if (event.target.closest('button')) return;
                const readEndpoint = notificationReadEndpoint(notification.id);
                if (!readEndpoint) return;
                fetch(readEndpoint, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' },
                }).then(function (response) {
                    if (!response.ok) throw new Error('Unable to mark notification as read.');
                }).catch(function (error) {
                    console.error(error);
                }).finally(function () { window.location.href = url; });
            });
        }

        stack.prepend(toast);
        window.setTimeout(function () { toast.remove(); }, 8000);
    }

    function renderList(container, items) {
        if (!items.length) {
            container.innerHTML = '<div class="text-center text-muted small py-4">You\'re all caught up! No notifications yet.</div>';
            return;
        }

        container.innerHTML = items.map(function (n) {
            const iconClass = ICONS[n.type] || ICONS.info;
            const unreadClass = n.read ? '' : 'bg-light';
            const safeUrl = safeInternalUrl(n.url);
            return (
                '<a href="javascript:void(0)" data-id="' + escapeAttribute(n.id) + '" data-url="' + escapeAttribute(safeUrl) + '" ' +
                'class="notif-item list-group-item list-group-item-action border-bottom px-3 py-2 ' + unreadClass + '">' +
                    '<div class="d-flex">' +
                        '<div class="me-2 pt-1"><i class="fa-solid ' + iconClass + '"></i></div>' +
                        '<div class="flex-grow-1">' +
                            '<div class="d-flex justify-content-between align-items-start">' +
                                '<h4 class="h6 mb-0 text-small fw-bold">' + escapeHtml(n.title) + '</h4>' +
                                (n.read ? '' : '<span class="badge bg-danger rounded-pill ms-2" style="font-size:.5rem;">NEW</span>') +
                            '</div>' +
                            '<p class="font-small mt-1 mb-0 text-muted">' + escapeHtml(n.message) + '</p>' +
                            '<small class="text-muted">' + escapeHtml(n.time) + '</small>' +
                        '</div>' +
                    '</div>' +
                '</a>'
            );
        }).join('');
    }

    function updateBadges(count) {
        document.querySelectorAll('.notif-badge').forEach(function (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });

        document.querySelectorAll('.notification-bell').forEach(function (bell) {
            bell.classList.toggle('unread', count > 0);
        });
    }

    function fetchNotifications() {
        const endpoint = notificationEndpoint('data-notifications-url');
        if (!endpoint) return;

        fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (response) {
                if (!response.ok) throw new Error('Unable to load notifications.');
                return response.json();
            })
            .then(function (data) {
                updateBadges(data.unread_count);
                document.querySelectorAll('.notif-list').forEach(function (container) {
                    renderList(container, data.notifications);
                });
                data.notifications.forEach(function (notification) {
                    if (!seenNotificationIds.has(notification.id)) {
                        if (notificationBaselineLoaded && !notification.read) showToast(notification);
                        seenNotificationIds.add(notification.id);
                    }
                });
                notificationBaselineLoaded = true;
            })
            .catch(function (error) {
                console.error(error);
            });
    }

    document.addEventListener('click', function (e) {
        const item = e.target.closest('.notif-item');
        if (item) {
            const id = item.getAttribute('data-id');
            const url = item.getAttribute('data-url');
            const endpoint = notificationReadEndpoint(id);
            if (!endpoint) return;
            fetch(endpoint, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' },
            }).then(function (response) {
                if (!response.ok) throw new Error('Unable to mark notification as read.');
                if (url) window.location.href = safeInternalUrl(url) || '/';
                else fetchNotifications();
            }).catch(function (error) {
                console.error(error);
            });
        }

        if (e.target && e.target.id === 'notif-mark-all') {
            e.preventDefault();
            const endpoint = notificationEndpoint('data-notifications-read-all-url');
            if (!endpoint) return;
            fetch(endpoint, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json' },
            }).then(function (response) {
                if (!response.ok) throw new Error('Unable to mark notifications as read.');
                fetchNotifications();
            }).catch(function (error) {
                console.error(error);
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        fetchNotifications();
        setInterval(fetchNotifications, 25000);
    });
})();
