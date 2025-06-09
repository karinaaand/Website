@php
use Carbon\Carbon;
@endphp
@extends('layouts.main')
@section('container')
<div class="rounded-lg bg-white p-6 shadow-lg">
    <div class="flex flex-1 justify-end mb-5">
        <button id="printButton" onclick="printModal()" class="rounded-lg bg-yellow-500 hover:bg-yellow-600 px-4 py-1 text-white">Cetak</button>
    </div>
    <div class="flex items-center justify-between w-full">
        <form action="" class="flex w-auto flex-row justify-between gap-3 ">
            <input class="rounded-sm px-2 py-1 ring-2 ring-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" name="" id="" />
            <h1 class="text-lg font-inter text-gray-800">sampai</h1>
            <input class="rounded-sm px-2 py-1 ring-2 ring-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" name="" id="" />
            <button class="rounded-2xl bg-blue-500 px-3 font-bold text-sm font-inter text-white hover:bg-blue-600"
                type="submit">
                TERAPKAN
            </button>
        </form>
        <form action="" class="flex">
            <input type="text" name="" id="searchInput" placeholder="Search..."
                class="rounded-full px-6 py-2 ring-2 ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </form>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-md mt-6">
        <table class="min-w-full text-sm text-center">
            <thead class="bg-gray-200">
                <th class="py-4">No</th>
                <th class="py-4">Kode Obat</th>
                <th class="py-4">Nama Obat</th>
                <th class="py-4">Stok</th>
                <th class="py-4">Expired Terdekat</th>
                <th class="py-4">Expired Terbaru</th>
                <th class="py-4">Action</th>
            </thead>
            <tbody id="tbody">
               
            </tbody>
        </table>
    </div>
    <!-- <div id="pagianation" class="p-6">
    </div> -->
