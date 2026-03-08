/******/ (() => { // webpackBootstrap
/*!***********************************************!*\
  !*** ./resources/js/modules/personal/form.js ***!
  \***********************************************/
$(document).ready(function () {
  CONFIG.baseUrl = "/".concat(controller);
  setupEventListeners();
  setupUnsavedChangesWarning();
});
function setupEventListeners() {
  $("#mainForm").on("submit", function (e) {
    e.preventDefault();
    handleSubmit();
  });
}
/******/ })()
;