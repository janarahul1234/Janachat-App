<x-layout>
    <x-slot name="title">Chat</x-slot>

    <x-slot name="css">
        <style>
            .bg-pattern {
                background: linear-gradient(#030712, hsla(224, 71%, 4%, .1)),
                url("{{ @asset('assets/images/dot-pattern.svg') }}") repeat;
            }

            .chat--ingoing::before {
                left: 0;
                background: url("{{ @asset('assets/images/message-outgoing.svg') }}") no-repeat;
            }

            .chat--outgoing::before {
                right: 0;
                background: url("{{ @asset('assets/images/message-ingoing.svg') }}") no-repeat;
            }
        </style>
    </x-slot>

    <main class="main relative w-full h-screen bg-pattern">
        <div class="sidebar bg-gray-950 flex sm:flex flex-col sm:border-r sm:border-gray-900" id="sidebar">
            <div class="sidebar__header px-4 sm:px-6 py-[25px] flex justify-between items-center">
                <div class="chats__header-title text-2xl sm:text-[2rem]">Chats</div>
                <div class="chats__header-button cursor-pointer" id="logout-button">
                    <i class="ri-logout-circle-line text-2xl text-red-500"></i>
                </div>
            </div>

            <div class='search-box bg-gray-900 mx-4 sm:mx-6 mb-4 px-4 py-3 flex items-center rounded focus-within:ring-1 focus-within:ring-inset focus-within:ring-blue-500'>
                <i class='text-2xl mr-4 ri-search-line'></i>
                <input type='text' class='search-box__input w-full bg-transparent border-0 focus:outline-none focus:ring-0' id="search-box" placeholder='Search your friends...' />
            </div>

            <div class='friends grow overflow-y-auto' id="friends"></div>
        </div>

        <div class="chat hidden sm:flex flex-col" id="chat-window">
            <div class="absolute top-1/2 left-1/2 -translate-x-2/4 -translate-y-2/4 text-gray-400 text-center select-none">
                Select a chat to start message
            </div>
        </div>
    </main>

    <x-slot name="js">
        <script type="module" src="{{ @asset('assets/js/_index.js') }}"></script>
        <script type="module" src="{{ @asset('assets/js/_logout.js') }}"></script>
    </x-slot>
</x-layout>