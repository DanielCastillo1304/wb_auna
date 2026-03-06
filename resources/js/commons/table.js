window.CONFIG = window.CONFIG || {
    recordsPerPage: 5,
    searchDelay: 500,
    baseUrl: "",
};

window.STATE = window.STATE || {
    currentPage: 1,
    totalPages: 1,
    isLoading: false,
    searchKeyword: null,
    deleteId: null,
    resetId: null,
};
window.searchTimer = null;
window.totalRecords = window.totalRecords || 0;

/** HELPERS DOM — se resuelven en el momento de uso **/
function $tableBody() {
    return $("#tableBody");
}
function $loadingSpinner() {
    return $("#loadingSpinner");
}
function $noResults() {
    return $("#noResults");
}
function $paginationContainer() {
    return $("#paginationContainer");
}
function $totalRecordsSpan() {
    return $("#totalRecords");
}

/** CARGA INICIAL **/
window.loadInitialRecords = function () {
    if (countRecords > 0) {
        loadRecords(0, CONFIG.recordsPerPage);
    } else {
        showNoResults();
    }
};

/** BÚSQUEDA **/
window.performSearch = function (keyword) {
    if (!keyword) {
        STATE.searchKeyword = null;
        STATE.currentPage = 1;
        loadRecords(0, CONFIG.recordsPerPage);
        return;
    }

    STATE.searchKeyword = keyword;
    STATE.currentPage = 1;
    showLoading();

    $.ajax({
        url: `${CONFIG.baseUrl}/search`,
        method: "POST",
        data: {
            keyword,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success(response) {
            if (response.success) {
                totalRecords = response.total;
                updateTotalRecords();
                renderRecords(response.data);
                updatePagination();
            } else {
                showError("Error al buscar registros");
            }
        },
        error: (xhr) => handleAjaxError(xhr),
        complete: () => hideLoading(),
    });
};

/** CARGA DE REGISTROS **/
window.loadRecords = function (from, to) {
    if (STATE.isLoading) return;

    STATE.isLoading = true;
    showLoading();
    $.ajax({
        url: `${CONFIG.baseUrl}/records/${from}/${to}/${STATE.searchKeyword ?? "null"}`,
        method: "GET",
        success(response) {
            if (response.success) {
                totalRecords = response.total;
                updateTotalRecords();
                renderRecords(response.data);
                updatePagination();
            } else {
                showError("Error al cargar registros");
            }
        },
        error: (xhr) => handleAjaxError(xhr),
        complete() {
            STATE.isLoading = false;
            hideLoading();
        },
    });
};

/** RENDERIZAR TABLA **/
window.renderRecords = function (data) {
    $tableBody().empty();

    if (!data?.length) {
        showNoResults();
        return;
    }

    hideNoResults();
    data.forEach((record, index) => {
        const rowNumber =
            (STATE.currentPage - 1) * CONFIG.recordsPerPage + index + 1;
        $tableBody().append(createTableRow(record, rowNumber));
    });
    if (STATE.selectedIds) {
        $(".journalist-checkbox").each(function () {
            $(this).prop("checked", STATE.selectedIds.has($(this).data("id")));
        });
    }

    if (typeof updateSelectionUI === "function") updateSelectionUI();
};

/** PAGINACIÓN **/
window.updatePagination = function () {
    STATE.totalPages = Math.ceil(totalRecords / CONFIG.recordsPerPage);

    if (STATE.totalPages <= 1) {
        $paginationContainer().html("");
        return;
    }

    const startPage = Math.max(1, STATE.currentPage - 2);
    const endPage = Math.min(STATE.totalPages, STATE.currentPage + 2);

    const btnBase =
        "px-3 py-1.5 rounded-lg text-xs font-bold border transition-all";
    const btnActive = "bg-slate-900 text-white border-slate-900";
    const btnDefault =
        "bg-white text-slate-700 hover:bg-slate-900 hover:text-white border-slate-200";
    const btnDisabled =
        "bg-slate-100 text-slate-400 cursor-not-allowed border-transparent";

    const prevDisabled = STATE.currentPage === 1;
    const nextDisabled = STATE.currentPage === STATE.totalPages;

    let pages = `
        <button class="${btnBase} ${prevDisabled ? btnDisabled : btnDefault}"
            ${prevDisabled ? "disabled" : `onclick="goToPage(${STATE.currentPage - 1})"`}>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>`;

    if (startPage > 1) {
        pages += `<button class="${btnBase} ${btnDefault}" onclick="goToPage(1)">1</button>`;
        if (startPage > 2)
            pages += `<span class="px-2 py-1 text-slate-400">…</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
        pages += `
            <button class="${btnBase} ${i === STATE.currentPage ? btnActive : btnDefault}"
                onclick="goToPage(${i})">${i}</button>`;
    }

    if (endPage < STATE.totalPages) {
        if (endPage < STATE.totalPages - 1)
            pages += `<span class="px-2 py-1 text-slate-400">…</span>`;
        pages += `<button class="${btnBase} ${btnDefault}" onclick="goToPage(${STATE.totalPages})">${STATE.totalPages}</button>`;
    }

    pages += `
        <button class="${btnBase} ${nextDisabled ? btnDisabled : btnDefault}"
            ${nextDisabled ? "disabled" : `onclick="goToPage(${STATE.currentPage + 1})"`}>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>`;

    const from = (STATE.currentPage - 1) * CONFIG.recordsPerPage + 1;
    const to = Math.min(
        STATE.currentPage * CONFIG.recordsPerPage,
        totalRecords,
    );

    $paginationContainer().html(`
        <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-between gap-3">
            <p class="text-sm text-gray-500 text-center md:text-left">
                Mostrando <span class="font-semibold text-slate-700">${from}</span>
                a <span class="font-semibold text-slate-700">${to}</span>
                de <span class="font-semibold text-slate-700">${totalRecords}</span> registros
            </p>
            <div class="flex gap-1.5 items-center justify-center">${pages}</div>
        </div>
    `);
};

/** NAVEGACIÓN DE PÁGINAS **/
window.goToPage = function (page) {
    if (page < 1 || page > STATE.totalPages || page === STATE.currentPage)
        return;

    STATE.currentPage = page;
    loadRecords(
        (page - 1) * CONFIG.recordsPerPage,
        page * CONFIG.recordsPerPage,
    );

    const $container = $("#recordContainer");
    if ($container.length) {
        $("html, body").animate(
            { scrollTop: $container.offset().top - 100 },
            300,
        );
    }
};

/** LOADING **/
window.showLoading = function () {
    $loadingSpinner().removeClass("hidden");
    $tableBody()
        .closest(".overflow-x-auto")
        .removeClass("hidden")
        .addClass("opacity-30 pointer-events-none");
    $noResults().addClass("hidden").removeClass("flex");
};

window.hideLoading = function () {
    $loadingSpinner().addClass("hidden");
    $tableBody()
        .closest(".overflow-x-auto")
        .removeClass("opacity-30 pointer-events-none");
};

window.showNoResults = function () {
    hideLoading();
    $tableBody().closest(".overflow-x-auto").addClass("hidden");
    $noResults().removeClass("hidden").addClass("flex");
    $paginationContainer().addClass("hidden");
};

window.hideNoResults = function () {
    $noResults().addClass("hidden").removeClass("flex");
    $tableBody().closest(".overflow-x-auto").removeClass("hidden");
    $paginationContainer().removeClass("hidden");
};

window.updateTotalRecords = function () {
    $totalRecordsSpan().text(totalRecords);
};

/** ELIMINAR REGISTRO **/
window.deleteRecord = function (id) {
    $.ajax({
        url: `${CONFIG.baseUrl}/destroy/${id}`,
        method: "DELETE",
        data: { _token: $('meta[name="csrf-token"]').attr("content") },
        success(response) {
            if (response.success) {
                showSuccess(
                    response.message || "Registro eliminado correctamente",
                );
                totalRecords = response.totalRecords;
                updateTotalRecords();
                loadRecords(
                    (STATE.currentPage - 1) * CONFIG.recordsPerPage,
                    STATE.currentPage * CONFIG.recordsPerPage,
                );
            } else {
                showError(response.message || "Error al eliminar registro");
            }
        },
        error: (xhr) => handleAjaxError(xhr),
        complete: () => closeDeleteModal(),
    });
};

/** RESTABLECER REGISTRO **/
window.resetRecord = function (id) {
    $.ajax({
        url: `${CONFIG.baseUrl}/reset/${id}`,
        method: "POST",
        data: { _token: $('meta[name="csrf-token"]').attr("content") },
        success(response) {
            if (response.success) {
                showSuccess(
                    response.message || "Registro restablecido correctamente",
                );
                totalRecords = response.totalRecords;
                updateTotalRecords();
                loadRecords(
                    (STATE.currentPage - 1) * CONFIG.recordsPerPage,
                    STATE.currentPage * CONFIG.recordsPerPage,
                );
            } else {
                showError(response.message || "Error al restablecer registro");
            }
        },
        error: (xhr) => handleAjaxError(xhr),
        complete: () => closeResetModal(),
    });
};

/** MODAL ELIMINAR **/
window.openDeleteModal = function (id) {
    STATE.deleteId = id;
    _openModal("#deleteModal");
};

window.closeDeleteModal = function () {
    _closeModal("#deleteModal");
    setTimeout(() => {
        STATE.deleteId = null;
    }, 300);
};

/** MODAL RESET **/
window.openResetModal = function (id) {
    STATE.resetId = id;
    _openModal("#resetModal");
};

window.closeResetModal = function () {
    _closeModal("#resetModal");
    setTimeout(() => {
        STATE.resetId = null;
    }, 300);
};

/** HELPERS INTERNOS DE MODAL **/
function _openModal(selector) {
    const $modal = $(selector);
    $modal.removeClass("opacity-0 pointer-events-none").addClass("opacity-100");
    $modal
        .find(".modal-content")
        .removeClass("scale-95 opacity-0")
        .addClass("scale-100 opacity-100");
}

function _closeModal(selector) {
    const $modal = $(selector);
    $modal
        .find(".modal-content")
        .removeClass("scale-100 opacity-100")
        .addClass("scale-95 opacity-0");
    $modal.removeClass("opacity-100").addClass("opacity-0 pointer-events-none");
}
