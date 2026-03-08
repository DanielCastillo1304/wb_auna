/******/ (() => { // webpackBootstrap
/*!************************************************!*\
  !*** ./resources/js/modules/personal/index.js ***!
  \************************************************/
$(document).ready(function () {
  CONFIG.baseUrl = "/".concat(controller);
  CONFIG.recordsPerPage = 15;
  loadInitialRecords();
  setupEventListeners();
});
function setupEventListeners() {
  var $searchInput = $("#searchInput");
  var $btnClearSearch = $("#btnClearSearch");

  // Búsqueda en tiempo real
  $searchInput.on("input", function () {
    var keyword = $(this).val().trim();
    clearTimeout(window.searchTimer);
    $btnClearSearch.toggleClass("hidden", !keyword);
    searchTimer = setTimeout(function () {
      return performSearch(keyword);
    }, CONFIG.searchDelay);
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
  $("#btnCancelDelete").on("click", function () {
    return closeDeleteModal();
  });

  // Cerrar modal al click fuera
  $("#deleteModal").on("click", function (e) {
    if (e.target === this) closeDeleteModal();
  });

  // Acciones de tabla (delegación)
  $(document).on("click", ".btn-edit", function () {
    window.location.href = "".concat(CONFIG.baseUrl, "/form/").concat($(this).data("id"));
  });
  $(document).on("click", ".btn-delete", function () {
    openDeleteModal($(this).data("id"));
  });
}

/** RENDERIZAR FILA — específico de personal **/
window.createTableRow = function (record, rowNumber) {
  var sexoBadge = record.sexo ? "<span class=\"inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold\"\n                style=\"background: ".concat(record.sexo.toLowerCase() === "femenino" ? "rgba(236,72,153,0.08)" : "rgba(59,130,246,0.08)", "; \n                       color: ").concat(record.sexo.toLowerCase() === "femenino" ? "rgb(219,39,119)" : "rgb(37,99,235)", ";\">\n               ").concat(record.sexo, "\n           </span>") : "—";
  var fechaIng = record.fec_ing ? new Date(record.fec_ing + "T00:00:00").toLocaleDateString("es-PE", {
    day: "2-digit",
    month: "short",
    year: "numeric"
  }) : "—";
  return "\n        <tr style=\"border-bottom: 1px solid #f1f5f9; transition: background 0.1s;\"\n            onmouseover=\"this.style.background='#f8fafc'\"\n            onmouseout=\"this.style.background=''\">\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs font-bold tabular-nums text-slate-400\">\n                    ".concat(String(rowNumber).padStart(2, "0"), "\n                </span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <div class=\"flex flex-col gap-0.5\">\n                    <span class=\"text-sm font-semibold text-slate-700\">").concat(record.ape_nom || "—", "</span>\n                    <span class=\"text-[11px] text-slate-400\">").concat(record.dni || "Sin DNI", "</span>\n                </div>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs text-slate-600 font-mono\">").concat(record.dni || "—", "</span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs text-slate-600\">").concat(record.cargo || "—", "</span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs text-slate-600\">").concat(record.area_n4 || "—", "</span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs text-slate-500\">").concat(record.sede || "—", "</span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <span class=\"text-xs text-slate-500\">").concat(fechaIng, "</span>\n            </td>\n\n            <td class=\"px-5 py-3\">\n                <div class=\"flex justify-end gap-1.5\">\n                    <button class=\"btn-edit w-7 h-7 flex items-center justify-center rounded transition-all\"\n                            style=\"color: #64748b;\"\n                            onmouseover=\"this.style.background='rgba(0,176,202,0.08)'; this.style.color='rgb(0,140,165)';\"\n                            onmouseout=\"this.style.background=''; this.style.color='#64748b';\"\n                            data-id=\"").concat(record.codpersonal, "\"\n                            title=\"Editar\">\n                        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"\n                                  d=\"M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\"/>\n                        </svg>\n                    </button>\n                    <button class=\"btn-delete w-7 h-7 flex items-center justify-center rounded transition-all\"\n                            style=\"color: #94a3b8;\"\n                            onmouseover=\"this.style.background='rgba(239,68,68,0.08)'; this.style.color='rgb(220,50,50)';\"\n                            onmouseout=\"this.style.background=''; this.style.color='#94a3b8';\"\n                            data-id=\"").concat(record.codpersonal, "\"\n                            title=\"Eliminar\">\n                        <span class=\"material-symbols-outlined text-[16px]\">delete</span>\n                    </button>\n                </div>\n            </td>\n        </tr>\n    ");
};
/******/ })()
;