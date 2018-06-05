<?php

namespace Wcr\Entitize\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

trait Entitize
{
    public function traitIndex(){
        $this->set('action', 'index');
        $this->set('resources', $this->newModel()->whereDeleted(0)->get(), 'index');
        $this->set('tableParams', $this->tableParams(), 'index');
        return $this->render('index');
    }

    public function traitCreate(){
        $this->set('action', 'create');
        $this->set('resource', $this->newModel(), 'create');
        $this->set('fields', $this->fields(), 'create');
        $this->set('partial_fields', $this->getPartial('fields'), 'create');
        return $this->render('create');
    }

    public function traitStore(){
        $this->set('action', 'store');
        $validator = Validator::make( Input::all(), $this->validationRules() );

        if($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }else{
            $resource = $this->newModel() ;
            foreach( $this->acceptedFields() as $a ){
                $resource->$a = Input::get($a);
            }
            $resource->save();
            Session::flash('message', 'Successfully created entity!');
            return Redirect::to(action($this->controllerName().'@index'));
        }
    }

    public function traitShow($id){
        $this->set('action', 'show');
        $this->set('resource', $this->newModel()->find($id), 'show');
        $this->set('fields', $this->fields(), 'show');
        return $this->render('show');
    }

    public function traitEdit($id){
        $this->set('action', 'edit');
        $this->set('resource', $this->newModel()->find($id), 'edit');
        $this->set('fields', $this->fields(), 'edit');
        $this->set('partial_fields', $this->getPartial('fields'), 'edit');
        return $this->render('edit');
    }

    public function traitUpdate($id){
        $this->set('action', 'update');
        $validator = Validator::make( Input::all(), $this->validationRules() );

        if($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }else{
            $resource = $this->newModel()->find($id);
            foreach( $this->acceptedFields() as $a ){
                $resource->$a = Input::get($a);
            }
            $resource->save();
            Session::flash('message', 'Successfully save entity!');
            return Redirect::to(action($this->controllerName().'@index'));
        }
    }

    public function traitDestroy($id){
        $this->set('action', 'update');
        $resource = $this->newModel()->find($id);
        $resource->deleted = true;
        $resource->save();
        Session::flash('message', 'Successfully deleted entity!');
        return Redirect::to(action($this->controllerName().'@index'));
    }

    public function index(){
        return $this->traitIndex();
    }

    public function create(){
        return $this->traitCreate();
    }

    public function store(){
        return $this->traitStore();
    }

    public function show($id){
        return $this->traitShow($id);
    }

    public function edit($id){
        return $this->traitEdit($id);
    }

    public function update($id){
        return $this->traitUpdate($id);
    }

    public function destroy($id){
        return $this->traitDestroy($id);
    }

    public function set ($k, $v, $view = 'all'){
        $vName = $view."ViewVariables";
        if(!isset($this->$vName)) $this->$vName = array();
        $this->$vName[$k] = $v;
    }

    public function render($view){
        $params = $this->preparedVariablesForView($view);
        $dirName = str_replace('Controller', '', $this->controllerName());
        if(view()->exists($dirName.'.'.$view)){
            $viewName = $dirName.'.'.$view;
        }else{
            $viewName = 'vendor.wcr.entitize.views.'.$view;
        }
        return view($viewName, $params)->render();
    }

    public function getPartial($view){
        $dirName = str_replace('Controller', '', $this->controllerName());
        if(view()->exists($dirName.'.partials.'.$view)){
            return $dirName.'.partials.'.$view;
        }else{
            return 'vendor.wcr.entitize.views.partials.'.$view;
        }
    }

    private function newModel (){
        $model = $this->modelName();
        return new $model;
    }

    private function modelName (){
        if(!isset($this->modelName)) {
            $modelName = str_replace('Controller', '', $this->controllerName());
            $this->modelName = $this->modelNamespace()."$modelName";
        }
        return $this->modelName;
    }

    private function className (){
        if(!isset($this->className)) $this->className = get_class($this);
        return $this->className;
    }

    private function controllerName (){
        if(!isset($this->controllerName)) {
            $expClassName = explode('\\', $this->className());
            $this->controllerName = end($expClassName);
        }
        return $this->controllerName;
    }

    private function modelNameSpace (){
        if(!isset($this->modelNameSpace)) $this->modelNameSpace = "App\\";
        return $this->modelNameSpace;
    }

    private function preparedVariablesForView ($v){
        $default = $this->defaultViewVariables();
        $general = array_merge($default, $this->allViewVariables());
        $vName = $v."ViewVariables";
        $view = $this->$vName();
        if(count($view) > 0) $all = array_merge($general, $view);
        else $all = $general;
        return $all;
    }

    private function indexViewVariables (){
        if(!isset($this->indexViewVariables)) $this->indexViewVariables = array();
        return $this->indexViewVariables;
    }

    private function createViewVariables (){
        if(!isset($this->createViewVariables)) $this->createViewVariables = array();
        return $this->createViewVariables;
    }

    private function editViewVariables (){
        if(!isset($this->editViewVariables)) $this->editViewVariables = array();
        return $this->editViewVariables;
    }

    private function showViewVariables (){
        if(!isset($this->showViewVariables)) $this->showViewVariables = array();
        return $this->showViewVariables;
    }

    private function allViewVariables (){
        if(!isset($this->allViewVariables)) $this->allViewVariables = array();
        return $this->allViewVariables;
    }

    private function defaultViewVariables (){
        return array(
            'controllerName' => $this->controllerName(),
            'partial_tools' => $this->getPartial('tools')
        );
    }

    private function fields (){
        if(!isset($this->fields)) $this->fields = array();
        return $this->fields;
    }

    private function acceptedFields (){
        if(!isset($this->acceptedFields)){
            $fields = array();
            foreach ($this->fields as $f){
                $fields[]=$f['name'];
            }
            $this->acceptedFields = $fields;
        }
        return $this->acceptedFields;
    }

    private function validationRules (){
        if(!isset($this->validationRules)){
            $rules = array();
            foreach ($this->fields as $f){
                if(isset($f['validation'])) $rules[$f['name']]=$f['validation'];
            }
            $this->validationRules = $rules;
        }
        return $this->validationRules;
    }

    private function tableParams (){
        if(!isset($this->tableParams)) $this->tableParams = array();
        return $this->tableParams;
    }

    public function selectOptions($list, $field = null, $key = null){
        // prepare key
        if($key == null ) $key = 'id';
        if (strpos($key, '()') !== false){
            $key = str_replace('()', '', $key);
            $key_is_func = true;
        }else $key_is_func = false;

        // prepare field
        if($field == null ) $field = 'name';
        if (strpos($field, '()') !== false){
            $field = str_replace('()', '', $field);
            $field_is_func = true;
        }else $field_is_func = false;

        $options = [];
        foreach($list as $c){
            if($key_is_func) $k = $c->$key();
            else $k = $c->$key;

            if($field_is_func) $options[$k] = $c->$field();
            else{
                if(isset($c->$field)) $options[$k] = $c->$field;
                else $options[$k] = $k;
            }
        }
        return $options;
    }
}