/* ── CONFIRM MODAL ── */
const CONFIRM_MODAL_HTML = `
<div class="admin-confirm-overlay" id="adminConfirmOverlay" role="dialog" aria-modal="true">
  <div class="admin-confirm-modal">
    <div class="admin-confirm-modal__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
      </svg>
    </div>
    <div class="admin-confirm-modal__title">¿Confirmar acción?</div>
    <div class="admin-confirm-modal__msg" id="adminConfirmMsg"></div>
    <div class="admin-confirm-modal__actions">
      <button type="button" class="admin-confirm-modal__btn admin-confirm-modal__btn--cancel" id="adminConfirmCancel">Cancelar</button>
      <button type="button" class="admin-confirm-modal__btn admin-confirm-modal__btn--danger" id="adminConfirmOk">Eliminar</button>
    </div>
  </div>
</div>`;

let _confirmResolve = null;

function adminConfirm(message, okLabel) {
    return new Promise(function (resolve) {
        _confirmResolve = resolve;
        const overlay = document.getElementById('adminConfirmOverlay');
        document.getElementById('adminConfirmMsg').textContent = message;
        if (okLabel) document.getElementById('adminConfirmOk').textContent = okLabel;
        else document.getElementById('adminConfirmOk').textContent = 'Eliminar';
        overlay.classList.add('is-open');
        document.getElementById('adminConfirmCancel').focus();
    });
}

function _closeConfirm(result) {
    const overlay = document.getElementById('adminConfirmOverlay');
    overlay.classList.remove('is-open');
    if (_confirmResolve) { _confirmResolve(result); _confirmResolve = null; }
}

document.addEventListener('DOMContentLoaded', function () {

    /* Inject confirm modal */
    document.body.insertAdjacentHTML('beforeend', CONFIRM_MODAL_HTML);
    document.getElementById('adminConfirmOk').addEventListener('click', () => _closeConfirm(true));
    document.getElementById('adminConfirmCancel').addEventListener('click', () => _closeConfirm(false));
    document.getElementById('adminConfirmOverlay').addEventListener('click', function (e) {
        if (e.target === this) _closeConfirm(false);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('adminConfirmOverlay').classList.contains('is-open')) {
            _closeConfirm(false);
        }
    });

    /* Intercept forms with data-confirm attribute */
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            adminConfirm(form.dataset.confirm, form.dataset.confirmOk).then(function (ok) {
                if (ok) form.submit();
            });
        });
    });

    /* ── ALERTS ── */
    const ICONS = {
        success: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        info:    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        warning: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    };

    const CLOSE_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

    function dismissAlert(el) {
        el.style.animation = 'none';
        el.style.transition = 'opacity .3s ease, transform .3s ease, max-height .4s ease .1s, margin-bottom .4s ease .1s, padding-top .4s ease .1s, padding-bottom .4s ease .1s';
        el.style.opacity   = '0';
        el.style.transform = 'translateY(-6px)';
        el.style.maxHeight = el.offsetHeight + 'px';
        requestAnimationFrame(() => {
            el.style.maxHeight     = '0';
            el.style.marginBottom  = '0';
            el.style.paddingTop    = '0';
            el.style.paddingBottom = '0';
        });
        setTimeout(() => el.remove(), 500);
    }

    document.querySelectorAll('.admin-alert').forEach(function (alert) {
        const type = ['success','error','info','warning'].find(t => alert.classList.contains('admin-alert--' + t)) || 'info';

        if (!alert.querySelector('.admin-alert__body')) {
            const body = document.createElement('span');
            body.className = 'admin-alert__body';
            body.innerHTML = alert.innerHTML;
            alert.innerHTML = '';
            alert.appendChild(body);
        }

        const iconWrap = document.createElement('span');
        iconWrap.className = 'admin-alert__icon';
        iconWrap.innerHTML = ICONS[type] || ICONS.info;
        alert.insertBefore(iconWrap, alert.firstChild);

        const btn = document.createElement('button');
        btn.className   = 'admin-alert__close';
        btn.type        = 'button';
        btn.title       = 'Cerrar';
        btn.innerHTML   = CLOSE_SVG;
        btn.addEventListener('click', () => dismissAlert(alert));
        alert.appendChild(btn);

        setTimeout(() => {
            if (document.body.contains(alert)) dismissAlert(alert);
        }, 5000);
    });
});
