{{--
    Shared notification bell dropdown body. Included inside the existing
    `<li class="nav-item dropdown">...<div class="dropdown-menu ...">` wrapper
    in the Admin / POS / Customer navbars. Populated live via notifications.js.
--}}
<div class="d-flex justify-content-between align-items-center border-bottom border-light px-3 py-2">
    <span class="fw-bold text-dark small text-uppercase">Notifications</span>
    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small" id="notif-mark-all">Mark all
        read</button>
</div>
<div class="notif-list" style="max-height: 360px; overflow-y: auto;">
    <div class="text-center text-muted small py-4" id="notif-empty">Loading…</div>
</div>
