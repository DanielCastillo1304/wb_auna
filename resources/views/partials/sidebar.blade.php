{{-- ============================================================
    SIDEBAR LATERAL (lg en adelante)
    ============================================================ --}}
<aside class="sidebar-panel fixed top-0 left-0 h-full w-[240px] z-50 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
    style="background: #0f1f35; border-right: 1px solid rgba(255,255,255,0.06);">

    <div class="sidebar-content h-full flex flex-col">

        {{-- ── BRAND ── --}}
        <div class="h-[60px] flex items-center px-5 flex-shrink-0"
            style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-1">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 bg-white">
                    <img src="{{ mix('img/icon.jpg') }}" class="w-4 h-4 object-contain" alt="Sistema de Control de Asistencia">
                </div>
                <div>
                    <p class="text-[13px] font-black text-white tracking-widest uppercase leading-none">ASSISTANCE</p>
                    <p class="text-[10px] mt-0.5 leading-none" style="color: rgba(0,176,202,0.6);">
                        Control de Asistencia
                    </p>
                </div>
                <span class="ml-auto text-[9px] font-black px-1.5 py-0.5 rounded"
                    style="background: rgba(190,214,0,0.1); color: rgb(190,214,0); border: 1px solid rgba(190,214,0,0.15);">
                    v1.0
                </span>
            </a>
        </div>

        {{-- ── MENÚ ── --}}
        <div class="flex-1 overflow-y-auto py-3 custom-scrollbar">

            {{-- Dashboard --}}
            @php $dashActive = ($extend['controller'] ?? '') == 'home'; @endphp
            <a href="{{ route('home') }}"
                class="flex items-center gap-2.5 mx-2 px-3 py-2 rounded-lg transition-all duration-150 group"
                style="{{ $dashActive ? 'background: rgba(0,176,202,0.12); color: rgb(0,176,202);' : 'color: rgba(255,255,255,0.45);' }}"
                onmouseover="{{ !$dashActive ? "this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.8)';" : '' }}"
                onmouseout="{{ !$dashActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.45)';" : '' }}">
                <span class="material-symbols-outlined text-[18px] flex-shrink-0"
                    style="{{ $dashActive ? 'color: rgb(0,176,202)' : '' }}">
                    grid_view
                </span>
                <span class="text-[13px] font-medium">Dashboard</span>
                @if ($dashActive)
                    <span class="ml-auto w-1 h-1 rounded-full" style="background: rgb(0,176,202);"></span>
                @endif
            </a>

            {{-- Label sección módulos --}}
            <p class="px-4 pt-4 pb-1 text-[9px] font-black uppercase tracking-[0.3em]"
               style="color: rgba(255,255,255,0.18);">Módulos</p>

            {{-- Módulos dinámicos --}}
            @php
                $parentModules = collect($authPermission ?? [])->filter(function ($profilePermission) {
                    return $profilePermission &&
                        $profilePermission->permission &&
                        $profilePermission->permission->module &&
                        $profilePermission->permission->module->codmodule_parent == null;
                });
            @endphp

            @foreach ($parentModules as $profilePermission)
                @php
                    $parentModule = $profilePermission->permission->module;
                    $authorizedChildren = $parentModule->children ?? collect();
                    $hasActiveChild = $authorizedChildren->contains(
                        fn($child) => request()->routeIs($child->route),
                    );
                @endphp

                @if ($authorizedChildren->count() > 0)
                    <div class="mb-0.5">
                        {{-- Padre --}}
                        <a href="#mod{{ $parentModule->codmodule }}"
                            class="toggle-section flex items-center justify-between mx-2 px-3 py-2 rounded-lg transition-all duration-150 {{ $hasActiveChild ? 'active-menu' : '' }}"
                            style="{{ $hasActiveChild
                                ? 'background: rgba(0,176,202,0.08); color: rgba(255,255,255,0.85);'
                                : 'color: rgba(255,255,255,0.4);' }}"
                            onmouseover="{{ !$hasActiveChild ? "this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.8)';" : '' }}"
                            onmouseout="{{ !$hasActiveChild ? "this.style.background=''; this.style.color='rgba(255,255,255,0.4)';" : '' }}">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] flex-shrink-0"
                                    style="{{ $hasActiveChild ? 'color: rgb(0,176,202)' : '' }}">
                                    {{ $parentModule->icon }}
                                </span>
                                <span class="text-[13px] font-medium">
                                    {{ $parentModule->name_large ?? $parentModule->name_short }}
                                </span>
                            </div>
                            <span class="material-symbols-outlined text-[16px] transition-transform duration-200 {{ $hasActiveChild ? '' : '-rotate-90' }}"
                                style="color: rgba(255,255,255,0.2);">
                                expand_more
                            </span>
                        </a>

                        {{-- Hijos --}}
                        <div id="mod{{ $parentModule->codmodule }}"
                            class="content-section mt-0.5 overflow-hidden transition-all duration-200 {{ $hasActiveChild ? 'block' : 'hidden' }}">
                            @foreach ($authorizedChildren as $child)
                                @php $childActive = request()->routeIs($child->route); @endphp
                                <a href="{{ route($child->route) }}"
                                    class="flex items-center gap-2.5 ml-6 mr-2 px-3 py-1.5 rounded-lg transition-all duration-150"
                                    style="{{ $childActive ? 'background: rgba(190,214,0,0.08); color: rgb(190,214,0);' : 'color: rgba(255,255,255,0.3);' }}"
                                    onmouseover="{{ !$childActive ? "this.style.background='rgba(255,255,255,0.04)'; this.style.color='rgba(255,255,255,0.7)';" : '' }}"
                                    onmouseout="{{ !$childActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.3)';" : '' }}">
                                    <span class="w-1 h-1 rounded-full flex-shrink-0"
                                        style="{{ $childActive ? 'background: rgb(190,214,0);' : 'background: rgba(255,255,255,0.2);' }}">
                                    </span>
                                    <span class="text-[13px]">
                                        {{ $child->name_large ?? $child->name_short }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

        </div>

        {{-- ── FOOTER ── --}}
        <div class="flex-shrink-0 px-4 py-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background: rgba(0,176,202,0.15); border: 1px solid rgba(0,176,202,0.2);">
                    <span class="material-symbols-outlined text-[16px]" style="color: rgb(0,176,202);">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-bold text-white truncate leading-none">
                        {{ $authUser->person->firstname ?? 'Usuario' }}
                    </p>
                    <p class="text-[10px] mt-0.5 leading-none" style="color: rgba(255,255,255,0.3);">
                        {{ $authUser->username ?? '' }}
                    </p>
                </div>
                <button onclick="document.getElementById('logout-form').submit()"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-all flex-shrink-0"
                    style="color: rgba(255,255,255,0.3);"
                    onmouseover="this.style.background='rgba(239,68,68,0.1)'; this.style.color='rgb(239,68,68)';"
                    onmouseout="this.style.background=''; this.style.color='rgba(255,255,255,0.3)';"
                    title="Cerrar sesión">
                    <span class="material-symbols-outlined text-[17px]">logout</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="GET" class="hidden">@csrf</form>
            </div>
        </div>

    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,176,202,0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(0,176,202,0.4); }

    .toggle-section .material-symbols-outlined:last-child {
        transition: transform 0.2s cubic-bezier(.4,0,.2,1);
    }
    .toggle-section.active-menu .material-symbols-outlined:last-child {
        transform: rotate(0deg) !important;
    }
