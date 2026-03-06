@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')

<div class="min-h-screen" style="background: #f0f9fb;">
    <div class="container mx-auto px-4 sm:px-6 py-8 max-w-3xl">

        {{-- HEADER --}}
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route($extend['controller'] . '.list') }}"
                class="group flex items-center justify-center w-10 h-10 rounded-xl text-white flex-shrink-0 transition-all duration-200 active:scale-95"
                style="background: rgba(0,176,202,0.12); border: 1px solid rgba(0,176,202,0.2);"
                onmouseover="this.style.background='rgb(0,176,202)'; this.style.borderColor='rgb(0,176,202)';"
                onmouseout="this.style.background='rgba(0,176,202,0.12)'; this.style.borderColor='rgba(0,176,202,0.2)';">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform"
                      style="color: rgb(0,140,165);">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]"
                          style="color: rgba(0,176,202,0.6);">
                        {{ $extend['title'] }}
                    </span>
                    <span class="w-1 h-1 rounded-full" style="background: rgba(0,176,202,0.3);"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]"
                          style="color: {{ isset($equipment_type) ? 'rgb(245,158,11)' : 'rgb(190,214,0)' }};">
                        {{ isset($equipment_type) ? 'Editando' : 'Nuevo registro' }}
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none">
                    {{ isset($equipment_type) ? 'Editar' : 'Crear' }}
                    <span style="color: rgb(0,176,202);">{{ $extend['title_form'] }}</span>
                </h1>
            </div>
        </div>

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-20 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- FORM --}}
        <form id="mainForm" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" id="recordId" value="{{ $equipment_type->codequipment_type ?? '' }}">

            {{-- CARD PRINCIPAL --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 4px 20px rgba(0,176,202,0.08);">

                {{-- CAMPOS --}}
                <div class="p-6 sm:p-8 space-y-6">

                    {{-- Campo: Nombre --}}
                    <div class="space-y-1.5">
                        <label for="name"
                               class="flex items-center gap-1 text-xs font-black uppercase tracking-wider"
                               style="color: rgb(0,140,165);">
                            Nombre
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            placeholder="Ej: Tipo de equipo A"
                            value="{{ $equipment_type->name ?? '' }}"
                            class="w-full h-11 px-4 text-sm text-slate-800 rounded-xl outline-none transition-all duration-200"
                            style="background: rgba(0,176,202,0.04); border: 1px solid rgba(0,176,202,0.2);"
                            onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 4px rgba(0,176,202,0.06)';"
                            onblur="this.style.background='rgba(0,176,202,0.04)'; this.style.borderColor='rgba(0,176,202,0.2)'; this.style.boxShadow='none';">
                        <span class="error-message hidden text-xs font-medium text-red-500 mt-1"
                              data-error-for="name"></span>
                    </div>

                    {{-- Agrega más campos aquí siguiendo el mismo patrón --}}

                </div>

                {{-- FOOTER ACCIONES --}}
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 px-6 sm:px-8 py-5 border-t"
                     style="background: rgba(0,176,202,0.02); border-color: rgba(0,176,202,0.1);">

                    <p class="text-[11px] font-medium" style="color: rgba(0,176,202,0.6);">
                        <span class="text-red-500">*</span> Campos obligatorios
                    </p>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- Cancelar --}}
                        <a href="{{ route($extend['controller'] . '.list') }}"
                            class="flex-1 sm:flex-none px-6 py-2.5 flex items-center justify-center gap-2 text-xs font-black uppercase tracking-widest rounded-xl transition-all"
                            style="background: rgba(0,176,202,0.06); border: 1px solid rgba(0,176,202,0.15); color: rgb(0,140,165);"
                            onmouseover="this.style.background='rgba(0,176,202,0.12)';"
                            onmouseout="this.style.background='rgba(0,176,202,0.06)';">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                            Cancelar
                        </a>

                        {{-- Guardar --}}
                        <button type="submit" id="btnSubmit"
                            class="flex-1 sm:flex-none px-8 py-2.5 flex items-center justify-center gap-2 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-200 active:scale-95 group"
                            style="background: linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%); box-shadow: 0 4px 14px rgba(0,176,202,0.3);"
                            onmouseover="this.style.background='linear-gradient(135deg, rgb(190,214,0) 0%, rgb(160,185,0) 100%)'; this.style.boxShadow='0 4px 14px rgba(190,214,0,0.3)';"
                            onmouseout="this.style.background='linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%)'; this.style.boxShadow='0 4px 14px rgba(0,176,202,0.3)';">
                            <span class="material-symbols-outlined text-[16px] group-hover:rotate-12 transition-transform">save</span>
                            <span id="btnSubmitText">
                                {{ isset($equipment_type) ? 'Actualizar' : 'Guardar' }}
                            </span>
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
        const recordId   = "{{ $equipment_type->codequipment_type ?? '' }}";
    </script>
    <script src="{{ mix('js/commons/form.js') }}"></script>
    <script src="{{ mix('js/modules/' . $extend['controller'] . '/form.js') }}"></script>
@endpush

@endsection