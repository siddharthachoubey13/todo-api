<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskModel;
use Carbon\Carbon;

class Task extends Controller
{
    public function create(Request $request)
    {
        if (!$request->has('title')) {
            return response()->json(['success' => false, 'message' => "'title' is missing"], 400);
        }

        if (!$request->has('due_date')) {
            return response()->json(['success' => false, 'message' => "'due_date(YYYY-MM-DD)' is missing"], 400);
        }

        (new TaskModel)->create(
            [
                'title' => $request->title,
                'due_date' => Carbon::parse($request->due_date),
                'status' => 'pending',
            ]
        );

        return response()->json(['success' => true, 'message' => "Task created successfully"], 200);
    }

    public function search(Request $request)
    {
        $title = $request->title;
        $due_date = $request->due_date;

        $items = TaskModel::orderBy('due_date', 'asc');

        if (!empty($title)) {
            $items->where('title', 'like', "%$title%");
        }

        if (!empty($due_date)) {
            $date = date('Y-m-d', strtotime($due_date));
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
        $task_id = $request->task_id;
        if (empty($task_id)) {
            return response()->json(['success' => false, 'message' => "'task_id' is missing"], 400);
        }
        (new TaskModel)->find($task_id)->subtask()->delete();
        (new TaskModel)->find($task_id)->delete();
        return response()->json(['success' => true, 'message' => "Task deleted successfully"], 200);
    }

    public function markStatus(Request $request)
    {
        $task_id = $request->task_id;
        $task_status = $request->task_status;
        if (empty($task_id)) {
            return response()->json(['success' => false, 'message' => "'task_id' is missing"], 400);
        }

        if (!in_array($task_status, ['pending', 'completed'])) {
            return response()->json(['success' => false, 'message' => "'task_status' can only be 'pending'/'completed'"], 400);
        }

        $task = (new TaskModel)->find($task_id);
        $task->status = $task_status;
        $task->save();

        return response()->json(['success' => true, 'message' => "Task updated successfully"], 200);
    }
}
