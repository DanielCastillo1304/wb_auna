@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route($extend['controller'] . '.list') }}"
                    class="group flex items-center justify-center min-w-[40px] w-10 h-10 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 tracking-tighter leading-tight">
                        {{ isset($profile) ? 'Editar' : 'Nuevo' }} <span
                            class="text-red-600">{{ $extend['title_form'] }}</span>
                    </h1>
                    <p class="text-slate-500 font-medium text-xs sm:text-sm">Completa los campos requeridos</p>
                </div>
            </div>
        </div>

        <!-- Mensajes de alerta -->
        <div id="alertContainer" class="fixed top-20 right-0 left-0 sm:left-auto sm:right-5 z-[100] px-4 sm:px-0 sm:min-w-[380px]"></div>

        <!-- Formulario -->
        <form id="mainForm" class="space-y-2" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="recordId" value="{{ $param->codparam ?? '' }}">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="param" class="block text-xs font-medium text-slate-600 ml-0.5">
                                Parámetro <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="param" name="param" value="{{ $param->param ?? '' }}"
                                placeholder="apikey"
                                class="w-full h-11 px-3.5 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-700 placeholder:text-slate-300 outline-none transition-all duration-200 hover:border-slate-300 hover:bg-white focus:bg-white focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/[0.08]"
                                required>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                        <!-- valor -->
                        <div class="space-y-2">
                            <label for="value" class="block text-xs font-medium text-slate-600 ml-0.5">
                                Valor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="value" name="value" value="{{ $param->value ?? '' }}"
                                placeholder="12345678"
                                class="w-full h-11 px-3.5 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-700 placeholder:text-slate-300 outline-none transition-all duration-200 hover:border-slate-300 hover:bg-white focus:bg-white focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/[0.08]"
                                required>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-xs font-medium text-slate-600 ml-0.5">
                                Descripción
                            </label>
                            <input type="text" id="description" name="description"
                                value="{{ $param->description ?? '' }}" placeholder="Parámetro del apikey"
                                class="w-full h-11 px-3.5 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-700 placeholder:text-slate-300 outline-none transition-all duration-200 hover:border-slate-300 hover:bg-white focus:bg-white focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/[0.08]">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-slate-50/80 px-4 sm:px-6 md:px-8 py-4 sm:py-6 flex flex-col sm:flex-row gap-3 justify-end border-t border-slate-100">
                <a href="{{ request('redirect') ?? route($extend['controller'] . '.list') }}"
                    class="order-2 sm:order-1 px-6 sm:px-8 py-3 sm:py-3.5 flex items-center justify-center bg-white hover:bg-slate-100 text-slate-600 font-black text-[9px] sm:text-[10px] uppercase tracking-widest rounded-md border border-slate-200 transition-all text-center">
                    Cancelar
                </a>
                <button type="submit" id="btnSubmit"
                    class="order-1 sm:order-2 px-8 sm:px-10 py-3.5 sm:py-4 md:py-3 bg-slate-900 hover:bg-red-600 text-white font-black text-[9px] sm:text-[10px] uppercase tracking-[0.15em] sm:tracking-[0.2em] rounded-md transition-all shadow-xl shadow-slate-200 flex items-center justify-center group active:scale-95">
                    <span class="material-symbols-outlined mr-2 text-[20px] group-hover:animate-pulse">save</span>
                    <span
                        id="btnSubmitText">{{ isset($param) ? 'Actualizar Registro' : 'Guardar ' . $extend['title_form'] }}</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        var controller = "{{ $extend['controller'] }}";
        var totalRecordsOld = {{ $extend['totalRecord'] }};
        var totalRecords = {{ $extend['totalRecord'] }};
        var recordId = "{{ $param->codparam ?? '' }}";
    </script>

@section('script')
    <script src="{{ asset("js/$extend[controller]/form.js") }}"></script>
@stop

@endsection
