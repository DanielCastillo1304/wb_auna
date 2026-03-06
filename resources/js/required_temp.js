// required_temp.js

// 1️⃣ Definir primero
window.toggleLoading = function (show) {
    const $overlay = $("#loadingOverlay");
    const $content = $("#mainContent");

    if (show) {
        $overlay.removeClass("hidden").css("opacity", "1");
        $content.css({ filter: "blur(1px)", "pointer-events": "none" });
    } else {
        $overlay.css("opacity", "0");
        $content.css({ filter: "", "pointer-events": "" });

        setTimeout(() => $overlay.addClass("hidden"), 300);
    }
};

// 2️⃣ Llamar inmediatamente después de definirla
toggleLoading(true);

// 3️⃣ Config AJAX
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// 4️⃣ Ocultar cuando el DOM esté listo
$(document).ready(function () {
    setTimeout(() => toggleLoading(false), 400);
});
