@extends('layouts.app')
@section('title', 'Vendor Transaction')
@section('content')
    @include('components.toast')
    <style>
        #created_at,
        .flatpickr-input {
            background-color: white !important;
        }

        #created_at:focus,
        .flatpickr-input:focus {
            background-color: white !important;
            border-color: #2563eb !important;
            outline: none !important;
            box-shadow: 0 0 0 1px #2563eb !important;
        }
    </style>

    <div class="bg-white w-full mb-6 flex flex-col">
        <div
            class="py-4 bg-white rounded flex flex-col md:flex-row justify-between items-center md:items-start gap-6 border-b border-gray-200">
            <div class="flex flex-col items-center md:items-start gap-1 w-full md:w-1/2">
                <span class="text-3xl font-extrabold text-green-700">{{ $vendor->name }}</span>
                <span class="text-gray-600 text-base">Phone: <span class="font-medium">{{ $vendor->phone }}</span></span>
            </div>
            <div class="flex flex-col items-center md:items-end gap-1 w-full md:w-1/2 text-gray-700">
                <p class="font-medium text-sm">Address: {{ $vendor->address }}</p>
                <p class="font-medium text-sm">Email: {{ $vendor->email }}</p>
            </div>
        </div>

        <form method="GET"
            action="{{ route('vendor.all.transaction', [\Illuminate\Support\Str::slug($vendor->name), $vendor->id]) }}"
            class="py-4 flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..."
                class="flex-grow px-4 py-2 border border-gray-300 text-gray-700 rounded focus:outline-none focus:ring-1 focus:ring-blue-600" />

            <div class="relative w-full sm:w-1/3">
                <input type="date" id="created_at" name="date" value="{{ request('date') }}" placeholder="dd/mm/yyyy"
                    class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
            </div>

            <button type="submit" class="w-10 h-10 text-sm bg-indigo-600 rounded text-white hover:bg-indigo-700 transition"
                title="Search">
                <i class="ri-search-line text-lg"></i>
            </button>

            <a href="{{ route('vendor.all.transaction', [\Illuminate\Support\Str::slug($vendor->name), $vendor->id]) }}"
                class="w-20 h-10 flex items-center justify-center bg-red-600 rounded text-sm text-white hover:bg-red-700 transition"
                title="Reset Search">
                Reset
            </a>
        </form>

        <div class="bg-white">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto bg-white rounded shadow">
                    <table id="transactionsTable" class="w-full border-collapse table-auto rounded">
                        <thead>
                            <tr class="bg-blue-600 text-white text-sm font-semibold">
                                <th class="px-4 py-4 text-left text-xs uppercase">Date</th>
                                <th class="px-4 py-4 text-left text-xs uppercase">Type</th>
                                <th class="px-4 py-4 text-left text-xs uppercase">Payment</th>
                                <th class="px-4 py-4 text-left text-xs uppercase">Invoice</th>
                                <th class="px-4 py-4 text-left text-xs uppercase">Credit / Debit</th>
                                <th class="px-4 py-4 text-left text-xs uppercase">Amount</th>
                                <th class="px-4 py-4 text-center text-xs uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr
                                    class="border-b hover:bg-gray-50 text-gray-700 text-sm cursor-pointer {{ $trx->invoice_status === 'invoice' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <td class="px-4 py-3">{{ $trx->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $trx->invoice_type }}</td>
                                    <td class="px-4 py-3"> ৳ {{ number_format($trx->payment) }} .00</td>
                                    <td class="px-4 py-3"> ৳ {{ number_format($trx->invoice) }} .00</td>
                                    <td class="px-4 py-3">
                                        @if ($trx->status === 'credit')
                                            <span
                                                class="px-8 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow">
                                                Credit
                                            </span>
                                        @else
                                            <span
                                                class="px-8 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-400 to-red-600 text-white shadow">
                                                Debit
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 font-semibold {{ $trx->status == 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        ৳ {{ number_format($trx->debit_credit) }} .00
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('vendor.transactions.destroy', $trx->id) }}" method="POST"
                                            onsubmit="return confirm('আপনি কি এই লেনদেনটি মুছে ফেলতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-6 py-1 text-white bg-red-600 hover:bg-red-700 rounded-3xl text-xs font-semibold transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
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
