@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-3">
            <h2>@yield('content-title', 'Title')</h2>
            <div class="row">
                <?= Former::open(action($controllerName.'@update', ['id'=>$resource->id]))->method('PUT'); ?>
                    <?php Former::populate($resource); ?>
                    <div class="col-md-12">

                        @include($partial_fields)

                        <?= Former::actions()->large_primary_submit('Submit')->large_inverse_reset('Reset'); ?>
                    </div>
                <?= Former::close() ?>
            </div>
        </div>
    </div>

@endsection