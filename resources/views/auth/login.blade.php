<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BERKAH Panglong POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5D4037',    // Coklat Tua
                        secondary: '#A1887F',  // Coklat Muda
                        surface: '#F8F9FA',    // Background abu sangat muda
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
        /* Animasi fade in pelan */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-surface min-h-screen text-slate-800 selection:bg-primary selection:text-white">

    <div class="flex min-h-screen w-full">
        
        <div class="hidden lg:flex lg:w-1/2 relative flex-col items-center justify-center overflow-hidden bg-white p-12 border-r border-slate-100">
            
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 -right-20 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>

            <div class="w-full max-w-lg mb-8 fade-in">
                <lottie-player 
                    src="{{ asset('Free Isometric Building Bundle.json') }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 100%; height: 350px;" 
                    loop 
                    autoplay>
                </lottie-player>
            </div>

            <div class="relative z-10 max-w-lg text-center fade-in" style="animation-delay: 0.2s;">
                <h1 class="text-4xl font-extrabold text-slate-800 mb-4 tracking-tight">BERKAH <span class="text-primary">Panglong</span></h1>
                <p class="text-base text-slate-500 leading-relaxed mb-8 font-medium">Sistem Enterprise Resource Planning (ERP) terintegrasi untuk manajemen stok, kasir POS, dan administrasi material bangunan.</p>

                <div class="flex items-center justify-center gap-4 text-sm font-semibold text-slate-600">
                    <span class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-200 shadow-sm"><i class="fas fa-bolt text-amber-500"></i> Cepat</span>
                    <span class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-200 shadow-sm"><i class="fas fa-shield-alt text-emerald-500"></i> Aman</span>
                    <span class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-200 shadow-sm"><i class="fas fa-chart-pie text-blue-500"></i> Akurat</span>
                </div>
            </div>
            
        </div>


        <div class="w-full lg:w-1/2 flex items-center justify-center bg-surface relative p-6 sm:p-12">
            
            <div class="absolute inset-0 overflow-hidden pointer-events-none lg:hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            </div>

            <div class="max-w-md w-full bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10 relative z-10 fade-in" style="animation-delay: 0.3s;">
                
                <div class="text-center mb-10">
                    <div class="lg:hidden w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-warehouse text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm font-medium">Silakan masuk dengan akun Anda untuk mengakses sistem.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 flex items-start gap-3 border border-red-100 shadow-sm animate-pulse">
                        <i class="fas fa-exclamation-circle mt-0.5 text-lg"></i>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary">
                                <i class="fas fa-envelope text-slate-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm outline-none font-medium text-slate-700 placeholder:text-slate-400 placeholder:font-normal" 
                                placeholder="admin@flowinti.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input type="password" name="password" required 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm outline-none font-medium text-slate-700 placeholder:text-slate-400 placeholder:font-normal" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-[#4a332c] hover:shadow-lg hover:shadow-primary/30 transition-all duration-300 flex items-center justify-center gap-2 mt-4 group">
                        <span>Masuk Sistem</span>
                        <i class="fas fa-arrow-right text-sm transform group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-xs font-medium text-slate-400">© {{ date('Y') }} FlowInti Panglong. All rights reserved.</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>