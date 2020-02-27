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

<div class="panel-body" align="right">
    <a class="btn btn-primary" href="{{ route('tasks.create',['to_do_lists_id' => $to_do_lists_id]) }}"><i class="fas fa-plus-circle"></i> Add new task</a>
</div>
<div class="container">
    <h2>All tasks for List #{{$to_do_lists_id}}</h2>
    <table class="table table-bordered" id="laravel">
        <thead>
        <tr>
            <th></th>
            <th>Task #</th>
            <th>Task name</th>
            <th>Deadline</th>
            <th>Completed</th>
            <th>Active</th>
            <th>Created at</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if (sizeof($tasks))
        @foreach($tasks as $task)
            @if ($task->completed == true)
                <tr class="completed">
            @else
                <tr class="not-completed">
            @endif

                @if (($task->active == true) && (date('Y-m-d') < date('Y-m-d', strtotime($task->deadline))) && ($task->completed == false))
                    <td align="center"><a class="btn btn-success" href="{{ route('tasks.complete',['id'=>$task->id]) }}"><i class="far fa-check-square"></i> Done</a></td>
                @elseif (($task->active == true) && (date('Y-m-d') < date('Y-m-d', strtotime($task->deadline))) && ($task->completed == true))
                    <td align="center"><a class="btn btn-secondary" href="{{ route('tasks.notcomplete',['id'=>$task->id]) }}"><i class="far fa-check-square"></i> Undo</a></td>
                @else
                    <td align="center">N/A</td>
                @endif
                <td>{{ $task->id }}</td>
                <td>{{ $task->task_name }}</td>
                <td>{{ date('d-m-Y', strtotime($task->deadline)) }}</td>
                <td>
                    @if ($task->completed)
                        Yes
                    @else
                        No
                    @endif
                </td>
                <td>
                    @if ($task->active)
                        Yes
                    @else
                        No
                    @endif</td>
                <td>{{ date('d-m-Y', strtotime($task->created_at)) }}</td>
                <td>
                    @if ((date('Y-m-d') < date('Y-m-d', strtotime($task->deadline))) && ($list_completed == false))
                        <a class="btn btn-secondary" href="{{ route('tasks.edit',$task->id) }}"><i class="far fa-edit"></i> Edit</a>
                        @if ($task->active)
                            <a class="btn btn-dark" href="{{ route('tasks.disable',['id'=>$task->id]) }}">Disable</a>
                        @else
                            <a class="btn btn-dark" href="{{ route('tasks.enable',['id'=>$task->id]) }}">Enable</a>
                        @endif
                    @endif
                <!--
                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group delete-btn">
                            <input type="submit" class="btn btn-danger" value="DELETE">
                        </div>
                    </form>
                    -->
                </td>
            </tr>
        @endforeach
        @else
            <td colspan="9" align="center">There are no tasks in this list</td>
        @endif
        </tbody>
    </table>
    {!! $tasks->links() !!}
</div>
@endsection