<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        return response()->json(Task::all());
    }

    public function store(Request $request)
{
    try {
        // Validate the request with custom error messages
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ],
            [
                'title.required' => 'The task title is needed.',
                'title.string' => 'The task title must be a string.',
                'title.max' => 'The task title may not exceed 255 characters.',
                'description.required' => 'The task description is needed.',
                'description.string' => 'The task description must be a string.',
                'description.max' => 'The task description may not exceed 255 characters.',
            ]
        );

        // Create the task if validation passes
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($task, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Let Laravel handle the validation response (optional, added for clarity)
        throw $e;
    } catch (\Exception $e) {
        // Handle all other exceptions
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
