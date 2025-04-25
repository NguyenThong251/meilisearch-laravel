<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SubtaskController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'nullable|exists:users,id',
            'files.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $fileUrls = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'subtasks',
                ]);
                $fileUrls[] = $uploadedFile->getSecurePath();
            }
        }

        $validated['file_urls'] = json_encode($fileUrls);
        $subtask = Subtask::create($validated);

        return response()->json([
            'message' => 'Subtask created successfully',
            'subtask' => $subtask,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'nullable|exists:users,id',
            'files.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $fileUrls = $subtask->file_urls ?? [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'subtasks',
                ]);
                $fileUrls[] = $uploadedFile->getSecurePath();
            }
        }

        $validated['file_urls'] = json_encode($fileUrls);
        $subtask->update($validated);

        return response()->json([
            'message' => 'Subtask updated successfully',
            'subtask' => $subtask,
        ]);
    }

    public function destroy($id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->delete();

        return response()->json(['message' => 'Subtask deleted successfully']);
    }
}
