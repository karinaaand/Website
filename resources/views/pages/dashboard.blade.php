@php
    use Carbon\Carbon;
@endphp
@extends('layouts.main')
@section('container')
<div class="grid grid-cols-2 gap-10">
    <div class="w-full">
        <div class="w-full rounded-lg shadow-lg py-6">
            <div class="bg-white rounded-lg p-4 md:col-span-2 h-[70vh]">
                <div class="bg-red-500 m-auto text-white text-center py-1 px-6 rounded-full mb-6 w-max text-sm font-semibold" style="border-radius: 9999px;">NOTIFIKASI 🔔
                </div>
                <div class="flex justify-between space-x-2 mb-4">
                    <button onclick="filterNotifications('all', this)" class="filter-btn bg-blue-500 text-white px-3 py-1 rounded-full flex-grow">ALL</button>
                    <button onclick="filterNotifications('expired', this)" class="filter-btn bg-blue-200 text-white px-3 py-1 rounded-full flex-grow">EXPIRED</button>
                    <button onclick="filterNotifications('stok', this)" class="filter-btn bg-blue-200 text-white px-3 py-1 rounded-full flex-grow">STOK</button>
                    <button onclick="filterNotifications('jatuh-tempo', this)" class="filter-btn bg-blue-200 text-white px-3 py-1 rounded-full flex-grow">JATUH TEMPO</button>
                </div>
                @if(isset($notifications) && $notifications->isNotEmpty())
    <ul id="notification-list" class="space-y-4 max-h-[50vh] overflow-y-auto">
        @foreach ($notifications as $notification)
            @if (isset($notification->vendor_name))
                {{-- Notifikasi Jatuh Tempo --}}
                <li class="notification-item all jatuh-tempo flex items-center justify-between px-4 py-2 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M19.4 15a8 8 0 11-14.8 0m14.8 0A7.97 7.97 0 0112 17m0-2a7.97 7.97 0 014.8-1.8m-4.8 1.8A7.97 7.97 0 017.2 15"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium">Tagihan dari {{ $notification->vendor_name }}</p>
                            <p class="text-xs text-gray-500">Jatuh Tempo: {{ \Carbon\Carbon::parse($notification->due)->format('d M') }}</p>
                        </div>
                    </div>
                    <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs">Jatuh Tempo</span>
                </li>

            @elseif (isset($notification->location))
                {{-- Notifikasi Stok Menipis --}}
                <li class="notification-item all stok flex items-center justify-between px-4 py-2 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m-5 2a9 9 0 100 9m0 0a9 9 0 0018 0"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium">{{ $notification->drug_name }}</p>
                            <p class="text-xs text-gray-500">Sisa Stok: {{ $notification->quantity }} ({{ $notification->location }})</p>
                        </div>
                    </div>
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Stok Menipis</span>
                </li>

            @elseif (isset($notification->expired))
                {{-- Notifikasi Obat Akan Expired --}}
                <li class="notification-item all expired flex items-center justify-between px-4 py-2 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h1m-1-4h.01"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium">{{ $notification->drug_name }}</p>
                            <p class="text-xs text-gray-500">Expired: {{ \Carbon\Carbon::parse($notification->expired)->format('d M') }}</p>
                        </div>
                    </div>
                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Akan Expired</span>
                </li>
            @endif
        @endforeach
    </ul>
