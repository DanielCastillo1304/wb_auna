@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')

<div class="">
    <div class="px-6 lg:px-10 py-6">

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-20 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- FILA 1: TÍTULO --}}
        <div class="mb-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-xl font-bold text-slate-800 leading-none">
                        {{ $extend['title'] }}
                    </h1>
                    <p class="text-xs text-gray-500 mt-0.5 font-normal">
                        Gestiona los registros de {{ strtolower($extend['title']) }}.
                    </p>
                </div>

                <a href="{{ route($extend['controller'] . '.form') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-white text-sm rounded-lg transition-all duration-200 active:scale-95 flex-shrink-0"
                    style="background: rgb(0,176,202); box-shadow: 0 2px 8px rgba(0,176,202,0.3);"
                    onmouseover="this.style.background='rgb(190,214,0)'; this.style.boxShadow='0 2px 8px rgba(190,214,0,0.3)';"
                    onmouseout="this.style.background='rgb(0,176,202)'; this.style.boxShadow='0 2px 8px rgba(0,176,202,0.3)';">
                    <span class="material-symbols-outlined text-[15px]">add</span>
                    Nuevo registro
                </a>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="bg-white rounded-lg px-4 py-3 mb-0 flex items-center gap-3"
             style="border: 1px solid #e8edf2; border-bottom: none; border-radius: 8px 8px 0 0;">

            {{-- Buscador --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-md w-56"
                 style="background: #f4f6f8; border: 1px solid #e2e8f0;">
                <span class="material-symbols-outlined text-[16px] flex-shrink-0 transition-colors"
                      id="searchIcon"
                      style="color: #94a3b8;">search</span>
                <input type="text" id="searchInput"
                    placeholder="Buscar..."
                    class="flex-1 text-sm text-slate-700 placeholder:text-slate-300 outline-none bg-transparent"
                    onfocus="this.parentElement.style.borderColor='rgba(0,176,202,0.5)'; document.getElementById('searchIcon').style.color='rgb(0,176,202)';"
                    onblur="this.parentElement.style.borderColor='#e2e8f0'; document.getElementById('searchIcon').style.color='#94a3b8';">
                <button id="btnClearSearch"
                    class="hidden flex-shrink-0 transition-colors -ms-2 mt-1"
                    style="color: #94a3b8;"
                    onmouseover="this.style.color='rgb(0,176,202)';"
                    onmouseout="this.style.color='#94a3b8';">
                    <span class="material-symbols-outlined text-[15px]">close</span>
                </button>
            </div>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- Info registros --}}
            <p class="text-xs text-slate-400 font-medium hidden sm:block">
                <span id="totalRecordsInfo" class="font-black text-slate-600">{{ $extend['totalRecord'] ?? 0 }}</span>
                registros
            </p>
        </div>

        {{-- TABLA --}}
        <div class="bg-white overflow-hidden relative"
             style="border: 1px solid #e8edf2; border-radius: 0 0 8px 8px;">

            {{-- LOADING OVERLAY --}}
            <div id="loadingSpinner"
                class="hidden absolute inset-0 z-20 flex flex-col items-center justify-center gap-3"
                style="background: rgba(255,255,255,0.95); backdrop-filter: blur(2px);">
                <div class="relative w-8 h-8">
                    <div class="absolute inset-0 rounded-full border-2"
                         style="border-color: rgba(0,176,202,0.1);"></div>
                    <div class="absolute inset-0 rounded-full border-2 border-transparent animate-spin"
                         style="border-top-color: rgb(0,176,202);"></div>
                    <div class="absolute inset-1 rounded-full border-2 border-transparent animate-spin"
                         style="border-top-color: rgb(190,214,0); animation-direction: reverse; animation-duration: 0.6s;"></div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] animate-pulse"
                   style="color: rgba(0,176,202,0.6);">Cargando...</p>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table id="recordContainer" class="w-full text-left min-w-[900px]">

                    {{-- HEAD --}}
                    <thead>
                        <tr style="border-bottom: 1px solid #e8edf2; background: #f8fafc;">
                            <th class="px-5 py-3 text-xs text-gray-500 w-14 font-semibold">#</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">Apellidos y nombres</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">DNI</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">Cargo</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">Área</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">Sede</th>
                            <th class="px-5 py-3 text-xs text-gray-500 font-semibold">Fec. ingreso</th>
                            <th class="px-5 py-3 text-xs text-gray-500 text-right w-28 font-semibold">Acciones</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody id="tableBody">
                        @for ($i = 0; $i < 8; $i++)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="px-5 py-3">
                                    <div class="h-3 w-7 rounded animate-pulse bg-slate-100"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 rounded animate-pulse bg-slate-100" style="width: {{ rand(40,65) }}%;"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 w-20 rounded animate-pulse bg-slate-100"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 rounded animate-pulse bg-slate-100" style="width: {{ rand(30,55) }}%;"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 rounded animate-pulse bg-slate-100" style="width: {{ rand(25,50) }}%;"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 w-24 rounded animate-pulse bg-slate-100"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="h-3 w-20 rounded animate-pulse bg-slate-100"></div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end gap-2">
                                        <div class="h-6 w-6 rounded animate-pulse bg-slate-100"></div>
                                        <div class="h-6 w-6 rounded animate-pulse bg-slate-100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- SIN RESULTADOS --}}
            <div id="noResults" class="hidden flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 bg-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-slate-400">search_off</span>
                </div>
                <p class="text-sm font-black text-slate-600 mb-0.5">Sin resultados</p>
                <p class="text-xs text-slate-400">No hay registros que coincidan con tu búsqueda</p>
            </div>

            {{-- PAGINACIÓN --}}
            <div id="paginationContainer"
                 class="px-5 py-3 border-t bg-slate-50/50"
                 style="border-color: #e8edf2;">
            </div>
        </div>

    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="deleteModal"
    class="opacity-0 pointer-events-none fixed inset-0 z-50 flex items-center justify-center px-4 transition-all duration-300"
    style="background: rgba(0,20,40,0.4); backdrop-filter: blur(4px);">
    <div class="modal-content scale-95 opacity-0 w-full max-w-[340px] bg-white rounded-xl overflow-hidden transition-all duration-300"
         style="border: 1px solid #e8edf2; box-shadow: 0 20px 40px rgba(0,0,0,0.12);">

        <div class="px-5 py-4 flex items-center gap-3"
             style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-red-50">
                <span class="material-symbols-outlined text-[17px] text-red-500">delete_forever</span>
            </div>
            <div>
                <p class="text-sm font-black text-slate-800 leading-none">¿Eliminar registro?</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Esta acción no se puede deshacer</p>
            </div>
        </div>

        <div class="px-5 py-4">
            <p class="text-sm text-slate-500 leading-relaxed">
                Este registro se eliminará <strong class="text-slate-700">permanentemente</strong> del sistema.
            </p>
        </div>

        <div class="px-5 pb-4 flex gap-2">
            <button id="btnCancelDelete"
                class="flex-1 h-9 rounded-lg text-xs font-bold text-slate-600 transition-all active:scale-95 bg-slate-100 hover:bg-slate-200">
                Cancelar
            </button>
            <button id="btnConfirmDelete"
                class="flex-1 h-9 rounded-lg text-white text-xs font-bold transition-all active:scale-95"
                style="background: rgb(220,50,50);"
                onmouseover="this.style.background='rgb(200,30,30)';"
                onmouseout="this.style.background='rgb(220,50,50)';">
                Sí, eliminar
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