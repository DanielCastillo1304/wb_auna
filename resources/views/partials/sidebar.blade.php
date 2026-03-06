<aside
    class="sidebar-panel fixed top-0 left-0 h-full w-[260px] z-50 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl"
    style="background: linear-gradient(180deg, #0a1628 0%, #0d1f35 50%, #0a1628 100%);">

    <div class="sidebar-content h-full flex flex-col">

        {{-- HEADER / BRAND --}}
        <div class="h-[80px] flex items-center px-6 border-b"
            style="border-color: rgba(0, 176, 202, 0.1); background: rgba(0, 176, 202, 0.03);">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-white">
                    <img src="{{ mix('img/icon.jpg') }}" class="w-5 h-5 object-contain" alt="Logo">
                </div>
                <div class="leading-tight flex-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-black text-white tracking-widest uppercase m-0">AUNA</p>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold"
                            style="background: rgba(190,214,0,0.1); border: 1px solid rgba(190,214,0,0.2); color: rgb(190,214,0);">
                            v1.0
                        </span>
                    </div>
                    <p class="text-[9px] font-bold uppercase tracking-[0.2em] opacity-70 mt-0.5"
                        style="color: rgb(0,176,202);">
                        Sistema Principal
                    </p>
                </div>
            </a>
        </div>

        {{-- MENÚ --}}
        <div class="flex-1 overflow-y-auto px-3 py-5 custom-scrollbar space-y-1">

            {{-- Label: Principal --}}
            <p class="px-3 pb-2 text-[10px] font-black uppercase tracking-[0.3em]" style="color: rgba(0,176,202,0.35);">
                Principal</p>

            {{-- Dashboard --}}
            <a href="{{ route('home') }}"
                class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200"
                style="{{ request()->routeIs('home')
                    ? 'background: rgba(0,176,202,0.12); color: rgb(0,176,202);'
                    : 'color: rgba(255,255,255,0.4);' }}"
                onmouseover="{{ !request()->routeIs('home') ? "this.style.background='rgba(255,255,255,0.04)'; this.style.color='rgba(255,255,255,0.85)';" : '' }}"
                onmouseout="{{ !request()->routeIs('home') ? "this.style.background=''; this.style.color='rgba(255,255,255,0.4)';" : '' }}">
                <span class="material-symbols-outlined text-[20px]"
                    style="{{ request()->routeIs('home') ? 'color: rgb(0,176,202)' : '' }}">
                    grid_view
                </span>
                <span class="text-sm font-semibold tracking-tight">Dashboard</span>

                @if (request()->routeIs('home'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full flex-shrink-0"
                        style="background: rgb(0,176,202); box-shadow: 0 0 8px rgba(0,176,202,0.8);"></span>
                @endif
            </a>

            {{-- Label: Módulos --}}
            <p class="px-3 pt-5 pb-2 text-[10px] font-black uppercase tracking-[0.3em]"
                style="color: rgba(0,176,202,0.35);">Módulos</p>

            @php
                $menu = [
                    [
                        'id' => 'mantenimiento',
                        'label' => 'Mantenimiento',
                        'icon' => 'build',
                        'children' => [
                            [
                                'label' => 'Tipos de equipo',
                                'route' => 'equipment_type.list',
                                'icon' => 'category',
                                'routes' => ['equipment_type.*'],
                            ],
                            [
                                'label' => 'Unidad de negocio',
                                'route' => 'business_unit.list',
                                'icon' => 'category',
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


                <div class="mb-1">
                    {{-- Módulo padre --}}
                    <a href="#mod-{{ $module['id'] }}"
                        class="toggle-section flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group {{ $isActive ? 'active-menu' : '' }}"
                        style="{{ $isActive
                            ? 'background: rgba(0,176,202,0.08); color: rgba(255,255,255,0.9);'
                            : 'color: rgba(255,255,255,0.4);' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] transition-colors"
                                style="{{ $isActive ? 'color: rgb(0,176,202)' : '' }}">
                                {{ $module['icon'] }}
                            </span>
                            <span class="text-sm font-semibold tracking-tight">{{ $module['label'] }}</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-[18px] transition-transform duration-300 {{ $isActive ? '' : '-rotate-90' }}"
                            style="color: rgba(0,176,202,0.4);">
                            expand_more
                        </span>
                    </a>

                    {{-- Hijos --}}
                    <div id="mod-{{ $module['id'] }}"
                        class="content-section mt-1 space-y-0.5 overflow-hidden transition-all duration-300 {{ $isActive ? 'block' : 'hidden' }}">
                        @foreach ($module['children'] as $child)
                            @php
                                $childRoutes = $child['routes'] ?? [$child['route']];
                                $childActive = collect($childRoutes)->contains(fn($r) => request()->routeIs($r));
                            @endphp
                            <a href="{{ route($child['route']) }}"
                                class="flex items-center gap-3 ml-4 mr-2 px-3 py-2 rounded-lg transition-all duration-200 group"
                                style="{{ $childActive ? 'background: rgba(190,214,0,0.08); color: rgb(190,214,0);' : 'color: rgba(255,255,255,0.35);' }}">

                                {{-- Dot indicador --}}
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 transition-all"
                                    style="{{ $childActive
                                        ? 'background: rgb(190,214,0); box-shadow: 0 0 8px rgba(190,214,0,0.7);'
                                        : 'background: rgba(255,255,255,0.15);' }}">
                                </span>

                                <span class="text-[13px] font-semibold tracking-tight">
                                    {{ $child['label'] }}
                                </span>

                                @if ($childActive)
                                    {{-- <span class="ml-auto material-symbols-outlined text-[14px]"
                                        style="color: rgb(190,214,0);">
                                        chevron_right
                                    </span> --}}
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FOOTER 
        @php
            $secretary   = $params['secretary_number'] ?? null;
            $phone       = $secretary->value       ?? '930227604';
            $message     = $secretary->description ?? 'Hola, necesito ayuda en el sistema.';
            $whatsappUrl = 'https://wa.me/51' . $phone . '?text=' . urlencode($message);
        @endphp

        <div class="px-4 py-4 border-t" style="border-color: rgba(0,176,202,0.1); background: rgba(0,176,202,0.02);">
            <div class="rounded-2xl p-4 border transition-all duration-500 group/card"
                 style="background: rgba(0,176,202,0.04); border-color: rgba(0,176,202,0.1);"
                 onmouseover="this.style.borderColor='rgba(0,176,202,0.3)'; this.style.background='rgba(0,176,202,0.07)';"
                 onmouseout="this.style.borderColor='rgba(0,176,202,0.1)'; this.style.background='rgba(0,176,202,0.04)';">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: rgba(0,176,202,0.1); border: 1px solid rgba(0,176,202,0.2);">
                            <span class="material-symbols-outlined text-[18px]"
                                  style="color: rgb(0,176,202);">support_agent</span>
                        </div>
                        <h3 class="text-white font-bold text-xs tracking-wide uppercase">¿Necesitas ayuda?</h3>
                    </div>
                    <p class="text-[12px] leading-relaxed" style="color: rgba(255,255,255,0.35);">
                        Nuestro equipo está disponible para ayudarte.
                    </p>
                    <a href="{{ $whatsappUrl }}" target="_blank"
                        class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl font-black text-[11px] uppercase tracking-wider transition-all duration-300 active:scale-95"
                        style="background: linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%); color: white; box-shadow: 0 4px 14px rgba(0,176,202,0.25);"
                        onmouseover="this.style.background='linear-gradient(135deg, rgb(190,214,0) 0%, rgb(160,185,0) 100%)'; this.style.boxShadow='0 4px 14px rgba(190,214,0,0.25)';"
                        onmouseout="this.style.background='linear-gradient(135deg, rgb(0,176,202) 0%, rgb(0,140,165) 100%)'; this.style.boxShadow='0 4px 14px rgba(0,176,202,0.25)';">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Contactar soporte
                    </a>
                </div>
            </div>
        </div> --}}
    </div>
</aside>
