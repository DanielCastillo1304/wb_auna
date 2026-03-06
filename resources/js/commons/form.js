/** ESTADO Y CONFIGURACIÓN GLOBAL **/
window.CONFIG = window.CONFIG || {
    baseUrl: "",
    baseUrlActions: "/actions",
    maxFileSize: 2 * 1024 * 1024,
    allowedImageTypes: ["image/jpeg", "image/jpg", "image/png"],
};

window.STATE = window.STATE || {
    isSubmitting: false,
    hasChanges: false,
    originalData: {},
};

/** SUBMIT GLOBAL **/
window.handleSubmit = function () {
    if (STATE.isSubmitting) return;

    clearErrors();

    if (typeof validateForm === "function" && !validateForm()) {
        showError(
            "Por favor, completa todos los campos requeridos correctamente",
        );
        return;
    }

    const recordId = $("#recordId").val();
    const url = recordId
        ? `${CONFIG.baseUrl}/store/${recordId}`
        : `${CONFIG.baseUrl}/store`;

    STATE.isSubmitting = true;
    setSubmitButton(true);

    $.ajax({
        url,
        method: "POST",
        data: new FormData($("#mainForm")[0]),
        processData: false,
        contentType: false,
        success(response) {
            if (response.success) {
                STATE.hasChanges = false;
                showSuccess(
                    response.message || "Registro guardado correctamente",
                );
                setTimeout(() => {
                    // Si el módulo define onSubmitSuccess, lo ejecuta
                    // sino redirige por defecto a /list
                    if (typeof onSubmitSuccess === "function") {
                        onSubmitSuccess(response);
                    } else {
                        window.location.href = `${CONFIG.baseUrl}/list`;
                    }
                }, 1500);
            } else {
                showError(response.message || "Error al guardar el registro");
            }
        },
        error: (xhr) => handleAjaxError(xhr),
        complete() {
            STATE.isSubmitting = false;
            setSubmitButton(false);
        },
    });
};

/** BOTÓN SUBMIT **/
window.setSubmitButton = function (loading) {
    const $btn = $("#btnSubmit");
    const $btnText = $("#btnSubmitText");

    const spinnerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2 btn-spinner" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>`;

    if (loading) {
        $btn.prop("disabled", true).prepend(spinnerHTML);
        $btnText.text("Guardando...");
    } else {
        $btn.prop("disabled", false).find(".btn-spinner").remove();
        $btnText.text($("#recordId").val() ? "Actualizar" : "Guardar");
    }
};

/** ERRORES DE CAMPO **/
window.showFieldError = function ($field, message) {
    $field.addClass("border-red-500 focus:ring-red-500");
    $field.next(".error-message").text(message).removeClass("hidden");
};

window.clearFieldError = function ($field) {
    $field.removeClass("border-red-500 focus:ring-red-500");
    $field.next(".error-message").text("").addClass("hidden");
};

window.clearErrors = function () {
    $("#mainForm")
        .find("input, select, textarea")
        .removeClass("border-red-500 focus:ring-red-500");
    $("#mainForm").find(".error-message").text("").addClass("hidden");
    hideAlerts();
};

/** VALIDADORES COMUNES **/
window.validateEmail = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
window.validatePhone = (v) => /^[0-9+\-\s()]{7,20}$/.test(v);
window.validateIdentifyNumber = (v) => /^[A-Z0-9]{6,20}$/i.test(v);

window.validatePhoto = function (file) {
    if (!CONFIG.allowedImageTypes?.includes(file.type)) {
        showError("Solo se permiten imágenes JPG o PNG");
        $("#logo").val("");
        return false;
    }
    if (file.size > (CONFIG.maxFileSize ?? 2 * 1024 * 1024)) {
        showError("La imagen no debe superar los 2MB");
        $("#logo").val("");
        return false;
    }
    return true;
};

/** PREVIEW DE FOTO **/
window.handlePhotoPreview = function (input) {
    if (!input.files?.[0]) return;
    const file = input.files[0];
    if (!validatePhoto(file)) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        $("#photoPreview").html(
            `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`,
        );
    };
    reader.readAsDataURL(file);
    STATE.hasChanges = true;
};

/** ADVERTENCIA DE CAMBIOS NO GUARDADOS **/
window.setupUnsavedChangesWarning = function () {
    $(window).on("beforeunload", function (e) {
        if (STATE.hasChanges && !STATE.isSubmitting) {
            e.preventDefault();
            return "Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?";
        }
    });
};
