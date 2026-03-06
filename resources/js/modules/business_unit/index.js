$(document).ready(function () {
    CONFIG.baseUrl = `/${controller}`;
    CONFIG.recordsPerPage = 5;

    loadInitialRecords();
    setupEventListeners();
});

function setupEventListeners() {
    const $searchInput = $("#searchInput");
    const $btnClearSearch = $("#btnClearSearch");

    // Búsqueda en tiempo real
    $searchInput.on("input", function () {
        const keyword = $(this).val().trim();
        clearTimeout(window.searchTimer);
        $btnClearSearch.toggleClass("hidden", !keyword);

        searchTimer = setTimeout(
            () => performSearch(keyword),
            CONFIG.searchDelay,
        );
    });

    // Limpiar búsqueda
    $btnClearSearch.on("click", function () {
        $searchInput.val("");
        $btnClearSearch.addClass("hidden");
        STATE.searchKeyword = null;
        STATE.currentPage = 1;
        loadRecords(0, CONFIG.recordsPerPage);
    });

    // Confirmar eliminación
    $("#btnConfirmDelete").on("click", function () {
        if (STATE.deleteId) deleteRecord(STATE.deleteId);
    });

    // Cancelar eliminación
    $("#btnCancelDelete").on("click", () => closeDeleteModal());

    // Cerrar modal al click fuera
    $("#deleteModal").on("click", function (e) {
        if (e.target === this) closeDeleteModal();
    });

    // Acciones de tabla (delegación)
    $(document).on("click", ".btn-edit", function () {
        window.location.href = `${CONFIG.baseUrl}/form/${$(this).data("id")}`;
    });

    $(document).on("click", ".btn-delete", function () {
        openDeleteModal($(this).data("id"));
    });
}

/** RENDERIZAR FILA — específico de este módulo **/
window.createTableRow = function (record, rowNumber) {
    return `
        <tr class="hover:bg-slate-50 transition-colors duration-150">
            <td class="px-6 py-4">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-500 text-xs font-black tabular-nums">
                    ${rowNumber}
                </span>
            </td>
            <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                ${record.name || "-"}
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-center gap-1.5">
                    <button class="btn-edit p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all"
                            data-id="${record.codbusiness_unit}" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="btn-delete p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                            data-id="${record.codbusiness_unit}" title="Eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
};
