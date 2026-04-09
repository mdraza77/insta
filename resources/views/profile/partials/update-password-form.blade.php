<section class="max-w-2xl">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            Ensure your account is using a strong password to stay secure.
        </p>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <!-- CURRENT PASSWORD -->
        <div>
            <label class="text-sm text-gray-400">Current Password</label>
            <input type="password" name="current_password"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white focus:ring-2 focus:ring-purple-500 outline-none">

            @error('current_password', 'updatePassword')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NEW PASSWORD -->
        <div>
            <label class="text-sm text-gray-400">New Password</label>
            <input type="password" name="password"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white focus:ring-2 focus:ring-purple-500 outline-none">

            @error('password', 'updatePassword')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- CONFIRM PASSWORD -->
        <div>
            <label class="text-sm text-gray-400">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white focus:ring-2 focus:ring-purple-500 outline-none">

            @error('password_confirmation', 'updatePassword')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- BUTTON -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition text-white">
                Save Changes
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-green-400 text-sm">Password updated ✔</span>
            @endif
        </div>

    </form>
</section>
