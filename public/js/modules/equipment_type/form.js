/******/ (() => { // webpackBootstrap
/*!*****************************************************!*\
  !*** ./resources/js/modules/equipment_type/form.js ***!
  \*****************************************************/
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

  // $("#logo").on("change", function () {
  //     handlePhotoPreview(this);
  // });
}
/******/ })()
;