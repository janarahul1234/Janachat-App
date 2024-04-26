export const header = () => {
  return `
        <div class="chat__header relative bg-gray-950 border-gray-900 p-4 flex items-center flex-col border-b" id="header">
          <div class="absolute top-1/2 left-4 -translate-y-2/4 text-gray-400 cursor-pointer hover:text-white transition-all sm:hidden" id="back-button">
            <i class="ri-arrow-left-line text-2xl"></i>
          </div>
    
          <div class="username text-white select-none"></div>
          <div class="status text-sm select-none"></div>
        </div>
      `;
};
