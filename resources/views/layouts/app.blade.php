<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Nunito:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.open {
            max-height: 200px;
        }

        .active {
            background-color: #3b3f5c;
            color: #ffffff;
        }

        .scroll-bar::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-bar::-webkit-scrollbar-track {
            background: #2a2f45;
        }

        .scroll-bar::-webkit-scrollbar-thumb {
            background-color: #444c65;
            border-radius: 3px;
        }

        .scroll-bar {
            scrollbar-width: thin;
        }
    </style>
</head>

<body class="bg-white text-gray-200">
    <div class="flex flex-col h-screen relative">
        <!-- Header -->
        <div class="header w-full bg-[#2a2f45] z-10 flex items-center fixed top-0 left-0 right-0 print:hidden">
            <div class="md:h-[70px] h-[60px] w-full py-3 md:px-6 px-3 relative">
                <div class="flex justify-between w-full items-center">
                    <div class="logo flex flex-row gap-16 hidden md:block">
                        <div class="flex flex-row gap-2 items-center">
                            <img src="{{ asset('image/logo.png') }}" alt="Logo" class="w-44 h-auto" />
                        </div>
                    </div>
                    <i id="menuBtn" class="ri-menu-line md:hidden block text-white text-xl font-medium"></i>
                    <div class="flex flex-row items-center gap-5">
                        <div class="relative">
                            <div
                                class="absolute -right-1 -top-0 w-4 h-4 rounded-full bg-red-500 flex items-center justify-center">
                                <span class="text-white text-[10px] leading-none">2</span>
                            </div>
                            <i
                                class="ri-notification-2-line cursor-pointer text-white/80 hover:text-white text-[21px]"></i>
                        </div>
                        <i class="ri-moon-line text-white/80 cursor-pointer hover:text-white text-[21px]"></i>
                        <div id="profile_menu_btn" class="flex items-center flex-row gap-2 cursor-pointer">
                            <img src="https://i.pravatar.cc/80?img=3" alt="Admin" class="w-10 h-10 rounded-full">
                            <span class="text-white/80 text-[15px]">Admin</span>
                            <i class="ri-arrow-down-s-line text-white/80"></i>
                        </div>
                        <i class="ri-settings-2-line cursor-pointer text-white/80 hover:text-white text-[21px]"></i>
                    </div>
                </div>
                <div id="profile_menu"
                    class="absolute top-full mt-0 right-0 w-60 bg-[#2a2f45] hidden rounded-b-lg overflow-hidden shadow-lg border border-gray-700">
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2 transition duration-300 hover:bg-[#3b3f5c] group">
                        <i class="ri-user-line text-lg text-gray-400 group-hover:text-white"></i>
                        <span class="text-gray-300 group-hover:text-white">Profile</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-2 hover:bg-[#3b3f5c] group w-full text-left">
                            <i class="ri-logout-box-r-line text-lg text-gray-400 group-hover:text-white"></i>
                            <span class="text-gray-300 group-hover:text-white">Logout</span>
                        </button>
                    </form>
                    <a href="#" class="flex items-center gap-3 px-4 py-2 hover:bg-[#3b3f5c] group">
                        <i class="ri-settings-3-line text-lg text-gray-400 group-hover:text-white"></i>
                        <span class="text-gray-300 group-hover:text-white">Site Setting</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="flex pt-[70px] print:p-0">
            <div id="sidebar"
                class="scroll-bar border-t border-gray-700 md:w-[210px] lg:w-[240px] z-50 md:block bg-[#2a2f45] fixed md:top-[70px] top-[60px] bottom-0 overflow-y-auto transition-all duration-500 ease-in-out transform -translate-x-full md:translate-x-0">
                <div class="pb-6 px-1.5 pt-1.5">
                    <ul>
                        <li class="group">
                            <a href="{{ route('dashboard') }}"
                                class="mb-1 flex items-center pl-4 py-2.5 rounded
                                {{ request()->routeIs('dashboard') ? 'bg-[#3b3f5c] text-white' : 'text-gray-300 hover:text-white hover:bg-[#3b3f5c]' }}">
                                <i class="ri-dashboard-line mr-1"></i>
                                <span class="text-[15px]">Dashboard & Overview</span>
                            </a>
                        </li>
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="variation">
                                <i class="ri-shape-line mr-1"></i>
                                <span class="text-[15px]">Variants & Attributes</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li>
                                    <a href="{{ route('brands.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-briefcase-4-line mr-2"></i>Brands
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('groups.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-shirt-line mr-2"></i>Brand by Group
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('sizes.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-font-size-2 mr-2"></i>Group by Sizes
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Stock -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="stock">
                                <i class="ri-database-2-line mr-1"></i>
                                <span class="text-[15px]">Inventory Control</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li><a href="{{ route('stocks.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]"><i
                                            class="ri-list-check mr-2"></i>All Stock</a>
                                </li>
                                <li>
                                    <a href="{{ route('stocks.create') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]"><i
                                            class="ri-upload-line mr-2"></i>Add Stock</a>
                                </li>
                                <li>
                                    <a href="{{ route('stocks.history') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-time-line mr-2"></i>Stock History
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('stocks.warnings') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c']">
                                        <i class="ri-alert-line mr-2"></i>Inventory Warnings
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('stocks.exhausted') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c']">
                                        <i class="ri-close-circle-line mr-2"></i>Inventory Exhausted
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Sales Management -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="sales">
                                <i class="ri-shopping-cart-2-line mr-1"></i>
                                <span class="text-[15px]">Sales Management</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li>
                                    <a href="{{ route('sales.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-file-list-2-line mr-2"></i>All Sales
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Memo Management -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="memo">
                                <i class="ri-file-copy-line mr-1"></i>
                                <span class="text-[15px]">Memo Management</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li>
                                    <a href="{{ route('memo.create') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c']">
                                        <i class="ri-add-box-line mr-2"></i>Create Memo
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('memo.pending') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c']">
                                        <i class="ri-time-line mr-2"></i>Pending Memo
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('memo.complete') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c']">
                                        <i class="ri-file-list-2-line mr-2"></i>Completed Memo
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <!-- Customers -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="customers">
                                <i class="ri-user-line mr-1"></i>
                                <span class="text-[15px]">Customer Relations</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li>
                                    <a href="{{ route('customer.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]"><i
                                            class="ri-user-smile-line mr-2"></i>All Customers</a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.create') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]"><i
                                            class="ri-user-add-line mr-2"></i>Add Customer</a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.transaction') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-history-line mr-2"></i>Daily Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.invoice') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-bill-line mr-2"></i>Billing & Invoicing
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.payment') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-bank-card-line mr-2"></i>Payment Management
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Vendor Management -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded submenu-toggle"
                                data-menu-key="vendors">
                                <i class="ri-store-2-line mr-1"></i>
                                <span class="text-[15px]">Vendor Management</span>
                                <i class="ri-arrow-down-s-line ml-auto mr-4"></i>
                            </a>
                            <ul class="submenu pl-2 bg-[#2a2f45]">
                                <li>
                                    <a href="{{ route('vendor.index') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-user-smile-line mr-2"></i>All Vendors
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vendor.create') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-user-add-line mr-2"></i>Add Vendor
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vendor.transaction') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-history-line mr-2"></i>Daily Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vendor.invoice') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-bill-line mr-2"></i>Billing & Invoicing
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vendor.payment') }}"
                                        class="flex items-center py-2 pl-6 text-[15px] text-gray-300 rounded hover:text-white hover:bg-[#3b3f5c]">
                                        <i class="ri-bank-card-line mr-2"></i>Payment Management
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Settings -->
                        <li class="group">
                            <a href="#"
                                class="mb-1 flex items-center pl-4 py-2.5 text-gray-300 hover:text-white hover:bg-[#3b3f5c] rounded">
                                <i class="ri-settings-3-line mr-1"></i>
                                <span class="text-[15px]">Account Settings</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-[210px] lg:ml-[240px] md:px-4 px-2 md:pt-4 overflow-y-auto print:p-0 print:m-0">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
    <script>
        document.getElementById("menuBtn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
        });

        profile_menu_btn.onclick = () => profile_menu.classList.toggle('hidden');

        document.addEventListener('DOMContentLoaded', function() {
            const submenuToggles = document.querySelectorAll('.submenu-toggle');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const submenuKey = this.dataset.menuKey;
                    if (submenu.classList.contains('open')) {
                        submenu.classList.remove('open');
                        submenu.style.maxHeight = '0';
                        this.classList.remove('bg-blue-500', 'text-white');
                        localStorage.removeItem('openMenu');
                    } else {
                        document.querySelectorAll('.submenu').forEach(sm => {
                            sm.classList.remove('open');
                            sm.style.maxHeight = '0';
                        });
                        document.querySelectorAll('.submenu-toggle').forEach(st => {
                            st.classList.remove('bg-blue-500', 'text-white');
                        });
                        submenu.classList.add('open');
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        this.classList.add('bg-blue-500', 'text-white');
                        localStorage.setItem('openMenu', submenuKey);
                    }
                });
            });

            const openMenuKey = localStorage.getItem('openMenu');
            if (openMenuKey) {
                const openToggle = document.querySelector(`[data-menu-key="${openMenuKey}"]`);
                if (openToggle) {
                    const submenu = openToggle.nextElementSibling;
                    submenu.classList.add('open');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    openToggle.classList.add('bg-blue-500', 'text-white');
                }
            }
        });
    </script>
</body>

</html>