</div>
<div id="printModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
            onclick="closePrintModal()">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
        <div class="text-center">
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Apa format file yang ingin Anda simpan?</h3>
            <p class="text-sm text-gray-500 mb-5">Pilihlah salah satu format file!</p>
        </div>
        <div class="flex justify-center space-x-4">
            <button onclick="exportToExcel()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                Excel
            </button>
            <button onclick="submitModal()" type="button"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Configuration
    const API_BASE_URL = 'http://localhost:8000/api/v1';
    const per_page = 100;
    const token = localStorage.getItem('token');

    // State variables
    let timeout = null;
    let selectedId;
    let query = "";
    let temporaryData;
    let data_stocks = null;
    let currentPage = 1;

    // API Client
    const api = axios.create({
        baseURL: API_BASE_URL,
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        }
    });

    // Event Listeners
    document.addEventListener('DOMContentLoaded', initializePage);

    // Initialize Page
    function initializePage() {
        if (token) {
            fetchStocks();
        }
    }

    // API Functions
    function fetchStocks(searchQuery = '', page = 1, dateFrom = '', dateTo = '') {
        const params = new URLSearchParams({
            per_page: per_page,
            search: searchQuery,
            page: page
        });

        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);

        api.get(`/reports/drugs?${params.toString()}`)
            .then(response => {
                data_stocks = response.data;
                renderStockTable(data_stocks);
                // updatePaginationInfo(data_stocks.data);
                // updatePagination(data_stocks.data);
            })
            .catch(error => {
                console.error('Gagal mengambil data stok:', error);
                showErrorMessage('Gagal memuat data stok');
            });
    }

    function handleDateFilter(event) {
        event.preventDefault();
        
        const dateFrom = dateFromInput?.value || '';
        const dateTo = dateToInput?.value || '';

        if (dateFrom && dateTo && dateFrom > dateTo) {
            showErrorMessage('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
            return;
        }

        fetchStocks(query, 1, dateFrom, dateTo);
    }

    function filterStockTable() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();

        const filteredData = globalStockData.filter(item =>
            item.drug_name.toLowerCase().includes(keyword) ||
            item.drug_code.toLowerCase().includes(keyword)
        );

        updateTable(filteredData);
    }

    function renderStockTable(stockData) {
        console.log('Rendering data:', stockData);
        const tbody = document.querySelector('tbody');
        if (!tbody) return;

        // Simpan data asli untuk filter
        globalStockData = stockData.data;

        // Render awal
        updateTable(globalStockData);
        // renderPagination(globalStockData);
    }

    function updateTable(data) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        // Hitung data yang akan ditampilkan di halaman saat ini
        const startIndex = (currentPage - 1) * per_page;
        const endIndex = startIndex + per_page;
        const paginatedData = data.slice(startIndex, endIndex);

        paginatedData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-200 hover:bg-gray-100';

            row.innerHTML = `
                <td class="py-3">${startIndex + index + 1}</td>
                <td class="py-3">${item.drug_code}</td>
                <td class="py-3">${item.drug_name}</td>
                <td class="py-3">${item.quantity} pcs</td>
                <td class="py-3">${item.oldest_expired}</td>
                <td class="py-3">${item.latest_expired}</td>
                <td class="flex justify-center py-3">
                    <button onclick="viewStockDetail(${item.id})" 
                            class="rounded-md bg-blue-500 p-2 hover:bg-blue-600 transition-colors duration-200">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.99972 2.5C14.4931 2.5 18.2314 5.73333 19.0156 10C18.2322 14.2667 14.4931 17.5 9.99972 17.5C5.50639 17.5 1.76805 14.2667 0.983887 10C1.76722 5.73333 5.50639 2.5 9.99972 2.5ZM9.99972 15.8333C11.6993 15.833 13.3484 15.2557 14.6771 14.196C16.0058 13.1363 16.9355 11.6569 17.3139 10C16.9341 8.34442 16.0038 6.86667 14.6752 5.80835C13.3466 4.75004 11.6983 4.17377 9.99972 4.17377C8.30113 4.17377 6.65279 4.75004 5.3242 5.80835C3.9956 6.86667 3.06536 8.34442 2.68555 10C3.06397 11.6569 3.99361 13.1363 5.32234 14.196C6.65106 15.2557 8.30016 15.833 9.99972 15.8333V15.8333ZM9.99972 13.75C9.00516 13.75 8.05133 13.3549 7.34807 12.6516C6.64481 11.9484 6.24972 10.9946 6.24972 10C6.24972 9.00544 6.64481 8.05161 7.34807 7.34835C8.05133 6.64509 9.00516 6.25 9.99972 6.25C10.9943 6.25 11.9481 6.64509 12.6514 7.34835C13.3546 8.05161 13.7497 9.00544 13.7497 10C13.7497 10.9946 13.3546 11.9484 12.6514 12.6516C11.9481 13.3549 10.9943 13.75 9.99972 13.75ZM9.99972 12.0833C10.5523 12.0833 11.0822 11.8638 11.4729 11.4731C11.8636 11.0824 12.0831 10.5525 12.0831 10C12.0831 9.44747 11.8636 8.91756 11.4729 8.52686C11.0822 8.13616 10.5523 7.91667 9.99972 7.91667C9.44719 7.91667 8.91728 8.13616 8.52658 8.52686C8.13588 8.91756 7.91639 9.44747 7.91639 10C7.91639 10.5525 8.13588 11.0824 8.52658 11.4731C8.91728 11.8638 9.44719 12.0833 9.99972 12.0833Z" fill="white"/>
                        </svg>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    // Utility Functions
    function formatDate(dateString) {
        if (!dateString) return '-';
        
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        try {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            
            return `${day} ${month} ${year}`;
        } catch (error) {
            return dateString;
        }
    }

    function changePage(page) {
        const dateFrom = dateFromInput?.value || '';
        const dateTo = dateToInput?.value || '';
        
        fetchStocks(query, page, dateFrom, dateTo);
    }

    function viewStockDetail(drugId) {
        window.location.href = `/report/drugs/${drugId}`;
    }

    function showErrorMessage(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    function showSuccessMessage(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Modal Functions
    function printModal() {
        const modal = document.getElementById('printModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closePrintModal() {
        const modal = document.getElementById('printModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function exportToExcel() {
        closePrintModal();
        
        const dateFrom = dateFromInput?.value || '';
        const dateTo = dateToInput?.value || '';
        const searchQuery = query || '';
        
        let exportUrl = '/export-excel';
        const params = new URLSearchParams();
        
        if (searchQuery) params.append('search', searchQuery);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        if (params.toString()) {
            exportUrl += '?' + params.toString();
        }
        
        window.location.href = exportUrl;
        showSuccessMessage('Mengunduh file Excel...');
    }

    function exportToPDF() {
        closePrintModal();
        
        const dateFrom = dateFromInput?.value || '';
        const dateTo = dateToInput?.value || '';
        const searchQuery = query || '';
        
        let exportUrl = '/export-pdf';
        const params = new URLSearchParams();
        
        if (searchQuery) params.append('search', searchQuery);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        if (params.toString()) {
            exportUrl += '?' + params.toString();
        }
        
        window.location.href = exportUrl;
        showSuccessMessage('Mengunduh file PDF...');
    }

    function submitModal() {
        exportToPDF();
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // ESC key to close modal
        if (event.key === 'Escape') {
            closePrintModal();
        }
        
        // Ctrl+P for print
        if (event.ctrlKey && event.key === 'p') {
            event.preventDefault();
            printModal();
        }
    });

    // Click outside modal to close
    document.getElementById('printModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closePrintModal();
        }
    });

</script>
@endsection
