@extends('layouts.main')

@section('container')
<div class="p-6 bg-white rounded-lg shadow-lg">
    <title>List Stok Obat</title>

    <div class="flex justify-end">
        <form action="">
            <input type="text" name="inventory-stock-search" id="inventory-stock-search" placeholder="Search..."
                class="ring-2 ring-gray-300 rounded-full px-6 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-6 text-center w-1">No</th>
                    <th class="py-3 px-6 text-center">Kode Obat</th>
                    <th class="py-3 px-6 text-center">Nama Obat</th>
                    <th class="py-3 px-6 text-center">Jumlah</th>
                    <th class="py-3 px-6 text-center">Expired Terdekat</th>
                    <th class="py-3 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="stock-data"></tbody>
            <tbody id="stock-value" class="hidden"></tbody>
        </table>
    </div>

    <div class="p-6" id="pagination-div">
        <!-- Pagination will be rendered here -->
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Configuration
    const API_BASE_URL = 'http://localhost:8000/api/v1';
    const per_page = 5;
    const token = localStorage.getItem('token');

    // State variables
    let timeout = null;
    let query = "";
    let data_stocks = null;

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
    document.getElementById('inventory-stock-search').addEventListener('input', handleSearchInput);

    // Initialize Page
    function initializePage() {
        fetchStocks();
    }

    // API Functions
    function fetchStocks(searchQuery = '', page = 1) {
        api.get(`/inventory/stocks?per_page=${per_page}&search=${searchQuery}&page=${page}`)
            .then(response => {
                data_stocks = response.data;
                renderStockTable(data_stocks);
            })
            .catch(error => {
                console.error('Gagal mengambil data stok:', error);
            });
    }

    // Event Handlers
    function handleSearchInput() {
        clearTimeout(timeout);
        query = this.value;

        timeout = setTimeout(() => {
            if (query.length > 0) {
                api.get(`/inventory/stocks/search?query=${query}&per_page=${per_page}`)
                    .then(response => {
                        renderStockTable(response.data);
                    })
                    .catch(error => {
                        console.error('Gagal mencari data stok:', error);
                    });
            } else {
                renderStockTable(data_stocks);
            }
        }, 400);
    }

    function renderStockTable(data) {
        const tbody = document.getElementById("stock-data");
        tbody.innerHTML = ""; // Clear existing rows

        data.data.forEach((item, index) => {
            const row = document.createElement("tr");
            row.className = "border-b border-gray-200 hover:bg-gray-100";

            // Create table cells
            const noCell = createTableCell("py-3 px-6", index + 1);
            const codeCell = createTableCell("py-3 px-6", item.drug.code);
            const nameCell = createTableCell("py-3 px-6 text-left", item.drug.name);
            const quantityCell = createTableCell("py-3 px-6", item.quantity / item.drug.piece_netto);
            const expiredCell = createTableCell("py-3 px-6", item.oldest);
            const actionCell = createActionCell(item);

            // Append cells to row
            row.appendChild(noCell);
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            row.appendChild(quantityCell);
            row.appendChild(expiredCell);
            row.appendChild(actionCell);

            // Append row to table
            tbody.appendChild(row);
        });

        // Render pagination
        renderPagination(data);
    }

    function createTableCell(className, content) {
        const cell = document.createElement("td");
        cell.className = className;
        cell.textContent = content;
        return cell;
    }

    function createActionCell(item) {
        const cell = document.createElement("td");
        cell.className = "py-3 px-6 flex justify-center";

        // Detail button
        const detailBtn = document.createElement("a");
        detailBtn.className = "bg-blue-500 hover:bg-blue-600 p-2 rounded-md";
        detailBtn.href = `/inventory/stocks/${item.drug.id}`;
        detailBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.99972 2.5C14.4931 2.5 18.2314 5.73333 19.0156 10C18.2322 14.2667 14.4931 17.5 9.99972 17.5C5.50639 17.5 1.76805 14.2667 0.983887 10C1.76722 5.73333 5.50639 2.5 9.99972 2.5ZM9.99972 15.8333C11.6993 15.833 13.3484 15.2557 14.6771 14.196C16.0058 13.1363 16.9355 11.6569 17.3139 10C16.9341 8.34442 16.0038 6.86667 14.6752 5.80835C13.3466 4.75004 11.6983 4.17377 9.99972 4.17377C8.30113 4.17377 6.65279 4.75004 5.3242 5.80835C3.9956 6.86667 3.06536 8.34442 2.68555 10C3.06397 11.6569 3.99361 13.1363 5.32234 14.196C6.65106 15.2557 8.30016 15.833 9.99972 15.8333V15.8333ZM9.99972 13.75C9.00516 13.75 8.05133 13.3549 7.34807 12.6516C6.64481 11.9484 6.24972 10.9946 6.24972 10C6.24972 9.00544 6.64481 8.05161 7.34807 7.34835C8.05133 6.64509 9.00516 6.25 9.99972 6.25C10.9943 6.25 11.9481 6.64509 12.6514 7.34835C13.3546 8.05161 13.7497 9.00544 13.7497 10C13.7497 10.9946 13.3546 11.9484 12.6514 12.6516C11.9481 13.3549 10.9943 13.75 9.99972 13.75ZM9.99972 12.0833C10.5523 12.0833 11.0822 11.8638 11.4729 11.4731C11.8636 11.0824 12.0831 10.5525 12.0831 10C12.0831 9.44747 11.8636 8.91756 11.4729 8.52686C11.0822 8.13616 10.5523 7.91667 9.99972 7.91667C9.44719 7.91667 8.91728 8.13616 8.52658 8.52686C8.13588 8.91756 7.91639 9.44747 7.91639 10C7.91639 10.5525 8.13588 11.0824 8.52658 11.4731C8.91728 11.8638 9.44719 12.0833 9.99972 12.0833Z" fill="white" />
            </svg>
        `;
        cell.appendChild(detailBtn);

        return cell;
    }

    function renderPagination(data) {
        // Implement pagination rendering logic here
    }
</script>
@endsection
