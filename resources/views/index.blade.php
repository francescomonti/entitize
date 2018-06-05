@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">

                <table class="table">
                    <thead>
                        <tr>
                            @foreach($tableParams as $k=>$v)
                                <th>{{$k}}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources as $resource)
                            <tr>
                                @foreach($tableParams as $v)
                                    @if($v == 'name')
                                        <td><a href="<?= action($controllerName.'@show', ['id'=>$resource->id]); ?>" >{{$resource->$v}}</a></td>
                                    @else
                                        <td>
                                            <?php
                                                if(strpos($v, '()') == false) echo $resource->$v;
                                                else{
                                                    if (isset($resource->{substr($v, 0, -2)}()->name)) echo $resource->{substr($v, 0, -2)}()->name;
                                                    else echo 'Dato non registrato';
                                                }
                                            ?>
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-right">
                                    @include($partial_tools, ['resource'=>$resource, 'controllerName' => $controllerName])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection