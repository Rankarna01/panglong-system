<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FlowInti POS & Inventory</title>
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
                        surface: '#F8F9FA',    // Background
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-surface min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                <i class="fas fa-warehouse text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">FlowInti POS</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Manajemen Panglong & Kasir</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-lg text-sm mb-6 flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-400"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none" 
                        placeholder="admin@flowinti.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-400"></i>
                    </div>
                    <input type="password" name="password" required 
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none" 
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-medium py-2.5 rounded-lg hover:bg-[#4a332c] transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                <span>Masuk Sistem</span>
                <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400">© 2026 FlowInti Panglong. All rights reserved.</p>
        </div>
    </div>

</body>
</html>