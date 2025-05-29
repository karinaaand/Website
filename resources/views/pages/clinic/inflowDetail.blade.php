@php
    use Carbon\Carbon;
@endphp
@extends('layouts.main')
@section('container')
<div class="mb-4 flex items-center">
            <div class="flex flex-1 justify-end mr-5">
                <button onclick="uploadModal()" class="rounded-lg bg-yellow-500 hover:bg-yellow-600 px-4 py-1 text-white">Cetak</button>
            </div>
        </div>
    <div class="rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center">
            <div class="flex-1 mr-5">
                <h2 class="text-2xl font-bold text-left" id="profile_name">{{ App\Models\Profile::first()->name }}</h2>
                <h2 class="text text-left" id="profile_address">{{ App\Models\Profile::first()->address }}</h2>
                <h2 class="text text-left" id="profile_phone">{{ App\Models\Profile::first()->phone }}</h2>
            </div>
            <div class="flex-[1.5] flex flex-col  items-center justify-center">
                <div class="flex items-center">
                    <span class="mr-2 text-lg font-normal text-black">No. LPB :</span>
                    <span class="text-lg font-normal" id="transactionCode">
                        {{--  {{ $transaction->code }}  --}}
                    </span>
                </div>
                <h1 class="text-center text-2xl font-bold">LAPORAN PENERIMAAN KLINIK</h1>
            </div>
            <div class="flex-1 ml-10">
                <p class="text-lg mb-4 text-left">Tanggal: <span id="transactionDate"></span>
                    {{-- {{ Carbon::parse($transaction->created_at)->translatedFormat('j F Y') }}  --}}
                </p>              </div>
        </div>
        <div class="overflow-hidden rounded-lg bg-white shadow-md mt-8">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-sm leading-normal text-black">
                        <th class="border p-2 w-1">No</th>
                        <th class="border p-2">Kode Obat</th>
                        <th class="border p-2">Nama Obat</th>
                        <th class="border p-2">Jumlah</th>
                        <th class="border p-2">Harga Satuan</th>
                        <th class="border p-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="detailsTable" class="text-sm font-light text-gray-700">
                    {{--  @foreach ($details as $number => $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="px-6 py-3 text-center">{{ $number + 1 }}</td>
                            <td class="px-6 py-3 text-center">{{ $item->drug()->code }}</td>
                            <td class="px-6 py-3 text-left">{{ $item->drug()->name }}</td>
                            <td class="px-6 py-3 text-center">{{ $item->quantity }}</td>
                            <td class="px-6 py-3 text-center">{{ 'Rp ' . number_format($item->piece_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-center">{{ 'Rp ' . number_format($item->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach  --}}
                </tbody>
            </table>
            <h2 type="text" class="text-right mt-6 pe-6 pb-6">Grand total : <span id="grandTotal"></span>
                {{--  {{ 'Rp ' . number_format($details->sum('total_price'), 0, ',', '.') }}  --}}
            </h2>
        </div>
    </div>
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
                onclick="closeUploadModal()">
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
                <button data-transaction-id="{{ $transaction->id }}" onclick="closeUploadModal(this.getAttribute('data-transaction-id'))"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                    Excel
                </button>
                <button data-transaction-id="{{ $transaction->id }}" onclick="submitModal(this.getAttribute('data-transaction-id'))" type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                    PDF
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Function untuk mengambil detail transaksi
    // function fetchTransactionDetails(transactionId) {
        const currentUrl = window.location.href.split('/');
        const ids = currentUrl.pop();
        console.log(ids); //buat cetak panggil ids/ yang berakhiran id menggunakan ids
        const token = localStorage.getItem('token'); // Masukkan token yang sudah ada
        axios.get(`http://localhost:8000/api/v1/inventory/inflows/${ids}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(function (response) {
            // Menangani respons sukses
            console.log(response.data);
            const data = response.data.data;
            // Menampilkan data pada elemen HTML
            const user = JSON.parse(localStorage.getItem('user'));
            console.log(user)
            document.getElementById('profile_name').textContent = user.name;
            document.getElementById('profile_phone').textContent = user.phone;
            document.getElementById('profile_address').textContent = user.address;
            document.getElementById('transactionCode').textContent = data['No. LPB'];
            document.getElementById('transactionDate').textContent = data['Date'];
            document.getElementById('grandTotal').textContent = 'Rp ' + data['Grand_total'];

            // Menampilkan detail item obat
            const details = data['Details'];
            let tableContent = '';
            details.forEach((item, index) => {
                tableContent += `
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-center">${index + 1}</td>
                        <td class="px-6 py-3 text-center">${item.drug_code}</td>
                        <td class="px-6 py-3 text-left">${item.drug_name}</td>
                        <td class="px-6 py-3 text-center">${item.total}</td>
                        <td class="px-6 py-3 text-center">${'Rp ' + formatCurrency(item.piece_price)}</td>
                        <td class="px-6 py-3 text-center">${'Rp ' + formatCurrency(item.subtotal)}</td>
                    </tr>
                `;
            });
            console.log(tableContent);

            // Update tabel dengan data
            document.getElementById('detailsTable').innerHTML = tableContent;
        })
        .catch(function (error) {
            // Menangani kesalahan
            console.error('Error fetching transaction details:', error);
        });

        // Format angka
    function formatCurrency(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Panggil fungsi ini saat halaman dimuat dengan ID transaksi
    document.addEventListener('DOMContentLoaded', function () {
        const transactionId = '{{ $transaction->id }}';  // Ganti dengan ID transaksi yang sesuai
        fetchTransactionDetails(transactionId);
    });
    // }
    </script>

    <script>
        function uploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal(transaction_id) {
            document.getElementById('uploadModal').classList.add('hidden');

            if (!transaction_id) {
                console.error("Transaction ID is missing!");
                return;
            }

            console.log("Download button clicked, transaction ID:", transaction_id); // Debugging

            // Redirect langsung ke endpoint Laravel
            window.location.href = `/clinic/export/${transaction_id}`;
        }

        function submitModal(transaction_id) {
            document.getElementById('uploadModal').classList.add('hidden');

            if (!transaction_id) {
                console.error("Transaction ID is missing!");
                return;
            }

            console.log("Download button clicked, transaction ID:", ids); // Debugging

            // Redirect langsung ke endpoint Laravel
            window.location.href = `/clinic/generate-pdf/${ids}`;
        }

        document.getElementById('confirmPrint').onclick = function() {
            const format = document.getElementById('format').value;
            if (format === 'pdf') {
                alert('Mencetak dalam format PDF...');
            } else if (format === 'excel') {
                alert('Mencetak dalam format Excel...');
            }
            document.getElementById('printOptions').classList.add('invisible');
        };
    </script>
@endsection
