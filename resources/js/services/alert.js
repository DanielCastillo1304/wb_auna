/**
 * AlertService — Global
 * Disponible en window.AlertService
 */
window.AlertService = {
    container: null,

    init() {
        // Crea el contenedor si no existe en el DOM
        if (!$("#alertContainer").length) {
            $("body").prepend(
                '<div id="alertContainer" class="fixed top-4 right-4 z-50 w-full max-w-sm"></div>',
            );
        }
        this.container = $("#alertContainer");
    },

    types: {
        success: {
            class: "bg-emerald-50 border-emerald-400 text-emerald-800",
            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            animate: "animate-slide-in",
        },
        error: {
            class: "bg-rose-50 border-rose-400 text-rose-800",
            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            animate: "animate-shake",
        },
        warning: {
            class: "bg-amber-50 border-amber-400 text-amber-800",
            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
            animate: "",
        },
        waiting: {
            class: "bg-amber-50 border-amber-400 text-amber-800",
            icon: '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>',
            animate: "",
        },
    },

    show(type, message, duration = 4000) {
        this.init();

        const config = this.types[type] ?? this.types.error;
        const isWaiting = type === "waiting";

        const $alert = $(`
            <div class="alert-item ${config.class} ${config.animate} border-l-4 p-4 mb-3 rounded-xl shadow-xl flex items-center transition-all duration-300" role="alert">
                <svg class="w-6 h-6 mr-3 flex-shrink-0 ${isWaiting ? "animate-spin" : ""}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${config.icon}
                </svg>
                <div class="flex-1 font-semibold text-sm">${message}</div>
                ${
                    !isWaiting
                        ? `
                    <button class="close-alert ml-auto pl-3 hover:opacity-70 transition-opacity">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>`
                        : ""
                }
            </div>
        `);

        this.container.append($alert);
        $alert.find(".close-alert").on("click", () => this.hide($alert));

        if (!isWaiting && duration > 0) {
            setTimeout(() => this.hide($alert), duration);
        }

        return $alert; // útil para poder cerrarla manualmente
    },

    hide($el) {
        if (!$el || !$el.length) return;
        $el.addClass("opacity-0 -translate-y-2");
        setTimeout(() => $el.remove(), 300);
    },

    hideAll() {
        this.container?.find(".alert-item").each((_, el) => this.hide($(el)));
    },
};

// Aliases globales — úsalos en cualquier página
window.showSuccess = (msg, duration) =>
    AlertService.show("success", msg, duration);
window.showError = (msg, duration) => AlertService.show("error", msg, duration);
window.showWarning = (msg, duration) =>
    AlertService.show("warning", msg, duration);
window.showWaiting = (msg) => AlertService.show("waiting", msg, 0);
window.hideAlerts = () => AlertService.hideAll();

// handleAjaxError también global
window.handleAjaxError = function (xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors ?? {};
        $(".error-message").addClass("hidden").text("");

        Object.entries(errors).forEach(([field, messages]) => {
            const $span = $(`.error-message[data-error-for="${field}"]`);
            if ($span.length) {
                $span
                    .text(messages[0])
                    .removeClass("hidden")
                    .addClass("animate-pulse");
                setTimeout(() => $span.addClass("hidden").text(""), 4000);
            }
        });

        showError("Por favor, corrige los errores.");
    } else {
        const map = {
            403: "Sin permisos para esta acción",
            404: "Recurso no encontrado",
            500: "Error del servidor",
        };
        showError(
            xhr.responseJSON?.message ?? map[xhr.status] ?? "Error inesperado",
        );
    }
};
