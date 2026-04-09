<x-app-layout>

    <div class="min-h-screen flex bg-[#0f0f1a] text-white">

        <!-- 🔥 SIDEBAR -->
        <div class="w-20 bg-[#16162b] flex flex-col items-center py-6 space-y-6">

            @php
                $menus = [
                    ['id' => 'profile', 'icon' => 'fa-user', 'label' => 'Edit Profile'],
                    ['id' => 'account', 'icon' => 'fa-circle-info', 'label' => 'Account'],
                    ['id' => 'password', 'icon' => 'fa-lock', 'label' => 'Password'],
                    ['id' => 'privacy', 'icon' => 'fa-shield', 'label' => 'Privacy'],
                ];
            @endphp

            @foreach ($menus as $menu)
                <div onclick="switchTab('{{ $menu['id'] }}')"
                    class="group relative cursor-pointer p-3 rounded-lg hover:bg-[#1e1e2f] transition">

                    <i class="fa-solid {{ $menu['icon'] }} text-lg"></i>

                    <!-- Hover text -->
                    <span
                        class="absolute left-14 top-1/2 -translate-y-1/2 
                    bg-black text-xs px-2 py-1 rounded opacity-0 
                    group-hover:opacity-100 transition whitespace-nowrap">
                        {{ $menu['label'] }}
                    </span>

                </div>
            @endforeach

        </div>

        <!-- 🔥 CONTENT -->
        <div class="flex-1 p-8">

            <!-- PROFILE -->
            <div id="profile" class="tab-content">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- ACCOUNT -->
            <div id="account" class="tab-content hidden">
                <h2 class="text-xl font-bold mb-4">Account Info</h2>
                <p class="text-gray-400">Coming soon...</p>
            </div>

            <!-- PASSWORD -->
            <div id="password" class="tab-content hidden">
                @include('profile.partials.update-password-form')
            </div>

            <!-- PRIVACY -->
            <div id="privacy" class="tab-content hidden">
                <h2 class="text-xl font-bold mb-4">Privacy</h2>
                <p class="text-gray-400">Coming soon...</p>
            </div>

        </div>

    </div>

    <!-- 🔥 SCRIPT -->
    <script>
        function switchTab(tabId) {
            // save current tab
            localStorage.setItem('activeTab', tabId);

            // hide all
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            // show selected
            document.getElementById(tabId).classList.remove('hidden');
        }

        // 🔥 ON PAGE LOAD
        document.addEventListener('DOMContentLoaded', function() {
            let savedTab = localStorage.getItem('activeTab') || 'profile';

            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            document.getElementById(savedTab).classList.remove('hidden');
        });
    </script>

</x-app-layout>
