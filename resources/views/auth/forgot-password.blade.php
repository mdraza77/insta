<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="w-full max-w-6xl grid md:grid-cols-2 bg-[#16162b] rounded-2xl overflow-hidden shadow-lg">

            <!-- LEFT SIDE -->
            <div
                class="hidden md:flex flex-col justify-center p-10 bg-gradient-to-br from-[#1e293b] to-[#0f172a] text-white">
                <h1 class="text-3xl font-bold mb-4">Insta</h1>

                <h2 class="text-2xl font-semibold mb-2">
                    Reset Your Password 🔐
                </h2>

                <p class="text-gray-400">
                    Enter your email and we'll send you a reset link.
                </p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="p-8 md:p-12 text-white">

                <h2 class="text-2xl font-semibold mb-2">Forgot Password</h2>

                <p class="text-gray-400 mb-6">
                    No worries, we'll help you recover your account.
                    <a href="{{ route('login') }}" class="text-purple-500">Login</a>
                </p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-green-400 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full py-3 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition">
                        Send Reset Link
                    </button>

                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
