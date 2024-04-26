import { deBounce, isEmpty } from "./_helper.js";

import { header } from "./components/header.js";
import { messageArea } from "./components/message_area.js";
import { messageBox } from "./components/message_box.js";
import { friend } from "./components/friend.js";
import { message } from "./components/message.js";

$(document).ready(function () {
  const sidebar = $("#sidebar");
  const searchBox = $("#search-box");
  const friends = $("#friends");
  const chatWindow = $("#chat-window");

  let selectFriend = null;
  let headerClear = null;
  let messageClear = null;

  setInterval(() => updateState(), 10000);
  getFriends();

  watcher(
    {
      url: "./api/users/friends",
      method: "GET",
      target: "friends",
      delay: 2000,
    },
    function () {
      getFriends();
    }
  );

  searchBox.on("input", function () {
    deBounce(function () {
      const searchQuery = searchBox.val();

      if (isEmpty(searchQuery)) {
        getFriends();
        return;
      }

      $.ajax({
        type: "POST",
        url: "./api/users/search",
        data: JSON.stringify({ name: searchQuery }),
        dataType: "JSON",
        success: function (response) {
          if (response.status !== "success") {
            friends.html(`
              <div class='error px-4 sm:px-6 py-4'>
                No friend found
              </div>
            `);
            return;
          }

          let markup = "";

          response.data.map((friend) => {
            markup += `
              <div class='friend px-4 sm:px-6 py-4 cursor-pointer hover:bg-gray-900/50' id="${friend.uuid}">
                ${friend.name}
              </div>
            `;
          });

          friends.html(markup);
        },
      });
    }, 1000);
  });

  friends.on("click", function () {
    selectFriend = event.target.id;

    if (selectFriend === "friends" || selectFriend === "") return;

    getFriends();
    openChatWindow();

    searchBox.val("");

    chatWindow.removeClass("hidden");
    chatWindow.addClass("flex");
    sidebar.addClass("hidden");
  });

  function updateState() {
    $.ajax({
      type: "GET",
      url: "./api/users/status",
      dataType: "JSON",
    });
  }

  function getFriends() {
    $.ajax({
      type: "GET",
      url: "./api/users/friends",
      dataType: "JSON",
      success: function (response) {
        let markup = "";

        if (response.status === "success") {
          response.friends.map((data) => (markup += friend(data)));
        }

        friends.html(markup);
      },
    });
  }

  function openChatWindow() {
    clearInterval(messageClear);
    clearInterval(headerClear);
    
    chatWindow.html(header() + messageArea() + messageBox());

    getHeader(selectFriend);
    getMessages(selectFriend);
    sendMessage(selectFriend);
    backButton();

    headerClear = setInterval(() => getHeader(selectFriend), 10000);

    messageClear = watcher(
      {
        url: "./api/chats/receive",
        method: "POST",
        payload: { uuid: selectFriend },
        target: "messages",
        delay: 5000,
      },
      function () {
        getFriends();
        getMessages(selectFriend);
      }
    );
  }

  function getHeader(uuid) {
    $.ajax({
      type: "GET",
      url: `./api/users/uuid/${uuid}`,
      dataType: "JSON",
      success: function (response) {
        if (response.status !== "success") return;

        $(".chat__header .username").text(response.data.name);
        $(".chat__header .status").text(response.data.status);

        if (response.data.status === "Online") {
          $(".chat__header .status").removeClass("text-gray-600");
          $(".chat__header .status").addClass("text-green-500");
        } else {
          $(".chat__header .status").removeClass("text-green-500");
          $(".chat__header .status").addClass("text-gray-600");
        }
      },
    });
  }

  function getMessages(uuid) {
    $.ajax({
      type: "POST",
      url: "./api/chats/receive",
      data: JSON.stringify({ uuid }),
      dataType: "JSON",
      success: function (response) {
        const messageArea = $("#message-area");

        if (response.status === "success") {
          let messages = "";
          response.messages.map((data) => (messages += message(data)));
          messageArea.html(messages);

          setTimeout(() => {
            messageArea.scrollTop(messageArea[0].scrollHeight);
          }, 0);
        } else {
          messageArea.html(`
            <div class='w-full text-white/65 text-sm text-center px-4 absolute bottom-10 left-0 select-none'>
              No message are available.<br/>
              Once you send message they will appear here.
            </div>
          `);
        }
      },
    });
  }

  function sendMessage(uuid) {
    $("#message-box").on("submit", function (e) {
      e.preventDefault();

      const message = $(".message-box__input").val();
      if (isEmpty(message)) return;

      const data = { uuid, message };

      $.ajax({
        type: "POST",
        url: "./api/chats/send",
        data: JSON.stringify(data),
        dataType: "JSON",
        success: function (response) {
          if (response.status === "success") {
            getFriends();
            getMessages(uuid);
            $(".message-box__input").val("");
          }
        },
      });
    });
  }

  function backButton() {
    $("#back-button").on("click", function () {
      chatWindow.removeClass("flex");
      chatWindow.addClass("hidden");
      sidebar.removeClass("hidden");
      sidebar.addClass("flex");
    });
  }

  function watcher(options, callback) {
    let prevItem = 0;

    const event = setInterval(function () {
      $.ajax({
        type: options.method,
        url: options.url,
        data: JSON.stringify(options.payload),
        dataType: "JSON",
        success: function (response) {
          if (response.status !== "success") {
            clearInterval(event);
            return console.log(response);
          }

          if (!response[options.target]) {
            clearInterval(event);
            return console.log("Target not found!");
          }

          setTimeout(function () {
            prevItem = response[options.target].length;
          }, 0);

          if (prevItem !== response[options.target].length) {
            return callback(response[options.target]);
          }
        },
        error: function (error) {
          clearInterval(event);
          return console.warn(`message: ${error}`);
        },
      });
    }, options.delay);

    return event;
  }
});
