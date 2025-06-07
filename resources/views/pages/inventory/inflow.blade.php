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
    <div class="mt-3">
        {{ $transactions->links() }}
    </div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const API_BASE_URL = 'http://localhost:8000/api/v1';
    const per_page = 5;
    const token = localStorage.getItem('token');

    let timeout = null;
    let query = "";
    let temporaryData;
    let data_inflow = null;
    let selectedId;

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
        renderInflowTable(response.data);
        })
        .catch(error => {
        console.error('Gagal mengambil data inventory inflow:', error);
        });
    }

    function showDeleteModal(id) {
        selectedId = id;
        document.getElementById('delete-inflow-form').setAttribute('action', `${API_BASE_URL}/inventory-inflows/${id}`);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function renderInflowTable(response) {
    const tbody = document.getElementById('inflow-data');
    tbody.innerHTML = '';

    // response.data adalah array inflows langsung
    response.data.forEach((item, index) => {
        const row = document.createElement('tr');

        row.innerHTML = `
        <td class="py-3 px-6 text-center">${index + 1}</td>
        <td class="py-3 px-6 text-center">${item['No. LPB']}</td>
        <td class="py-3 px-6 text-center">${item['Vendor']}</td>
        <td class="py-3 px-6 text-center">${item['Date']}</td>
        <td class="py-3 px-6 text-center">
            <!-- Kalau belum ada ID dan action, kosongkan dulu atau buat dummy -->
            <button disabled class="text-gray-400 cursor-not-allowed">Edit</button>
            <button disabled class="text-gray-400 cursor-not-allowed">Delete</button>
        </td>
        `;

        tbody.appendChild(row);
    });
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

        const editBtn = document.createElement("a");
        editBtn.className = "flex cursor-pointer items-center bg-yellow-300 text-white text-sm px-2 py-2 rounded-lg shadow hover:bg-yellow-400 mr-2";
        editBtn.setAttribute("title", "Edit");
        editBtn.href = `/inventory/inflow/${item.id}/edit`;
        editBtn.innerHTML = `<svg width="20" height="20" ...>...</svg>`; // icon edit

        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className = "bg-red-500 text-white text-sm px-2 py-2 rounded-lg shadow hover:bg-red-600";
        deleteBtn.setAttribute("title", "Delete");
        deleteBtn.innerHTML = `<svg width="20" height="20" ...>...</svg>`; // icon delete
        deleteBtn.onclick = () => showDeleteModal(item.id);

        cell.appendChild(editBtn);
        cell.appendChild(deleteBtn);

        return cell;
    }

    function updatePaginationInfo(data) {
        const start = ((data.current_page - 1) * data.per_page) + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        const total = data.total;

        document.getElementById('pagination-info').textContent =
            `Showing ${start} to ${end} of ${total} results`;
    }

    function renderPagination(data) {
        const currentPage = data.data.current_page;
        const lastPage = data.data.last_page;

        let elements = '<nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">';

        elements += `<span onclick="${currentPage === 1 ? '' : `getInflowPage(${currentPage - 1})`}"
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium
            ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer hover:bg-gray-50'}
            bg-white border border-gray-300 rounded-l-md">‹</span>`;

        for (let i = 1; i < data.data.links.length - 1; i++) {
            elements += `<span onclick="getInflowPage(${i})"
                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium
                text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500">${i}</span>`;
        }

        elements += `<span onclick="${currentPage === lastPage ? '' : `getInflowPage(${currentPage + 1})`}"
            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium
            ${currentPage === lastPage ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer hover:bg-gray-50'}
            bg-white border border-gray-300 rounded-r-md">›</span>`;

        elements += '</nav>';
        document.getElementById("pagination-div").innerHTML = elements;
    }

    function getInflowPage(page) {
        if (query.length > 0) {
            fetchInflow(query, page);
        } else {
            fetchInflow('', page);
        }
    }
</script>

@endsection