{{-- @extends('layouts.app') --}}


{{-- @section('content') --}}
    <h2>@yield('content-title', 'Title')</h2>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                @yield('entitize')
            </div>
        </div>
    </div>
{{-- @endsection --}}