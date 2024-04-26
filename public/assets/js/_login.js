import { redirect, isEmpty } from "./_helper.js";

$(document).ready(function () {
  const inputForm = $("#input-form");
  const username = $("#username");
  const password = $("#password");

  const errorBoxs = {
    username: username.parent().children(".form__error"),
    password: password.parent().parent().children(".form__error"),
  };

  let data = { username: "", password: "" };

  username.on("blur", function () {
    if (isEmpty(username.val())) {
      return errorBoxs.username.text("Username are required");
    }

    errorBoxs.username.text("");
    data.username = username.val();
  });

  password.on("blur", function () {
    if (isEmpty(password.val())) {
      return errorBoxs.password.text("Password are required");
    }

    errorBoxs.password.text("");
    data.password = password.val();
  });

  inputForm.on("submit", function (e) {
    e.preventDefault();

    if (isEmpty(data.username)) {
      errorBoxs.username.text("Username are required");
    }

    if (isEmpty(data.password)) {
      errorBoxs.password.text("Password are required");
    }

    if (data.username && data.password) {
      $.ajax({
        type: "POST",
        url: "./api/users/login",
        data: JSON.stringify(data),
        dataType: "JSON",
        success: function (response) {
          if (response.status === "success") {
            return redirect("./");
          }

          if (response.error.details.field === "username") {
            errorBoxs.username.text("Invalid username");
          }

          if (response.error.details.field === "password") {
            errorBoxs.password.text("Invalid password");
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  });
});
