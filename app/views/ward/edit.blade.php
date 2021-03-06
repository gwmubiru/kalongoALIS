@extends("layout")
@section("content")

    @if (Session::has('message'))
        <div class="alert alert-info">{{ trans(Session::get('message')) }}</div>
    @endif
    <div>
        <ol class="breadcrumb">
          <li><a href="{{{URL::route('user.home')}}}">{{ trans('messages.home') }}</a></li>
          <li>
            <a href="{{ URL::route('ward.index') }}">Health Units</a>
          </li>
          <li class="active">Edit Health Unit</li>
        </ol>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading ">
            <span class="glyphicon glyphicon-edit"></span>
            Edit Health Unit
        </div>
        <div class="panel-body">
            @if($errors->all())
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            {{ Form::model($ward, array('route' => array('ward.update', $ward->id), 
                'method' => 'PUT', 'id' => 'form-edit-ward')) }}
                <div class="form-group">
                    {{ Form::label('name', Lang::choice('messages.name',1)) }}
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('description', trans('messages.description')) }}
                    {{ Form::textarea('description', Input::old('description'), 
                        array('class' => 'form-control', 'rows' => '2')) }}
                </div>
                 <div class="form-group">
                    {{ Form::label('ward_type_id',Lang::choice('messages.ward-type',1)) }}
                    {{ Form::select('ward_type_id', $ward_types, $ward->ward_type_id,
                                            array('class' => 'form-control')) }}
                </div>
                <div class="form-group actions-row">
                    {{ Form::button('<span class="glyphicon glyphicon-save"></span> '. trans('messages.save'), 
                        ['class' => 'btn btn-primary', 'onclick' => 'submit()']) }}
                </div>

            {{ Form::close() }}
        </div>
    </div>
@stop
