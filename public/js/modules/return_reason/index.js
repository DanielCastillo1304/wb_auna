/******/ (() => { // webpackBootstrap
/*!*****************************************************!*\
  !*** ./resources/js/modules/return_reason/index.js ***!
  \*****************************************************/
$(document).ready(function () {
  CONFIG.baseUrl = "/".concat(controller);
  CONFIG.recordsPerPage = 5;
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

/** RENDERIZAR FILA — específico de este módulo **/
window.createTableRow = function (record, rowNumber) {
  return "\n         <tr style=\"border-bottom: 1px solid #f1f5f9; transition: background 0.1s;\"\n            onmouseover=\"this.style.background='#f8fafc'\"\n            onmouseout=\"this.style.background=''\">\n            <td class=\"px-5 py-3 text-xs\">\n                <span class=\"text-xs font-bold tabular-nums text-slate-400\">\n                    ".concat(String(rowNumber).padStart(2, "0"), "\n                </span>\n            </td>\n\n            <td class=\"px-5 py-3 text-xs\">\n                <span class=\"text-sm font-medium text-slate-700\">\n                    ").concat(record.name || "—", "\n                </span>\n            </td>\n            <td class=\"px-6 py-4\">\n                <div class=\"flex justify-center gap-1.5\">\n                    <button class=\"btn-edit w-7 h-7 flex items-center justify-center rounded transition-all\"\n                            style=\"color: #64748b;\"\n                            onmouseover=\"this.style.background='rgba(0,176,202,0.08)'; this.style.color='rgb(0,140,165)';\"\n                            onmouseout=\"this.style.background=''; this.style.color='#64748b';\"\n                            data-id=\"").concat(record.codreturn_reason, "\"\n                            title=\"Eliminar\">\n                        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\"/>\n                        </svg>\n                    </button>\n                    <button class=\"btn-delete w-7 h-7 flex items-center justify-center rounded transition-all\"\n                            style=\"color: #94a3b8;\"\n                            onmouseover=\"this.style.background='rgba(239,68,68,0.08)'; this.style.color='rgb(220,50,50)';\"\n                            onmouseout=\"this.style.background=''; this.style.color='#94a3b8';\"\n                            data-id=\"").concat(record.codreturn_reason, "\"\n                            title=\"Eliminar\">\n                        <span class=\"material-symbols-outlined text-[16px]\">delete</span>\n                    </button>\n                </div>\n            </td>\n        </tr>\n    ");
};
/******/ })()
;