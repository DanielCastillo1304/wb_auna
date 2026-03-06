$(document).ready(function () {
    // MENU
    $(".toggle-section").on("click", function (e) {
        e.preventDefault();

        if (this.dataset.fixed === "true") return;

        const $btn = $(this);
        const $section = $($btn.attr("href"));
        const isOpen = $section.hasClass("open");

        // Cerrar otros (acordeón)
        $(".content-section.open")
            .not($section)
            .each(function () {
                const $s = $(this);
                const $p = $(`a[href="#${this.id}"]`);

                if ($p.data("fixed") !== true) {
                    closeSection($s, $p);
                }
            });

        // Toggle actual
        isOpen ? closeSection($section, $btn) : openSection($section, $btn);
    });

    function openSection($section, $btn) {
        const height = $section
            .css({ display: "block", height: "auto", opacity: 0 })
            .outerHeight();

        $section
            .height(0)
            .addClass("open")
            .stop(true)
            .animate(
                { height, opacity: 1 },
                { duration: 200, easing: "swing" },
            );

        $btn.addClass("active-menu text-white");
    }

    function closeSection($section, $btn) {
        $section.stop(true).animate(
            { height: 0, opacity: 0 },
            {
                duration: 200,
                easing: "swing",
                complete() {
                    $section.removeClass("open").hide().css("height", "");
                },
            },
        );

        $btn.removeClass("active-menu text-white");
    }

    // SIDEBAR
    let sidebarVisible = true;

    $(".menubar").click(function () {
        if (sidebarVisible) {
            // Ocultar el sidebar
            $(".sidebar-panel").css("transform", "translateX(-260px)");
            $(".main-panel").css("margin-left", "0px");
            $(this).find("span").text("close");
        } else {
            // Mostrar el sidebar
            $(".sidebar-panel").css("transform", "translateX(0)");
            $(".main-panel").css("margin-left", "260px");
            $(this).find("span").text("menu");
        }
        sidebarVisible = !sidebarVisible;
    });

    // Dropdown de Usuario
    $(".box-user").on("click", function (e) {
        e.stopPropagation();
        $(".box-user-collapse").fadeToggle(200);
        $(".box-filial-collapse").fadeOut(100); // Cerrar el otro
    });
});
