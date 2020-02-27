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
    <form action="{{ url('tasks') }}" method="POST" class="form-horizontal">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="list" class="col-sm-3 control-label">Task name</label>
            <div class="col-sm-6">
                <input type="text" name="task_name" id="task_name" value="" class="form-control">
                <input type="hidden" id="to_do_lists_id" name="to_do_lists_id" value="{{$to_do_lists_id}}">
            </div>
            <label for="list" class="col-sm-3 control-label">Deadline</label>
            <div class="col-sm-6">
                <input type="text" name="deadline" id="deadline" value="" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Save Task
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">

    $('#deadline').datepicker({

        format: 'yyyy-mm-dd'

    });

</script>
@endsection