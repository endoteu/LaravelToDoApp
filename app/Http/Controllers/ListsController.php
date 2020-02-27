<?php

namespace App\Http\Controllers;

use App\ToDoLists;
use App\Tasks;
use Illuminate\Http\Request;
use Validator, Input, Redirect, Response, DB, Config;

class ListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = ToDoLists::orderBy('id','DESC')->paginate(10);
        return view('lists.all_lists', ['lists' => $lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lists.create');
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
            'list_name' => 'required|min:3|max:255',
        ]);

        $list = new ToDoLists();
        $list->list_name = $request->list_name;
        $list->completed = false;
        $list->active = true;

        $list->save();

        return redirect()->route('lists.home')
            ->with('success','List created successfully');
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
        $list = ToDoLists::find($id)->where('id',$id)->first();

        return view('lists.edit', compact('list'));
        //$list = ToDoLists::where('id',$id)->pluck('list_name');
        //return view('lists.edit', ['list_name' => $list[0],'to_do_list_id'=>$id]);
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
        $list = ToDoLists::find($id);
        $list->list_name = $request->get('list_name');
        $list->completed = false;
        $list->active = true;

        $list->save();

        return redirect()->route('lists.home')
            ->with('success','List changes saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ToDoLists::find($id)->delete();
        Tasks::where('to_do_list_id',$id)->delete();

        return redirect()->route('lists.home')
            ->with('success','List ' . $id . ' and it\'s tasks deleted successfully');
    }
}
