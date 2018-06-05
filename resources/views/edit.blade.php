@extends('layouts.entitize')

@section('entitize')

    <?= Former::open(action($controllerName.'@update', ['id'=>$resource->id]))->method('PUT'); ?>
        <?php Former::populate($resource); ?>
        <div class="col-md-12">

            @include($partial_fields)

            <?= Former::actions()->large_primary_submit('Submit')->large_inverse_reset('Reset'); ?>
        </div>
    <?= Former::close() ?>

@endsection