@endif


            </div>
            <div id="unread-indicator" class="text-center text-blue-500 font-semibold">
                <span>Scroll ke bawah untuk melihat semua notifikasi...</span>
            </div>
        </div>
        <div class="w-full rounded-lg shadow-lg p-6 mt-5">
            <h1 class="font-bold text-lg">Penjualan Obat Terbanyak</h1>
            <canvas id="penjualan"></canvas>
        </div>
    </div>
    <div class="w-full">
        <div class="w-full rounded-lg shadow-lg p-6">
            <h1 class="font-bold text-lg">Grafik Keuntungan Penjualan Obat 7 Hari Terakhir</h1>
            <canvas id="obat"></canvas>
            <div class="flex justify-between">
                <div>
                    <h1 class="font-bold text-lg">Hari dengan Keuntungan Tertinggi</h1>
                    <h1 class="text-gray-400 font-bold text-lg" id="highestDay"></h1>
                </div>
                <div class="mb-4">
                    <a href="{{ route('report.transactions.index') }}">
                        <button
                            class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors duration-200">></button>
                    </a>
                </div>
            </div>

        </div>
        <div class="w-full rounded-lg shadow-lg p-6 mt-2">
            <h1 class="font-bold text-lg text-center">Riwayat Transaksi Terakhir</h1>
            <div class="mt-6">
                <table class="w-full text-sm">
                    <thead>
                        <th class="py-3 px-6 text-center">No Transaksi</th>
                        <th class="py-3 px-6 text-center">Tanggal</th>
                        <th class="py-3 px-6 text-center">Subtotal</th>
                    </thead>
                </table>
            </div>
            <div class="overflow-auto max-h-[50vh]">
                <table class="w-full text-sm">
                    <tbody id="history-tbody">
                        {{--  @foreach ($histories as $item)
                        <tr>
                        <td class="text-center py-3">{{ $item->code }}</td>
                        <td class="text-center py-3">{{ Carbon::parse($item->created_at)->translatedFormat('j F Y') }}</td>
                        <td class="text-center py-3">{{ 'Rp ' . number_format($item->income, 0, ',', '.') }}</td>
                        </tr>

                        @endforeach  --}}
                    </tbody>
                </table>
            </div>
            <div id="unread-indicator" class="text-center mt-2 text-blue-500 font-semibold">
                <span>Scroll ke bawah untuk melihat semua transaksi...</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function filterNotifications(category, button) {
        const notifications = document.querySelectorAll('.notification-item');
        const buttons = document.querySelectorAll('.filter-btn');
        notifications.forEach(notification => {
            if (category === 'all') {
                notification.style.display = 'flex';
            } else {
                if (notification.classList.contains(category)) {
                    notification.style.display = 'flex';
                } else {
                    notification.style.display = 'none';
                }
            }
        });

        buttons.forEach(btn => {
            btn.classList.remove('bg-blue-500', 'translate-y-[-10px]');
            btn.classList.add('bg-blue-200');
        });
        button.classList.add('bg-blue-500','translate-y-[-10px]');
        button.classList.remove('bg-blue-200');
    }

    {{--  const obat = document.getElementById('obat')  --}}
    const penjualan = document.getElementById('penjualan')

    {{--  fetch("{{ route('dashboard.chart-obat') }}")
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('obat').getContext('2d');
        function getColor(data, value) {
            let sortedData = [...data].sort((a, b) => b - a);
            let rank = sortedData.indexOf(value);
            let opacity = 1 - (rank * 0.2);
            return `rgba(78, 128, 255, ${opacity})`;
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    axis: "y",
                    label: 'Jumlah Obat Terjual',
                    data: data.dataset,
                    fill: true,
                    backgroundColor: data.dataset.map(value => getColor(data.dataset, value)),
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: "y",
                plugins: {
                    tooltip: { enabled: true }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            },
            plugins: [{
                id: 'displayNumbersInside',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, index) => {
                        const meta = chart.getDatasetMeta(index);
                        meta.data.forEach((bar, i) => {
                            const value = dataset.data[i];
                            const xPos = bar.x - 10;
                            const yPos = bar.y + 6;
                            ctx.fillStyle = '#fff';
                            ctx.font = 'bold 14px Arial';
                            ctx.textAlign = 'right';
                            ctx.fillText(value, xPos, yPos);
                        });
                    });
                }
            }]
        });
    });



    fetch("{{ route('dashboard.chart-penjualan') }}")
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('penjualan').getContext('2d');

        function getColor(data, needle) {
            let sortedData = [...data].sort((a, b) => b - a);
            let rank = sortedData.indexOf(needle);
            if (rank === -1) return '#4E80FF7F';
            return rank < 3 ? '#4E80FF' : '#4E80FF7F';
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Keuntungan (Rp)',
                    data: data.dataset,
                    backgroundColor: data.dataset.map(value => getColor(data.dataset, value)),
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: "x",
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        document.getElementById('highestDay').textContent = `${data.highestDay}`;
    });

    fetch("/api/v1/dashboard/histories ", {
        headers:{
            Authorization: "Bearer 5|qdSXXTadUxetv532QIzvFz3Y8rqXp2pyHTdndaQX957bac37"
        }
    })
    .then(res=>res.json())
    .then(res=>{
        for(const data of res.data.data){
            document.getElementById("history-tbody").innerHTML+=`
                <tr>
                    <td class="text-center py-3">${data['No Transaksi']}</td>
                    <td class="text-center py-3">${data['Date']}</td>
                    <td class="text-center py-3">${data['Subtotal']}</td>
                </tr>
            `
        }
    })  --}}

     // Chart Obat
    {{--  axios.get("{{ route('dashboard.chart-obat') }}")
    .then(response => {
        const data = response.data;
        console.log('data grafik    ');
        console.log(data);
        const ctx = document.getElementById('obat').getContext('2d');

        function getColor(data, value) {
            let sortedData = [...data].sort((a, b) => b - a);
            let rank = sortedData.indexOf(value);
            let opacity = 1 - (rank * 0.2);
            return `rgba(78, 128, 255, ${opacity})`;
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    axis: "y",
                    label: 'Jumlah Obat Terjual',
                    data: data.dataset,
                    fill: true,
                    backgroundColor: data.dataset.map(value => getColor(data.dataset, value)),
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: "y",
                plugins: {
                    tooltip: { enabled: true }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            },
            plugins: [{
                id: 'displayNumbersInside',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, index) => {
                        const meta = chart.getDatasetMeta(index);
                        meta.data.forEach((bar, i) => {
                            const value = dataset.data[i];
                            const xPos = bar.x - 10;
                            const yPos = bar.y + 6;
                            ctx.fillStyle = '#fff';
                            ctx.font = 'bold 14px Arial';
                            ctx.textAlign = 'right';
                            ctx.fillText(value, xPos, yPos);
                        });
                    });
                }
            }]
        });
    });  --}}

    function buatChartObat(data) {
    const ctx = document.getElementById('obat').getContext('2d');

    // Pemetaan hari ke Bahasa Indonesia
    const hariInggrisKeIndonesia = {
        "Sunday": "M",
        "Monday": "S",
        "Tuesday": "S",
        "Wednesday": "R",
        "Thursday": "K",
        "Friday": "J",
        "Saturday": "S"
    };

    function getColor(dataset, value) {
        let sortedData = [...dataset].sort((a, b) => b - a);
        let rank = sortedData.indexOf(value);
        let opacity = 1 - (rank * 0.2);
        return `rgba(78, 128, 255, ${opacity})`;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels.map(label => hariInggrisKeIndonesia[label] || label),
            datasets: [{
                axis: "y",
                label: 'Keuntungan()Rp)',
                data: data.dataset,
                fill: true,
                backgroundColor: data.dataset.map(value => getColor(data.dataset, value)),
                borderRadius: 10
            }]
        },
        options: {
            indexAxis: "x",
            plugins: {
                tooltip: { enabled: true }
            },
            scales: {
                x: { beginAtZero: true }
            }
        },
        plugins: [{
            id: 'displayNumbersInside',
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, index) => {
                    const meta = chart.getDatasetMeta(index);
                    meta.data.forEach((bar, i) => {
                        const value = dataset.data[i];
                        const xPos = bar.x - 10;
                        const yPos = bar.y + 6;
                        ctx.fillStyle = '#fff';
                        ctx.font = 'bold 14px Arial';
                        ctx.textAlign = 'right';
                        ctx.fillText(value, xPos, yPos);
                    });
                });
            }
        }]
    });
}




    // Chart Penjualan
