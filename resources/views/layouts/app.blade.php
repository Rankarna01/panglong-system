<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($global_setting) ? $global_setting->app_name : 'Sistem Panglong' }}</title>
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
    </style>
</head>
<body class="text-slate-700 relative bg-surface overflow-x-hidden">

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

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan!',
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonColor: '#5D4037'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{!! session('error') !!}',
            confirmButtonColor: '#5D4037'
        });
    </script>
    @endif
</body>
</html>