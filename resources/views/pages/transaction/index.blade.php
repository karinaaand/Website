@php
    use Carbon\Carbon;
@endphp
@extends('layouts.main')
@section('container')
<div class="shadow-lg p-4 rounded-md">
    <table class="w-full text-sm text-center border border-gray-300 rounded-lg p-4 overflow-hidden shadow-lg">
        <thead class="bg-gray-200">
            <th class="py-4 rounded-l-sm">Invoice</th>
            <th class="py-4">Tanggal</th>
            <th class="py-4">Waktu</th>
            <th class="py-4 rounded-r-sm">Action</th>
        </thead>
        <tbody id="history-data">

        </tbody>
    </table>
<div class="p-6">
{{ $transactions->links() }}
</div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Configuration
    const API_BASE_URL = 'http://localhost:8000/api/v1';
    const token = localStorage.getItem('token');

    // State variables
    let timeout = null;
    let query = "";
    let temporaryData;
    let data_history = null;
    let selectedId;

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
            fetchHistory();
        }
    }

    // API Functions
    function fetchHistory() {
        api.get(`/checkout/history`)
            .then(response => {
                data_history = response.data;
                renderHistoryTable(data_history);
            })
            .catch(error => {
                console.error('Gagal mengambil data History:', error);
            });
    }

    function renderHistoryTable(data) {
        const tbody = document.getElementById("history-data");
        tbody.innerHTML = ""; // Clear existing rows

        data.data.data.forEach((item, index) => {
            const row = document.createElement("tr");
            row.className = "border-b border-gray-200 hover:bg-gray-100";

            const createdAt = new Date(item.created_at);
            const tanggal = formatTanggalIndo(createdAt); // 10 Juni 2025
            const jam = formatJam(createdAt);   

            // Create table cells
            const codeCell = createTableCell("py-3 px-6", item.code);
            const dateCell = createTableCell("py-3 px-6", tanggal);
            const timeCell = createTableCell("py-3 px-6", jam);
            const actionCell = createActionCell(item);

            // Append cells to row
            row.appendChild(codeCell);
            row.appendChild(dateCell);
            row.appendChild(timeCell);
            row.appendChild(actionCell);

            // Append row to table
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

        // Edit button
        const showBtn = document.createElement("a");
        showBtn.className = "bg-blue-500 hover:bg-blue-600 p-2 rounded-md";
        showBtn.setAttribute("title", "Edit");
        showBtn.href = `/transaction/${item.id}`;
        showBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.99972 2.5C14.4931 2.5 18.2314 5.73333 19.0156 10C18.2322 14.2667 14.4931 17.5 9.99972 17.5C5.50639 17.5 1.76805 14.2667 0.983887 10C1.76722 5.73333 5.50639 2.5 9.99972 2.5ZM9.99972 15.8333C11.6993 15.833 13.3484 15.2557 14.6771 14.196C16.0058 13.1363 16.9355 11.6569 17.3139 10C16.9341 8.34442 16.0038 6.86667 14.6752 5.80835C13.3466 4.75004 11.6983 4.17377 9.99972 4.17377C8.30113 4.17377 6.65279 4.75004 5.3242 5.80835C3.9956 6.86667 3.06536 8.34442 2.68555 10C3.06397 11.6569 3.99361 13.1363 5.32234 14.196C6.65106 15.2557 8.30016 15.833 9.99972 15.8333V15.8333ZM9.99972 13.75C9.00516 13.75 8.05133 13.3549 7.34807 12.6516C6.64481 11.9484 6.24972 10.9946 6.24972 10C6.24972 9.00544 6.64481 8.05161 7.34807 7.34835C8.05133 6.64509 9.00516 6.25 9.99972 6.25C10.9943 6.25 11.9481 6.64509 12.6514 7.34835C13.3546 8.05161 13.7497 9.00544 13.7497 10C13.7497 10.9946 13.3546 11.9484 12.6514 12.6516C11.9481 13.3549 10.9943 13.75 9.99972 13.75ZM9.99972 12.0833C10.5523 12.0833 11.0822 11.8638 11.4729 11.4731C11.8636 11.0824 12.0831 10.5525 12.0831 10C12.0831 9.44747 11.8636 8.91756 11.4729 8.52686C11.0822 8.13616 10.5523 7.91667 9.99972 7.91667C9.44719 7.91667 8.91728 8.13616 8.52658 8.52686C8.13588 8.91756 7.91639 9.44747 7.91639 10C7.91639 10.5525 8.13588 11.0824 8.52658 11.4731C8.91728 11.8638 9.44719 12.0833 9.99972 12.0833Z" fill="white"/>
            </svg>
        `;

        cell.appendChild(showBtn);

        return cell;
    }

    function formatTanggalIndo(date) {
        const bulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        const hari = date.getDate();
        const bulan = bulanIndo[date.getMonth()];
        const tahun = date.getFullYear();
        return `${hari} ${bulan} ${tahun}`;
    }

    function formatJam(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes();
        hours = hours % 12 || 12; // Konversi ke format 12 jam
        minutes = minutes < 10 ? '0' + minutes : minutes;
        return `${hours}:${minutes}`;
    }
    // Initialize data on page load
    initializePage();
</script>
@endsection