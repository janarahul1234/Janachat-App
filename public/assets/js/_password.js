$(document).ready(function () {
  const passwordField = $("#password");
  const passwordIcon = $("#password-icon");

  passwordIcon.on("click", function () {
    if (passwordField.attr("type") === "password") {
      passwordField.attr("type", "text");
      $(this).removeClass("ri-eye-line");
      $(this).addClass("ri-eye-off-line");
    } else {
      passwordField.attr("type", "password");
      $(this).removeClass("ri-eye-off-line");
      $(this).addClass("ri-eye-line");
    }
  });
});
