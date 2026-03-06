@extends('layouts.app')

@section('title', 'Panel de Control | ' . env('APP_NAME'))

@section('content')

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background: #f0f9fb;">
        <div class="max-w-[1400px] mx-auto">

            {{-- HERO CARD --}}
            <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 mb-8 sm:mb-10"
                style="background: linear-gradient(135deg, #ffffff 0%, #f0fafc 60%, #e8f7fa 100%); border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 4px 24px rgba(0,176,202,0.08);">
                {{-- Glow turquesa --}}
                <div class="absolute top-0 right-0 w-96 h-96 rounded-full pointer-events-none"
                    style="background: radial-gradient(circle, rgba(0,176,202,0.15) 0%, transparent 70%); transform: translate(30%, -30%);">
                </div>

                {{-- Glow verde --}}
                <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full pointer-events-none"
                    style="background: radial-gradient(circle, rgba(190,214,0,0.08) 0%, transparent 70%); transform: translate(-30%, 30%);">
                </div>

                {{-- Dot pattern --}}
                <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
                    style="background-image: radial-gradient(circle at 1px 1px, rgba(0,176,202,1) 1px, transparent 0); background-size: 24px 24px;">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                    {{-- Saludo --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] font-black uppercase tracking-[0.25em] px-3 py-1 rounded-full"
                                style="background: rgba(0,176,202,0.15); color: rgb(0,176,202); border: 1px solid rgba(0,176,202,0.2);">
                                Panel de Control
                            </span>
                            <span class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-[0.2em]"
                                style="color: rgba(190,214,0,0.7);">
                                <span class="w-1.5 h-1.5 rounded-full animate-pulse"
                                    style="background: rgb(190,214,0);"></span>
                                En línea
                            </span>
                        </div>

                        {{-- Saludo --}}
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-none mb-3"
                            style="color: #0a1628;">
                            ¡Hola,
                            <span style="color: rgb(0,176,202);">{{ Auth::user()->username }}</span>!
                        </h1>

                        <p class="text-sm sm:text-base font-medium leading-relaxed max-w-xl"
                            style="color: rgba(10,22,40,0.55);">
                            Bienvenido al sistema principal de
                            <span class="font-black" style="color: rgb(0,140,165);">AUNA</span>.
                            Tienes acceso a las herramientas esenciales para tu gestión.
                        </p>

                        {{-- Línea decorativa --}}
                        <div class="flex items-center gap-2 mt-5">
                            <div class="h-px w-8 rounded-full" style="background: rgb(0,176,202);"></div>
                            <div class="h-px w-4 rounded-full" style="background: rgb(190,214,0);"></div>
                            <div class="h-px w-2 rounded-full" style="background: rgba(255,255,255,0.2);"></div>
                        </div>
                    </div>

                    <span class="block text-3xl sm:text-4xl font-black tabular-nums tracking-tight" id="liveClock"
                        style="color: rgb(0,140,165);">
                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] mb-1"
                            style="color: rgba(0,176,202,0.7);">
                            {{ now()->translatedFormat('l') }}
                        </span>
                        <span class="block text-xs font-bold mb-3" style="color: rgba(255,255,255,0.4);">
                            {{ now()->translatedFormat('d F, Y') }}
                        </span>
                        <span class="block text-3xl sm:text-4xl font-black tabular-nums tracking-tight" id="liveClock"
                            style="color: rgb(0,176,202); text-shadow: 0 0 30px rgba(0,176,202,0.4);">
                            {{ now()->format('H:i:s') }}
                        </span>
                        <div class="mt-3 h-px w-full rounded-full"
                            style="background: linear-gradient(90deg, transparent, rgba(0,176,202,0.4), transparent);">
                        </div>
                        <span class="block text-[10px] font-bold mt-2 uppercase tracking-widest"
                            style="color: rgba(190,214,0,0.6);">
                            Sistema v1.0
                        </span>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            // Reloj en tiempo real
            function updateClock() {
                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                const el = document.getElementById('liveClock');
                if (el) el.textContent = `${h}:${m}:${s}`;
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>
    @endpush

@endsection
