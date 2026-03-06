/******/ (() => { // webpackBootstrap
/*!********************************************!*\
  !*** ./resources/js/modules/auth/login.js ***!
  \********************************************/
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
/**
 * UI Utilities — Login Page
 */
var UI = {
  resetErrors: function resetErrors() {
    $(".alert-error").fadeOut(300);
    $("input").removeClass("border-red-600 ring-4 ring-red-600/10");
    $(".credential-error").text("").addClass("hidden");
  },
  showInputError: function showInputError(field, message) {
    $("#".concat(field)).addClass("border-red-600 ring-4 ring-red-600/10").attr("aria-invalid", "true");
    $("#alert-".concat(field)).stop(true).fadeIn(300).text(message).attr("role", "alert");
  },
  setButtonLoading: function setButtonLoading($btn, isLoading, originalHTML) {
    var spinnerHTML = "\n            <span class=\"flex items-center justify-center gap-2\">\n                <svg class=\"animate-spin h-4 w-4 text-white\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\">\n                    <circle class=\"opacity-25\" cx=\"12\" cy=\"12\" r=\"10\" stroke=\"currentColor\" stroke-width=\"4\"></circle>\n                    <path class=\"opacity-75\" fill=\"currentColor\" d=\"M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\"></path>\n                </svg>\n                Validando...\n            </span>";
    $btn.prop("disabled", isLoading).html(isLoading ? spinnerHTML : originalHTML);
  }
};

/**
 * Validación del formulario de login
 * @returns {boolean} true si es válido
 */
function validateLoginForm() {
  var isValid = true;
  var fields = [{
    id: "username",
    message: "El nombre de usuario es obligatorio"
  }, {
    id: "password",
    message: "La contraseña es obligatoria"
  }];
  fields.forEach(function (_ref) {
    var id = _ref.id,
      message = _ref.message;
    if (!$("#".concat(id)).val().trim()) {
      UI.showInputError(id, message);
      isValid = false;
    }
  });
  return isValid;
}

/**
 * Muestra un error popup en el contenedor .credential-error
 * @param {string|null} message
 */
window.showPopupError = function (message) {
  var $container = $(".credential-error");
  if (!message) {
    $container.fadeOut(300, function () {
      return $container.addClass("hidden");
    });
    return;
  }
  $container.text(message).removeClass("hidden").hide().fadeIn(300);
  clearTimeout($container.data("hideTimeout"));
  $container.data("hideTimeout", setTimeout(function () {
    $container.fadeOut(300, function () {
      return $container.addClass("hidden");
    });
  }, 4000));
};
window.submitForm = function (url, form, $button, onSuccess) {
  var originalHTML = $button.html();
  $.ajax({
    url: url,
    type: "POST",
    data: new FormData(form),
    processData: false,
    contentType: false,
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    success: function success(response) {
      onSuccess(response);
    },
    error: function error(xhr) {
      UI.setButtonLoading($button, false, originalHTML);
      if (xhr.status === 422) {
        var _xhr$responseJSON$err, _xhr$responseJSON;
        var errors = (_xhr$responseJSON$err = (_xhr$responseJSON = xhr.responseJSON) === null || _xhr$responseJSON === void 0 ? void 0 : _xhr$responseJSON.errors) !== null && _xhr$responseJSON$err !== void 0 ? _xhr$responseJSON$err : {};
        UI.resetErrors();
        Object.entries(errors).forEach(function (_ref2) {
          var _ref3 = _slicedToArray(_ref2, 2),
            field = _ref3[0],
            messages = _ref3[1];
          UI.showInputError(field, messages[0]);
        });
      } else if (xhr.status === 429) {
        showPopupError("Demasiados intentos. Espere un momento e intente de nuevo.");
      } else {
        showPopupError("Ocurrió un error inesperado. Intente de nuevo.");
      }
    }
  });
};
$(document).ready(function () {
  toggleLoading(true);
  setTimeout(function () {
    return toggleLoading(false);
  }, 600);
  $("input").on("input", function () {
    var id = $(this).attr("id");
    $(this).removeClass("border-red-600 ring-4 ring-red-600/10").removeAttr("aria-invalid");
    $("#alert-".concat(id)).fadeOut(300);
  });
  $(".submit-login").on("click", function (e) {
    e.preventDefault();
    UI.resetErrors();
    if (!validateLoginForm()) return;
    var $btn = $(this);
    var originalHTML = $btn.html();
    UI.setButtonLoading($btn, true);
    submitForm(route("login.post"), $(".form")[0], $btn, function (res) {
      if (res.code === 200) {
        showSuccess("Bienvenido, redirigiendo...");
        setTimeout(function () {
          window.location.href = res.redirect;
        }, 800);
      } else {
        var _res$msg;
        UI.setButtonLoading($btn, false, originalHTML);
        showError((_res$msg = res.msg) !== null && _res$msg !== void 0 ? _res$msg : "Credenciales incorrectas.");
      }
    });
  });
});
/******/ })()
;