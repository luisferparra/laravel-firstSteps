@extends('adminlte::page')

@section('title', 'Registros')

@section('content_header')
<h1>Gestión Registros</h1>
@stop

@section('content')
@if (session('status'))
                    <div class="alert alert-{{ session('status') }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('msg') }}
                    </div>
                @endif
              
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Responsive Hover Table</h3>
                <div class="box-tools">
                    <form id='frmSearch' name='frmSearch' method="GET" action="">
                        <div class="input-group input-group-sm" style="width: 150px;">


                            <input type="text" name="table_search" id='table_search' class="form-control pull-right" placeholder="Search" value="@if (!is_null($table_search)){{$table_search}}@endif">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                    </form>
                </div>


            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody><tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Policy</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        @foreach ($registers as $register)
                        <tr>
                            <td>{{$register->id }}</td>
                            <td>{{$register->name }}</td>
                            <td>{{$register->surname }}</td>
                            <td>{{$register->age }}</td>
                            <td>{{$register->gender }}</td>

                            <td>{{$register->policy->policy }}</td>
                            <td>@if (!is_null($register->created_at))
                                {{$register->created_at->format('d/m/Y') }}
                                @endif
                            </td>
                                <td><a href="{{route('AdminRegistersEdit',['register'=>$register->id])}}"><button type="button" class="btn btn-primary">Edit</button></a> </td>

                        </tr>
                        @endforeach
        <!--                <tr>
                          <td>183</td>
                          <td>John Doe</td>
                          <td>11-7-2014</td>
                          <td><span class="label label-success">Approved</span></td>
                          <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                          <td>219</td>
                          <td>Alexander Pierce</td>
                          <td>11-7-2014</td>
                          <td><span class="label label-warning">Pending</span></td>
                          <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                          <td>657</td>
                          <td>Bob Doe</td>
                          <td>11-7-2014</td>
                          <td><span class="label label-primary">Approved</span></td>
                          <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                          <td>175</td>
                          <td>Mike Doe</td>
                          <td>11-7-2014</td>
                          <td><span class="label label-danger">Denied</span></td>
                          <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>-->
                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="box-footer">
              <a href="{{route('AdminRegistersNew')}}"
                 <button type=button class="btn btn-info pull-right-container">Nuevo Registro</button></a>
              </div>
</div>
                
@stop