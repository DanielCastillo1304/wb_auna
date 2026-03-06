@extends('layouts.auth')

@section('title', 'Acceso Administrativo | AUNA')

@section('content')

<div class="w-full min-h-screen flex items-center justify-center p-4 sm:p-6"
     style="background: linear-gradient(135deg, #f0f9fb 0%, #e8f7fa 50%, #f5fdf5 100%);">

    {{-- Glow decorativo fondo --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(0,176,202,0.06) 0%, transparent 70%); transform: translate(20%, -20%);"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(190,214,0,0.05) 0%, transparent 70%); transform: translate(-20%, 20%);"></div>

    <div class="w-full max-w-[420px] relative">

        {{-- CARD --}}
        <div class="bg-white rounded-3xl overflow-hidden"
             style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 20px 60px rgba(0,140,165,0.12), 0 4px 20px rgba(0,176,202,0.08);">

            {{-- Barra superior --}}
            <div class="h-1.5 w-full"
                 style="background: linear-gradient(90deg, rgb(0,176,202) 0%, rgb(190,214,0) 100%);"></div>

            <div class="p-8 sm:p-10">

                {{-- LOGO + TÍTULO --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-4 p-2"
                         style="background: linear-gradient(135deg, rgba(0,176,202,0.08) 0%, rgba(190,214,0,0.06) 100%); border: 1px solid rgba(0,176,202,0.15);">
                        <img src="{{ mix('img/logo.svg') }}"
                             width="200"
                             class="drop-shadow-sm"
                             loading="lazy"
                             alt="Logo AUNA">
                    </div>
                    <div class="flex items-center gap-3 w-full mt-5">
                        <div class="h-px flex-1" style="background: rgba(0,176,202,0.15);"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] whitespace-nowrap"
                           style="color: rgba(0,140,165,0.6);">
                            Acceso Administrativo
                        </p>
                        <div class="h-px flex-1" style="background: rgba(0,176,202,0.15);"></div>
                    </div>
                </div>

                {{-- ERROR CREDENCIALES --}}
                <div class="credential-error-container mb-4">
                    <div class="credential-error hidden items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold"
                         style="background: rgba(220,50,50,0.06); border: 1px solid rgba(220,50,50,0.15); color: rgb(200,40,40);">
                    </div>
                </div>

                {{-- FORM --}}
                <form class="space-y-5 form">
                    @csrf

                    {{-- username --}}
                    <div>
                        <label for="username"
                               class="block text-[10px] font-black uppercase tracking-widest mb-1.5"
                               style="color: rgb(0,140,165);">
                            Nombre de usuario
                        </label>
                        <input
                            type="username"
                            name="username"
                            id="username"
                            placeholder="luchinbot"
                            class="block w-full px-4 py-3.5 text-sm rounded-xl outline-none transition-all duration-200 placeholder:text-slate-300"
                            style="background: rgba(0,176,202,0.04); border: 1px solid rgba(0,176,202,0.2); color: #1e293b;"
                            onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 4px rgba(0,176,202,0.06)';"
                            onblur="this.style.background='rgba(0,176,202,0.04)'; this.style.borderColor='rgba(0,176,202,0.2)'; this.style.boxShadow='none';">
                        <span class="alert-error text-[10px] font-bold mt-1.5 ml-1 block"
                              style="color: rgb(200,40,40);"
                              id="alert-username"></span>
                    </div>

                    {{-- Password --}}
                    @php
                        $secretary   = $params['secretary_number'] ?? null;
                        $phone       = $secretary->value       ?? '930227604';
                        $message     = $secretary->description ?? 'Hola, necesito ayuda para recuperar mi contraseña.';
                        $whatsappUrl = 'https://wa.me/51' . $phone . '?text=' . urlencode($message);
                    @endphp

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password"
                                   class="text-[10px] font-black uppercase tracking-widest"
                                   style="color: rgb(0,140,165);">
                                Contraseña
                            </label>
                            <a href="{{ $whatsappUrl }}"
                               target="_blank"
                               class="text-[10px] font-bold uppercase tracking-tight transition-colors"
                               style="color: rgba(0,176,202,0.6);"
                               onmouseover="this.style.color='rgb(0,176,202)';"
                               onmouseout="this.style.color='rgba(0,176,202,0.6)';">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                class="block w-full px-4 pr-12 py-3.5 text-sm rounded-xl outline-none transition-all duration-200 placeholder:text-slate-300"
                                style="background: rgba(0,176,202,0.04); border: 1px solid rgba(0,176,202,0.2); color: #1e293b;"
                                onfocus="this.style.background='white'; this.style.borderColor='rgba(0,176,202,0.5)'; this.style.boxShadow='0 0 0 4px rgba(0,176,202,0.06)';"
                                onblur="this.style.background='rgba(0,176,202,0.04)'; this.style.borderColor='rgba(0,176,202,0.2)'; this.style.boxShadow='none';">

                            <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-2 transition-colors"
                                    style="color: rgba(0,176,202,0.5);"
                                    onmouseover="this.style.color='rgb(0,176,202)';"
                                    onmouseout="this.style.color='rgba(0,176,202,0.5)';">
                                <svg xmlns="http://www.w3.org/2000/svg" id="eyeOpen" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" id="eyeClosed" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        <span class="alert-error text-[10px] font-bold mt-1.5 ml-1 block"
                              style="color: rgb(200,40,40);"
                              id="alert-password"></span>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                            class="submit-login w-full text-white font-black uppercase tracking-[0.15em] py-4 rounded-xl transition-all duration-300 active:scale-[0.98] mt-2"
                            style="background: linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%); box-shadow: 0 4px 20px rgba(0,176,202,0.3);"
                            onmouseover="this.style.background='linear-gradient(135deg, rgb(190,214,0) 0%, rgb(160,185,0) 100%)'; this.style.boxShadow='0 4px 20px rgba(190,214,0,0.3)';"
                            onmouseout="this.style.background='linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%)'; this.style.boxShadow='0 4px 20px rgba(0,176,202,0.3)';">
                        Iniciar Sesión
                    </button>
                </form>

                {{-- VOLVER --}}
                <div class="mt-8 flex justify-center">
                    <a href="{{ url()->previous() }}"
                       class="group flex items-center gap-2 px-4 py-2 rounded-full transition-all"
                       onmouseover="this.style.background='rgba(0,176,202,0.06)';"
                       onmouseout="this.style.background='';">
                        <div class="p-1.5 rounded-full transition-all"
                             style="background: rgba(0,176,202,0.08);"
                             onmouseover="this.style.background='rgb(0,176,202)'; this.querySelector('svg').style.color='white';"
                             onmouseout="this.style.background='rgba(0,176,202,0.08)'; this.querySelector('svg').style.color='';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="3" stroke="currentColor" class="w-3 h-3"
                                 style="color: rgb(0,140,165);">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest transition-colors"
                              style="color: rgba(0,140,165,0.6);"
                              onmouseover="this.style.color='rgb(0,140,165)';"
                              onmouseout="this.style.color='rgba(0,140,165,0.6)';">
                            Volver
                        </span>
                    </a>
                </div>
            </div>

            {{-- Barra inferior --}}
            <div class="h-1 w-full"
                 style="background: linear-gradient(90deg, rgb(190,214,0) 0%, rgb(0,176,202) 100%);"></div>
        </div>

        {{-- Créditos --}}
        <p class="text-center text-[10px] font-bold uppercase tracking-widest mt-5"
           style="color: rgba(0,140,165,0.5);">
            AUNA © {{ date('Y') }} · Sistema Principal
        </p>
    </div>
</div>

@push('scripts')
    <script>
        const controller = "{{ $extend['controller'] }}";
    </script>
    <script src="{{ mix('js/modules/'. $extend['controller'] .'/login.js') }}"></script>
@endpush

@endsection