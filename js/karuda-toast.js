/**
 * Karuda Computers — Unified Global Toast Engine
 * Replaces and harmonizes all toasts (toastr, custom toasts, alerts)
 * into one single, beautiful, modern toast notification system.
 */
(function(window, document) {
    'use strict';

    function getOrCreateContainer() {
        var container = document.getElementById('kc-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'kc-toast-container';
            container.className = 'kc-toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    function showToast(arg1, arg2, arg3, arg4) {
        var title = 'Notice';
        var message = '';
        var type = 'success';
        var duration = 4200;

        // Smart signature resolver:
        // Case 1: showToast('Message')
        // Case 2: showToast('Message', 'error')
        // Case 3: showToast('Title', 'Message', 'success')
        // Case 4: showToast('Message', 'Title', 'info', 3000)
        var validTypes = ['success', 'error', 'warning', 'info'];

        if (typeof arg2 === 'undefined' || arg2 === null) {
            message = String(arg1 || '');
            title = 'Notice';
        } else if (typeof arg2 === 'string' && validTypes.indexOf(arg2.toLowerCase()) !== -1) {
            message = String(arg1 || '');
            type = arg2.toLowerCase();
            title = type.charAt(0).toUpperCase() + type.slice(1);
            if (typeof arg3 === 'number') duration = arg3;
        } else if (typeof arg3 === 'string' && validTypes.indexOf(arg3.toLowerCase()) !== -1) {
            title = String(arg1 || 'Notice');
            message = String(arg2 || '');
            type = arg3.toLowerCase();
            if (typeof arg4 === 'number') duration = arg4;
        } else {
            // Default: arg1 = Title, arg2 = Message
            title = String(arg1 || 'Notice');
            message = String(arg2 || '');
            if (arg3 && typeof arg3 === 'string') type = arg3.toLowerCase();
            if (typeof arg3 === 'number') duration = arg3;
            if (typeof arg4 === 'number') duration = arg4;
        }

        // Clean text formatting
        message = message.replace(/^✅\s*|^🛒\s*|^⚠️\s*|^❌\s*/, '');
        if (type === 'success' && title === 'Notice') title = 'Success';

        // Ensure DOM ready
        if (!document.body) {
            document.addEventListener('DOMContentLoaded', function() {
                showToast(arg1, arg2, arg3, arg4);
            });
            return;
        }

        var container = getOrCreateContainer();
        var toast = document.createElement('div');
        toast.className = 'kc-toast kc-toast-' + type;

        var iconClass = 'fa-check';
        if (type === 'error') iconClass = 'fa-triangle-exclamation';
        else if (type === 'warning') iconClass = 'fa-circle-exclamation';
        else if (type === 'info') iconClass = 'fa-circle-info';

        toast.innerHTML = 
            '<div class="kc-toast-icon-wrap"><i class="fa-solid ' + iconClass + '"></i></div>' +
            '<div class="kc-toast-content">' +
                '<div class="kc-toast-title">' + title + '</div>' +
                '<div class="kc-toast-message">' + message + '</div>' +
            '</div>' +
            '<button type="button" class="kc-toast-close" aria-label="Close">&times;</button>' +
            '<div class="kc-toast-progress" style="animation-duration: ' + duration + 'ms;"></div>';

        container.appendChild(toast);

        // Slide in
        requestAnimationFrame(function() {
            setTimeout(function() {
                toast.classList.add('kc-toast-show');
            }, 10);
        });

        var isRemoved = false;
        function removeToast() {
            if (isRemoved) return;
            isRemoved = true;
            toast.classList.remove('kc-toast-show');
            toast.classList.add('kc-toast-hide');
            setTimeout(function() {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 360);
        }

        toast.querySelector('.kc-toast-close').addEventListener('click', removeToast);
        var autoTimer = setTimeout(removeToast, duration);

        // Pause on hover
        toast.addEventListener('mouseenter', function() {
            clearTimeout(autoTimer);
            var prog = toast.querySelector('.kc-toast-progress');
            if (prog) prog.style.animationPlayState = 'paused';
        });
        toast.addEventListener('mouseleave', function() {
            var prog = toast.querySelector('.kc-toast-progress');
            if (prog) prog.style.animationPlayState = 'running';
            autoTimer = setTimeout(removeToast, 2000);
        });
    }

    // Expose Global Functions
    window.showKcToast = showToast;
    window.KarudaToast = {
        show: showToast,
        success: function(msg, title, dur) { showToast(title || 'Success', msg, 'success', dur); },
        error: function(msg, title, dur) { showToast(title || 'Error', msg, 'error', dur); },
        warning: function(msg, title, dur) { showToast(title || 'Warning', msg, 'warning', dur); },
        info: function(msg, title, dur) { showToast(title || 'Information', msg, 'info', dur); }
    };

    // Global override for toastr library calls so all old/existing calls use this unified design
    var unifiedToastr = {
        options: {},
        success: function(msg, title, opt) {
            showToast(title || 'Success', msg, 'success');
        },
        error: function(msg, title, opt) {
            showToast(title || 'Error', msg, 'error');
        },
        warning: function(msg, title, opt) {
            showToast(title || 'Warning', msg, 'warning');
        },
        info: function(msg, title, opt) {
            showToast(title || 'Notice', msg, 'info');
        },
        clear: function() {
            var c = document.getElementById('kc-toast-container');
            if (c) c.innerHTML = '';
        },
        remove: function() {
            var c = document.getElementById('kc-toast-container');
            if (c) c.innerHTML = '';
        }
    };

    // Override now
    window.toastr = unifiedToastr;

    // In case toastr script loads later, protect and re-assign
    try {
        Object.defineProperty(window, 'toastr', {
            get: function() { return unifiedToastr; },
            set: function() { /* preserve unified toastr */ },
            configurable: true
        });
    } catch(e) {
        window.toastr = unifiedToastr;
    }

})(window, document);
