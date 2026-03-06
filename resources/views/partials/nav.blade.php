<nav class="navbar flex justify-between items-center bg-white h-[70px] px-4 md:px-8 sticky top-0 z-[30]"
     style="border-bottom: 1px solid rgba(0,176,202,0.12); box-shadow: 0 2px 12px rgba(0,176,202,0.06);">

    {{-- IZQUIERDA --}}
    <div class="flex items-center gap-4">
        <button class="menubar hidden md:block p-2 rounded-xl transition-all active:scale-95"
                style="color: rgba(0,140,165,0.6);"
                onmouseover="this.style.background='rgba(0,176,202,0.08)'; this.style.color='rgb(0,140,165)';"
                onmouseout="this.style.background=''; this.style.color='rgba(0,140,165,0.6)';">
            <span class="material-symbols-outlined text-[26px]">menu_open</span>
        </button>
        <div class="h-7 w-px hidden md:block" style="background: rgba(0,176,202,0.15);"></div>
    </div>

    {{-- DERECHA — Usuario --}}
    <div class="relative group">

        {{-- Trigger --}}
        <div class="box-user flex items-center gap-3 pl-3 py-1.5 pr-2 rounded-xl cursor-pointer transition-all"
             onmouseover="this.style.background='rgba(0,176,202,0.06)';"
             onmouseout="this.style.background='';">

            {{-- Info usuario --}}
            <div class="flex-col items-end hidden md:flex">
                <span class="text-sm font-black text-slate-800 leading-none">
                    {{ Auth::user()->username }}
                </span>
                <span class="text-[10px] font-black uppercase tracking-wider mt-0.5 px-1.5 py-0.5 rounded-md"
                      style="background: rgba(0,176,202,0.08); color: rgb(0,140,165);">
                    Usuario
                </span>
            </div>

            {{-- Avatar --}}
            <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 transition-transform group-hover:scale-105"
                 style="border: 2px solid rgba(0,176,202,0.2); box-shadow: 0 2px 8px rgba(0,176,202,0.15);">
                <img src="{{ mix('img/person.jpg') }}"
                     class="w-full h-full object-cover"
                     alt="Foto de usuario">
            </div>

            {{-- Chevron --}}
            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:rotate-180 duration-200"
                  style="color: rgba(0,176,202,0.5);">
                expand_more
            </span>
        </div>

        {{-- Dropdown --}}
        <div class="box-user-collapse absolute top-full right-0 mt-2 w-[240px] hidden z-50 bg-white rounded-2xl overflow-hidden"
             style="border: 1px solid rgba(0,176,202,0.15); box-shadow: 0 8px 30px rgba(0,20,40,0.1);">

            {{-- Header dropdown --}}
            <div class="p-4 border-b" style="background: rgba(0,176,202,0.04); border-color: rgba(0,176,202,0.1);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0"
                         style="border: 2px solid rgba(0,176,202,0.2);">
                        <img src="{{ mix('img/person.jpg') }}"
                             class="w-full h-full object-cover"
                             alt="Foto de usuario">
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-700 truncate">
                            {{ Auth::user()->username }}
                        </p>
                        <p class="text-[10px] font-bold uppercase tracking-wider mt-0.5"
                           style="color: rgba(0,140,165,0.7);">
                            Sesión activa
                        </p>
                    </div>
                </div>
            </div>

            {{-- Opciones --}}
            <div class="p-2 space-y-1">

                {{-- <div class="h-px mx-1" style="background: rgba(0,176,202,0.1);"></div> --}}

                <a href="{{ route('logout') }}"
                    class="flex items-center gap-3 p-3 rounded-xl transition-all group/out text-red-500"
                    onmouseover="this.style.background='rgba(255,80,80,0.06)';"
                    onmouseout="this.style.background='';">
                    <span class="material-symbols-outlined text-[20px] group-hover/out:translate-x-0.5 transition-transform">
                        logout
                    </span>
                    <span class="text-sm font-black uppercase tracking-tight">Cerrar sesión</span>
                </a>
            </div>
        </div>
    </div>
</nav>