</style>


{{-- ============================================================
    NAVEGACIÓN INFERIOR MOBILE
    ============================================================ --}}
<div class="mobile-bottom-nav md:hidden">
    <div class="fixed bottom-0 left-0 right-0 backdrop-blur-md border-t z-40"
        style="background: rgba(15,31,53,0.95); border-color: rgba(255,255,255,0.07); box-shadow: 0 -4px 24px rgba(0,0,0,0.4);">
        <div class="flex items-center justify-around h-16 px-6">

            {{-- Perfil --}}
            <button onclick="toggleUserMenu()"
                class="flex flex-col items-center justify-center transition-colors"
                style="color: rgba(255,255,255,0.4);"
                onmouseover="this.style.color='rgba(255,255,255,0.8)'"
                onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                <div class="w-10 h-10 flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined text-[24px]">account_circle</span>
                </div>
            </button>

            {{-- Home --}}
            <a href="{{ route('home') }}"
                class="flex flex-col items-center justify-center relative transition-colors"
                style="{{ request()->routeIs('home') ? 'color: white;' : 'color: rgba(255,255,255,0.4);' }}">
                <div class="absolute -top-5 w-12 h-12 flex items-center justify-center rounded-full transition-all duration-200"
                    style="background: rgb(0,130,160); box-shadow: 0 4px 20px rgba(0,176,202,0.4);">
                    <span class="material-symbols-outlined text-white text-[22px]">home</span>
                </div>
            </a>

            {{-- Módulos --}}
            <button onclick="toggleModulesMenu()"
                class="flex flex-col items-center justify-center transition-colors"
                style="color: rgba(255,255,255,0.4);"
                onmouseover="this.style.color='rgba(255,255,255,0.8)'"
                onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                <div class="w-10 h-10 flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined text-[24px]">apps</span>
                </div>
            </button>

        </div>
    </div>
