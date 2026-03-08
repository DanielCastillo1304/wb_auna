@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - ' . $extend['title'])

@section('content')
    <div class="min-h-screen" style="background: #f4f6f8;">

        {{-- TOPBAR DEL FORM --}}
        <div class="bg-white px-6 lg:px-10 py-4 flex items-center gap-4" style="border-bottom: 1px solid #e8edf2;">

            <a href="{{ route($extend['controller'] . '.list') }}"
                class="group flex items-center justify-center w-8 h-8 rounded-lg flex-shrink-0 transition-all duration-200 active:scale-95 bg-slate-100 hover:bg-slate-200">
                <span class="material-symbols-outlined text-[17px] text-slate-500 group-hover:-translate-x-0.5 transition-transform">
                    arrow_back
                </span>
            </a>

            <div class="w-px h-5 bg-slate-200 flex-shrink-0"></div>

            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route($extend['controller'] . '.list') }}"
                    class="font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    {{ $extend['title'] }}
                </a>
                <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                <span class="font-semibold text-slate-700">
                    {{ isset($personal) ? 'Editar registro' : 'Nuevo registro' }}
                </span>
            </div>

            <span class="ml-1 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                style="{{ isset($personal)
                    ? 'background: rgba(245,158,11,0.1); color: rgb(217,119,6); border: 1px solid rgba(245,158,11,0.2);'
                    : 'background: rgba(160,185,0,0.1); color: rgb(120,140,0); border: 1px solid rgba(160,185,0,0.2);' }}">
                {{ isset($personal) ? 'Editando' : 'Nuevo' }}
            </span>
        </div>

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-16 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- CONTENIDO --}}
        <div class="px-6 lg:px-10 py-6 max-w-5xl">

            <form id="mainForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" id="recordId" value="{{ $personal->codpersonal ?? '' }}">

                <div class="space-y-4">

                    {{-- ── DATOS PERSONALES ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">person</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Datos personales</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- DNI --}}
                            <div class="space-y-1.5">
                                <label for="dni" class="text-xs text-slate-500">DNI</label>
                                <input type="text" id="dni" name="dni" maxlength="15"
                                    placeholder="Ej: 12345678"
                                    value="{{ $personal->dni ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="dni"></span>
                            </div>

                            {{-- Apellidos y nombres --}}
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="ape_nom" class="flex items-center gap-1 text-xs text-slate-500">
                                    Apellidos y nombres <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="ape_nom" name="ape_nom" required maxlength="255"
                                    placeholder="Ej: García López, Juan"
                                    value="{{ $personal->ape_nom ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="ape_nom"></span>
                            </div>

                            {{-- Usuario SFSF --}}
                            <div class="space-y-1.5">
                                <label for="usr_sfsf" class="text-xs text-slate-500">Usuario SFSF</label>
                                <input type="text" id="usr_sfsf" name="usr_sfsf" maxlength="50"
                                    placeholder="Ej: jgarcia"
                                    value="{{ $personal->usr_sfsf ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="usr_sfsf"></span>
                            </div>

                            {{-- Sexo --}}
                            <div class="space-y-1.5">
                                <label for="sexo" class="text-xs text-slate-500">Sexo</label>
                                <select id="sexo" name="sexo"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 appearance-none"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                    <option value="">Seleccionar...</option>
                                    <option value="Masculino" {{ ($personal->sexo ?? '') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino"  {{ ($personal->sexo ?? '') === 'Femenino'  ? 'selected' : '' }}>Femenino</option>
                                </select>
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="sexo"></span>
                            </div>

                            {{-- Correo --}}
                            <div class="space-y-1.5">
                                <label for="correo" class="text-xs text-slate-500">Correo electrónico</label>
                                <input type="email" id="correo" name="correo" maxlength="255"
                                    placeholder="Ej: jgarcia@empresa.com"
                                    value="{{ $personal->correo ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="correo"></span>
                            </div>

                            {{-- Teléfono --}}
                            <div class="space-y-1.5">
                                <label for="telefono" class="text-xs text-slate-500">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" maxlength="50"
                                    placeholder="Ej: 999888777"
                                    value="{{ $personal->telefono ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="telefono"></span>
                            </div>

                            {{-- Fecha de ingreso --}}
                            <div class="space-y-1.5">
                                <label for="fec_ing" class="flex items-center gap-1 text-xs text-slate-500">
                                    Fecha de ingreso <span class="text-red-400">*</span>
                                </label>
                                <input type="date" id="fec_ing" name="fec_ing" required
                                    value="{{ $personal->fec_ing ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="fec_ing"></span>
                            </div>

                        </div>
                    </div>

                    {{-- ── DATOS LABORALES ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">work</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Datos laborales</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                            {{-- Tipo de contrato --}}
                            <div class="space-y-1.5">
                                <label for="tipo_contrato" class="text-xs text-slate-500">Tipo de contrato</label>
                                <input type="text" id="tipo_contrato" name="tipo_contrato" maxlength="150"
                                    placeholder="Ej: Plazo indeterminado"
                                    value="{{ $personal->tipo_contrato ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="tipo_contrato"></span>
                            </div>

                            {{-- Exclusividad --}}
                            <div class="space-y-1.5">
                                <label for="exclusividad" class="text-xs text-slate-500">Exclusividad</label>
                                <input type="text" id="exclusividad" name="exclusividad" maxlength="100"
                                    placeholder="Ej: Exclusivo"
                                    value="{{ $personal->exclusividad ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <span class="error-message hidden text-[11px] font-medium text-red-500" data-error-for="exclusividad"></span>
                            </div>

                        </div>
                    </div>

                    {{-- ── ORGANIZACIÓN ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">account_tree</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Organización</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- Sociedad --}}
                            <div class="space-y-1.5">
                                <label for="cod_sociedad" class="text-xs text-slate-500">Cód. sociedad</label>
                                <input type="text" id="cod_sociedad" name="cod_sociedad" maxlength="50"
                                    placeholder="Ej: S001"
                                    value="{{ $personal->cod_sociedad ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="soc" class="text-xs text-slate-500">Sociedad</label>
                                <input type="text" id="soc" name="soc" maxlength="255"
                                    placeholder="Ej: AUNA Perú S.A.C."
                                    value="{{ $personal->soc ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- Alcance --}}
                            <div class="space-y-1.5">
                                <label for="alcance" class="text-xs text-slate-500">Alcance</label>
                                <input type="text" id="alcance" name="alcance" maxlength="255"
                                    placeholder="Ej: Nacional"
                                    value="{{ $personal->alcance ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="negocio_atendido" class="text-xs text-slate-500">Negocio atendido</label>
                                <input type="text" id="negocio_atendido" name="negocio_atendido" maxlength="255"
                                    placeholder="Ej: Oncología"
                                    value="{{ $personal->negocio_atendido ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- N1 --}}
                            <div class="space-y-1.5">
                                <label for="cod_n1" class="text-xs text-slate-500">Cód. N1</label>
                                <input type="text" id="cod_n1" name="cod_n1" maxlength="50"
                                    value="{{ $personal->cod_n1 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="n1" class="text-xs text-slate-500">N1</label>
                                <input type="text" id="n1" name="n1" maxlength="255"
                                    value="{{ $personal->n1 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- N2 --}}
                            <div class="space-y-1.5">
                                <label for="cod_n2" class="text-xs text-slate-500">Cód. N2</label>
                                <input type="text" id="cod_n2" name="cod_n2" maxlength="50"
                                    value="{{ $personal->cod_n2 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="n2" class="text-xs text-slate-500">N2</label>
                                <input type="text" id="n2" name="n2" maxlength="255"
                                    value="{{ $personal->n2 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- N3 --}}
                            <div class="space-y-1.5">
                                <label for="cod_n3" class="text-xs text-slate-500">Cód. N3</label>
                                <input type="text" id="cod_n3" name="cod_n3" maxlength="50"
                                    value="{{ $personal->cod_n3 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="n3" class="text-xs text-slate-500">N3</label>
                                <input type="text" id="n3" name="n3" maxlength="255"
                                    value="{{ $personal->n3 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- N4 --}}
                            <div class="space-y-1.5">
                                <label for="cod_n4" class="text-xs text-slate-500">Cód. N4</label>
                                <input type="text" id="cod_n4" name="cod_n4" maxlength="50"
                                    value="{{ $personal->cod_n4 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="area_n4" class="text-xs text-slate-500">Área N4</label>
                                <input type="text" id="area_n4" name="area_n4" maxlength="255"
                                    value="{{ $personal->area_n4 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            {{-- N5 --}}
                            <div class="space-y-1.5">
                                <label for="cod_n5" class="text-xs text-slate-500">Cód. N5</label>
                                <input type="text" id="cod_n5" name="cod_n5" maxlength="50"
                                    value="{{ $personal->cod_n5 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="n5" class="text-xs text-slate-500">N5</label>
                                <input type="text" id="n5" name="n5" maxlength="255"
                                    value="{{ $personal->n5 ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                        </div>
                    </div>

                    {{-- ── CARGO ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">badge</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Cargo</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="cargo" class="text-xs text-slate-500">Cargo</label>
                                <input type="text" id="cargo" name="cargo" maxlength="255"
                                    placeholder="Ej: Analista de TI"
                                    value="{{ $personal->cargo ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5">
                                <label for="cod_funcion" class="text-xs text-slate-500">Cód. función</label>
                                <input type="text" id="cod_funcion" name="cod_funcion" maxlength="50"
                                    value="{{ $personal->cod_funcion ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-3">
                                <label for="cat_ocup" class="text-xs text-slate-500">Categoría ocupacional</label>
                                <input type="text" id="cat_ocup" name="cat_ocup" maxlength="255"
                                    placeholder="Ej: Profesional"
                                    value="{{ $personal->cat_ocup ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                        </div>
                    </div>

                    {{-- ── COSTOS Y UBICACIÓN ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Costos y ubicación</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                            <div class="space-y-1.5">
                                <label for="ccosto" class="text-xs text-slate-500">Centro de costo</label>
                                <input type="text" id="ccosto" name="ccosto" maxlength="50"
                                    value="{{ $personal->ccosto ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-3">
                                <label for="desc_ccosto" class="text-xs text-slate-500">Descripción centro de costo</label>
                                <input type="text" id="desc_ccosto" name="desc_ccosto" maxlength="255"
                                    value="{{ $personal->desc_ccosto ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            <div class="space-y-1.5">
                                <label for="cod_sede" class="text-xs text-slate-500">Cód. sede</label>
                                <input type="text" id="cod_sede" name="cod_sede" maxlength="50"
                                    value="{{ $personal->cod_sede ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-3">
                                <label for="sede" class="text-xs text-slate-500">Sede</label>
                                <input type="text" id="sede" name="sede" maxlength="255"
                                    placeholder="Ej: Lima Norte"
                                    value="{{ $personal->sede ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                        </div>
                    </div>

                    {{-- ── JEFATURA DIRECTA ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">supervisor_account</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Jefatura directa</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            <div class="space-y-1.5">
                                <label for="posicion_jefe" class="text-xs text-slate-500">Posición del jefe</label>
                                <input type="text" id="posicion_jefe" name="posicion_jefe" maxlength="100"
                                    value="{{ $personal->posicion_jefe ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5">
                                <label for="cargo_jef" class="text-xs text-slate-500">Cargo del jefe</label>
                                <input type="text" id="cargo_jef" name="cargo_jef" maxlength="255"
                                    value="{{ $personal->cargo_jef ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5">
                                <label for="nom_jef" class="text-xs text-slate-500">Nombre del jefe</label>
                                <input type="text" id="nom_jef" name="nom_jef" maxlength="255"
                                    value="{{ $personal->nom_jef ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                        </div>
                    </div>

                    {{-- ── INFORMACIÓN RRHH ── --}}
                    <div class="bg-white rounded-lg overflow-hidden"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div class="px-6 py-3 flex items-center gap-2"
                            style="border-bottom: 1px solid #f1f5f9; background: #fafbfc;">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">groups</span>
                            <p class="text-xs font-black text-slate-600 uppercase tracking-wider">Información RRHH</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            <div class="space-y-1.5">
                                <label for="division_personal" class="text-xs text-slate-500">División personal</label>
                                <input type="text" id="division_personal" name="division_personal" maxlength="255"
                                    value="{{ $personal->division_personal ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="desc_division_personal" class="text-xs text-slate-500">Desc. división personal</label>
                                <input type="text" id="desc_division_personal" name="desc_division_personal" maxlength="255"
                                    value="{{ $personal->desc_division_personal ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-3">
                                <label for="desc_area_personal" class="text-xs text-slate-500">Desc. área personal</label>
                                <input type="text" id="desc_area_personal" name="desc_area_personal" maxlength="255"
                                    value="{{ $personal->desc_area_personal ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5">
                                <label for="regimen_laboral" class="text-xs text-slate-500">Régimen laboral</label>
                                <input type="text" id="regimen_laboral" name="regimen_laboral" maxlength="150"
                                    placeholder="Ej: Régimen privado"
                                    value="{{ $personal->regimen_laboral ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <div class="space-y-1.5 lg:col-span-2">
                                <label for="relacion_laboral" class="text-xs text-slate-500">Relación laboral</label>
                                <input type="text" id="relacion_laboral" name="relacion_laboral" maxlength="150"
                                    placeholder="Ej: Empleado"
                                    value="{{ $personal->relacion_laboral ?? '' }}"
                                    class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                    onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                    onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                        </div>
                    </div>

                    {{-- ── FOOTER ── --}}
                    <div class="bg-white rounded-lg px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3"
                        style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <p class="text-[11px] text-slate-400">
                            <span class="text-red-400">*</span> Campos obligatorios
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route($extend['controller'] . '.list') }}"
                                class="flex-1 sm:flex-none h-9 px-5 flex items-center justify-center gap-1.5 text-xs font-normal text-slate-600 rounded-lg transition-all bg-slate-200 hover:bg-slate-300 active:scale-95">
                                <span class="material-symbols-outlined text-[15px]">chevron_left</span>
                                Cancelar
                            </a>
                            <button type="submit" id="btnSubmit"
                                class="flex-1 sm:flex-none h-9 px-6 flex items-center justify-center gap-1.5 text-white text-xs font-normal rounded-lg transition-all duration-200 active:scale-95"
                                style="background: rgb(0,176,202); box-shadow: 0 2px 8px rgba(0,176,202,0.3);"
                                onmouseover="this.style.background='rgb(190,214,0)'; this.style.boxShadow='0 2px 8px rgba(190,214,0,0.3)'; this.style.color='white';"
                                onmouseout="this.style.background='rgb(0,176,202)'; this.style.boxShadow='0 2px 8px rgba(0,176,202,0.3)'; this.style.color='white';">
                                <span id="btnSubmitText">
                                    {{ isset($personal) ? 'Actualizar' : 'Guardar' }}
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
            const recordId   = "{{ $personal->codpersonal ?? '' }}";
        </script>
        <script src="{{ mix('js/commons/form.js') }}"></script>
        <script src="{{ mix('js/modules/' . $extend['controller'] . '/form.js') }}"></script>
    @endpush

@endsection