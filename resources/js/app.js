/*
 * Utilidades de interfaz compartidas por todas las vistas.
 * jQuery, Bootstrap y SweetAlert2 se cargan como scripts clásicos desde
 * /vendor (ver layouts/app.blade.php); aquí solo se agregan ayudas que
 * se usan dentro de manejadores de eventos.
 */

const icons = {
    success: 'bi-check-circle-fill',
    error: 'bi-exclamation-octagon-fill',
    info: 'bi-info-circle-fill',
};

function region() {
    let el = document.getElementById('ftToastRegion');
    if (!el) {
        el = document.createElement('div');
        el.id = 'ftToastRegion';
        el.className = 'ft-toast-region';
        el.setAttribute('aria-live', 'polite');
        document.body.appendChild(el);
    }
    return el;
}

/** Aviso no bloqueante en la esquina inferior. */
function toast(message, type = 'success', timeout = 4500) {
    const item = document.createElement('div');
    item.className = `ft-toast ft-toast--${type}`;
    item.setAttribute('role', type === 'error' ? 'alert' : 'status');

    const icon = document.createElement('i');
    icon.className = `bi ${icons[type] || icons.info}`;
    icon.setAttribute('aria-hidden', 'true');

    const text = document.createElement('div');
    text.textContent = message;

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'btn-close btn-sm ft-toast__close';
    close.setAttribute('aria-label', 'Cerrar aviso');
    close.addEventListener('click', () => item.remove());

    item.append(icon, text, close);
    region().appendChild(item);
    if (timeout) setTimeout(() => item.remove(), timeout);
    return item;
}

const swalButtons = {
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-outline-secondary',
        denyButton: 'btn btn-outline-secondary',
        actions: 'gap-2',
    },
};

/** Confirmación accesible. Devuelve una promesa que resuelve true/false. */
function confirmAction(message, {
    title = '¿Confirmas esta acción?',
    confirmText = 'Confirmar',
    cancelText = 'Cancelar',
    danger = false,
} = {}) {
    if (!window.Swal) return Promise.resolve(window.confirm(message));
    return window.Swal.fire({
        ...swalButtons,
        customClass: {
            ...swalButtons.customClass,
            confirmButton: danger ? 'btn btn-danger' : 'btn btn-primary',
        },
        title,
        text: message,
        icon: danger ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        focusCancel: danger,
    }).then((result) => result.isConfirmed);
}

/** Mensaje modal (reemplaza a alert()). Devuelve una promesa. */
function notice(message, type = 'success', title) {
    const titles = { success: 'Listo', error: 'No se pudo completar', info: 'Aviso', warning: 'Revisa la información' };
    if (!window.Swal) {
        window.alert(message);
        return Promise.resolve();
    }
    return window.Swal.fire({
        ...swalButtons,
        icon: type === 'error' ? 'error' : type,
        title: title || titles[type] || titles.info,
        text: message,
        confirmButtonText: 'Aceptar',
    });
}

window.FT = { toast, confirm: confirmAction, notice };

// SweetAlert2 hereda los botones del sistema de diseño en todas las vistas.
if (window.Swal) {
    window.Swal = window.Swal.mixin(swalButtons);
}
