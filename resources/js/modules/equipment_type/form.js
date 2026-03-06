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

    // $("#logo").on("change", function () {
    //     handlePhotoPreview(this);
    // });
}
