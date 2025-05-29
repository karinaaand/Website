{{--  @extends('layouts.form')
@section('container')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-semibold text-center text-gray-800">Masuk</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700" for="email">Email</label>
            <input class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" id="email" name="email">
        </div>
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label class="text-sm font-medium text-gray-700" for="password">Kata sandi Anda</label>
                <span class="flex items-center text-sm leading-5 text-gray-500 cursor-pointer" id="togglePassword">
                    <i class="fas fa-eye-slash mr-2"></i> Sembunyi
                </span>
            </div>
            <input class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" id="password" name="password">
        </div>
        <button class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">Masuk</button>
    </form>
    <div class="mt-4 text-center">
        <a class="text-sm font-medium text-gray-700 hover:underline" href="{{ route('user.forgot') }}">Lupa kata sandi?</a>
    </div>
</div>

<script>
    // JavaScript untuk toggle visibilitas kata sandi
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function (e) {
        // Mengubah tipe input password antara "password" dan "text"
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;

        // Mengubah ikon dan teks
        if (type === 'password') {
            togglePassword.innerHTML = '<i class="fas fa-eye-slash mr-2"></i> Sembunyikan';
        } else {
            togglePassword.innerHTML = '<i class="fas fa-eye mr-2"></i> Tampilkan';
        }
    });
</script>

@endsection  --}}
@extends('layouts.form')
@section('container')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-semibold text-center text-gray-800">Masuk</h2>
    <form id="loginForm">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700" for="email">Email</label>
            <input class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" id="email" name="email" required>
        </div>
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label class="text-sm font-medium text-gray-700" for="password">Kata sandi Anda</label>
                <span class="flex items-center text-sm leading-5 text-gray-500 cursor-pointer" id="togglePassword">
                    <i class="fas fa-eye-slash mr-2"></i> Sembunyi
                </span>
            </div>
            <input class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" id="password" name="password" required>
        </div>
        <button class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">Masuk</button>
    </form>
    <div class="mt-4 text-center">
        <a class="text-sm font-medium text-gray-700 hover:underline" href="{{ route('user.forgot') }}">Lupa kata sandi?</a>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePassword.innerHTML = type === 'password'
            ? '<i class="fas fa-eye-slash mr-2"></i> Sembunyikan'
            : '<i class="fas fa-eye mr-2"></i> Tampilkan';
    });

    // Handle login form with Axios
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = passwordInput.value;

        axios.post('http://localhost:8000/api/v1/login', {
            email: email,
            password: password
        })
        .then(response => {
            const userData = response.data.data.user;
            const token = response.data.data.token;

            // Console log token
            console.log('Token:', token);

            // Simpan ke localStorage jika ingin
            localStorage.setItem('user', JSON.stringify(userData));
            localStorage.setItem('token', token);


            console.log(userData);
            window.location.href = 'dashboard';





        })

        .catch(error => {
            console.error(error);
            alert('Login gagal. Periksa kembali email atau kata sandi Anda.');
        });
    });
</script>
@endsection
