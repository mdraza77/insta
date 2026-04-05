<x-app-layout>
    <div class="py-4">
        <div class="mb-8">
            @include('components.stories')
        </div>

        <div class="flex flex-col">
            @foreach (range(1, 5) as $post)
                @include('components.post-card')
            @endforeach
        </div>
    </div>
</x-app-layout>
