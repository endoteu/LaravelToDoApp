<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\ToDoLists;
use Illuminate\Http\Request;
use Validator, Input, Redirect, Response, DB, Config;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Tasks::where('to_do_lists_id',$request->to_do_lists_id)->orderBy('id','DESC')->paginate(10);
        $result = ToDoLists::where('id',$request->to_do_lists_id)->pluck('completed');
        $list_completed = $result[0];

        return view('tasks.all_tasks', ['tasks' => $tasks, 'to_do_lists_id' => $request->to_do_lists_id, 'list_completed' => $list_completed]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('tasks.create',['to_do_lists_id' => $request->to_do_lists_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|min:3|max:255',
            'deadline' => 'required|date|date_format:Y-m-d|after:today',
        ]);

        $task = new Tasks();
        $task->to_do_lists_id = $request->to_do_lists_id;
        $task->task_name = $request->task_name;
        $task->deadline = $request->deadline;
        $task->completed = false;
        $task->active = true;

        $task->save();

        return redirect()->route('tasks.home',['to_do_lists_id' => $request->to_do_lists_id])
            ->with('success','Task created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Tasks::find($id)->where('id',$id)->first();

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $to_do_lists_id = Tasks::where('id',$id)->pluck('to_do_lists_id');

        $request->validate([
            'task_name' => 'required|min:3|max:255',
            'deadline' => 'required|date|date_format:Y-m-d|after:today',
        ]);

        Tasks::where('id', $id)
            ->update(['task_name' => $request->get('task_name'),'deadline'=>$request->get('deadline')]);


        return redirect()->route('tasks.home',['to_do_lists_id'=>$to_do_lists_id[0]])
            ->with('success','Task changes saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $to_do_lists_id = Tasks::where('id',$id)->pluck('to_do_lists_id');

        Tasks::find($id)->delete();

        return redirect()->route('tasks.home',['to_do_lists_id'=>$to_do_lists_id[0]])
            ->with('success','Task deleted successfully');
    }

    /**
     * Disable task
     */
    public function disable(Request $request) {
        Tasks::where('id',$request->id)->update(['active' => false]);

        return redirect()->back()->with('success','Task #' . $request->id . ' disabled successfully');
    }

    /**
     * Enable task
     */
    public function enable(Request $request) {
        Tasks::where('id',$request->id)->update(['active' => true]);

        return redirect()->back()->with('success','Task #' . $request->id . ' enabled successfully');
    }

    /**
     * Complete task
     */
    public function complete(Request $request) {
        $task = Tasks::find($request->id);

        if (($task->active == true) && (date('Y-m-d') < date('Y-m-d', strtotime($task->deadline)))) {
            Tasks::where('id',$request->id)->update(['completed' => true]);

            $tasks = Tasks::select('id')->where('to_do_lists_id',$task->to_do_lists_id)->where('completed',false)->pluck('id');

            if (!isset($tasks[0])) {
                ToDoLists::where('id',$task->to_do_lists_id)->update(['completed' => true]);

                return redirect()->back()->with('success','Task #' . $task->id . ' and List #' . $task->to_do_lists_id . ' are completed successfully');
            } else {
                return redirect()->back()->with('success','Task #' . $task->id . ' is completed successfully');
            }
        } else {
            return redirect()->back()->with('success','Task #' . $task->id . ' cannot be completed');
        }
    }

    /**
     * Undo completed task
     */
    public function notcomplete(Request $request) {
        $task = Tasks::find($request->id);
        $list = ToDoLists::find($task->to_do_lists_id);

        if (($task->active == true) && ($list->completed == false)) {
            Tasks::where('id',$request->id)->update(['completed' => false]);
            return redirect()->back()->with('success','Task #' . $task->id . ' is marked as NOT completed successfully');
        } else {
            Tasks::where('id',$request->id)->update(['completed' => false]);
            ToDoLists::where('id',$task->to_do_lists_id)->update(['completed' => false]);
            return redirect()->back()->with('success','Task #' . $task->id . ' anc List # ' . $task->to_do_lists_id .' is set as not completed');
        }
    }
}
