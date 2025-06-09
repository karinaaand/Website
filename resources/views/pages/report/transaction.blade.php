@php
    use Carbon\Carbon;
    use App\Models\Transaction\Trash;
@endphp
@extends('layouts.main')
@section('container')
    <div class="rounded-lg bg-white p-6 shadow-lg">
        <div class="flex flex-1 justify-end mb-5">
            <button onclick="printModal()"
                class="rounded-lg bg-yellow-500 hover:bg-yellow-600 px-4 py-1 text-white">Cetak</button>
        </div>
        <div class="flex items-center justify-between w-full">
            <form action="" class="flex w-auto flex-row justify-between gap-3">
                <input class="rounded-sm px-2 py-1 ring-2 ring-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="date" value="{{ $_GET['start'] ?? '' }}" name="start"/>
                <h1 class="text-lg font-inter text-gray-800">sampai</h1>
                <input class="rounded-sm px-2 py-1 ring-2 ring-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="date" value="{{ $_GET['end'] ?? '' }}" name="end"/>
                <button class="rounded-2xl bg-blue-500 px-3 font-bold text-sm font-inter text-white hover:bg-blue-600"
                    type="submit">
                    TERAPKAN
                </button>
            </form>
            <form action="" class="flex">
                <input type="text" name="" id="transaction-search" placeholder="Search..."
                    class="rounded-full px-6 py-2 ring-2 ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </form>
        </div>
        <div class="overflow-hidden rounded-lg bg-white shadow-md mt-6">
            <table class="min-w-full text-sm text-center">
                <thead class="bg-gray-200">
                    <th class="py-4">No</th>
                    <th class="py-4">Kode Transaksi</th>
                    <th class="py-4">Tanggal</th>
                    <th class="py-4">Jenis</th>
                    <th class="py-4">Subtotal</th>
                    <th class="py-4">Action</th>
                </thead>
                <tbody id="transaction-value"></tbody>
                <tbody id="transaction-data">
                    @foreach ($transactions as $number => $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="text-center py-3">{{ $number + 1 }}</td>
                            <td class="text-center py-3">{{ $item->code }}</td>
                            <td class="text-center py-3">
                                {{ Carbon::parse($item->created_at)->translatedFormat('j F Y') }}
                            </td>
                            <td class="text-center py-3">{{ $item->variant }}</td>
                            <td class="text-center py-3">
                                @php
                                    $amount = match ($item->variant) {
                                        'LPB' => $item->outcome,
                                        'LPK' => $item->details()->sum('total_price'),
                                        'Checkout' => $item->income,
                                        'Retur' => 0,
                                        'Trash' => -$item->loss,
                                        default => null,
                                    };
                                @endphp
                                {{ $amount !== null ? 'Rp ' . number_format($amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="flex justify-center py-3">
                                @php
                                    $routes = [
                                        'Checkout' => route('transaction.show', $item->id),
                                        'Trash' => $item->trash() ? route('management.trash.show', $item->id) : null,
                                        'Retur' => $item->retur() ? route('management.retur.show', $item->id) : null,
                                        'LPB' => route('inventory.inflows.show', $item->id),
                                        'default' => route('clinic.inflows.show', $item->id),
                                    ];

                                    $route = $routes[$item->variant] ?? $routes['default'];
                                @endphp

                                <a href="{{ $routes[$item->variant] ?? $routes['default'] }}"
                                    class="bg-blue-500 hover:bg-blue-600 p-2 rounded-md">
                                    @include('icons.mata')
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $transactions->links() }}
        </div>
    </div>
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
                <a href="{{ route('transaction.export.excel', ['start' => request('start'), 'end' => request('end')]) }}"
                    onclick="closePrintModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 focus:outline-none">
                    Excel
                </a>
                <a href="{{ route('transaction.export.pdf', ['start' => request('start'), 'end' => request('end')]) }}"
                    onclick="closePrintModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 focus:outline-none">
                    PDF
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    // Configuration
        const API_BASE_URL = 'http://localhost:8000/api/v1';
        const per_page = 10;
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
                fetchTransacts();
            }
        }

        // API Functions
        function fetchTransacts(searchQuery = '', page = 1, dateFrom = '', dateTo = '') {
            api.get(`/reports/transactions?per_page=${per_page}&search=${searchQuery}&page=${page}`)
                .then(response => {
                    data_stocks = response.data;
                    console.log('Data stok:', data_stocks);
                    // renderTransactTable(data_stocks);
                    // updatePaginationInfo(data_stocks.pagination);
                })
                .catch(error => {
                    console.error('Gagal mengambil data stok:', error);
                    showErrorMessage('Gagal memuat data stok');
                });
        }

        const drugInput = document.getElementById('transaction-search');
        drugInput.addEventListener('input', function () {
            clearTimeout(timeout);
            const query = this.value;
            timeout = setTimeout(() => {
                if (query.length > 0) {
                    document.getElementById('transaction-data').classList.add('hidden');
                    document.getElementById('transaction-value').classList.remove('hidden');

                    // Ganti fetch dengan axios
                    axios.get(`/transaction-search?query=${query}`)
                        .then(response => {
                            const data = response.data; // axios hasilnya di .data
                            const suggestions = document.getElementById('transaction-value');
                            suggestions.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach((item, number) => {
                                    let createdAt = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric'
                                    }) : '-';

                                    let outcome;
                                    switch (item.variant) {
                                        case 'LPB':
                                            outcome = formatCurrency(item.outcome);
                                            break;
                                        case 'LPK':
                                            outcome = formatCurrency(item.detail?.reduce((sum, detail) => sum + (detail.total_price || 0), 0));
                                            break;
                                        case 'Checkout':
                                            outcome = formatCurrency(item.income);
                                            break;
                                        case 'Retur':
                                            outcome = formatCurrency(0);
                                            break;
                                        case 'Trash':
                                            outcome = '-' + formatCurrency(item.loss);
                                            break;
                                        default:
                                            outcome = '-';
                                            break;
                                    }

                                    let routes = {
                                        'Checkout': `/transaction/${item.id}`,
                                        'Trash': `/management/trash/${item.id}`,
                                        'Retur': `/management/retur/${item.id}`,
                                        'LPB': `/inventory/inflows/${item.id}`,
                                        'default': `/clinic/inflows/${item.id}`
                                    };
                                    let detailLink = routes[item.variant] || routes['default'];

                                    suggestions.innerHTML += `
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                            <td class="text-center py-3">${number + 1}</td>
                                            <td class="text-center py-3">${item.code || '-'}</td>
                                            <td class="text-center py-3">${createdAt}</td>
                                            <td class="text-center py-3">${item.variant || '-'}</td>
                                            <td class="text-center py-3">${outcome}</td>
                                            <td class="flex justify-center py-3">
                                                <a href="${detailLink}" class="bg-blue-500 hover:bg-blue-600 p-2 rounded-md">
                                                    <!-- SVG ICON HERE -->
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });

                } else {
                    document.getElementById('transaction-data').classList.remove('hidden');
                    document.getElementById('transaction-value').classList.add('hidden');
                }
            }, 400);
        });

        function printModal() {
            document.getElementById('printModal').classList.remove('hidden');
        }

        function formatCurrency(value) {
            if (value == null) return '-';
            return `Rp ${value.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
        }

        function closePrintModal() {
            document.getElementById('printModal').classList.add('hidden');
        }
    </script>
@endsection