<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0f0f1a] px-4">

        <div class="w-full max-w-5xl grid md:grid-cols-2 bg-[#16162b] rounded-2xl overflow-hidden shadow-lg">

            <!-- LEFT SIDE -->
            <div
                class="hidden md:flex flex-col justify-center p-10 bg-gradient-to-br from-[#1e293b] to-[#0f172a] text-white">

                <h1 class="text-3xl font-bold mb-4">Insta</h1>

                <h2 class="text-2xl font-semibold mb-2">
                    Capturing Moments,<br> Creating Memories
                </h2>

                <p class="text-gray-400">
                    Join our community and share your story with the world.
                </p>

                <!-- Optional image -->
                {{-- <img src="/images/auth-illustration.png" class="mt-8 rounded-xl"> --}}
            </div>

            <!-- RIGHT SIDE (FORM) -->
            <div class="p-8 md:p-12 text-white">

                <h2 class="text-2xl font-semibold mb-2">Welcome back</h2>

                <p class="text-gray-400 mb-6">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-purple-500">Sign up</a>
                </p>

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email/Username Input -->
                    <input type="text" name="login" placeholder="Email or Username"
                        value="{{ old('login', 'mdraza8297@gmail.com') }}"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">

                    <small class="text-red-500">
                        @error('login')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Password -->
                    <input type="password" name="password" placeholder="Password"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none"
                        value="Success2026$">
                    <small class="text-red-500">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </small>

                    <div class="flex items-center justify-between mt-2">

                        <!-- Remember Me -->
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-gray-600 bg-[#1e1e2f] text-purple-500 focus:ring-purple-500">

                            <span class="text-sm text-gray-400">Remember me</span>
                        </label>

                        <!-- Forgot Password -->
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-purple-400 hover:text-purple-300 transition">
                            Forgot password?
                        </a>

                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full py-3 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition">
                        Sign in
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
