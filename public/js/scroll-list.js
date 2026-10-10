(function () {
    'use strict';

    function findTable(container, rootDocument) {
        rootDocument = rootDocument || document;
        let ancestor = container.parentElement;

        while (ancestor && ancestor !== rootDocument.body) {
            const tables = ancestor.querySelectorAll('table');
            if (tables.length === 1 && tables[0].querySelector('tbody')) {
                return tables[0];
            }
            ancestor = ancestor.parentElement;
        }

        const previousTables = Array.from(rootDocument.querySelectorAll('table')).filter(function (table) {
            return Boolean(table.compareDocumentPosition(container) & Node.DOCUMENT_POSITION_FOLLOWING)
                && table.querySelector('tbody');
        });

        return previousTables.length ? previousTables[previousTables.length - 1] : null;
    }

    function getScrollSection(table) {
        let section = table.closest('.table-responsive');

        if (!section) {
            section = document.createElement('div');
            section.className = 'table-responsive';
            table.parentNode.insertBefore(section, table);
            section.appendChild(table);
        }

        section.classList.add('scroll-list-section');
        section.tabIndex = 0;
        section.setAttribute('aria-label', 'Scrollable list of records');
        return section;
    }

    function getNextLink(pagination) {
        return Array.from(pagination.querySelectorAll('a[href]')).find(function (link) {
            return link.rel === 'next'
                || /next|›|»/i.test((link.getAttribute('aria-label') || '') + ' ' + link.textContent);
        });
    }

    function matchingTable(documentFragment, table) {
        if (table.id) {
            const match = documentFragment.getElementById(table.id);
            if (match && match.querySelector('tbody')) {
                return match;
            }
        }

        const tables = documentFragment.querySelectorAll('table');
        const tableIndex = Array.from(document.querySelectorAll('table')).indexOf(table);
        const match = tables[tableIndex];

        return match && match.querySelector('tbody') ? match : null;
    }

    function matchingPagination(documentFragment, table) {
        return Array.from(documentFragment.querySelectorAll('nav[aria-label="Pagination Navigation"], .pagination'))
            .find(function (pagination) {
                return findTable(pagination, documentFragment) === table;
            });
    }

    function createLoadMoreControl(container, onLoad) {
        const status = document.createElement('div');
        status.className = 'scroll-list-controls';
        status.setAttribute('role', 'status');
        status.setAttribute('aria-live', 'polite');

        const message = document.createElement('span');
        message.className = 'scroll-list-message';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-outline-primary btn-sm scroll-list-load-more';
        button.textContent = 'Load more';
        button.addEventListener('click', onLoad);

        status.append(message, button);
        container.insertAdjacentElement('afterend', status);

        return {
            element: status,
            message: message,
            button: button,
            set: function (text, enabled) {
                message.textContent = text;
                button.hidden = !enabled;
                button.disabled = !enabled;
            }
        };
    }

    function initialize(pagination) {
        if (pagination.dataset.scrollListInitialized === 'true') {
            return;
        }

        const table = findTable(pagination);
        if (!table) {
            return;
        }

        pagination.dataset.scrollListInitialized = 'true';
        pagination.hidden = true;

        const state = {
            nextUrl: null,
            loading: false,
            finished: false
        };

        const controls = createLoadMoreControl(pagination, function () {
            loadNextPage();
        });

        function loadNextPage() {
            if (state.loading || state.finished || !state.nextUrl) {
                return;
            }

            state.loading = true;
            controls.set('Loading records…', false);

            fetch(state.nextUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Could not load more records.');
                }
                return response.text();
            }).then(function (html) {
                const nextDocument = new DOMParser().parseFromString(html, 'text/html');
                const nextTable = matchingTable(nextDocument, table);
                if (!nextTable) {
                    throw new Error('The next page did not contain the expected list.');
                }

                const newRows = Array.from(nextTable.querySelectorAll('tbody tr'));
                newRows.forEach(function (row) {
                    table.tBodies[0].appendChild(document.importNode(row, true));
                });

                const loadedCount = document.getElementById('user-list-loaded-count');
                if (loadedCount) {
                    const count = table.querySelectorAll('tbody tr[data-entry-id]').length;
                    loadedCount.textContent = new Intl.NumberFormat().format(count);
                    loadedCount.parentElement.setAttribute('aria-label', count + ' users loaded');
                }

                const nextPager = matchingPagination(nextDocument, nextTable);
                const nextLink = nextPager ? getNextLink(nextPager) : null;
                state.nextUrl = nextLink ? nextLink.href : null;
                state.finished = !state.nextUrl || newRows.length === 0;

                controls.set(
                    state.finished ? 'All records loaded' : 'More records are available',
                    !state.finished
                );
            }).catch(function (error) {
                controls.set(error.message, true);
                controls.button.textContent = 'Retry';
            }).finally(function () {
                state.loading = false;
            });
        }

        getScrollSection(table);
        const nextLink = getNextLink(pagination);
        state.nextUrl = nextLink ? nextLink.href : null;
        state.finished = !state.nextUrl;
        controls.set(state.finished ? 'All records loaded' : 'More records are available', !state.finished);
    }

    function initializeAll() {
        document.querySelectorAll('nav[aria-label="Pagination Navigation"], .pagination').forEach(function (pager) {
            const navigation = pager.closest('nav[aria-label="Pagination Navigation"]');
            if (!navigation || navigation === pager) {
                initialize(pager);
            }
        });
    }

    function mount(container, loadPage) {
        const status = typeof container === 'string' ? document.querySelector(container) : container;
        if (!status) {
            throw new Error('Scroll list needs a valid load-more container.');
        }

        const table = findTable(status);
        let scrollSection = null;
        if (table) {
            scrollSection = getScrollSection(table);
        }

        const state = {
            currentPage: 1,
            lastPage: 1,
            loading: false
        };

        const controls = createLoadMoreControl(status, requestNextPage);
        status.hidden = true;

        function requestNextPage() {
            if (state.loading || state.currentPage >= state.lastPage) {
                return;
            }

            state.loading = true;
            controls.set('Loading records…', false);
            Promise.resolve(loadPage(state.currentPage + 1)).catch(function (error) {
                if (error && error.statusText === 'abort') {
                    return;
                }
                controls.set('Could not load more records. Try again.', true);
                controls.button.textContent = 'Retry';
            }).finally(function () {
                state.loading = false;
            });
        }

        return {
            update: function (currentPage, lastPage) {
                state.currentPage = Number(currentPage) || 1;
                state.lastPage = Number(lastPage) || 1;
                controls.button.textContent = 'Load more';
                controls.set(
                    state.currentPage < state.lastPage ? 'More records are available' : 'All records loaded',
                    state.currentPage < state.lastPage
                );
            },
            reset: function () {
                state.currentPage = 1;
                state.lastPage = 1;
                state.loading = false;
                if (scrollSection) {
                    scrollSection.scrollTop = 0;
                }
                controls.button.textContent = 'Load more';
                controls.set('Loading records…', false);
            }
        };
    }

    window.FreebazarScrollList = { mount: mount };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAll);
    } else {
        initializeAll();
    }
})();
