$(document).ready(function () {
    CONFIG.baseUrl = `/${controller}`;
    setupEventListeners();
    setupUnsavedChangesWarning();
});

function setupEventListeners() {
    $("#mainForm").on("submit", (e) => {
        e.preventDefault();
        handleSubmit();
    });
}
