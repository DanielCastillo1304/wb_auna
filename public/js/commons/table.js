/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./resources/js/commons/table.js ***!
  \***************************************/
window.CONFIG = window.CONFIG || {
  recordsPerPage: 5,
  searchDelay: 500,
  baseUrl: ""
};
window.STATE = window.STATE || {
  currentPage: 1,
  totalPages: 1,
  isLoading: false,
  searchKeyword: null,
  deleteId: null,
  resetId: null
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
    url: "".concat(CONFIG.baseUrl, "/search"),
    method: "POST",
    data: {
      keyword: keyword,
      _token: $('meta[name="csrf-token"]').attr("content")
    },
    success: function success(response) {
      if (response.success) {
        totalRecords = response.total;
        updateTotalRecords();
        renderRecords(response.data);
        updatePagination();
      } else {
        showError("Error al buscar registros");
      }
    },
    error: function error(xhr) {
      return handleAjaxError(xhr);
    },
    complete: function complete() {
      return hideLoading();
    }
  });
};

/** CARGA DE REGISTROS **/
window.loadRecords = function (from, to) {
  var _STATE$searchKeyword;
  if (STATE.isLoading) return;
  STATE.isLoading = true;
  showLoading();
  $.ajax({
    url: "".concat(CONFIG.baseUrl, "/records/").concat(from, "/").concat(to, "/").concat((_STATE$searchKeyword = STATE.searchKeyword) !== null && _STATE$searchKeyword !== void 0 ? _STATE$searchKeyword : "null"),
    method: "GET",
    success: function success(response) {
      if (response.success) {
        totalRecords = response.total;
        updateTotalRecords();
        renderRecords(response.data);
        updatePagination();
      } else {
        showError("Error al cargar registros");
      }
    },
    error: function error(xhr) {
      return handleAjaxError(xhr);
    },
    complete: function complete() {
      STATE.isLoading = false;
      hideLoading();
    }
  });
};

