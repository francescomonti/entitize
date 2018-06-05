@extends('layouts.app')

@section('content')
    <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row">
                    @foreach($fields as $ff)
                        <?php $ffname = $ff['name']; ?>
                        @if($resource->$ffname != '')
                            <div class="show-field col-md-2 col-xs-4 text-right">
                                <b><?= (isset($ff['label'])) ? $ff['label'] : $ff['name'] ?></b>
                            </div>
                            <div class="show-field col-md-4 col-xs-8">
                                <?php 
                                if(isset($ff['relation'])) echo $resource->{$ff['relation']}()->name;
                                else echo $resource->$ffname;
                                ?>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection