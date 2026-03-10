@extends('layouts.app')

@section('title', (env('APP_NAME') ?? 'AUNA') . ' - Subir personal')

@section('content')
    <div class="">

        {{-- TOPBAR DEL FORM --}}
        <div class="bg-white px-6 lg:px-10 py-4 flex items-center gap-4" style="border-bottom: 1px solid #e8edf2;">

            {{-- Volver --}}
            <a href="{{ route('home') }}"
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
                <a href=""
                    class="font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    Subir personal
                </a>
                <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                <span class="font-semibold text-slate-700">
                   Nueva subida
                </span>
            </div>

            {{-- Badge estado --}}
            <span class="ml-1 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                style="background: rgba(245,158,11,0.1); color: rgb(217,119,6); border: 1px solid rgba(245,158,11,0.2);">
                Subiendo
            </span>
        </div>

        {{-- ALERT CONTAINER --}}
        <div id="alertContainer" class="fixed top-16 right-5 z-[100] w-full max-w-sm pointer-events-none"></div>

        {{-- CONTENIDO --}}
        <div class="px-6 lg:px-10 py-6 max-w-3xl">

            <form action="/import-personal" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- CARD --}}
                <div class="bg-white rounded-lg overflow-hidden"
                    style="border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
                    {{-- CAMPOS --}}
                    <div class="p-6 space-y-5">

                        <div class="space-y-1.5">
                            <label for="name" class="flex items-center gap-1 text-xs text-slate-500">
                                Excel de personal
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="file" id="file" name="file" required
                                class="w-full h-10 px-3.5 text-sm text-slate-700 rounded-md outline-none transition-all duration-200 placeholder:text-slate-300"
                                style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
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
                            <a href="{{ route('home') }}"
                                class="flex-1 sm:flex-none h-9 px-5 flex items-center justify-center gap-1.5 text-xs font-normal text-slate-600 rounded-lg transition-all bg-slate-200 hover:bg-slate-300 active:scale-95">
                                <span class="material-symbols-outlined text-[15px]">chevron_left</span>
                                Cancelar
                            </a>

                            {{-- Guardar --}}
                            <button type="submit"
                                class="flex-1 sm:flex-none h-9 px-6 flex items-center justify-center gap-1.5 text-white text-xs font-normal rounded-lg transition-all duration-200 active:scale-95 group"
                                style="background: rgb(0,176,202); box-shadow: 0 2px 8px rgba(0,176,202,0.3);"
                                onmouseover="this.style.background='rgb(190,214,0)'; this.style.boxShadow='0 2px 8px rgba(190,214,0,0.3)'; this.style.color='white';"
                                onmouseout="this.style.background='rgb(0,176,202)'; this.style.boxShadow='0 2px 8px rgba(0,176,202,0.3)'; this.style.color='white';">
                                <span id="btnSubmitText">
                                    Subir
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
