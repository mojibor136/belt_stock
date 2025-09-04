@extends('layouts.app')
@section('title', 'Size Management')
@section('content')
    @include('components.toast')
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-1 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Size Management</h1>
                <p class="text-sm text-gray-500">Manage your sizes and their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href="{{ route('sizes.create') }}"
                    class="flex items-center gap-2 h-10 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-add-line text-lg"></i>Add Size
                </a>

                <button
                    class="flex items-center gap-2 h-10 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <form action="{{ route('sizes.index') }}" method="GET"
                class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">
                <div class="relative w-full sm:w-1/2">
                    <input type="text" name="search" placeholder="Search sizes..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="brand"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="group"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">Select Group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                                {{ $group->group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </form>

            <a href="{{ route('sizes.index') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Sizes</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-center">Group</th>
                        <th class="px-4 py-3 text-center">Sales Rate</th>
                        <th class="px-4 py-3 text-center">Stock</th>
                        <th class="px-4 py-3 text-center">Created At</th>
                        <th class="px-4 py-3 text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse ($sizes as $index => $size)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $size->size }}</td>
                            <td class="px-4 py-3 capitalize">{{ $size->brand->brand }}</td>
                            <td class="px-4 py-3 text-center">{{ $size->group->group }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ $size->sales_rate }}
                                @if ($size->rate_type == 'pieces')
                                    Ps
                                @elseif($size->rate_type == 'inch')
                                    Inch
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">{{ $size->stocks->count() }}</td>
                            <td class="px-4 py-3 text-center">{{ $size->created_at->format('d M, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end items-center gap-1">
                                    <a href="{{ route('sizes.edit', $size->id) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow"
                                        title="Edit">
                                        <i class="ri-edit-line text-lg"></i>
                                    </a>

                                    <a href="{{ route('sizes.show', $size->id) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded shadow"
                                        title="View">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>

                                    <form action="{{ route('sizes.destroy', $size->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-10 h-8 bg-red-500 hover:bg-red-600 text-white rounded shadow cursor-pointer"
                                            title="Delete">
                                            <i class="ri-delete-bin-6-line text-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                No sizes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
