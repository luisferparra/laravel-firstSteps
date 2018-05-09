@extends('adminlte::page')

@section('title', 'Registros')

@section('content_header')
<h1>Gestión Registros</h1>
@stop

@section('content')
<form name='forms' id='forms' method='post' action="">

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">User @if (!empty($register)) Edit {{$register->name .' ('.$register->id.')'}}@else New @endif</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form">
                    <div class="box-body">
                        <div class="form-group @if($errors->has('name')) has-error @endif">
                            <label for="name">{{__('admin.name')}}</label>

                            <input type="text" class="form-control" id="name" name ="name" placeholder="Nombre"  value="@if ($errors->any()){{old('name')}}@elseif (isset($register)){{$register->name}}@endif">
                            @if($errors->has('name')) <span class="help-block">{{$errors->get('name')[0]}}</span>@endif
                        </div>
<!--                        <div class="form-group has-error">
                            <label class="control-label" for="inputError">- <i class="fa fa-times-circle-o"></i> -Input with
                                error</label>
                            <input type="text" class="form-control" id="inputError" placeholder="Enter ...">
                            <span class="help-block">Help block with error</span>
                        </div>-->
                        <div class="form-group @if($errors->has('surname')) has-error @endif">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" value="@if ($errors->any()){{old('surname')}}@elseif (isset($register)){{$register->surname}}@endif">
                            @if($errors->has('surname')) <span class="help-block">{{$errors->get('surname')[0]}}</span>@endif
                            </div>
                        <div class="form-group  @if($errors->has('age')) has-error @endif">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="Age" value="@if ($errors->any()){{old('age')}}@elseif (isset($register)){{$register->age}}@endif">
                            @if($errors->has('age')) <span class="help-block">{{$errors->get('age')[0]}}</span>@endif
                            @php 
                            $gender = ''; 
                            $policy_id='';
                            if ($errors->any()):
                            $gender = old('gender');
                            $policy_id = old('policy_id');
                            elseif (isset($register)):
                            $gender = $register->gender;
                            $policy_id = $register->policy_id;
                            
                            endif;


                            @endphp
                        </div>
                        <div class="form-group  @if($errors->has('gender')) has-error @endif">
                            <label>Gender</label>
                            <select class="form-control" name='gender' id='gender'>
                                <option></option>
                                <option value='male' @if ($gender == 'male') selected @endif>Male</option>
                                <option value='female' @if ($gender == 'female') selected @endif>Female</option>
                            </select>
                            @if($errors->has('gender')) <span class="help-block">{{$errors->get('gender')[0]}}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('policy_id')) has-error @endif">
                            <label>Policies</label>
                            <select class="form-control" name='policy_id' id='policy_id'>
                                <option></option>
                                @foreach ($policy_list as $policy) 
                                <option value='{{$policy->id}}' @if ($policy_id == $policy->id) selected @endif>{{$policy->policy}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('policy_id')) <span class="help-block">{{$errors->get('policy_id')[0]}}</span>@endif
                        </div>

                    </div>
                    
                    <!-- /.box-body -->
                    {{ csrf_field() }}
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</form>
@stop