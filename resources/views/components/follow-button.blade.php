@props(['user', 'isFullWidth' => false])

<div x-data="{
    following: {{ auth()->user()->isFollowing($user) ? 'true' : 'false' }},
    loading: false,
    async toggle() {
        if (this.loading) return;
        this.loading = true;
        try {
            const res = await fetch('{{ route('user.follow', $user->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            this.following = (data.status === 'following');
        } catch (e) { console.error('Follow failed', e); } finally { this.loading = false; }
    }
}" class="{{ $isFullWidth ? 'w-full' : '' }}">
    <button @click="toggle" :disabled="loading"
        :class="{
            'bg-spheria-gray text-white border border-gray-700': following,
            'bg-purple-600 text-white hover:bg-purple-700': !following,
            'w-full text-center': {{ $isFullWidth ? 'true' : 'false' }},
            'opacity-50 cursor-not-allowed': loading
        }"
        class="px-6 py-1.5 rounded-lg text-sm font-bold transition duration-200 shadow-sm">
        <span x-text="following ? 'Following' : 'Follow'"></span>
    </button>
</div>
