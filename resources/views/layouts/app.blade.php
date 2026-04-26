<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowInti POS - Panglong</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5D4037',    // Coklat Tua
                        secondary: '#A1887F',  // Coklat Muda
                        surface: '#F8F9FA',    // Light Background
                        accent: '#8D6E63',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8F9FA; }
        .sidebar-active { background-color: #5D4037; color: white; border-radius: 8px; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        
        /* Loading Spinner CSS */
        .loader {
            border: 4px solid #F3F4F6;
            border-top: 4px solid #5D4037;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="text-slate-700 relative bg-surface overflow-x-hidden">
    
    <div id="page-loader" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-opacity duration-300">
        <div class="loader mb-4"></div>
        <p class="text-sm font-semibold text-primary tracking-widest uppercase animate-pulse">Memuat Data...</p>
    </div>

    <div class="flex min-h-screen w-full overflow-x-hidden">
        
        @include('layouts.partials.sidebar-' . Auth::user()->role)

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            @include('layouts.partials.header')

            <main class="p-4 md:p-6 w-full">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. Hilangkan Loading saat halaman selesai dimuat
        window.addEventListener('load', function () {
            const loader = document.getElementById('page-loader');
            loader.classList.add('opacity-0');
            setTimeout(() => { loader.classList.add('hidden'); }, 300);
        });

        // 2. Munculkan Loading saat form disubmit (kecuali form delete yang pakai SweetAlert)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                if(!this.hasAttribute('onsubmit')) {
                    const loader = document.getElementById('page-loader');
                    loader.classList.remove('hidden', 'opacity-0');
                }
            });
        });

        // 3. Munculkan Loading saat klik link menu sidebar (kecuali '#' atau target blank)
        document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])').forEach(link => {
            link.addEventListener('click', function(e) {
                const loader = document.getElementById('page-loader');
                loader.classList.remove('hidden', 'opacity-0');
            });
        });
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{!! session('success') !!}',
            confirmButtonColor: '#5D4037',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    @endif
</body>
</html>