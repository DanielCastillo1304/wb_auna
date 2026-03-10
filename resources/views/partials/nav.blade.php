<nav class="navbar flex justify-between items-center bg-white h-[60px] px-4 md:px-8 sticky top-0 z-[30]"
     style="border-bottom: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

    {{-- ── IZQUIERDA: Toggle ── --}}
    <div class="flex items-center gap-3">
        <button class="menubar hidden md:flex items-center justify-center w-8 h-8 rounded-lg transition-all active:scale-95 text-slate-400 hover:text-slate-600 hover:bg-slate-100">
            <span class="material-symbols-outlined text-[22px]">menu_open</span>
        </button>
        <div class="h-5 w-px bg-slate-200 hidden md:block"></div>
    </div>

    {{-- ── DERECHA: Usuario ── --}}
    <div class="flex items-center gap-2">
        <div class="relative group">

            {{-- Trigger --}}
            <div class="box-user flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg cursor-pointer transition-all hover:bg-slate-50"
                 style="border: 1px solid transparent;"
                 onmouseover="this.style.borderColor='#e8edf2';"
                 onmouseout="this.style.borderColor='transparent';">

                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0 bg-slate-900 flex items-center justify-center"
                     style="border: 1.5px solid #e8edf2;">
                    <img src="{{ mix('img/person.jpg') }}"
                         class="w-full h-full object-cover"
                         alt="{{ $authUser->username}}">
                </div>

                {{-- Info --}}
                <div class="hidden md:block text-left">
                    <p class="text-[13px] font-bold text-slate-700 leading-none">
                        {{ $authUser->username}}
                    </p>
                    <p class="text-[10px] font-medium mt-0.5 leading-none" style="color: rgb(0,140,165);">
                        {{ $authUser->profile->name_large }}
                    </p>
                </div>

                {{-- Chevron --}}
                <span class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200 group-hover:rotate-180 hidden md:block">
                    expand_more
                </span>
            </div>

            {{-- Dropdown --}}
            <div class="box-user-collapse absolute top-full right-0 mt-1.5 w-[220px] hidden z-50 bg-white rounded-xl overflow-hidden"
                 style="border: 1px solid #e8edf2; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <div class="px-4 py-3 flex items-center gap-3" style="border-bottom: 1px solid #f1f5f9;">
                    <div class="w-9 h-9 rounded-lg overflow-hidden flex-shrink-0 bg-slate-900"
                         style="border: 1.5px solid #e8edf2;">
                        <img src="{{ mix('img/person.jpg') }}"
                             class="w-full h-full object-cover"
                             alt="{{ $authUser->username}}">
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-700 truncate leading-none">
                            {{ $authUser->username }}
                        </p>
                        <p class="text-[11px] mt-0.5 font-medium" style="color: rgb(0,140,165);">
                            Sesión activa
                        </p>
                    </div>
                </div>

                {{-- Opciones --}}
                <div class="p-1.5 space-y-0.5">
                    <button onclick="document.getElementById('logout-form').submit()"
                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-red-500 hover:bg-red-50 group/out">
                        <span class="material-symbols-outlined text-[18px] group-hover/out:translate-x-0.5 transition-transform">logout</span>
                        <span class="text-sm font-semibold">Cerrar sesión</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</nav>