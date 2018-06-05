<?php foreach($fields as $ff){
    if (isset($ff['type'])) $type = $ff['type'];
    else $type = 'text';
    $name = $ff['name'];
    $label = isset($ff['label']) ? $ff['label'] : $ff['name'];

    $field = Former::$type($name);

    switch($type){
        case 'select':
            $field = $field->options($ff['options'])->label($label);
            break;
        default:
            $field = $field->label($label);
            break;
    }

    if(isset($ff['default'])) $field = $field->value($ff['default']);

    if(isset($ff['validation'])){
        if($ff['validation']=='required') $field = $field->required();
        else $field = $field->pattern($ff['validation']);
    }
    echo $field; 
} ?>