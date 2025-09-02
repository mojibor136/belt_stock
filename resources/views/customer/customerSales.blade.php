@extends('layouts.app')
@section('title', 'Customer Sales')

@section('content')
    <div class="bg-white w-full mb-6 flex flex-col">
        <div
            class="py-6 bg-white rounded flex flex-col md:flex-row justify-between items-center md:items-start gap-6 border-b border-gray-200">
            <div class="flex flex-col items-center md:items-start gap-1 w-full md:w-1/2">
                <span class="text-3xl font-extrabold text-green-700">Md Mojibor Rahman</span>
                <span class="text-gray-600 text-base">Phone: <span class="font-medium">01311890283</span></span>
            </div>
            <div class="flex flex-col items-center md:items-end gap-2 w-full md:w-1/2 text-gray-700">
                <p class="font-medium text-sm">Dhaka Nawabpur</p>
                <p class="font-medium text-sm">mojibor@gmail.com</p>
            </div>
        </div>

        <form method="GET" action="" class="py-4 flex items-center gap-2">
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                placeholder="Search sales..."
                class="flex-grow px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <button type="button" id="searchBtn"
                class="w-10 h-10 text-sm bg-indigo-600 rounded text-white hover:bg-indigo-700 transition" title="Search">
                <i class="ri-search-line text-lg"></i>
            </button>
            <button type="button" id="clearBtn"
                class="w-20 h-10 flex items-center justify-center bg-red-600 rounded text-sm text-white hover:bg-red-700 transition"
                title="Clear Search">
                Clear
            </button>
        </form>

        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto rounded">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-600 text-white text-sm font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Group</th>
                                <th class="px-4 py-3 text-left">Sizes</th>
                                <th class="px-4 py-3 text-left">Brand</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-center">Update Date</th>
                                <th class="px-4 py-3 text-right pr-8">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            @php
                                $brands = [
                                    ['size' => 40, 'brand' => 'Samsung', 'group' => 'A'],
                                    ['size' => 50, 'brand' => 'Apple', 'group' => 'B'],
                                    ['size' => 60, 'brand' => 'Sony', 'group' => 'C'],
                                    ['size' => 70, 'brand' => 'LG', 'group' => 'D'],
                                    ['size' => 40, 'brand' => 'Nokia', 'group' => 'E'],
                                    ['size' => 50, 'brand' => 'OnePlus', 'group' => 'HM'],
                                    ['size' => 60, 'brand' => 'Huawei', 'group' => 'AX'],
                                    ['size' => 70, 'brand' => 'Dell', 'group' => 'A'],
                                    ['size' => 40, 'brand' => 'HP', 'group' => 'B'],
                                    ['size' => 50, 'brand' => 'Xiaomi', 'group' => 'C'],
                                ];
                            @endphp

                            @foreach ($brands as $index => $brand)
                                <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-left">{{ $brand['group'] }}</td>
                                    <td class="px-4 py-3">{{ $brand['size'] }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $brand['brand'] }}</td>
                                    <td class="px-4 py-3 text-center">120</td>
                                    <td class="px-4 py-3 text-center">{{ now()->format('d M, Y') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end items-center">
                                            <a href="#"
                                                class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded shadow transition">
                                                Return
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchBtn').addEventListener('click', filterTable);
        document.getElementById('clearBtn').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            filterTable();
        });

        function filterTable() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#transactionsTable tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(query) ? '' : 'none';
            });
        }
    </script>
@endpush
