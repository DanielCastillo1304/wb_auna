$(document).ready(function () {
    CONFIG.baseUrl = `/${controller}`;
    CONFIG.recordsPerPage = 15;

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

/** RENDERIZAR FILA — específico de personal **/
window.createTableRow = function (record, rowNumber) {
    const sexoBadge = record.sexo
        ? `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold"
                style="background: ${record.sexo.toLowerCase() === "femenino" ? "rgba(236,72,153,0.08)" : "rgba(59,130,246,0.08)"}; 
                       color: ${record.sexo.toLowerCase() === "femenino" ? "rgb(219,39,119)" : "rgb(37,99,235)"};">
               ${record.sexo}
           </span>`
        : "—";

    const fechaIng = record.fec_ing
        ? new Date(record.fec_ing + "T00:00:00").toLocaleDateString("es-PE", {
              day: "2-digit",
              month: "short",
              year: "numeric",
          })
        : "—";

    return `
        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.1s;"
            onmouseover="this.style.background='#f8fafc'"
            onmouseout="this.style.background=''">

            <td class="px-5 py-3">
                <span class="text-xs font-bold tabular-nums text-slate-400">
                    ${String(rowNumber).padStart(2, "0")}
                </span>
            </td>

            <td class="px-5 py-3">
                <div class="flex flex-col gap-0.5">
                    <span class="text-sm font-semibold text-slate-700">${record.ape_nom || "—"}</span>
                    <span class="text-[11px] text-slate-400">${record.dni || "Sin DNI"}</span>
                </div>
            </td>

            <td class="px-5 py-3">
                <span class="text-xs text-slate-600 font-mono">${record.dni || "—"}</span>
            </td>

            <td class="px-5 py-3">
                <span class="text-xs text-slate-600">${record.cargo || "—"}</span>
            </td>

            <td class="px-5 py-3">
                <span class="text-xs text-slate-600">${record.area_n4 || "—"}</span>
            </td>

            <td class="px-5 py-3">
                <span class="text-xs text-slate-500">${record.sede || "—"}</span>
            </td>

            <td class="px-5 py-3">
                <span class="text-xs text-slate-500">${fechaIng}</span>
            </td>

            <td class="px-5 py-3">
                <div class="flex justify-end gap-1.5">
                    <button class="btn-edit w-7 h-7 flex items-center justify-center rounded transition-all"
                            style="color: #64748b;"
                            onmouseover="this.style.background='rgba(0,176,202,0.08)'; this.style.color='rgb(0,140,165)';"
                            onmouseout="this.style.background=''; this.style.color='#64748b';"
                            data-id="${record.codpersonal}"
                            title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="btn-delete w-7 h-7 flex items-center justify-center rounded transition-all"
                            style="color: #94a3b8;"
                            onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.color='rgb(220,50,50)';"
                            onmouseout="this.style.background=''; this.style.color='#94a3b8';"
                            data-id="${record.codpersonal}"
                            title="Eliminar">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>
            </td>
        </tr>
    `;
};
