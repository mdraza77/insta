<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="w-full max-w-6xl grid md:grid-cols-2 bg-[#16162b] rounded-2xl overflow-hidden shadow-lg">

            <!-- LEFT SIDE -->
            <div
                class="hidden md:flex flex-col justify-center p-10 bg-gradient-to-br from-[#1e293b] to-[#0f172a] text-white">
                <h1 class="text-3xl font-bold mb-4">Insta</h1>

                <h2 class="text-2xl font-semibold mb-2">
                    Join the Community 🚀
                </h2>

                <p class="text-gray-400">
                    Create your account and start sharing your moments.
                </p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="p-8 md:p-12 text-white">

                <h2 class="text-2xl font-semibold mb-2">Create Account</h2>

                <p class="text-gray-400 mb-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-purple-500">Sign in</a>
                </p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Email -->
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Username -->
                    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('username')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Password -->
                    <input type="password" name="password" placeholder="Password"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Confirm Password -->
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"
                        class="w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 focus:ring-2 focus:ring-purple-500 outline-none">
                    <small class="text-red-500">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </small>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full py-3 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition">
                        Sign Up
                    </button>

                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
