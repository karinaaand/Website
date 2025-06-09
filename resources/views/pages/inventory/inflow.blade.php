@extends('layouts.main')
@php
        use Carbon\Carbon;
    @endphp
@section('container')
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="mb-4">
            <a href="{{ route('inventory.inflows.create') }}">
                <button
                    class="bg-blue-500 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors duration-200">+
                    Tambah</button>
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-3 px-6 text-center w-1">No</th>
                        <th class="py-3 px-6 text-center">Kode LPB</th>
                        <th class="py-3 px-6 text-center">Nama Vendor</th>
                        <th class="py-3 px-6 text-center">Tanggal Masuk</th>
                        <th class="py-3 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700" id="inflow-data">
                    <!-- Data dari API akan dimasukkan di sini -->
                </tbody>
            </table>
        </div>

    </div>
     <!-- Pagination and Showing Info Section -->
    <div class="flex items-center justify-between p-4">
        <div>
            <p class="text-gray-700 text-sm" id="pagination-info">
                Showing 0 to 0 of 0 results
            </p>
        </div>
        <div id="pagination-div">
            {{-- Pagination will be populated by JavaScript --}}
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const API_BASE_URL = 'http://localhost:8000/api/v1';
    const per_page = 10;
    const token = localStorage.getItem('token');

    let timeout = null;
    let query = "";
    let temporaryData;
    let data_inflow = null;
    let selectedId;
    let currentPage = 1;

    // const inflowInput = document.getElementById('inflow-search');

    const api = axios.create({
        baseURL: API_BASE_URL,
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        }
    });

    document.addEventListener('DOMContentLoaded', initializePage);
    // inflowInput.addEventListener('input', handleSearchInput);

    function initializePage() {
        if (token) {
            fetchInflow();
        }
    }

    function fetchInflow() {
        api.get(`/inventory/inflows?per_page=${per_page}`)
            .then(response => {
                console.log('Response data:', response.data);
                data_inflow = response.data;
                renderInflowTable(data_inflow);
                updatePaginationInfo(data_inflow.pagination);
            })
            .catch(error => {
                console.error('Gagal mengambil data inventory inflow:', error);
            });
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function renderInflowTable(data) {
        console.log('Rendering data:', data);
        const tbody = document.querySelector('tbody');
        if (!tbody) return;

        // Simpan data asli untuk filter
        globalData = data.data;
        globalPage = data.pagination;

        // Render awal
        updateTable(globalData, globalPage);
        renderPagination(globalPage);
    }

    function updateTable(data, page) {
        const tbody = document.getElementById('inflow-data');
        tbody.innerHTML = '';

        // Hitung data yang akan ditampilkan di halaman saat ini
        const startIndex = (currentPage - 1) * per_page;
        const current = (page.current_page - 1) * per_page;
        const endIndex = startIndex + per_page;
        const paginatedData = data.slice(startIndex, endIndex);

        // response.data adalah array inflows langsung
        paginatedData.forEach((item, index) => {
            const row = document.createElement('tr');
            // console.log(item);
            row.innerHTML = `
            <td class="py-3 px-6 text-center">${current + index + 1}</td>
            <td class="py-3 px-6 text-center">${item['No. LPB']}</td>
            <td class="py-3 px-6 text-center">${item['Vendor']}</td>
            <td class="py-3 px-6 text-center">${item['Date']}</td>
            <td class="flex justify-center py-3">
                <a href="/inventory/inflows/${item['id']}"
                    class="bg-blue-500 hover:bg-blue-600 p-2 rounded-md">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                        d="M9.99972 2.5C14.4931 2.5 18.2314 5.73333 19.0156 10C18.2322 14.2667 14.4931 17.5 9.99972 17.5C5.50639 17.5 1.76805 14.2667 0.983887 10C1.76722 5.73333 5.50639 2.5 9.99972 2.5ZM9.99972 15.8333C11.6993 15.833 13.3484 15.2557 14.6771 14.196C16.0058 13.1363 16.9355 11.6569 17.3139 10C16.9341 8.34442 16.0038 6.86667 14.6752 5.80835C13.3466 4.75004 11.6983 4.17377 9.99972 4.17377C8.30113 4.17377 6.65279 4.75004 5.3242 5.80835C3.9956 6.86667 3.06536 8.34442 2.68555 10C3.06397 11.6569 3.99361 13.1363 5.32234 14.196C6.65106 15.2557 8.30016 15.833 9.99972 15.8333V15.8333ZM9.99972 13.75C9.00516 13.75 8.05133 13.3549 7.34807 12.6516C6.64481 11.9484 6.24972 10.9946 6.24972 10C6.24972 9.00544 6.64481 8.05161 7.34807 7.34835C8.05133 6.64509 9.00516 6.25 9.99972 6.25C10.9943 6.25 11.9481 6.64509 12.6514 7.34835C13.3546 8.05161 13.7497 9.00544 13.7497 10C13.7497 10.9946 13.3546 11.9484 12.6514 12.6516C11.9481 13.3549 10.9943 13.75 9.99972 13.75ZM9.99972 12.0833C10.5523 12.0833 11.0822 11.8638 11.4729 11.4731C11.8636 11.0824 12.0831 10.5525 12.0831 10C12.0831 9.44747 11.8636 8.91756 11.4729 8.52686C11.0822 8.13616 10.5523 7.91667 9.99972 7.91667C9.44719 7.91667 8.91728 8.13616 8.52658 8.52686C8.13588 8.91756 7.91639 9.44747 7.91639 10C7.91639 10.5525 8.13588 11.0824 8.52658 11.4731C8.91728 11.8638 9.44719 12.0833 9.99972 12.0833Z"
                        fill="white" />
                    </svg>
                </a>
            </td>
            `;

            tbody.appendChild(row);
        });
    }

    function updatePaginationInfo(data) {
        console.log('Pagination data:', data);
        const start = ((data.current_page - 1) * data.per_page) + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        const total = data.total;

        document.getElementById('pagination-info').textContent =
            `Showing ${start} to ${end} of ${total} results`;
    }

    function renderPagination(pagination) {
        const currentPage = pagination.current_page;
        const lastPage = pagination.last_page;
        // console.log('Current Page:', currentPage);
        
        let elements = '<nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">';

        // Previous button
        elements += `<span onclick="${currentPage === 1 ? '' : `getDataPage(${currentPage - 1})`}"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium
                ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer hover:bg-gray-50'}
                bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                ${currentPage === 1 ? 'disabled aria-disabled="true"' : ''}>
                &lsaquo;
                </span>`;

        // Tombol-tombol halaman
        // Tampilkan maksimal 5 tombol halaman
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, currentPage + 2);
        
        if (currentPage <= 3) {
            endPage = Math.min(5, lastPage);
        }
        
        if (currentPage >= lastPage - 2) {
            startPage = Math.max(1, lastPage - 4);
        }

        // Tombol pertama jika diperlukan
        if (startPage > 1) {
            elements += `<span onclick="getDataPage(1)" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">1</span>`;
            if (startPage > 2) {
                elements += '<span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5">...</span>';
            }
        }

        // Tombol halaman
        for (let i = startPage; i <= endPage; i++) {
            elements += `<span onclick="getDataPage(${i})" 
                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium
                ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}
                border border-gray-300 leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                ${i}
                </span>`;
        }

        // Tombol terakhir jika diperlukan
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                elements += '<span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5">...</span>';
            }
            elements += `<span onclick="getDataPage(${lastPage})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">${lastPage}</span>`;
        }

        // Next button
        elements += `<span onclick="${currentPage === lastPage ? '' : `getDataPage(${currentPage + 1})`}"
                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium
                ${currentPage === lastPage ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer hover:bg-gray-50'}
                bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                ${currentPage === lastPage ? 'disabled aria-disabled="true"' : ''}>
                &rsaquo;
                </span>`;
        elements += '</nav>';

        document.getElementById("pagination-div").innerHTML = elements;
    }

    // Pagination handler
    function getDataPage(page) {
        if (query.length > 0) {
            api.get(`/inventory/inflows?per_page=${per_page}`)
                .then(response => {
                    temporaryData = response.data;
                    renderInflowTable(temporaryData);
                    updatePaginationInfo(temporaryData.data);
                });
        } else {
            fetchInflow('', page);
        }
    }
</script>

@endsection
