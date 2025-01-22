<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        // return response()->json(Task::orderBy('created_at', 'desc')->get());
        return response()->json(Task::all());
    }

    // Add a new task
    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string|max:255',
            ]);

            $task = Task::create(['description' => $request->description]);
            return response()->json($task, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task creation failed!'], 500);
        }
    }

    // Update task status
    public function update(Request $request, Task $task)
    {
        try {
            $task->is_completed = !$task->is_completed;
            $task->save();

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task update failed!'], 500);
        }
    }

    // Delete a task
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task deletion failed!'], 500);
        }
    }
}
