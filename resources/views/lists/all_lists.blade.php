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
    <a class="btn btn-primary" href="{{ route('lists.create') }}"><i class="fas fa-plus-circle"></i> Create New List</a>
</div>
<div class="container">
    <h2>All To Do Lists</h2>
    <table class="table table-bordered" id="laravel">
        <thead>
        <tr>
            <th>List #</th>
            <th>List name</th>
            <th>Completed</th>
            <th>Active</th>
            <th>Created at</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lists as $list)
            @if ($list->completed == true)
                <tr class="completed">
            @else
                <tr class="not-completed">
            @endif
                <td>{{ $list->id }}</td>
                <td>{{ $list->list_name }}</td>
                <td>
                    @if ($list->completed)
                        Yes
                    @else
                        No
                    @endif
                </td>
                <td>
                    @if ($list->active)
                        Yes
                    @else
                        No
                    @endif</td>
                <td>{{ date('d-m-Y', strtotime($list->created_at)) }}</td>
                <td>
                    @if ($list->completed == false)
                        <a class="btn btn-success" href="{{ route('tasks.create',['to_do_lists_id' => $list->id]) }}"><i class="fas fa-plus-circle"></i> Add task</a>
                        <a class="btn btn-secondary" href="{{ route('lists.edit',$list->id) }}"><i class="far fa-edit"></i> Edit</a>
                    @endif
                    <a class="btn btn-info" href="{{ route('tasks.home',['to_do_lists_id'=>$list->id]) }}"><i class="fas fa-search-plus"></i> View tasks</a>
                    <form method="POST" action="{{ route('lists.destroy', $list->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group delete-btn">
                            <input type="submit" class="btn btn-danger" value="DELETE" onclick="return confirm('This action will delete the list and all tasks associated with it! Are you sure you want to DELETE the ?');" >
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $lists->links() !!}
</div>
@endsection