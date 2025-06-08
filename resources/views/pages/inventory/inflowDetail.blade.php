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
                <h2 id="profile-name" class="text-xl font-bold text-left"></h2>
                <h2 id="profile-address" class="text-sm text-left"></h2>
                <h2 id="profile-phone" class="text-sm text-left"></h2>
            </div>
            <div class="flex-[1.5] flex flex-col  items-center justify-center">
                <div class="flex items-center">
                    <span class="mr-2 font-normal text-black">No. LPB :</span>
                    <span id="no-lbp"></span>
                </div>
                <h1 class="text-center text-2xl font-bold">LAPORAN PENERIMAAN BARANG</h1>
            </div>
            <div class="flex-1 ml-10">
                <!-- <p class="text-sm mb-4 text-left">Tanggal: {{ Carbon::parse($transaction->created_at)->translatedFormat('j F Y') }}</p> -->
                <p class="text-sm mb-4 text-left">Tanggal:
                    <span id="date"></span>
                </p>

                <!-- <h2 class="text-lg font-bold text-left">{{ $transaction->vendor()->name }}</h2>
                <h2 class="text-sm text-left">{{ $transaction->vendor()->address }}</h2>
                <h2 class="text-sm text-left">{{ $transaction->vendor()->phone }}</h2> -->
                <h2 id="vendor-name" class="text-lg font-bold text-left"></h2>
                <h2 id="vendor-address" class="text-sm text-left"></h2>
                <h2 id="vendor-phone" class="text-sm text-left"></h2>
            </div>
        </div>
        <div class="overflow-hidden rounded-lg bg-white shadow-md mt-8">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 w-1">No</th>
                        <th class="border p-2">Kode Obat</th>
                        <th class="border p-2">Nama Obat</th>
                        <th class="border p-2">Jumlah</th>
                        <th class="border p-2">Harga Satuan</th>
                        <th class="border p-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tabel" class="text-gray-700">

                </tbody>
            </table>
            <h2 type="text" class="text-right mt-6 pe-6 pb-6">Grand total : Rp <span id="grand-total"></span></h2>
            <!-- <h2 type="text" class="text-right mt-6 pe-6 pb-6">Grand total : {{ 'Rp ' . number_format($transaction->outcome, 0, ',', '.') }}</h2> -->

        </div>
    </div>
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600" onclick="closeUploadModal()">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Apa format file yang ingin Anda simpan?</h3>
                <p class="text-sm text-gray-500 mb-5">Pilihlah salah satu format file!</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button data-transaction-id="{{ $transaction->id }}" onclick="closeUploadModal(this.getAttribute('data-transaction-id'))"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                    Excel
                </button>

                <button data-transaction-id="{{ $transaction->id }}" onclick="submitModal(this.getAttribute('data-transaction-id'))" type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none">
                    PDF
                </button>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        
    document.addEventListener('DOMContentLoaded', function () {
        const API_BASE_URL = 'http://localhost:8000/api/v1';
        const token = localStorage.getItem('token');

        const urlParts = window.location.pathname.split('/');
        const id = urlParts[urlParts.length - 1]; // ambil ID dari URL

        const api = axios.create({
            baseURL: API_BASE_URL,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        axios.get(`${API_BASE_URL}/inventory/inflows/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        .then(response => {
            console.log('Response data:', response.data);
            // const profile = response.data.data.Profile;
            const data = response.data.data;
            
            document.getElementById('profile-name').textContent = data.Profile.name;
            document.getElementById('profile-address').textContent = data.Profile.address;
            document.getElementById('profile-phone').textContent = data.Profile.phone;
            document.getElementById('no-lbp').textContent = data["No. LPB"];
            document.getElementById('date').textContent = data["Date"];
            document.getElementById('vendor-name').textContent = data.Vendor.name;
            document.getElementById('vendor-address').textContent = data.Vendor.address;
            document.getElementById('vendor-phone').textContent = data.Vendor.phone;

            // Tabel detail
            const tbodyEl = document.getElementById('tabel');
            tbodyEl.innerHTML = ''; // kosongkan dulu

            data.Details.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-100');
                console.log(item);

                tr.innerHTML = `
                    <td class="px-6 py-3 text-center">${index + 1}</td>
                    <td class="px-6 py-3 text-center">${item.drug_code}</td>
                    <td class="px-6 py-3 text-center">${item.drug_name}</td>
                    <td class="px-6 py-3 text-center">${item.total}</td>
                    <td class="px-6 py-3 text-center">Rp ${item.piece_price}</td>
                    <td class="px-6 py-3 text-center">Rp ${item.subtotal}</td>
                `;

                tbodyEl.appendChild(tr);
            });

            document.getElementById('grand-total').textContent = data.Grand_total;

        })
        .catch(error => {
            console.error('Gagal ambil data profile:', error);
        });
    });

    </script>


@endsection
