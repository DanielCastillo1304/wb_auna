@extends('layouts.auth')

@section('title', 'Acceso Administrativo | AUNA')

@section('content')

<div class="w-full min-h-screen flex items-center justify-center p-4 sm:p-6"
     style="background: #f4f6f8;">

    <div class="w-full max-w-[400px]">

        {{-- LOGO --}}
        <div class="flex flex-col items-center mb-6">
            <img src="{{ mix('img/logo.svg') }}"
                 width="160"
                 class="drop-shadow-sm mb-3"
                 loading="lazy"
                 alt="Logo AUNA">
            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">
                Sistema Principal
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-xl overflow-hidden"
             style="border: 1px solid #e8edf2; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">

            {{-- Header card --}}
            <div class="px-7 pt-7 pb-5" style="border-bottom: 1px solid #f1f5f9;">
                <h1 class="text-lg font-black text-slate-800 leading-none">Iniciar sesión</h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">
                    Ingresa tus credenciales para continuar
                </p>
            </div>

            <div class="px-7 py-6">

                {{-- ERROR CREDENCIALES --}}
                <div class="credential-error-container mb-4">
                    <div class="credential-error hidden items-center gap-2 px-3.5 py-2.5 rounded-lg text-xs font-semibold"
                         style="background: rgba(220,50,50,0.05); border: 1px solid rgba(220,50,50,0.15); color: rgb(185,28,28);">
                        <span class="material-symbols-outlined text-[16px] flex-shrink-0">error</span>
                        <span class="credential-error-text"></span>
                    </div>
                </div>

                {{-- FORM --}}
                <form class="space-y-4 form">
                    @csrf

                    {{-- Usuario --}}
                    <div>
                        <label for="username"
                               class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Usuario
                        </label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            placeholder="Ingresa tu usuario"
                            autocomplete="username"
                            class="block w-full h-10 px-3.5 text-sm text-slate-700 rounded-lg outline-none transition-all duration-200 placeholder:text-slate-300"
                            style="background: #f8fafc; border: 1px solid #e2e8f0;"
                            onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                            onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <span class="alert-error hidden text-[11px] font-medium text-red-500 mt-1 block"
                              id="alert-username"></span>
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password"
                               class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                class="block w-full h-10 px-3.5 pr-10 text-sm text-slate-700 rounded-lg outline-none transition-all duration-200 placeholder:text-slate-300"
                                style="background: #f8fafc; border: 1px solid #e2e8f0;"
                                onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 3px rgba(0,176,202,0.08)';"
                                onblur="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">

                            <button type="button" id="togglePassword"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center rounded transition-colors"
                                    style="color: #94a3b8;"
                                    onmouseover="this.style.color='rgb(0,176,202)';"
                                    onmouseout="this.style.color='#94a3b8';">
                                <svg xmlns="http://www.w3.org/2000/svg" id="eyeOpen" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" id="eyeClosed" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        <span class="alert-error hidden text-[11px] font-medium text-red-500 mt-1 block"
                              id="alert-password"></span>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                            class="submit-login w-full h-10 text-white text-xs font-black uppercase tracking-widest rounded-lg transition-all duration-200 active:scale-[0.98] mt-2"
                            style="background: rgb(0,176,202); box-shadow: 0 2px 8px rgba(0,176,202,0.3);"
                            onmouseover="this.style.background='rgb(190,214,0)'; this.style.color='rgb(50,60,0)'; this.style.boxShadow='0 2px 8px rgba(190,214,0,0.3)';"
                            onmouseout="this.style.background='rgb(0,176,202)'; this.style.color='white'; this.style.boxShadow='0 2px 8px rgba(0,176,202,0.3)';">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-[10px] font-medium text-slate-400 mt-5 uppercase tracking-widest">
            AUNA © {{ date('Y') }}
        </p>
    </div>
</div>

@push('scripts')
    <script>
        const controller = "{{ $extend['controller'] }}";
    </script>
    <script src="{{ mix('js/modules/'.$extend['controller'] .'/login.js') }}"></script>
@endpush

@endsection