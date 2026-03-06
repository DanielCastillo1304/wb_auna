@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
            <div class="w-full lg:w-auto">
                <nav class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                    <span class="text-slate-600">Gestión de {{ $extend['title'] }}</span>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tighter capitalize leading-tight">
                    {{ $extend['title'] }}
                </h1>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <a href="{{ route($extend['controller'] . '.form') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl shadow-xl shadow-slate-200 hover:bg-red-600 hover:shadow-red-200 transition-all active:scale-95">
                    <span class="material-symbols-outlined mr-2 text-[20px]">add_circle</span>
                    <span class="whitespace-nowrap">Nuevo Registro</span>
                </a>
            </div>
        </div>
        <div id="alertContainer" class="fixed top-20 right-0 left-0 sm:left-auto sm:right-5 z-[100] px-4 sm:px-0 sm:min-w-[380px]"></div>

        <div class="bg-white rounded-2xl p-2 mb-8 shadow-sm flex flex-col md:flex-row gap-2">
            <div class="relative flex-1 group">
                <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 group-focus-within:text-red-600 transition-colors">search</span>
                <input type="text" id="searchInput" placeholder="Buscar por nombre, abreviatura o fecha de fundación..."
                    class="w-full h-12 pl-12 pr-4 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-red-600/10 text-sm font-medium transition-all placeholder:text-slate-400 placeholder:font-normal">
            </div>

            <button id="btnClearSearch"
                class="group hidden md:flex items-center justify-center w-12 h-12 bg-slate-50 text-slate-400 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all active:scale-95"
                title="Limpiar búsqueda">
                <span class="material-symbols-outlined transition-transform group-hover:rotate-90">backspace</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
            <div id="loadingSpinner"
                class="hidden absolute inset-0 z-20 backdrop-blur-sm bg-white/70 flex flex-col justify-center items-center py-10">
                <div class="relative flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-100 border-t-red-600"></div>
                    <span class="material-symbols-outlined absolute text-slate-400 text-sm animate-pulse">sync</span>
                </div>
                <p class="mt-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 animate-pulse">
                    Sincronizando...</p>
            </div>
            <div class="overflow-x-auto overflow-y-hidden custom-scrollbar flex-1">
                <table id="recordContainer" class="w-full text-left border-collapse min-w-[800px] lg:min-w-full">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Parámetro</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200 overflow-hidden hover:overflow-hidden">
                        <!-- Los registros se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>

            <div id="noResults" class="hidden flex-col items-center justify-center py-10 px-6 text-center">
                <span class="material-symbols-outlined text-slate-300 text-5xl mb-2">person_search</span>
                <h3 class="text-sm font-bold text-slate-900">Sin coincidencias</h3>
            </div>

            <div id="paginationContainer"
                class="bg-slate-50/50 px-4 sm:px-6 py-4 border-t border-slate-100 w-full overflow-x-auto"></div>

        </div>
    </div>


    <div id="deleteModal"
        class="opacity-0 pointer-events-none fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center px-4 transition-all duration-300">
        <div
            class="modal-content scale-95 opacity-0 w-full max-w-sm bg-white rounded-2xl border border-slate-100 shadow-[0_24px_60px_rgba(0,0,0,0.2)] overflow-hidden transition-all duration-300 delay-75">
            {{-- Header oscuro --}}
            <div class="bg-[#0c1527] px-6 py-5 flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-red-500/15 border border-red-500/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-red-400 text-[20px]">warning</span>
                </div>
                <div>
                    <p class="text-[13px] font-black text-white">¿Eliminar registro?</p>
                    <p class="text-[11px] text-blue-300/50 mt-0.5 font-sans">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <div class="px-6 py-5">
                <p class="text-[13px] text-slate-500 font-sans">¿Estás seguro de que deseas eliminar este registro
                    permanentemente?</p>
            </div>

            <div class="px-6 pb-5 flex gap-2.5">
                <button onclick="closeDeleteModal()"
                    class="flex-1 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-[12px] font-bold uppercase tracking-[0.12em] transition-all active:scale-95">
                    Cancelar
                </button>
                <button id="btnConfirmDelete"
                    class="flex-1 h-10 rounded-xl bg-red-500 hover:bg-red-600 text-white text-[12px] font-bold uppercase tracking-[0.12em] shadow-[0_4px_16px_rgba(239,68,68,0.3)] transition-all active:scale-95">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    <script>
        var controller = "{{ $extend['controller'] }}";
        var totalRecordsOld = {{ $extend['totalRecord'] }};
        var totalRecords = {{ $extend['totalRecord'] }};
        var countRecords = {{ count($data) }};
        var keyword = null;
        var user_verified = "{{ Auth::user()->verified ?? 0 }}";
    </script>

@section('script')
    <script src="{{ asset("js/$extend[controller]/index.js") }}"></script>
    <script src="{{ asset("js/$extend[controller]/table.js") }}"></script>
    <script src="{{ asset('js/table.js') }}"></script>
@stop

@endsection
