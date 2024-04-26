import { redirect } from "./_helper.js";

$(document).ready(function () {
  const logoutButton = $("#logout-button");

  logoutButton.on("click", function () {
    $.ajax({
      type: "GET",
      url: "./api/users/logout",
      dataType: "JSON",
      success: function (response) {
        if (response.status === "success") {
          return redirect("./login");
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});