</div>


{{-- ============================================================
    MODAL MÓDULOS
    ============================================================ --}}
<div id="modulesModal" class="fixed inset-0 backdrop-blur-sm z-50 hidden" style="background: rgba(0,0,0,0.6);" onclick="toggleModulesMenu()">
    <div class="absolute bottom-0 left-0 right-0 rounded-t-2xl max-h-[85vh] flex flex-col"
         style="background: #0f1f35; border-top: 1px solid rgba(255,255,255,0.07); box-shadow: 0 -8px 40px rgba(0,0,0,0.5); transform: translateY(100%); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);"
         onclick="event.stopPropagation()">

        <div class="flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 rounded-full" style="background: rgba(255,255,255,0.1);"></div>
        </div>

        <div class="flex items-center justify-between px-5 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.07);">
            <h2 class="text-[14px] font-black text-white tracking-tight uppercase">Módulos</h2>
            <button onclick="toggleModulesMenu()" class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors"
                style="background: rgba(255,255,255,0.05);"
                onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                <span class="material-symbols-outlined text-[18px]" style="color: rgba(255,255,255,0.5);">close</span>
            </button>
        </div>

        <div class="overflow-y-auto flex-1 p-4 space-y-2 custom-scrollbar">
            @php
                $parentModules = collect($authPermission ?? [])->filter(function ($profilePermission) {
                    return $profilePermission &&
                        $profilePermission->permission &&
                        $profilePermission->permission->module &&
                        $profilePermission->permission->module->codmodule_parent == null;
                });
            @endphp

            @if ($parentModules->count() > 0)
                @foreach ($parentModules as $profilePermission)
                    @php
                        $parentModule = $profilePermission->permission->module;
                        $authorizedChildren = $parentModule->children ?? collect();
                        $hasActiveChild = $authorizedChildren->contains(
                            fn($child) => request()->routeIs($child->route),
                        );
                    @endphp

                    @if ($authorizedChildren->count() > 0)
                        <div class="rounded-xl overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.02);">
                            <button onclick="toggleModule({{ $loop->index }})"
                                class="w-full flex items-center justify-between px-4 py-3 transition-colors"
                                onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                                onmouseout="this.style.background=''">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                                        style="background: rgba(0,176,202,0.12); border: 1px solid rgba(0,176,202,0.2);">
                                        <span class="material-symbols-outlined text-[18px]" style="color: rgb(0,176,202);">{{ $parentModule->icon }}</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[13px] font-semibold text-white">
                                            {{ $parentModule->name_large ?? $parentModule->name_short }}
                                        </p>
                                        <p class="text-[11px]" style="color: rgba(255,255,255,0.3);">
                                            {{ $authorizedChildren->count() }} submódulos
                                        </p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-[16px] transition-transform duration-200 module-arrow-{{ $loop->index }}"
                                    style="color: rgba(255,255,255,0.2);">
                                    expand_more
                                </span>
                            </button>

                            <div id="moduleContent{{ $loop->index }}"
                                class="module-children hidden"
                                style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; border-top: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.15);">
                                <div class="p-3 space-y-0.5">
                                    @foreach ($authorizedChildren as $child)
                                        @php $childActive = request()->routeIs($child->route); @endphp
                                        <a href="{{ route($child->route) }}"
                                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all"
                                            style="{{ $childActive ? 'background: rgba(190,214,0,0.08); color: rgb(190,214,0);' : 'color: rgba(255,255,255,0.45);' }}"
                                            onmouseover="{{ !$childActive ? "this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.8)';" : '' }}"
                                            onmouseout="{{ !$childActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.45)';" : '' }}"
                                            onclick="toggleModulesMenu()">
                                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                style="{{ $childActive ? 'background: rgb(190,214,0);' : 'background: rgba(255,255,255,0.2);' }}">
                                            </span>
                                            <div class="flex-1">
                                                <p class="text-[13px] font-medium">{{ $child->name_large ?? $child->name_short }}</p>
                                                @if ($child->description)
                                                    <p class="text-[11px] mt-0.5" style="color: rgba(255,255,255,0.25);">{{ $child->description }}</p>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-[48px] block mb-3" style="color: rgba(255,255,255,0.1);">apps</span>
                    <p class="text-[13px]" style="color: rgba(255,255,255,0.25);">No hay módulos disponibles</p>
                </div>
            @endif
        </div>
    </div>
