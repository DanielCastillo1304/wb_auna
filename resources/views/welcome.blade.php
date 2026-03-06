@extends('layouts.app')

@section('title', 'Panel de Control | ' . env('APP_NAME'))

@section('content')

<div class="min-h-screen px-6 lg:px-10 py-6" style="background: #f4f6f8;">

    {{-- HEADER --}}
    <div class="mb-6">
        <p class="text-[10px] font-bold uppercase mb-1"
           style="color: rgba(0,140,165,0.55);">
            Panel de Control
        </p>
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    ¡Hola, <span style="color: rgb(0,140,165);">{{ Auth::user()->username }}</span>!
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">
                    Bienvenido al sistema de gestión AUNA · {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Reloj --}}
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white"
                 style="border: 1px solid #e8edf2;">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse flex-shrink-0"
                      style="background: rgb(190,214,0);"></span>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En línea</span>
                <div class="w-px h-3 bg-slate-200 mx-1"></div>
                <span class="text-[12px] font-black tabular-nums" id="liveClock"
                      style="color: rgb(0,140,165);">
                    {{ now()->format('H:i:s') }}
                </span>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        <div class="bg-white rounded-lg px-4 py-3.5 flex items-center gap-3"
             style="border: 1px solid #e8edf2;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background: rgba(0,176,202,0.08);">
                <span class="material-symbols-outlined text-[20px]" style="color: rgb(0,176,202);">person</span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none">Usuario</p>
                <p class="text-sm font-black text-slate-700 mt-0.5 leading-none truncate">
                    {{ Auth::user()->username }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg px-4 py-3.5 flex items-center gap-3"
             style="border: 1px solid #e8edf2;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background: rgba(190,214,0,0.1);">
                <span class="material-symbols-outlined text-[20px]" style="color: rgb(140,170,0);">calendar_today</span>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none">Fecha</p>
                <p class="text-sm font-black text-slate-700 mt-0.5 leading-none">
                    {{ now()->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg px-4 py-3.5 flex items-center gap-3"
             style="border: 1px solid #e8edf2;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background: rgba(0,176,202,0.08);">
                <span class="material-symbols-outlined text-[20px]" style="color: rgb(0,176,202);">schedule</span>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none">Hora</p>
                <p class="text-sm font-black tabular-nums mt-0.5 leading-none" id="liveClockCard"
                   style="color: rgb(0,140,165);">
                    {{ now()->format('H:i') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg px-4 py-3.5 flex items-center gap-3"
             style="border: 1px solid #e8edf2;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background: rgba(190,214,0,0.1);">
                <span class="material-symbols-outlined text-[20px]" style="color: rgb(140,170,0);">verified</span>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none">Sistema</p>
                <p class="text-sm font-black text-slate-700 mt-0.5 leading-none">v1.0 · Activo</p>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hms = [now.getHours(), now.getMinutes(), now.getSeconds()]
            .map(n => String(n).padStart(2, '0')).join(':');
        const hm  = hms.slice(0, 5);
        const el1 = document.getElementById('liveClock');
        const el2 = document.getElementById('liveClockCard');
        if (el1) el1.textContent = hms;
        if (el2) el2.textContent = hm;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush

@endsection