@extends('layouts.main')

@section('content')

@include('common.errors')

@if (session('success'))
    <div class="col-sm-12">
        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

<div class="panel-body">

    <form action="{{ route('lists.update', $list->id) }}" method="POST" class="form-horizontal">
        @method('PATCH')
        @csrf
        <div class="form-group">
            <label for="list" class="col-sm-3 control-label">List name</label>

            <div class="col-sm-6">
                <input type="text" name="list_name" id="list_name" value="{{ isset($list->list_name) ? $list->list_name : old('list_name') }}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Save List
                </button>
            </div>
        </div>
    </form>
</div>
@endsection