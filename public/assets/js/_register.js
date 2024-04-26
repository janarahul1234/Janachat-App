import { redirect, isEmpty } from "./_helper.js";

const onValidUsername = (val) => {
  const usernameRegex = /^[a-z0-9_]+$/;
  return usernameRegex.test(val);
};

$(document).ready(function () {
  const inputForm = $("#input-form");
  const name = $("#name");
  const username = $("#username");
  const password = $("#password");

  const errorBoxs = {
    name: name.parent().children().eq(2),
    username: username.parent().children().eq(2),
    password: password.parent().parent().children().eq(2),
  };

  let data = { name: "", username: "", password: "" };

  name.on("blur", function () {
    if (isEmpty(name.val())) {
      return errorBoxs.name.text("Name are required");
    }

    errorBoxs.name.text("");
    data.name = name.val();
  });

  username.on("blur", function () {
    if (isEmpty(username.val())) {
      return errorBoxs.username.text("Username are required");
    }

    if (!onValidUsername(username.val())) {
      return errorBoxs.username.text(
        "Username can be a-z, 0-9 and '-' are acceptable"
      );
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

    if (isEmpty(data.name)) {
      errorBoxs.name.text("Name are required");
    }

    if (isEmpty(data.username)) {
      errorBoxs.username.text("Username are required");
    }

    if (isEmpty(data.password)) {
      errorBoxs.password.text("Password are required");
    }

    if (data.name && data.username && data.password) {
      $.ajax({
        type: "POST",
        url: "./api/users/register",
        data: JSON.stringify(data),
        dataType: "JSON",
        success: function (response) {
          if (response.status === "success") {
            return redirect("./login");
          }
          
          if (response.error?.code === 409) {
            errorBoxs.username.text("Username already registered");
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  });
});
