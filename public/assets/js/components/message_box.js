export const messageBox = () => {
  return `
        <form method="POST" class='chat__message-box bg-gray-950 p-4 sm:px-6 flex gap-4 border-t border-gray-900' id="message-box" autocomplete="off">
          <input type='text' class='message-box__input grow bg-transparent focus:outline-none' placeholder='Type a message...' name="message"/>
          <button class="message-box__button text-gray-400 hover:text-white transition-all">
            <i class='ri-send-plane-2-line message-box__button-icon text-2xl'></i>
          </button>
        </form>
      `;
};
