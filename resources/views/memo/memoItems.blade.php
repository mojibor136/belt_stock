@extends('layouts.app')
@section('title', 'Customer Sales')
@section('content')
    <div class="bg-white w-full mb-6 flex flex-col">
        <div class="py-6 px-4 mb-4 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center text-center md:text-left gap-4">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <span class="text-gray-700 text-md">
                        Phone: <span class="font-medium">{{ $customer->phone }}</span>
                    </span>
                    <p class="font-medium text-md text-gray-700">Address: {{ $customer->address }}</p>
                    <p class="font-medium text-md text-gray-700">Memo No: <strong>{{ $memo->memo_no }}</strong></p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-2xl font-extrabold text-green-700">{{ $customer->name }}</span>
                    <img src="{{ asset($setting->site_logo) }}" alt="Logo" class="w-44 h-auto" />
                </div>

                <div class="flex flex-col items-center md:items-end gap-2 text-gray-700">
                    <p class="font-medium text-md">Email: {{ $customer->email }}</p>
                    <p class="font-medium text-md text-gray-700">Transport: {{ $customer->transport }}</p>
                    <p class="font-medium text-md">Customer ID: #{{ $customer->id }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto rounded">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-600 text-white text-sm font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Brand</th>
                                <th class="px-4 py-3 text-center">Group</th>
                                <th class="px-4 py-3 text-center">Size</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-center">Sales Date</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        @php $serial = 1; @endphp
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            @forelse ($memo->items as $item)
                                @foreach ($item->sizes as $size)
                                    <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                                        <td class="px-4 py-3">{{ $serial++ }}</td>
                                        <td class="px-4 py-3">{{ $item->brand->brand ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">{{ $item->group->group ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">{{ $size->size }}</td>
                                        <td class="px-4 py-3 text-center font-semibold">{{ $size->quantity }}</td>
                                        <td class="px-4 py-3 text-center text-gray-600">
                                            {{ $memo->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <span onclick="window.location='{{ route('memo.items.return', $size->id) }}'"
                                                class="px-3 py-2 rounded text-white capitalize text-sm font-medium bg-red-600 cursor-pointer hover:bg-red-700">
                                                Return
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                        কোনো ডাটা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: null
            });
        });
    </script>
@endpush
