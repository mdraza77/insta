<section class="max-w-2xl">

    <h2 class="text-xl font-semibold text-white mb-6">Edit Profile</h2>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        <!-- PROFILE PHOTO -->
        {{-- <div class="flex items-center gap-4">
            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                class="w-16 h-16 rounded-full object-cover">

            <label class="text-sm text-purple-400 cursor-pointer hover:underline">
                <i class="fa-solid fa-pencil"></i> Change Image
                <input type="file" name="photo" class="hidden">
            </label>
        </div> --}}

        <div x-data="imageCropper()">
            <input type="file" id="upload-photo" @change="handleFile" class="hidden" accept="image/*">

            <button type="button" @click="document.getElementById('upload-photo').click()"
                class="bg-blue-600 px-4 py-2 rounded text-sm">
                Change Photo
            </button>

            <template x-teleport="body">
                <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
                    <div class="bg-zinc-900 rounded-xl overflow-hidden max-w-lg w-full">
                        <div class="p-4 border-b border-zinc-800 flex justify-between items-center">
                            <h3 class="text-white font-semibold">Crop Photo</h3>
                            <button @click="showModal = false" class="text-gray-400">&times;</button>
                        </div>

                        <div class="p-4">
                            <div class="max-h-[400px] overflow-hidden rounded-lg bg-black">
                                <img id="image-to-crop" class="max-w-full">
                            </div>
                        </div>

                        <div class="p-4 bg-zinc-800 flex justify-end gap-3">
                            <button @click="showModal = false" class="text-white text-sm">Cancel</button>
                            <button @click="cropAndSave"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- USERNAME -->
        @php
            $canEdit = !$user->username_updated_at || $user->username_updated_at->diffInDays(now()) >= 14;
        @endphp

        <div>
            <label class="text-sm text-gray-400">Username</label>

            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                {{ $canEdit ? '' : 'readonly' }}
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white 
        {{ !$canEdit ? 'opacity-50 cursor-not-allowed' : '' }}">

            <p class="text-xs text-gray-500 mt-1">
                @if ($canEdit)
                    You can update your username.
                @else
                    You can change your username after
                    {{ $user->username_updated_at->addDays(14)->format('M d, Y') }}
                @endif
            </p>

            @error('username')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- FULL NAME -->
        <div>
            <label class="text-sm text-gray-400">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white">
        </div>
        @error('name')
            <p class="text-red-400 text-sm">{{ $message }}</p>
        @enderror

        <!-- BIO -->
        <div>
            <label class="text-sm text-gray-400">Bio</label>
            <textarea name="bio" maxlength="150"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white resize-none" rows="3">{{ old('bio', $user->bio) }}</textarea>

            <p class="text-xs text-gray-500 mt-1">
                {{ strlen($user->bio ?? '') }}/150
            </p>
            @error('bio')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- EMAIL -->
        <div>
            <label class="text-sm text-gray-400">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white">
            @error('email')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- PHONE -->
        <div>
            <label class="text-sm text-gray-400">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white">
            @error('phone')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- GENDER -->
        <div>
            <label class="text-sm text-gray-400">Gender</label>
            <select name="gender"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white">
                <option value="">Select</option>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Prefer not to say</option>
            </select>
            @error('gender')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- WEBSITE -->
        <div>
            <label class="text-sm text-gray-400">Website</label>
            <input type="url" name="website" value="{{ old('website', $user->website) }}"
                class="mt-1 w-full px-4 py-3 rounded-lg bg-[#1e1e2f] border border-gray-700 text-white"
                placeholder="https://example.com">
            @error('website')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- BUTTON -->
        <div class="pt-3">
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:opacity-90 transition text-white">
                Submit
            </button>
        </div>

    </form>


    <script>
        function imageCropper() {
            return {
                showModal: false,
                cropper: null,

                handleFile(e) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.showModal = true;
                        const image = document.getElementById('image-to-crop');
                        image.src = event.target.result;

                        if (this.cropper) this.cropper.destroy();

                        setTimeout(() => {
                            this.cropper = new Cropper(image, {
                                aspectRatio: 1, // Square crop for profile
                                viewMode: 2,
                                dragMode: 'move',
                            });
                        }, 100);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                },

                cropAndSave() {
                    const canvas = this.cropper.getCroppedCanvas({
                        width: 400,
                        height: 400
                    });
                    const base64 = canvas.toDataURL('image/jpeg');

                    // Backend par bhejne ke liye fetch use karein
                    fetch('{{ route('profile.photo.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            image: base64
                        })
                    }).then(() => window.location.reload());
                }
            }
        }
    </script>
</section>
