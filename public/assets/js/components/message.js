export const message = ({ message, timestamp, type }) => {
  if (type === "incoming") {
    return `
      <div class='chat__message chat--ingoing relative max-w-[90%] sm:max-w-[80%] bg-gray-900 px-4 pt-[11px] pb-3 rounded rounded-bl-none self-start'>
        <div class='chat__message-text text-white mb-2 break-all'>
          ${message}
        </div>
        <div class='chat__message-timestamp text-white/65 text-xs'>
          ${timestamp}
        </div>
      </div>
    `;
  } else if (type === "outgoing") {
    return `
      <div class='chat__message chat--outgoing relative max-w-[90%] sm:max-w-[80%] bg-blue-500 px-4 pt-[11px] pb-3 rounded rounded-br-none self-end'>
        <div class='chat__message-text text-white mb-2 text-right break-all'>
          ${message}
        </div>
        <div class='chat__message-timestamp text-white/65 text-xs text-right'>
          ${timestamp}
        </div>
      </div>
    `;
  }
};