function buatChartPenjualan(dataArray) {
    const ctx = document.getElementById('penjualan').getContext('2d');
    const labels = dataArray.map(item => item.drug_name);
    const dataset = dataArray.map(item => item.total_sold);

    function getColor(data, value) {
        let sorted = [...data].sort((a, b) => b - a);
        let rank = sorted.indexOf(value);
        if (rank === -1) return '#4E80FF7F'; // fallback color
        return rank < 3 ? '#4E80FF' : '#4E80FF7F';
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            axis: "x",
            labels: labels,
            datasets: [{
                label: 'Jumlah Obat Terjual',
                data: dataset,
                backgroundColor: dataset.map(val => getColor(dataset, val)),
                borderRadius: 10
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

    // Histories
function tampilkanRiwayatTransaksi(dataList) {
    const tbody = document.getElementById("history-tbody");
    tbody.innerHTML = ''; // bersihkan dulu isi tabel

    dataList.forEach(data => {
        tbody.innerHTML += `
            <tr class="justify-center">
                <td class="text-center py-3">${data['No Transaksi']}</td>
                <td class="text-center py-3">${data['Date'].substring(0, 10)}</td>
                <td class="text-center py-3">${data['Subtotal']}</td>
            </tr>
        `;
    });
}



    const token = localStorage.getItem('token');


// Cek apakah token ada
if (token) {
    console.log('Token dari localStorage:', token);

    let Obat_terlaris = null;
    let penjualan_mingguan = null;
    let riwayat_transaksi = null;
    let tagihan_jatuh_tempo = null;
    let stok_menipis = null;
    let obat_kedaluwarsa = null;

    const endpoints = [
        { url: '/api/v1/dashboard/obat', description: 'Obat terlaris', assignTo: 'Obat_terlaris' },
        { url: '/api/v1/dashboard/penjualan', description: 'Penjualan mingguan', assignTo: 'penjualan_mingguan' },
        { url: '/api/v1/dashboard/histories', description: 'Riwayat transaksi', assignTo: 'riwayat_transaksi' },
        { url: '/api/v1/dashboard/due-bills', description: 'Tagihan jatuh tempo', assignTo: 'tagihan_jatuh_tempo' },
        { url: '/api/v1/dashboard/low-stock', description: 'Peringatan stok menipis', assignTo: 'stok_menipis' },
        { url: '/api/v1/dashboard/expiring', description: 'Obat akan kedaluwarsa', assignTo: 'obat_kedaluwarsa' },
    ];

    const dataStore = {};

    endpoints.forEach(endpoint => {
        axios.get(`http://localhost:8000${endpoint.url}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(response => {
            console.log(`Data ${endpoint.description}:`, response.data);
            dataStore[endpoint.assignTo] = response.data;

            // Jika kamu ingin juga simpan ke variabel luar
            switch (endpoint.assignTo) {
                case 'Obat_terlaris':
                    Obat_terlaris = response.data.data;
                    buatChartPenjualan(Obat_terlaris);
                    break;
                case 'penjualan_mingguan':
                    const rawData = response.data.data.data;
                    const chartData = {
                        labels: Object.keys(rawData),
                        dataset: Object.values(rawData)      // [0, 0, 0, 0, 0, 0, 0]
                    };
                    buatChartObat(chartData);
                    break;
                case 'riwayat_transaksi':
                    riwayat_transaksi = response.data.data.data;
                    tampilkanRiwayatTransaksi(riwayat_transaksi);
                    break;
                case 'tagihan_jatuh_tempo':
                    tagihan_jatuh_tempo = response.data;
                    break;
                case 'stok_menipis':
                    stok_menipis = response.data;
                    break;
                case 'obat_kedaluwarsa':
                    obat_kedaluwarsa = response.data;
                    break;
            }
            // Kamu bisa pakai dataStore[endpoint.assignTo] juga kalau lebih nyaman
        })
        .catch(error => {
            console.error(`Gagal mengambil ${endpoint.description}:`, error);
        });
    });

} else {
    console.warn('Token tidak ditemukan. Mungkin pengguna belum login.');
}


</script>
@endsection
