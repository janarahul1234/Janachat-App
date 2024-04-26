<x-layout>
    <x-slot name="title">Login</x-slot>

    <main class="main h-screen px-6 flex flex-col justify-center items-center">
        <h1 class="title text-[2rem] mb-[57px]">Login to you account</h1>

        <form method="POST" class="form w-[330px]" id="input-form" autocomplete="off">
            <div class="form__field">
                <label for="username" class="form__label text-gray-400 pb-[.188rem]">Username</label>
                <input type="text" name="username" id="username" class="form__input w-full bg-gray-900 px-4 py-3.5 rounded focus:outline-none focus:ring-1 focus:ring-inset focus:ring-blue-500" autofocus>
                <div class="form__error h-5 text-sm text-red-500 mt-1 mb-[15px]"></div>
            </div>

            <div class="form__field">
                <label for="password" class="form__label text-gray-400 pb-[.188rem]">Password</label>
                <div class="form__field-group w-full bg-gray-900 flex items-center rounded focus-within:ring-1 focus-within:ring-inset focus-within:ring-blue-500">
                    <input type="password" name="password" id="password" class="form__input w-full bg-transparent px-4 py-3.5 border-0 focus:outline-none focus:ring-0">
                    <i class="ri-eye-line text-gray-400 text-2xl mr-[15px] cursor-pointer" id="password-icon"></i>
                </div>
                <div class="form__error h-5 text-sm text-red-500 mt-1 mb-[20px]"></div>
            </div>

            <button type="submit" class="form__submit w-full py-[13px] bg-blue-500 font-medium flex justify-center items-center gap-[.438rem] rounded hover:bg-blue-400 transition-all">
                Continue to chat
                <i class="ri-key-line text-2xl"></i>
            </button>
        </form>

        <p class="mt-3.5 text-gray-400">
            Don't have an account?
            <a href="{{ @route('register') }}" class="text-white hover:underline">Sign up</a>
        </p>

        <p>{{ $test }}</p>
    </main>

    <x-slot name="js">
        <script src="{{ @asset('assets/js/_password.js') }}"></script>
        <script type="module" src="{{ @asset('assets/js/_login.js') }}"></script>
    </x-slot>
</x-layout>