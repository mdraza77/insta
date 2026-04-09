<section class="max-w-2xl">
    <!-- FORM -->
    <form method="POST" action="{{ route('profile.privacy.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- PRIVATE ACCOUNT -->
        <div class="flex items-start gap-3">
            <input type="checkbox" name="is_private" value="1" {{ $user->is_private ? 'checked' : '' }}
                class="mt-1 w-5 h-5 rounded bg-[#1e1e2f] border-gray-600 text-purple-500 focus:ring-purple-500">

            <div>
                <p class="text-white font-medium">Private Account</p>
                <p class="text-sm text-gray-400">Only approved followers can see your posts</p>
            </div>
        </div>

        <!-- ACTIVITY STATUS -->
        <div class="flex items-start gap-3">
            <input type="checkbox" name="show_activity" value="1" {{ $user->show_activity ? 'checked' : '' }}
                class="mt-1 w-5 h-5 rounded bg-[#1e1e2f] border-gray-600 text-purple-500">

            <div>
                <p class="text-white font-medium">Show Activity Status</p>
                <p class="text-sm text-gray-400">Let others see when you're active</p>
            </div>
        </div>

        <!-- READ RECEIPTS -->
        <div class="flex items-start gap-3">
            <input type="checkbox" name="read_receipts" value="1" {{ $user->read_receipts ? 'checked' : '' }}
                class="mt-1 w-5 h-5 rounded bg-[#1e1e2f] border-gray-600 text-purple-500">

            <div>
                <p class="text-white font-medium">Show Read Receipts</p>
                <p class="text-sm text-gray-400">Let others know when you've seen messages</p>
            </div>
        </div>

        <!-- RESTRICT MENTIONS -->
        <div class="flex items-start gap-3">
            <input type="checkbox" name="restrict_mentions" value="1"
                {{ $user->restrict_mentions ? 'checked' : '' }}
                class="mt-1 w-5 h-5 rounded bg-[#1e1e2f] border-gray-600 text-purple-500">

            <div>
                <p class="text-white font-medium">Restrict Mentions</p>
                <p class="text-sm text-gray-400">Only allow mentions from people you follow</p>
            </div>
        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="px-6 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition text-white">
            Save Privacy Settings
        </button>

    </form>
</section>
