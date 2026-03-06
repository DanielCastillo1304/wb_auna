@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')
    <div class="min-h-screen" style="background: #f4f6f8;">

        {{-- TOPBAR DEL FORM --}}
        <div class="bg-white px-6 lg:px-10 py-4 flex items-center gap-4" style="border-bottom: 1px solid #e8edf2;">

            {{-- Volver --}}
            <a href="{{ route($extend['controller'] . '.list') }}"
                class="group flex items-center justify-center w-8 h-8 rounded-lg flex-shrink-0 transition-all duration-200 active:scale-95 bg-slate-100 hover:bg-slate-200">
                <span
                    class="material-symbols-outlined text-[17px] text-slate-500 group-hover:-translate-x-0.5 transition-transform">
                    arrow_back
                </span>
            </a>

            {{-- Separador --}}
            <div class="w-px h-5 bg-slate-200 flex-shrink-0"></div>

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route($extend['controller'] . '.list') }}"
                    class="font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    {{ $extend['title'] }}
                </a>
                <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                <span class="font-semibold text-slate-700">
                    {{ isset($business_unit) ? 'Editar registro' : 'Nuevo registro' }}
                </span>
            </div>

            {{-- Badge estado --}}
            <span class="ml-1 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                style="{{ isset($business_unit)
                    ? 'background: rgba(245,158,11,0.1); color: rgb(217,119,6); border: 1px solid rgba(245,158,11,0.2);'
                    : 'background: rgba(160,185,0,0.1); color: rgb(120,140,0); border: 1px solid rgba(160,185,0,0.2);' }}">
                {{ isset($business_unit) ? 'Editando' : 'Nuevo' }}
            </span>
        </div>

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-16 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- CONTENIDO --}}
        <div class="px-6 lg:px-10 py-6 max-w-3xl">

            <form id="mainForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" id="recordId" value="{{ $business_unit->codbusiness_unit ?? '' }}">

                {{-- CARD --}}
                <div class="bg-white rounded-lg overflow-hidden"
                    style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
                    {{-- CAMPOS --}}
                    <div class="p-6 space-y-5">

                        <div class="space-y-1.5">
                            <label for="name" class="flex items-center gap-1 text-xs text-slate-500">
                                Nombre de la unidad
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" required placeholder="Ej: AUNA IDEAS"
                                value="{{ $business_unit->name ?? '' }}"
                                class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            <span class="error-message hidden text-[11px] font-medium text-red-500 mt-1"
                                data-error-for="name"></span>
                        </div>

                    </div>

                    {{-- CARD FOOTER --}}
                    <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3"
                        style="border-top: 1px solid #f1f5f9; background: #fafbfc;">

                        <p class="text-[11px] text-slate-400">
                            <span class="text-red-400">*</span> Campos obligatorios
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto">

                            {{-- Cancelar --}}
                            <a href="{{ route($extend['controller'] . '.list') }}"
                                class="flex-1 sm:flex-none h-9 px-5 flex items-center justify-center gap-1.5 text-xs font-normal text-slate-600 rounded-lg transition-all bg-slate-200 hover:bg-slate-300 active:scale-95">
                                <span class="material-symbols-outlined text-[15px]">chevron_left</span>
                                Cancelar
                            </a>

                            {{-- Guardar --}}
                            <button type="submit" id="btnSubmit"
                                class="flex-1 sm:flex-none h-9 px-6 flex items-center justify-center gap-1.5 text-white text-xs font-normal rounded-lg transition-all duration-200 active:scale-95 group"
                                style="background: rgb(0,176,202); box-shadow: 0 2px 8px rgba(0,176,202,0.3);"
                                onmouseover="this.style.background='rgb(190,214,0)'; this.style.boxShadow='0 2px 8px rgba(190,214,0,0.3)'; this.style.color='white';"
                                onmouseout="this.style.background='rgb(0,176,202)'; this.style.boxShadow='0 2px 8px rgba(0,176,202,0.3)'; this.style.color='white';">
                                <span id="btnSubmitText">
                                    {{ isset($business_unit) ? 'Actualizar' : 'Guardar' }}
                                </span>
                                {{-- <span class="material-symbols-outlined text-[15px]">chevron_right</span> --}}
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const controller = "{{ $extend['controller'] }}";
            const recordId = "{{ $business_unit->codbusiness_unit ?? '' }}";
        </script>
        <script src="{{ mix('js/commons/form.js') }}"></script>
        <script src="{{ mix('js/modules/' . $extend['controller'] . '/form.js') }}"></script>
    @endpush

@endsection
