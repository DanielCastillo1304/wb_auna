/******/ (() => { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/modules/disk_capacity/form.js ***!
  \****************************************************/
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