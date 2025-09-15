@extends('layouts.app')
@section('title', 'Create Memo')
@section('content')
    @include('components.toast')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #f3f8ff;
        }

        .select2-container--default .select2-selection--single {
            border-color: rgb(209 213 219 / var(--tw-border-opacity, 1)) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
            height: 100%;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            line-height: 32px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 8px;
            padding-right: 20px;
            overflow: visible !important;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-results__option {
            white-space: normal;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 1px #2563eb !important;
            outline: none !important;
        }

        .select2-container--default .select2-results__option {
            color: #374151 !important;
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        .border {
            border-color: rgb(209 213 219 / var(--tw-border-opacity, 1)) !important;
        }

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

    <div class="p-6 bg-white mb-6 rounded shadow border border-gray-200">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">📝 Create Memo</h1>
        <form action="{{ route('memo.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Memo No</label>
                    <input type="text" name="memo_no"
                        class="border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none px-3 h-10 text-gray-700 w-full rounded"
                        placeholder="5050">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                    <input type="text" name="created_at" id="created_at" placeholder="dd/mm/yyyy"
                        class="w-full px-3 h-10 border rounded border-gray-300 text-gray-700 focus:ring-1 focus:ring-blue-600 focus:outline-none"
                        value="{{ old('created_at', date('d/m/Y')) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Select Customer</label>
                    <select id="customer-select" name="customer"
                        class="customer-select w-full border border-gray-300 rounded px-3 h-10 text-sm focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                        <option value="">Select a customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="customer-info">
                </div>
                <input type="hidden" id="debit_credit_status" name="debit_credit_status">
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mt-4 mb-2">📦 Memo Items</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border p-2">Brand</th>
                            <th class="border p-2">Group</th>
                            <th class="border p-2">Size & Quantity</th>
                            <th class="border p-2">Inch Rate</th>
                            <th class="border p-2">Piece Rate</th>
                            <th class="border p-2">Subtotal</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-container" class="text-gray-700 hidden">
                        <tr class="item-row hover:bg-gray-50 transition">
                            <td class="border p-2 text-center">
                                <select name="items[0][brand_id]"
                                    class="brand-select border border-gray-300 h-10 px-2 w-32 rounded focus:ring-1 focus:ring-blue-600 focus:outline-none"
                                    required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border p-2 text-center">
                                <select name="items[0][group_id]"
                                    class="group-select border border-gray-300 h-10 px-2 w-32 rounded focus:ring-1 focus:ring-blue-600 focus:outline-none"
                                    required>
                                    <option value="">Select Group</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <div class="flex flex-col sizes-container">
                                    <div class="size-row w-full flex flex-row gap-2">
                                        <select name="items[0][sizes][0][size]"
                                            class="size-select px-2 h-full flex-1 border-r text-center focus:outline-none focus:ring-0"
                                            required>
                                            <option value="">Select Size</option>
                                        </select>
                                        <div class="flex size items-center justify-center border rounded h-10 w-full">
                                            <input type="number" name="items[0][sizes][0][quantity]"
                                                class="px-2 h-full w-full min-w-0 qty-input border-none text-center focus:outline-none focus:ring-0"
                                                placeholder="Qty">
                                            <button type="button"
                                                class="remove-size bg-red-500 text-white px-3.5 h-full w-10 flex items-center justify-center hover:bg-red-600">✖</button>
                                            <button type="button"
                                                class="add-size text-2xl w-10 bg-blue-200 px-3 h-full flex items-center justify-center">+</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="border p-2 text-center">
                                <input type="text" name="items[0][rate]" placeholder="0"
                                    class="border px-2 rounded h-10 w-full text-center rate-input focus:outline-none focus:ring-0">
                            </td>
                            <td class="border p-2 text-center">
                                <input type="text" name="items[0][piece_rate]" placeholder="0"
                                    class="border px-2 rounded h-10 w-full text-center piece-rate-input focus:outline-none focus:ring-0">
                            </td>
                            <td class="border p-2 text-center">
                                <div
                                    class="subtotal-cell border bg-gray-100 h-10 w-full px-4 rounded flex items-center justify-center">
                                    0</div>
                            </td>
                            <td class="border py-2 px-2 text-center">
                                <button type="button"
                                    class="remove-row px-2 h-10 w-full bg-red-100 rounded">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center">
                <button type="button" id="add-row" class="bg-gray-800 text-white px-5 py-2 w-40 rounded h-10 shadow">+
                    Add
                    Row</button>

                <div class="flex flex-col w-40 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700">৳</span>
                    <input type="number" name="debit" id="debit" placeholder="0.00" readonly
                        class="pl-7 pr-3 py-2 border rounded text-gray-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="flex flex-col w-40 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-700">৳</span>
                    <input type="number" name="total" id="total" placeholder="0.00" disabled
                        class="pl-7 pr-3 py-2 border rounded text-gray-800 bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            <div class="text-right mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 w-40 rounded h-10 shadow">💾 Save
                    Memo</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let sizesArray = {};
        let rowIndex = 1;

        function initSelect2() {
            $('.customer-select').select2({
                placeholder: "-- Select Customer --",
                width: '100%'
            });
            $('.group-select').select2({
                placeholder: "Select Group",
                width: '100%'
            });
            $('.brand-select').select2({
                placeholder: "Select Brand",
                width: '100%'
            });
            $('.size-select').select2({
                placeholder: "Select Size",
                width: '100%'
            });
        }

        $(document).ready(function() {
            initSelect2();
            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: "{{ old('created_at', date('d/m/Y')) }}"
            });
            $('.sizes-container .size-row:first .remove-size').hide();
        });

        let customerStatus = '';

        $('#customer-select').on('change', function() {
            let customerID = $(this).val();

            if (customerID) {
                $('#items-container').removeClass('hidden');
            } else {
                $('#items-container').addClass('hidden');
                $('#customer-info').html('');
                $('#debit').val('');
                $('#debit_credit_status').val('');
                recalcGrandTotal();
            }

            if (customerID) {
                $.ajax({
                    url: '/get-customer-data/' + customerID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let html = `
                <div class="p-4 bg-white border border-gray-100 rounded space-y-2">
                    <p><strong class="text-gray-700">Name:</strong> 
                       <span class="text-green-600 font-semibold">${data.name}</span></p>
                    <p><strong class="text-gray-700">Phone:</strong> 
                       <span class="text-blue-600 font-medium">${data.phone}</span></p>
                    <p><strong class="text-gray-700">Address:</strong> 
                       <span class="text-gray-800">${data.address}</span></p>
                </div>
                `;
                        $('#customer-info').html(html);

                        customerStatus = data.status ? data.status.toLowerCase() : '';

                        if (data.amount) {
                            $('#debit').val(data.amount);
                            $('#debit_credit_status').val(data.status);
                            recalcGrandTotal();
                        }
                    },
                    error: function() {
                        alert('Failed to fetch customer data!');
                    }
                });
            } else {
                $('#customer-info').html('');
                $('#debit').val('');
                $('#debit_credit_status').val('');
                recalcGrandTotal();
            }
        });

        $(document).on('change', '.brand-select', function() {
            let tr = $(this).closest('tr');
            let brandID = $(this).val();
            let groupSelect = tr.find('.group-select');
            groupSelect.empty().append('<option value="">Select Group</option>');
            if (brandID) {
                $.ajax({
                    url: '/get-groups/' + brandID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, value) {
                            groupSelect.append('<option value="' + value.id + '">' + value
                                .group + '</option>');
                        });
                        groupSelect.trigger('change.select2');
                    },
                    error: function() {
                        alert('Failed to fetch groups!');
                    }
                });
            }
        });
        $(document).on('change', '.group-select', function() {
            let tr = $(this).closest('tr');
            let groupID = $(this).val();
            let rateInput = tr.find('.rate-input');
            if (groupID) {
                $.ajax({
                    url: '/get-group-data/' + groupID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        rateInput.val(data.rate);
                        sizesArray[groupID] = data.sizes;
                        let sizeSelect = tr.find('.size-select');
                        sizeSelect.empty().append('<option value="">Select Size</option>');
                        $.each(data.sizes, function(idx, sizeObj) {
                            sizeSelect.append('<option value="' + sizeObj.size + '">' + sizeObj
                                .size + '</option>');
                        });
                    },
                    error: function() {
                        alert('Failed to fetch group data!');
                    }
                });
            }
        });

        document.getElementById('add-row').addEventListener('click', function() {
            let totalSizes = document.querySelectorAll('.sizes-container .size-row').length;
            if (totalSizes >= 70) {
                alert('আপনি সর্বোচ্চ ১৪টি সারি (row) যোগ করতে পারবেন!');
                return;
            }

            const container = document.getElementById('items-container');
            const templateRow = container.querySelector('.item-row');

            if (totalSizes >= 70) {
                alert('আপনি সর্বোচ্চ ১৪টি সারি (row) যোগ করতে পারবেন!');
                return;
            }

            let currentRowCount = 0;
            $('#items-container .item-row').each(function() {
                let sizeCount = $(this).find('.size-row').length;
                currentRowCount += Math.ceil(sizeCount / 5);
            });

            if (currentRowCount >= 14) {
                alert('সব মিলিয়ে সর্বোচ্চ ৭০টি সাইজ যোগ করা যাবে!');
                return;
            }

            let newRow = templateRow.cloneNode(true);

            let sizesContainer = newRow.querySelector('.sizes-container');
            let sizeRows = sizesContainer.querySelectorAll('.size-row');
            sizeRows.forEach((row, idx) => {
                if (idx > 0) row.remove();
                else {
                    let select = row.querySelector('.size-select');
                    let qty = row.querySelector('.qty-input');
                    if (select) select.selectedIndex = 0;
                    if (qty) qty.value = '';
                }
            });

            $(newRow).find('.select2-container').remove();

            newRow.querySelectorAll('input').forEach(el => el.value = '');
            let brandSelect = newRow.querySelector('.brand-select');
            let groupSelect = newRow.querySelector('.group-select');
            let sizeSelect = newRow.querySelector('.size-select');
            brandSelect.selectedIndex = 0;
            groupSelect.innerHTML = '<option value="">Select Group</option>';
            sizeSelect.innerHTML = '<option value="">Select Size</option>';
            newRow.querySelector('.subtotal-cell').innerText = '0';

            newRow.querySelectorAll('input, select').forEach(el => {
                el.name = el.name.replace(/\d+/, rowIndex);
            });

            container.appendChild(newRow);
            initSelect2();
            rowIndex++;
        });


        document.addEventListener('click', function(e) {
            let tr, container;

            if (e.target.classList.contains('add-size')) {
                let totalSizes = document.querySelectorAll('.sizes-container .size-row').length;
                if (totalSizes >= 70) {
                    alert('সব মিলিয়ে সর্বোচ্চ ৭০টি সাইজ যোগ করা যাবে!');
                    return;
                }

                tr = e.target.closest('tr');
                container = tr.querySelector('.sizes-container');

                let firstSizeRow = container.querySelector('.size-row');
                let newSize = firstSizeRow.cloneNode(true);
                $(newSize).find('.select2-container').remove();

                let select = newSize.querySelector('.size-select');
                let qty = newSize.querySelector('.qty-input');
                if (select) select.selectedIndex = 0;
                if (qty) qty.value = '';

                let sizeCount = container.querySelectorAll('.size-row').length;
                newSize.querySelectorAll('input, select').forEach(el => {
                    el.name = el.name.replace(/\[sizes\]\[\d+\]/, `[sizes][${sizeCount}]`);
                });

                let addBtn = newSize.querySelector('.add-size');
                if (addBtn) addBtn.remove();

                let removeBtn = newSize.querySelector('.remove-size');
                if (removeBtn) removeBtn.style.display = "flex";

                container.appendChild(newSize);
                initSelect2();
                recalcSubtotal(tr);
            }

            if (e.target.classList.contains('remove-size')) {
                tr = e.target.closest('tr');
                container = tr.querySelector('.sizes-container');
                if (container.querySelectorAll('.size-row').length > 1) {
                    e.target.closest('.size-row').remove();
                    recalcSubtotal(tr);
                }
            }

            if (e.target.classList.contains('remove-row')) {
                tr = e.target.closest('tr');
                tr.remove();
                recalcGrandTotal();
            }
        });

        function recalcSubtotal(tr) {
            let subtotal = 0;
            let rate = parseFloat($(tr).find('.rate-input').val()) || 0;
            let pieceRate = parseFloat($(tr).find('.piece-rate-input').val()) || 0;
            $(tr).find('.size-row').each(function() {
                let size = parseFloat($(this).find('.size-select').val()) || 0;
                let qty = parseFloat($(this).find('.qty-input').val()) || 0;
                if (rate > 0 && size > 0) subtotal += size * rate * qty;
                else subtotal += pieceRate * qty;
            });
            $(tr).find('.subtotal-cell').text(subtotal.toFixed(2));
            recalcGrandTotal();
        }
        $(document).on('input', '.size-select,.qty-input,.rate-input,.piece-rate-input', function() {
            recalcSubtotal($(this).closest('tr')[0]);
        });

        function recalcGrandTotal() {
            let total = 0;

            $('#items-container .item-row').each(function() {
                total += parseFloat($(this).find('.subtotal-cell').text()) || 0;
            });

            let debit = parseFloat($('#debit').val()) || 0;

            if (customerStatus === 'debit') {
                total += debit;
            } else if (customerStatus === 'credit') {
                total -= debit;
            }

            $('#total').val(total.toFixed(2));
        }
    </script>
@endpush
