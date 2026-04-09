<section class="max-w-2xl">
    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-700 rounded-lg overflow-hidden">

            <tbody class="divide-y divide-gray-700">

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400 w-1/3">Username</td>
                    <td class="px-6 py-3 text-white">{{ $user->username }}</td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Full Name</td>
                    <td class="px-6 py-3 text-white">{{ $user->name }}</td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Email</td>
                    <td class="px-6 py-3 text-white">{{ $user->email }}</td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Phone Number</td>
                    <td class="px-6 py-3 text-white">{{ $user->phone ?? 'Not provided' }}</td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Gender</td>
                    <td class="px-6 py-3 text-white">
                        {{ $user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not provided' }}
                    </td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Website</td>
                    <td class="px-6 py-3 text-white">
                        {{ $user->website ?? 'Not provided' }}
                    </td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Bio</td>
                    <td class="px-6 py-3 text-white">{{ $user->bio ?? 'No bio' }}</td>
                </tr>

                <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Date Joined</td>
                    <td class="px-6 py-3 text-white">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                </tr>

                {{-- <tr class="hover:bg-[#1e1e2f]">
                    <td class="px-6 py-3 text-gray-400">Account Type</td>
                    <td class="px-6 py-3 text-white">Standard</td>
                </tr> --}}

            </tbody>
        </table>
    </div>

</section>
