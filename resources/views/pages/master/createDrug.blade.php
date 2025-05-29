@extends('layouts.main')

@section('container')
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <!-- Form Section -->
        <form id="create-drug-form" action="{{ route('master.drug.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-6 gap-6">
                <div class="flex flex-wrap col-span-4">
                    <div class="flex w-full mb-4">
                        <label for="nama_obat" class="w-1/4">Nama Obat</label>
                        <input type="text" id="name" name="name"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Inputkan nama obat">
                    </div>
                    <div class="flex w-full mb-4">
                        <label for="category_id" name="category_id" class="w-1/4">Kategori Obat</label>
                        <select id="category_id" name="category_id"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori Obat</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="flex w-full mb-4">
                        <label for="variant_id" class="w-1/4">Jenis Obat</label>
                        <select id="variant_id" name="variant_id"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Jenis Obat</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="flex w-full mb-4">
                        <label for="manufacture_id" class="w-1/4">Produsen Obat</label>
                        <select id="manufacture_id" name="manufacture_id"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Produsen Obat</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="flex w-full mb-4">
                        <label for="maximum_capacity" class="w-1/4">PKMa</label>
                        <input type="number" id="maximum_capacity" name="maximum_capacity"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Inputkan maksimum PKMa">
                    </div>
                    <div class="flex w-full mb-4">
                        <label for="minimum_capacity" class="w-1/4">PKMi</label>
                        <input type="number" id="minimum_capacity" name="minimum_capacity"
                            class="border border-gray-300 rounded p-2 w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Inputkan minimum PKMi">
                    </div>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td rowspan="3" class="w-48">Konversi</td>
                                <td class="py-2 pe-24 pl-2">
                                    <div class="flex">
                                        <input type="number" id="pack_quantity" name="pack_quantity"
                                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="0">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                            pack/box
                                        </span>
                                    </div>
                                </td>
                                <td class="py-2 pe-24">
                                    <div class="flex">
                                        <input type="number" id="pack_margin" name="pack_margin"
                                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Margin">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                            %
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 pe-24 pl-2">
                                    <div class="flex">
                                        <input type="number" id="piece_quantity" name="piece_quantity"
                                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="0">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                            pcs/pack
                                        </span>
                                    </div>
                                </td>
                                <td class="py-2 pe-24">
                                    <div class="flex">
                                        <input type="number" id="piece_margin" name="piece_margin"
                                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Margin">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                                            %
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 pe-24 pl-2">
                                    <span class="text-xs italic text-gray-400">Netto</span>
                                    <div class="flex">
                                        <input type="number" id="piece_netto" name="piece_netto"
                                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Netto">
                                        <select id="piece_unit" name="piece_unit"
                                            class="border border-gray-300 rounded-e-lg p-2 w-2/5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="ml">ml</option>
                                            <option value="mg">mg</option>
                                            <option value="butir">butir</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-white border rounded-lg p-6 shadow-sm w-full h-max col-span-2">
                    <div class="flex justify-between items-center">
                        <label for="last_price" class="mr-2">Harga</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                              Rp
                            </span>
                            <input type="number" id="last_price" name="last_price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-40 text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Inputkan harga">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                              / pcs
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <label for="last_discount" class="mr-2">Diskon</label>
                        <div class="flex">
                            <input type="number" id="last_discount" name="last_discount"
                                class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Inputkan discount">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-e-md">
                              %
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-500 text-white rounded hover:bg-blue-600 px-6 py-2">Simpan</button>
            </div>
        </form>

        <!-- Table Section -->
        <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-center">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-3 px-6 w-1">No</th>
                        <th class="py-3 px-6">Nama Obat</th>
                        <th class="py-3 px-6">Kategori</th>
                        <th class="py-3 px-6">Jenis</th>
                        <th class="py-3 px-6">Produsen</th>
                        <th class="py-3 px-6">Harga</th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody id="drug-data">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96 relative">
            <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
                onclick="closeEditModal()">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <h2 class="text-center text-xl font-semibold mb-6">Ubah Data Obat</h2>
            <form method="PUT" id="edit-drug-form">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-start text-gray-700 mb-2" for="edit_name">Nama Obat</label>
                    <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        type="text" id="edit_name" name="name">
                </div>
                <div class="mb-4">
                    <label class="block text-start text-gray-700 mb-2" for="edit_category_id">Kategori</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="edit_category_id" name="category_id">
                        <!-- Options will be populated by JavaScript -->
                    </select>
                </div>
                <div class="flex justify-center space-x-4 mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 border rounded-lg text-gray-700 border-gray-300 bg-gray-200 hover:bg-gray-300 w-full flex-1">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white hover:bg-blue-700 rounded-lg w-full flex-1">Edit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configuration
        const API_BASE_URL = 'http://localhost:8000/api/v1';
        const token = localStorage.getItem('token');

        // State variables
        let selectedId;
        let categories = [];
        let variants = [];
        let manufactures = [];

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
        document.getElementById('create-drug-form').addEventListener('submit', handleCreateForm);
        document.getElementById('edit-drug-form').addEventListener('submit', handleEditForm);

        // Initialize Page
        function initializePage() {
            if (token) {
                fetchDropdownData();
                fetchDrugs();
            }
        }

        // Fetch dropdown data
        function fetchDropdownData() {
            // Fetch categories
            api.get('/categories')
                .then(response => {
                    categories = response.data.data;
                    populateDropdown('category_id', categories);
                    populateDropdown('edit_category_id', categories);
                })
                .catch(error => console.error('Error fetching categories:', error));

            // Fetch variants
            api.get('/variants')
                .then(response => {
                    variants = response.data.data;
                    populateDropdown('variant_id', variants);
                })
                .catch(error => console.error('Error fetching variants:', error));

            // Fetch manufactures
            api.get('/manufactures')
                .then(response => {
                    manufactures = response.data.data;
                    populateDropdown('manufacture_id', manufactures);
                })
                .catch(error => console.error('Error fetching manufactures:', error));
        }

        // Fetch drugs data
        function fetchDrugs() {
            api.get('/drugs')
                .then(response => {
                    renderDrugTable(response.data.data);
                })
                .catch(error => {
                    console.error('Gagal mengambil data obat:', error);
                });
        }

        // Populate dropdown
        function populateDropdown(elementId, data) {
            const select = document.getElementById(elementId);
            select.innerHTML = `<option value="">Pilih ${elementId.replace('_', ' ')}</option>`;

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                select.appendChild(option);
            });
        }

        // Event Handlers
        function handleCreateForm(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            api.post('/drugs', data)
                .then(response => {
                    alert('Data obat berhasil ditambahkan');
                    fetchDrugs();
                    e.target.reset();
                })
                .catch(error => {
                    console.error('Gagal menambahkan obat:', error);
                    alert('Gagal menambahkan obat');
                });
        }

        function handleEditForm(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            api.put(`/drugs/${selectedId}`, data)
                .then(response => {
                    alert('Data obat berhasil diubah');
                    fetchDrugs();
                    closeEditModal();
                })
                .catch(error => {
                    console.error('Gagal mengubah obat:', error);
                    alert('Gagal mengubah obat');
                });
        }

        // UI Functions
        function showEditModal(drug) {
            document.getElementById('edit_name').value = drug.name;

            // Set category dropdown
            const categorySelect = document.getElementById('edit_category_id');
            Array.from(categorySelect.options).forEach(option => {
                if (option.value == drug.category_id) {
                    option.selected = true;
                }
            });

            selectedId = drug.id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function renderDrugTable(data) {
            const tbody = document.getElementById("drug-data");
            tbody.innerHTML = ""; // Clear existing rows

            data.forEach((item, index) => {
                const row = document.createElement("tr");
                row.className = "border-b border-gray-200 hover:bg-gray-100";

                // Create table cells
                const noCell = createTableCell("py-3 px-6", index + 1);
                const nameCell = createTableCell("py-3 px-6 text-left", item.name);
                const categoryCell = createTableCell("py-3 px-6", getCategoryName(item.category_id));
                const variantCell = createTableCell("py-3 px-6", getVariantName(item.variant_id));
                const manufactureCell = createTableCell("py-3 px-6", getManufactureName(item.manufacture_id));
                const priceCell = createTableCell("py-3 px-6", formatPrice(item.last_price));
                const actionCell = createDrugActionCell(item);

                // Append cells to row
                row.appendChild(noCell);
                row.appendChild(nameCell);
                row.appendChild(categoryCell);
                row.appendChild(variantCell);
                row.appendChild(manufactureCell);
                row.appendChild(priceCell);
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

        function createDrugActionCell(item) {
            const cell = document.createElement("td");
            cell.className = "py-3 px-6 flex justify-center";

            // Edit button
            const editBtn = document.createElement("a");
            editBtn.className = "flex cursor-pointer items-center bg-yellow-300 text-white text-sm px-2 py-2 rounded-lg shadow hover:bg-yellow-400 mr-2";
            editBtn.setAttribute("title", "Edit");
            editBtn.innerHTML = `
                <svg width="20" height="21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.728 9.68602L14.314 8.27202L5 17.586V19H6.414L15.728 9.68602ZM17.142 8.27202L18.556 6.85802L17.142 5.44402L15.728 6.85802L17.142 8.27202ZM7.242 21H3V16.757L16.435 3.32202C16.6225 3.13455 16.8768 3.02924 17.142 3.02924C17.4072 3.02924 17.6615 3.13455 17.849 3.32202L20.678 6.15102C20.8655 6.33855 20.9708 6.59286 20.9708 6.85802C20.9708 7.12319 20.8655 7.37749 20.678 7.56502L7.243 21H7.242Z" fill="white" />
                </svg>
            `;
            editBtn.onclick = () => showEditModal(item);

            // Delete button
            const deleteBtn = document.createElement("button");
            deleteBtn.type = "button";
            deleteBtn.className = "bg-red-500 text-white text-sm px-2 py-2 rounded-lg shadow hover:bg-red-600";
            deleteBtn.setAttribute("title", "Delete");
            deleteBtn.innerHTML = `
                <svg width="20" height="21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.167 5.50002H18.3337V7.16669H16.667V18C16.667 18.221 16.5792 18.433 16.4229 18.5893C16.2666 18.7456 16.0547 18.8334 15.8337 18.8334H4.16699C3.94598 18.8334 3.73402 18.7456 3.57774 18.5893C3.42146 18.433 3.33366 18.221 3.33366 18V7.16669H1.66699V5.50002H5.83366V3.00002C5.83366 2.77901 5.92146 2.56704 6.07774 2.41076C6.23402 2.25448 6.44598 2.16669 6.66699 2.16669H13.3337C13.5547 2.16669 13.7666 2.25448 13.9229 2.41076C14.0792 2.56704 14.167 2.77901 14.167 3.00002V5.50002ZM15.0003 7.16669H5.0003V17.1667H15.0003V7.16669ZM7.5003 3.83335V5.50002H12.5003V3.83335H7.5003Z" fill="white"/>
                </svg>
            `;
            deleteBtn.onclick = () => deleteDrug(item.id);

            cell.appendChild(editBtn);
            cell.appendChild(deleteBtn);

            return cell;
        }

        // Helper functions
        function getCategoryName(categoryId) {
            const category = categories.find(c => c.id == categoryId);
            return category ? category.name : '-';
        }

        function getVariantName(variantId) {
            const variant = variants.find(v => v.id == variantId);
            return variant ? variant.name : '-';
        }

        function getManufactureName(manufactureId) {
            const manufacture = manufactures.find(m => m.id == manufactureId);
            return manufacture ? manufacture.name : '-';
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
        }

        function deleteDrug(id) {
            if (confirm('Apakah Anda yakin ingin menghapus obat ini?')) {
                api.delete(`/drugs/${id}`)
                    .then(response => {
                        alert('Obat berhasil dihapus');
                        fetchDrugs();
                    })
                    .catch(error => {
                        console.error('Gagal menghapus obat:', error);
                        alert('Gagal menghapus obat');
                    });
            }
        }
    </script>
@endsection
