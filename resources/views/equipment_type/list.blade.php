@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')

<div class="min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 py-8">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <nav class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[0.2em] mb-2"
                     style="color: rgba(0,176,202,0.6);">
                    <a href="{{ route('home') }}"
                       class="transition-colors hover:opacity-100"
                       style="color: rgba(0,176,202,0.6);"
                       onmouseover="this.style.color='rgb(0,176,202)'"
                       onmouseout="this.style.color='rgba(0,176,202,0.6)'">
                        Dashboard
                    </a>
                    <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                    <span style="color: rgb(0,140,165);">Listado</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none">
                        {{ $extend['title'] }}
                    </h1>
                    <span id="totalRecords"
                          class="inline-flex items-center px-2.5 py-1 text-xs font-black rounded-lg tabular-nums"
                          style="background: rgba(0,176,202,0.1); color: rgb(0,140,165); border: 1px solid rgba(0,176,202,0.2);">
                        {{ $extend['totalRecord'] ?? 0 }}
                    </span>
                </div>
            </div>

            <a href="{{ route($extend['controller'] . '.form') }}"
                class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-200 active:scale-95"
                style="background: linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%); box-shadow: 0 4px 14px rgba(0,176,202,0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgb(190,214,0) 0%, rgb(160,185,0) 100%)'; this.style.boxShadow='0 4px 14px rgba(190,214,0,0.3)';"
                onmouseout="this.style.background='linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%)'; this.style.boxShadow='0 4px 14px rgba(0,176,202,0.3)';">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Agregar
            </a>
        </div>

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-5 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- BUSCADOR --}}
        <div class="bg-white rounded-2xl p-2 mb-4 flex gap-2"
             style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 2px 12px rgba(0,176,202,0.06);">
            <div class="relative flex-1 group">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 material-symbols-outlined text-[20px] transition-colors"
                      style="color: rgba(0,176,202,0.4);"
                      id="searchIcon">
                    search
                </span>
                <input type="text" id="searchInput"
                    placeholder="Buscar registros por nombre..."
                    class="w-full h-11 pl-11 pr-4 rounded-xl border border-transparent text-sm font-medium text-slate-700 placeholder:font-normal outline-none transition-all"
                    style="background: rgba(0,176,202,0.04); placeholder-color: rgba(0,176,202,0.3);"
                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.3)'; this.style.boxShadow='0 0 0 4px rgba(0,176,202,0.06)'; document.getElementById('searchIcon').style.color='rgb(0,176,202)';"
                    onblur="this.style.background='rgba(0,176,202,0.04)'; this.style.borderColor='transparent'; this.style.boxShadow='none'; document.getElementById('searchIcon').style.color='rgba(0,176,202,0.4)';">
            </div>
            <button id="btnClearSearch"
                class="hidden flex-shrink-0 w-11 h-11 flex items-center justify-center rounded-xl border border-transparent transition-all active:scale-95"
                style="background: rgba(0,176,202,0.06); color: rgba(0,176,202,0.5);"
                onmouseover="this.style.background='rgba(0,176,202,0.12)'; this.style.color='rgb(0,176,202)';"
                onmouseout="this.style.background='rgba(0,176,202,0.06)'; this.style.color='rgba(0,176,202,0.5)';"
                title="Limpiar búsqueda">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- TABLA --}}
        <div class="bg-white rounded-2xl overflow-hidden relative"
             style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 4px 20px rgba(0,176,202,0.08);">

            {{-- LOADING OVERLAY --}}
            <div id="loadingSpinner"
                class="hidden absolute inset-0 z-20 flex flex-col items-center justify-center gap-3"
                style="background: rgba(255,255,255,0.85); backdrop-filter: blur(2px);">
                <div class="relative w-10 h-10">
                    <div class="absolute inset-0 rounded-full border-4"
                         style="border-color: rgba(0,176,202,0.15);"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-transparent animate-spin"
                         style="border-top-color: rgb(0,176,202);"></div>
                    <div class="absolute inset-2 rounded-full border-4 border-transparent animate-spin"
                         style="border-top-color: rgb(190,214,0); animation-direction: reverse; animation-duration: 0.7s;"></div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.25em] animate-pulse"
                   style="color: rgba(0,176,202,0.7);">
                    Cargando...
                </p>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table id="recordContainer" class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-b" style="background: rgba(0,176,202,0.03); border-color: rgba(0,176,202,0.1);">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]"
                                style="color: rgba(0,140,165,0.7);">#</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]"
                                style="color: rgba(0,140,165,0.7);">Nombre</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center"
                                style="color: rgba(0,140,165,0.7);">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y" style="--tw-divide-opacity: 1;">
                    </tbody>
                </table>
            </div>

            {{-- SIN RESULTADOS --}}
            <div id="noResults" class="hidden flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                     style="background: rgba(0,176,202,0.07);">
                    <span class="material-symbols-outlined text-[32px]"
                          style="color: rgba(0,176,202,0.5);">search_off</span>
                </div>
                <h3 class="text-sm font-black text-slate-700 mb-1">Sin resultados</h3>
                <p class="text-xs font-medium" style="color: rgba(0,176,202,0.6);">
                    No se encontraron registros con ese criterio
                </p>
            </div>

            {{-- PAGINACIÓN --}}
            <div id="paginationContainer"
                 class="px-6 py-4 border-t"
                 style="background: rgba(0,176,202,0.02); border-color: rgba(0,176,202,0.1);">
            </div>
        </div>

    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="deleteModal"
    class="opacity-0 pointer-events-none fixed inset-0 z-50 flex items-center justify-center px-4 transition-all duration-300"
    style="background: rgba(0,20,40,0.5); backdrop-filter: blur(4px);">
    <div class="modal-content scale-95 opacity-0 w-full max-w-sm bg-white rounded-2xl overflow-hidden transition-all duration-300"
         style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 24px 60px rgba(0,20,40,0.3);">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-center gap-4"
             style="background: linear-gradient(135deg, #0a1628 0%, #0d1f35 100%);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(255,80,80,0.1); border: 1px solid rgba(255,80,80,0.2);">
                <span class="material-symbols-outlined text-[20px]" style="color: rgb(255,100,100);">delete_forever</span>
            </div>
            <div>
                <p class="text-sm font-black text-white leading-none">¿Eliminar registro?</p>
                <p class="text-[11px] mt-1" style="color: rgba(0,176,202,0.6);">Esta acción no se puede deshacer</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <p class="text-sm text-slate-500 leading-relaxed">
                Este registro se eliminará permanentemente del sistema.
            </p>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5 flex gap-2.5">
            <button id="btnCancelDelete"
                class="flex-1 h-10 rounded-xl text-slate-600 text-xs font-black uppercase tracking-widest transition-all active:scale-95"
                style="background: rgba(0,176,202,0.06); border: 1px solid rgba(0,176,202,0.15);"
                onmouseover="this.style.background='rgba(0,176,202,0.12)';"
                onmouseout="this.style.background='rgba(0,176,202,0.06)';">
                Cancelar
            </button>
            <button id="btnConfirmDelete"
                class="flex-1 h-10 rounded-xl text-white text-xs font-black uppercase tracking-widest transition-all active:scale-95"
                style="background: linear-gradient(135deg, rgb(220,50,50) 0%, rgb(185,30,30) 100%); box-shadow: 0 4px 14px rgba(220,50,50,0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgb(240,60,60) 0%, rgb(200,40,40) 100%)';"
                onmouseout="this.style.background='linear-gradient(135deg, rgb(220,50,50) 0%, rgb(185,30,30) 100%)';">
                Eliminar
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const controller   = "{{ $extend['controller'] }}";
        const countRecords = {{ $extend['totalRecord'] ?? 0 }};
    </script>
    <script src="{{ mix('js/commons/table.js') }}"></script>
    <script src="{{ mix('js/modules/' . $extend['controller'] . '/index.js') }}"></script>
@endpush

@endsection