@extends('layouts.base')

@section('body-class', 'bg-gray-50')

@section('body')
    <div class="wrapper relative" style="background: #f0f9fb !important;">
        @include('partials.sidebar')
        <main class="main-panel ms-0 md:ms-[250px] transition-all duration-300 ease">
            @include('partials.nav')
            <div class="relative">
                <div class="content md:px-16 px-4 mb-16 md:mb-0">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
@endsection