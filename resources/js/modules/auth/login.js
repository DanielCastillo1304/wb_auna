/**
 * UI Utilities — Login Page
 */
const UI = {
    resetErrors() {
        $(".alert-error").fadeOut(300);
        $("input").removeClass("border-red-600 ring-4 ring-red-600/10");
        $(".credential-error").text("").addClass("hidden");
    },

    showInputError(field, message) {
        $(`#${field}`)
            .addClass("border-red-600 ring-4 ring-red-600/10")
            .attr("aria-invalid", "true");

        $(`#alert-${field}`)
            .stop(true)
            .fadeIn(300)
            .text(message)
            .attr("role", "alert");
    },

    setButtonLoading($btn, isLoading, originalHTML) {
        const spinnerHTML = `
            <span class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Validando...
            </span>`;

        $btn.prop("disabled", isLoading).html(
            isLoading ? spinnerHTML : originalHTML,
        );
    },
};

/**
 * Validación del formulario de login
 * @returns {boolean} true si es válido
 */
function validateLoginForm() {
    let isValid = true;

    const fields = [
        { id: "username", message: "El nombre de usuario es obligatorio" },
        { id: "password", message: "La contraseña es obligatoria" },
    ];

    fields.forEach(({ id, message }) => {
        if (!$(`#${id}`).val().trim()) {
            UI.showInputError(id, message);
            isValid = false;
        }
    });

    return isValid;
}

/**
 * Muestra un error popup en el contenedor .credential-error
 * @param {string|null} message
 */
window.showPopupError = function (message) {
    const $container = $(".credential-error");

    if (!message) {
        $container.fadeOut(300, () => $container.addClass("hidden"));
        return;
    }

    $container.text(message).removeClass("hidden").hide().fadeIn(300);

    clearTimeout($container.data("hideTimeout"));
    $container.data(
        "hideTimeout",
        setTimeout(() => {
            $container.fadeOut(300, () => $container.addClass("hidden"));
        }, 4000),
    );
};

window.submitForm = function (url, form, $button, onSuccess) {
    const originalHTML = $button.html();

    $.ajax({
        url,
        type: "POST",
        data: new FormData(form),
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success(response) {
            onSuccess(response);
        },
        error(xhr) {
            UI.setButtonLoading($button, false, originalHTML);

            if (xhr.status === 422) {
                const errors = xhr.responseJSON?.errors ?? {};
                UI.resetErrors();
                Object.entries(errors).forEach(([field, messages]) => {
                    UI.showInputError(field, messages[0]);
                });
            } else if (xhr.status === 429) {
                showPopupError(
                    "Demasiados intentos. Espere un momento e intente de nuevo.",
                );
            } else {
                showPopupError(
                    "Ocurrió un error inesperado. Intente de nuevo.",
                );
            }
        },
    });
};

$(document).ready(function () {
    toggleLoading(true);
    setTimeout(() => toggleLoading(false), 600);

    $("input").on("input", function () {
        const id = $(this).attr("id");
        $(this)
            .removeClass("border-red-600 ring-4 ring-red-600/10")
            .removeAttr("aria-invalid");
        $(`#alert-${id}`).fadeOut(300);
    });

    $(".submit-login").on("click", function (e) {
        e.preventDefault();

        UI.resetErrors();

        if (!validateLoginForm()) return;

        const $btn = $(this);
        const originalHTML = $btn.html();

        UI.setButtonLoading($btn, true);

        submitForm(route("login.post"), $(".form")[0], $btn, function (res) {
            if (res.code === 200) {
                showSuccess("Bienvenido, redirigiendo...");
                setTimeout(() => {
                    window.location.href = res.redirect;
                }, 800);
            } else {
                UI.setButtonLoading($btn, false, originalHTML);
                showError(res.msg ?? "Credenciales incorrectas.");
            }
        });
    });
});
