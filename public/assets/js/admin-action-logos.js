(function () {
    const actionIcons = {
        create: '<path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        delete: '<path d="M4 7h16M9 7V4h6v3m3 0-.8 13H6.8L6 7m4 4v5m4-5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        search: '<circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="2"/><path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        filter: '<path d="M4 5h16l-6.2 7v5l-3.6 2v-7L4 5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
        export: '<path d="M7 3.5h7l4.5 4.6v11.4a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 19.5v-14A2 2 0 0 1 7.5 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3.8v4.7h4.2M12 10v6m-2.5-2.5L12 16l2.5-2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        upload: '<path d="M12 15V4m0 0L8 8m4-4 4 4M5 14v4.5A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.5V14" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>',
        save: '<path d="M5 4h12l3 3v13H4V4h1Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 4v5h8V4M8 20v-7h8v7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
        edit: '<path d="m4 16.5-.8 4.3 4.3-.8L19 8.5 15.5 5 4 16.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m13.8 6.7 3.5 3.5" stroke="currentColor" stroke-width="1.8"/>',
        view: '<path d="M2.5 12s3.3-6 9.5-6 9.5 6 9.5 6-3.3 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8"/>',
        verify: '<path d="m5 12.5 4.2 4.2L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        reset: '<path d="M4.5 11a7.5 7.5 0 1 1 .8 4.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><path d="M4.5 5.5V11H10" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>',
        cancel: '<path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        back: '<path d="m14.5 5-7 7 7 7M8 12h12" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>',
        default: '<path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
    };

    const actionPatterns = [
        ['delete', /\b(delete|remove|destroy|decline|reject)\b/],
        ['create', /\b(add|create|new)\b/],
        ['upload', /\b(upload|import)\b/],
        ['export', /\b(export|download|print|pdf|excel|csv)\b/],
        ['search', /\b(search|find)\b/],
        ['filter', /\b(filter|apply filters?)\b/],
        ['reset', /\b(reset|clear filters?)\b/],
        ['cancel', /\b(cancel|close)\b/],
        ['back', /\b(back|return)\b/],
        ['view', /\b(view|details|show)\b/],
        ['verify', /\b(verify|approve|activate|confirm)\b/],
        ['edit', /\b(edit|update|change)\b/],
        ['save', /\b(save|submit|send|login|register)\b/]
    ];

    function getAction(button) {
        const label = [
            button.getAttribute('aria-label'),
            button.getAttribute('title'),
            button.value,
            button.textContent
        ].filter(Boolean).join(' ').toLowerCase();

        for (const [action, pattern] of actionPatterns) {
            if (pattern.test(label)) {
                return action;
            }
        }

        if (button.classList.contains('btn-danger')) {
            return 'delete';
        }
        if (button.classList.contains('btn-success')) {
            return 'create';
        }
        if (button.matches('[type="reset"]')) {
            return 'reset';
        }

        return 'default';
    }

    function createLogo(action) {
        const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.setAttribute('fill', 'none');
        icon.setAttribute('aria-hidden', 'true');
        icon.setAttribute('focusable', 'false');
        icon.classList.add('admin-action-logo-mark', `admin-action-logo--${action}`);
        icon.innerHTML = actionIcons[action] || actionIcons.default;
        return icon;
    }

    function addLogo(button) {
        if (button.dataset.adminActionLogoReady === 'true'
            || button.closest('.navbar, .toolbar-icon-button, .admin-dsr-icon-button')
            || button.matches('.btn-close, [data-bs-toggle="collapse"], .dropdown-toggle')) {
            return;
        }

        button.dataset.adminActionLogoReady = 'true';
        const action = getAction(button);
        if (button.matches('input[type="submit"], input[type="button"], input[type="reset"]')) {
            const wrapper = document.createElement('span');
            wrapper.className = 'admin-action-logo-input-wrap';
            button.parentNode.insertBefore(wrapper, button);
            wrapper.append(createLogo(action), button);
            return;
        }

        button.classList.add('admin-action-logo-button');
        const existingIcon = button.querySelector('i, svg');

        if (existingIcon) {
            existingIcon.classList.add('admin-action-logo-mark', `admin-action-logo--${action}`);
            return;
        }

        button.insertBefore(createLogo(action), button.firstChild);
    }

    function enhance(container) {
        if (!container.querySelectorAll) {
            return;
        }

        const selector = '.btn, button:not(.btn-close), input[type="submit"], input[type="button"], input[type="reset"]';
        if (container.matches && container.matches(selector)) {
            addLogo(container);
        }
        container.querySelectorAll(selector).forEach(addLogo);
    }

    function initialize() {
        const content = document.querySelector('.content');
        if (!content) {
            return;
        }

        enhance(content);
        new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        enhance(node);
                    }
                });
            });
        }).observe(content, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
})();
