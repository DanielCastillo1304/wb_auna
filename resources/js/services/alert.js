/**
 * AlertService — Global
 * Disponible en window.AlertService
 */
window.AlertService = {
    container: null,

    init() {
        if (!$("#alertContainer").length) {
            $("body").prepend(
                '<div id="alertContainer" class="fixed top-4 right-4 z-[200] w-full max-w-sm space-y-2 pointer-events-none"></div>',
            );
        }
        this.container = $("#alertContainer");
    },

    types: {
        success: {
            icon: "check_circle",
            iconColor: "rgb(0,176,202)",
            borderColor: "rgba(0,176,202,0.3)",
            bgColor: "rgba(0,176,202,0.06)",
            textColor: "#0a4a5a",
            accentColor: "rgb(0,176,202)",
        },
        error: {
            icon: "error",
            iconColor: "rgb(220,50,50)",
            borderColor: "rgba(220,50,50,0.25)",
            bgColor: "rgba(220,50,50,0.05)",
            textColor: "#5a0a0a",
            accentColor: "rgb(220,50,50)",
        },
        warning: {
            icon: "warning",
            iconColor: "rgb(217,119,6)",
            borderColor: "rgba(245,158,11,0.25)",
            bgColor: "rgba(245,158,11,0.05)",
            textColor: "#5a3a0a",
            accentColor: "rgb(217,119,6)",
        },
        waiting: {
            icon: "sync",
            iconColor: "rgb(190,214,0)",
            borderColor: "rgba(190,214,0,0.3)",
            bgColor: "rgba(190,214,0,0.05)",
            textColor: "#3a4a00",
            accentColor: "rgb(190,214,0)",
        },
    },

    show(type, message, duration = 4000) {
        this.init();

        const config = this.types[type] ?? this.types.error;
        const isWaiting = type === "waiting";
        const id = `alert-${Date.now()}`;

        const $alert = $(`
                <div id="${id}"
                    class="alert-item pointer-events-auto"
                    style="
                        background: white;
                        border: 1px solid ${config.borderColor};
                        border-left: 3px solid ${config.accentColor};
                        border-radius: 10px;
                        box-shadow: 0 4px 16px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
                        padding: 12px 14px;
                        display: flex;
                        align-items: flex-start;
                        gap: 10px;
                        opacity: 0;
                        transform: translateX(16px);
                        transition: opacity 0.25s ease, transform 0.25s ease;
                    "
                    role="alert">

                    <span class="material-symbols-outlined flex-shrink-0 ${isWaiting ? "animate-spin" : ""}"
                        style="font-size: 18px; color: ${config.iconColor}; margin-top: 1px;">
                        ${config.icon}
                    </span>

                    <div class="flex-1 min-w-0">
                        <p style="font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.4; margin: 0;">
                            ${message}
                        </p>
                    </div>

                    ${
                        !isWaiting
                            ? `
                    <button class="close-alert flex-shrink-0 w-5 h-5 flex items-center justify-center rounded transition-all"
                            style="color: #94a3b8; margin-top: 1px;"
                            onmouseover="this.style.background='#f1f5f9'; this.style.color='#475569';"
                            onmouseout="this.style.background=''; this.style.color='#94a3b8';">
                        <span class="material-symbols-outlined" style="font-size: 15px;">close</span>
                    </button>`
                            : ""
                    }
                </div>
            `);

        this.container.append($alert);

        // Animar entrada
        requestAnimationFrame(() => {
            $alert.css({ opacity: "1", transform: "translateX(0)" });
        });

        // Click en cerrar
        $alert.find(".close-alert").on("click", () => this._hide($alert));

        // Auto-dismiss
        if (!isWaiting && duration > 0) {
            const timer = setTimeout(() => this._hide($alert), duration);
            $alert.data("timer", timer);

            // Barra de progreso
            const $bar = $(`
                <div style="
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 2px;
                    width: 100%;
                    background: ${config.accentColor};
                    opacity: 0.4;
                    border-radius: 0 0 10px 10px;
                    transition: width ${duration}ms linear;
                "></div>
            `);
            $alert.css("position", "relative").append($bar);
            requestAnimationFrame(() => {
                setTimeout(() => $bar.css("width", "0%"), 50);
            });
        }

        return $alert;
    },

    _hide($el) {
        if (!$el || !$el.length) return;
        clearTimeout($el.data("timer"));
        $el.css({ opacity: "0", transform: "translateX(16px)" });
        setTimeout(() => {
            $el.css({ "max-height": $el.outerHeight(), overflow: "hidden" });
            $el.animate(
                { "max-height": 0, marginBottom: 0, padding: 0 },
                200,
                function () {
                    $el.remove();
                },
            );
        }, 250);
    },

    hideAll() {
        this.container?.find(".alert-item").each((_, el) => this._hide($(el)));
    },
};

// Aliases globales
window.showSuccess = (msg, duration) =>
    AlertService.show("success", msg, duration);
window.showError = (msg, duration) => AlertService.show("error", msg, duration);
window.showWarning = (msg, duration) =>
    AlertService.show("warning", msg, duration);
window.showWaiting = (msg) => AlertService.show("waiting", msg, 0);
window.hideAlerts = () => AlertService.hideAll();

// handleAjaxError global
window.handleAjaxError = function (xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors ?? {};
        $(".error-message").addClass("hidden").text("");

        Object.entries(errors).forEach(([field, messages]) => {
            const $span = $(`.error-message[data-error-for="${field}"]`);
            if ($span.length) {
                $span.text(messages[0]).removeClass("hidden");
                setTimeout(() => $span.addClass("hidden").text(""), 5000);
            }
        });

        showError("Por favor, corrige los errores marcados.");
    } else {
        const map = {
            401: "No autorizado",
            403: "Sin permisos para esta acción",
            404: "Recurso no encontrado",
            429: "Demasiados intentos, espera un momento",
            500: "Error interno del servidor",
        };
        showError(
            xhr.responseJSON?.message ?? map[xhr.status] ?? "Error inesperado",
        );
    }
};