/** RENDERIZAR TABLA **/
window.renderRecords = function (data) {
  $tableBody().empty();
  if (!(data !== null && data !== void 0 && data.length)) {
    showNoResults();
    return;
  }
  hideNoResults();
  data.forEach(function (record, index) {
    var rowNumber = (STATE.currentPage - 1) * CONFIG.recordsPerPage + index + 1;
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
  var startPage = Math.max(1, STATE.currentPage - 2);
  var endPage = Math.min(STATE.totalPages, STATE.currentPage + 2);
  var btnBase = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-all";
  var btnActive = "bg-slate-900 text-white border-slate-900";
  var btnDefault = "bg-white text-slate-700 hover:bg-slate-900 hover:text-white border-slate-200";
  var btnDisabled = "bg-slate-100 text-slate-400 cursor-not-allowed border-transparent";
  var prevDisabled = STATE.currentPage === 1;
  var nextDisabled = STATE.currentPage === STATE.totalPages;
  var pages = "\n        <button class=\"".concat(btnBase, " ").concat(prevDisabled ? btnDisabled : btnDefault, "\"\n            ").concat(prevDisabled ? "disabled" : "onclick=\"goToPage(".concat(STATE.currentPage - 1, ")\""), ">\n            <svg class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2\">\n                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 19l-7-7 7-7\"/>\n            </svg>\n        </button>");
  if (startPage > 1) {
    pages += "<button class=\"".concat(btnBase, " ").concat(btnDefault, "\" onclick=\"goToPage(1)\">1</button>");
    if (startPage > 2) pages += "<span class=\"px-2 py-1 text-slate-400\">\u2026</span>";
  }
  for (var i = startPage; i <= endPage; i++) {
    pages += "\n            <button class=\"".concat(btnBase, " ").concat(i === STATE.currentPage ? btnActive : btnDefault, "\"\n                onclick=\"goToPage(").concat(i, ")\">").concat(i, "</button>");
  }
  if (endPage < STATE.totalPages) {
    if (endPage < STATE.totalPages - 1) pages += "<span class=\"px-2 py-1 text-slate-400\">\u2026</span>";
    pages += "<button class=\"".concat(btnBase, " ").concat(btnDefault, "\" onclick=\"goToPage(").concat(STATE.totalPages, ")\">").concat(STATE.totalPages, "</button>");
  }
  pages += "\n        <button class=\"".concat(btnBase, " ").concat(nextDisabled ? btnDisabled : btnDefault, "\"\n            ").concat(nextDisabled ? "disabled" : "onclick=\"goToPage(".concat(STATE.currentPage + 1, ")\""), ">\n            <svg class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2\">\n                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 5l7 7-7 7\"/>\n            </svg>\n        </button>");
  var from = (STATE.currentPage - 1) * CONFIG.recordsPerPage + 1;
  var to = Math.min(STATE.currentPage * CONFIG.recordsPerPage, totalRecords);
  $paginationContainer().html("\n        <div class=\"flex flex-col sm:flex-row sm:items-center justify-center sm:justify-between gap-3\">\n            <p class=\"text-sm text-gray-500 text-center md:text-left\">\n                Mostrando <span class=\"font-semibold text-slate-700\">".concat(from, "</span>\n                a <span class=\"font-semibold text-slate-700\">").concat(to, "</span>\n                de <span class=\"font-semibold text-slate-700\">").concat(totalRecords, "</span> registros\n            </p>\n            <div class=\"flex gap-1.5 items-center justify-center\">").concat(pages, "</div>\n        </div>\n    "));
};

/** NAVEGACIÓN DE PÁGINAS **/
window.goToPage = function (page) {
  if (page < 1 || page > STATE.totalPages || page === STATE.currentPage) return;
  STATE.currentPage = page;
  loadRecords((page - 1) * CONFIG.recordsPerPage, page * CONFIG.recordsPerPage);
  var $container = $("#recordContainer");
  if ($container.length) {
    $("html, body").animate({
      scrollTop: $container.offset().top - 100
    }, 300);
  }
};

/** LOADING **/
window.showLoading = function () {
  $loadingSpinner().removeClass("hidden");
  $tableBody().closest(".overflow-x-auto").removeClass("hidden").addClass("opacity-30 pointer-events-none");
  $noResults().addClass("hidden").removeClass("flex");
};
window.hideLoading = function () {
  $loadingSpinner().addClass("hidden");
  $tableBody().closest(".overflow-x-auto").removeClass("opacity-30 pointer-events-none");
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
    url: "".concat(CONFIG.baseUrl, "/destroy/").concat(id),
    method: "DELETE",
    data: {
      _token: $('meta[name="csrf-token"]').attr("content")
    },
    success: function success(response) {
      if (response.success) {
        showSuccess(response.message || "Registro eliminado correctamente");
        totalRecords = response.totalRecords;
        updateTotalRecords();
        loadRecords((STATE.currentPage - 1) * CONFIG.recordsPerPage, STATE.currentPage * CONFIG.recordsPerPage);
      } else {
        showError(response.message || "Error al eliminar registro");
      }
    },
    error: function error(xhr) {
      return handleAjaxError(xhr);
    },
    complete: function complete() {
      return closeDeleteModal();
    }
  });
};

/** RESTABLECER REGISTRO **/
window.resetRecord = function (id) {
  $.ajax({
    url: "".concat(CONFIG.baseUrl, "/reset/").concat(id),
    method: "POST",
    data: {
      _token: $('meta[name="csrf-token"]').attr("content")
    },
    success: function success(response) {
      if (response.success) {
        showSuccess(response.message || "Registro restablecido correctamente");
        totalRecords = response.totalRecords;
        updateTotalRecords();
        loadRecords((STATE.currentPage - 1) * CONFIG.recordsPerPage, STATE.currentPage * CONFIG.recordsPerPage);
      } else {
        showError(response.message || "Error al restablecer registro");
      }
    },
    error: function error(xhr) {
      return handleAjaxError(xhr);
    },
    complete: function complete() {
      return closeResetModal();
    }
  });
};

/** MODAL ELIMINAR **/
window.openDeleteModal = function (id) {
  STATE.deleteId = id;
  _openModal("#deleteModal");
};
window.closeDeleteModal = function () {
  _closeModal("#deleteModal");
  setTimeout(function () {
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
  setTimeout(function () {
    STATE.resetId = null;
  }, 300);
};

/** HELPERS INTERNOS DE MODAL **/
function _openModal(selector) {
  var $modal = $(selector);
  $modal.removeClass("opacity-0 pointer-events-none").addClass("opacity-100");
  $modal.find(".modal-content").removeClass("scale-95 opacity-0").addClass("scale-100 opacity-100");
}
function _closeModal(selector) {
  var $modal = $(selector);
  $modal.find(".modal-content").removeClass("scale-100 opacity-100").addClass("scale-95 opacity-0");
  $modal.removeClass("opacity-100").addClass("opacity-0 pointer-events-none");
}
/******/ })()
;