<aside
    class="sidebar-panel fixed top-0 left-0 h-full w-[240px] z-50 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
    style="background: #0f1f35; border-right: 1px solid rgba(255,255,255,0.06);">

    <div class="sidebar-content h-full flex flex-col">

        {{-- BRAND --}}
        <div class="h-[60px] flex items-center px-5 flex-shrink-0"
             style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-1">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 bg-white">
                    <img src="{{ mix('img/icon.jpg') }}" class="w-4 h-4 object-contain" alt="Logo">
                </div>
                <div>
                    <p class="text-[13px] font-black text-white tracking-widest uppercase leading-none">AUNA</p>
                    <p class="text-[10px] mt-0.5 leading-none" style="color: rgba(0,176,202,0.6);">
                        Sistema Principal
                    </p>
                </div>
                <span class="ml-auto text-[9px] font-black px-1.5 py-0.5 rounded"
                      style="background: rgba(190,214,0,0.1); color: rgb(190,214,0); border: 1px solid rgba(190,214,0,0.15);">
                    v1.0
                </span>
            </a>
        </div>

        {{-- MENÚ --}}
        <div class="flex-1 overflow-y-auto py-3 custom-scrollbar">

            {{-- Label --}}
            {{-- <p class="px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em]"
               style="color: rgba(255,255,255,0.2);">INICIO</p> --}}

            {{-- Dashboard --}}
            @php $dashActive = request()->routeIs('home'); @endphp
            <a href="{{ route('home') }}"
                class="flex items-center gap-2.5 mx-2 px-3 py-2 rounded-lg transition-all duration-150 group"
                style="{{ $dashActive
                    ? 'background: rgba(0,176,202,0.12); color: rgb(0,176,202);'
                    : 'color: rgba(255,255,255,0.45);' }}"
                onmouseover="{{ !$dashActive ? "this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.8)';" : '' }}"
                onmouseout="{{ !$dashActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.45)';" : '' }}">
                <span class="material-symbols-outlined text-[18px] flex-shrink-0"
                      style="{{ $dashActive ? 'color: rgb(0,176,202)' : '' }}">
                    grid_view
                </span>
                <span class="text-[13px] font-medium">Dashboard</span>
                @if($dashActive)
                    <span class="ml-auto w-1 h-1 rounded-full"
                          style="background: rgb(0,176,202);"></span>
                @endif
            </a>

            @php
                $menu = [
                    [
                        'id'       => 'mantenimiento',
                        'label'    => 'Mantenimiento',
                        'icon'     => 'build',
                        'children' => [
                            [
                                'label'  => 'Tipos de equipo',
                                'route'  => 'equipment_type.list',
                                'routes' => ['equipment_type.*'],
                            ],
                            [
                                'label'  => 'Unidad de negocio',
                                'route'  => 'business_unit.list',
                                'routes' => ['business_unit.*'],
                            ],
                        ],
                    ],
                ];
            @endphp

            @foreach ($menu as $module)
                @php
                    $isActive = collect($module['children'])->contains(function ($child) {
                        $routes = $child['routes'] ?? [$child['route']];
                        return collect($routes)->contains(fn($r) => request()->routeIs($r));
                    });
                @endphp

                {{-- Separador label de sección --}}
                {{-- <p class="px-4 pt-4 pb-2 text-[10px] font-bold uppercase tracking-[0.2em]"
                   style="color: rgba(255,255,255,0.2);">
                    {{ $module['label'] }}
                </p> --}}

                <div class="mb-1">
                    {{-- Padre --}}
                    <a href="#mod-{{ $module['id'] }}"
                        class="toggle-section flex items-center justify-between mx-2 px-3 py-2 rounded-lg transition-all duration-150 {{ $isActive ? 'active-menu' : '' }}"
                        style="{{ $isActive
                            ? 'background: rgba(0,176,202,0.08); color: rgba(255,255,255,0.85);'
                            : 'color: rgba(255,255,255,0.4);' }}"
                        onmouseover="{{ !$isActive ? "this.style.background='rgba(255,255,255,0.05)'; this.style.color='rgba(255,255,255,0.8)';" : '' }}"
                        onmouseout="{{ !$isActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.4)';" : '' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] flex-shrink-0"
                                  style="{{ $isActive ? 'color: rgb(0,176,202)' : '' }}">
                                {{ $module['icon'] }}
                            </span>
                            <span class="text-[13px] font-medium">{{ $module['label'] }}</span>
                        </div>
                        <span class="material-symbols-outlined text-[16px] transition-transform duration-200 {{ $isActive ? '' : '-rotate-90' }}"
                              style="color: rgba(255,255,255,0.2);">
                            expand_more
                        </span>
                    </a>

                    {{-- Hijos --}}
                    <div id="mod-{{ $module['id'] }}"
                        class="content-section mt-0.5 overflow-hidden transition-all duration-200 {{ $isActive ? 'block' : 'hidden' }}">
                        @foreach ($module['children'] as $child)
                            @php
                                $childRoutes = $child['routes'] ?? [$child['route']];
                                $childActive = collect($childRoutes)->contains(fn($r) => request()->routeIs($r));
                            @endphp
                            <a href="{{ route($child['route']) }}"
                                class="flex items-center gap-2.5 ml-6 mr-2 px-3 py-1.5 rounded-lg transition-all duration-150 group"
                                style="{{ $childActive
                                    ? 'background: rgba(190,214,0,0.08); color: rgb(190,214,0);'
                                    : 'color: rgba(255,255,255,0.3);' }}"
                                onmouseover="{{ !$childActive ? "this.style.background='rgba(255,255,255,0.04)'; this.style.color='rgba(255,255,255,0.7)';" : '' }}"
                                onmouseout="{{ !$childActive ? "this.style.background=''; this.style.color='rgba(255,255,255,0.3)';" : '' }}">
                                <span class="w-1 h-1 rounded-full flex-shrink-0"
                                      style="{{ $childActive
                                          ? 'background: rgb(190,214,0);'
                                          : 'background: rgba(255,255,255,0.2);' }}">
                                </span>
                                <span class="text-[13px]">{{ $child['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FOOTER --}}
        <div class="flex-shrink-0 px-4 py-3"
             style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg overflow-hidden flex-shrink-0"
                     style="border: 1px solid rgba(255,255,255,0.1);">
                    <img src="{{ mix('img/person.jpg') }}" class="w-full h-full object-cover" alt="User">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-bold text-white truncate leading-none">
                        {{ Auth::user()->username }}
                    </p>
                    <p class="text-[10px] mt-0.5 leading-none" style="color: rgba(255,255,255,0.3);">
                        Usuario
                    </p>
                </div>
                <a href="{{ route('logout') }}"
                   class="w-7 h-7 flex items-center justify-center rounded-lg transition-all flex-shrink-0"
                   style="color: rgba(255,255,255,0.3);"
                   onmouseover="this.style.background='rgba(239,68,68,0.1)'; this.style.color='rgb(239,68,68)';"
                   onmouseout="this.style.background=''; this.style.color='rgba(255,255,255,0.3)';"
                   title="Cerrar sesión">
                    <span class="material-symbols-outlined text-[17px]">logout</span>
                </a>
            </div>
        </div>

    </div>
</aside>