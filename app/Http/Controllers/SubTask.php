<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTaskModel;
use Carbon\Carbon;

class SubTask extends Controller
{
    public function create(Request $request)
    {
        if (!$request->has('task_id')) {
            return response()->json(['success' => false, 'message' => "'task_id' is missing"], 400);
        }

        if (!$request->has('title')) {
            return response()->json(['success' => false, 'message' => "'title' is missing"], 400);
        }

        if (!$request->has('due_date')) {
            return response()->json(['success' => false, 'message' => "'due_date(YYYY-MM-DD)' is missing"], 400);
        }

        (new SubTaskModel)->create(
            [
                'title' => $request->title,
                'task_id' => $request->task_id,
                'due_date' => Carbon::parse($request->due_date),
                'status' => 'pending',
            ]
        );

        return response()->json(['success' => true, 'message' => "SubTask created successfully"], 200);
    }

    public function search(Request $request)
    {
        $title = $request->title;
        $due_date = $request->due_date;

        $items = SubTaskModel::orderBy('due_date', 'asc');

        if (!empty($title)) {
            $items->where('title', 'like', "%$title%");
        }

        if (!empty($due_date)) {
            $date = strtotime("Y-m-d", $due_date);
            $items->whereDate('due_date', $date);
        }

        if (!empty($items)) {
            $items = $items->with('subtask')->get();
            return response(['success' => true, 'data' => ['items' => $items]]);
        }

        return response(['success' => true, 'data' => ['items' => "No data found"]]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            return response()->json(['success' => false, 'message' => "'id' is missing"], 400);
        }

        (new SubTaskModel)->find($id)->delete();
        return response()->json(['success' => true, 'message' => "subtask deleted successfully"], 200);
    }

    public function markStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if (empty($id)) {
            return response()->json(['success' => false, 'message' => "'id' is missing"], 400);
        }

        if (!in_array($status, ['pending', 'completed'])) {
            return response()->json(['success' => false, 'message' => "'status' can only be 'pending'/'completed'"], 400);
        }

        $subtask = (new SubTaskModel)->find($id);
        $subtask->status = $status;
        $subtask->save();

        return response()->json(['success' => true, 'message' => "SubTask updated successfully"], 200);
    }
}