</div>


{{-- ============================================================
    MODAL USUARIO
    ============================================================ --}}
<div id="userModal" class="fixed inset-0 backdrop-blur-sm z-50 hidden" style="background: rgba(0,0,0,0.6);" onclick="toggleUserMenu()">
    <div class="absolute bottom-0 left-0 right-0 rounded-t-2xl"
         style="background: #0f1f35; border-top: 1px solid rgba(255,255,255,0.07); box-shadow: 0 -8px 40px rgba(0,0,0,0.5); transform: translateY(100%); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);"
         onclick="event.stopPropagation()">

        <div class="flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 rounded-full" style="background: rgba(255,255,255,0.1);"></div>
        </div>

        <div class="flex items-center gap-4 px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.07);">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: rgba(0,176,202,0.12); border: 1px solid rgba(0,176,202,0.2);">
                <span class="material-symbols-outlined text-[26px]" style="color: rgb(0,176,202);">person</span>
            </div>
            <div>
                <p class="text-[14px] font-bold text-white">{{ $authUser->person->firstname ?? 'Usuario' }}</p>
                <p class="text-[12px]" style="color: rgba(255,255,255,0.35);">{{ $authUser->username ?? 'sin@correo.com' }}</p>
            </div>
        </div>

        <div class="p-4 space-y-1">
            <a href="{{ route('user.password') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
               style="color: rgba(255,255,255,0.5);"
               onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.85)';"
               onmouseout="this.style.background=''; this.style.color='rgba(255,255,255,0.5)';">
                <span class="material-symbols-outlined text-[19px]">lock</span>
                <span class="text-[13px] font-medium">Cambiar contraseña</span>
            </a>
            <button onclick="document.getElementById('logout-form').submit()"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                    style="color: rgba(239,68,68,0.6);"
                    onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.color='rgb(239,68,68)';"
                    onmouseout="this.style.background=''; this.style.color='rgba(239,68,68,0.6)';">
                <span class="material-symbols-outlined text-[19px]">logout</span>
                <span class="text-[13px] font-medium">Cerrar Sesión</span>
            </button>
        </div>

        <div class="h-4"></div>

        <form id="logout-form" action="{{ route('logout') }}" method="GET" class="hidden">
            @csrf
        </form>
    </div>
</div>


<script>
    function toggleModulesMenu() {
        const modal = document.getElementById('modulesModal');
        const content = modal.querySelector('.rounded-t-2xl');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => content.style.transform = 'translateY(0)', 10);
        } else {
            content.style.transform = 'translateY(100%)';
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    function toggleUserMenu() {
        const modal = document.getElementById('userModal');
        const content = modal.querySelector('.rounded-t-2xl');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => content.style.transform = 'translateY(0)', 10);
        } else {
            content.style.transform = 'translateY(100%)';
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    function toggleModule(index) {
        const content = document.getElementById('moduleContent' + index);
        const arrow = document.querySelector('.module-arrow-' + index);
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.style.maxHeight = content.scrollHeight + 'px';
            arrow.style.transform = 'rotate(180deg)';
        } else {
            content.style.maxHeight = '0';
            arrow.style.transform = 'rotate(0deg)';
            setTimeout(() => content.classList.add('hidden'), 300);
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('modulesModal').classList.contains('hidden')) toggleModulesMenu();
            if (!document.getElementById('userModal').classList.contains('hidden')) toggleUserMenu();
        }
    });
</script>