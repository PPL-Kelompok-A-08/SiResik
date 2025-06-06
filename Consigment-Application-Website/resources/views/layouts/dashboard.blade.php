<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk | Telu Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body, html, * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <div class="h-20 flex items-center justify-center border-b">
                <span class="text-2xl font-bold text-blue-700">Logo</span>
            </div>
            <nav class="flex-1 py-6 px-4 space-y-2">
                <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <span class="mr-3">🖥️</span> Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2 rounded-lg text-blue-700 bg-blue-100 font-semibold">
                    <span class="mr-3">🔲</span> Produk
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <span class="mr-3">💬</span> Komentar & Rating
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <span class="mr-3">🤍</span> Daftar Favorit
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50">
                    <span class="mr-3">🛒</span> Pemesanan
                </a>
            </nav>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow flex items-center justify-between px-8 h-20">
                <span class="text-2xl font-bold text-blue-700">Telu Marketplace</span>
                <div class="flex items-center space-x-4">
                    <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">👤</span>
                    <span class="font-semibold text-gray-700">User X</span>
                </div>
            </header>
            <!-- Content -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html> 