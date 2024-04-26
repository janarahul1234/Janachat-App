export const friend = ({ uuid, name, message, timestamp, type }) => {
  let icon = "ri-asterisk";

  if (type === "incoming") {
    icon = "ri-arrow-left-down-line";
  } else if (type === "outgoing") {
    icon = "ri-arrow-right-up-line";
  }

  return `
    <div class="friend relative flex items-center justify-between gap-4 px-4 sm:px-6 py-2 cursor-pointer hover:bg-gray-900/50" id="${uuid}">
      <div class="friend__data w-[55%]" id="${uuid}">
        <h2 class="friend__name mb-1.5 select-none" id="${uuid}">
          ${name}
        </h2>
        <p class="friend__message text-sm truncate text-gray-400 select-none" id="${uuid}">
          <i class="${icon} text-base" id="${uuid}"></i>
          ${message}
        </p>
      </div>
      <span class="friend__timestamp text-xs self-start shrink-0 text-gray-400 select-none" id="${uuid}">
        ${timestamp}
      </span>
    </div>
  `;
};
