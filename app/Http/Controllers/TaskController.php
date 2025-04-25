<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tasks = Task::with(['creator', 'project', 'assignees'])
            ->paginate($perPage);

        return response()->json([
            'tasks' => $tasks->items(),
            'total' => $tasks->total(),
            'current_page' => $tasks->currentPage(),
            'last_page' => $tasks->lastPage(),
        ]);
    }

    public function show($id)
    {
        $task = Task::with(['creator', 'project', 'assignees', 'subtasks', 'checklists'])
            ->findOrFail($id);

        return response()->json(['task' => $task]);
    }


    // public function search(Request $request)
    // {
    //     $query = $request->query('query');
    //     $tasks = Task::search($query)->get();

    //     return response()->json([
    //         'data' => $tasks,
    //     ]);
    // }



    public function search(Request $request)
    {


        try {
            $query = $request->query('query');
            $perPage = $request->input('per_page', 10);

            // Tìm kiếm với Scout và lấy ID của tasks
            $taskIds = Task::search($query)->get()->pluck('id');

            // Nếu không tìm thấy task, trả về response rỗng
            if ($taskIds->isEmpty()) {
                return response()->json([
                    'tasks' => [],
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                ], 200);
            }

            // Lấy tasks từ database với quan hệ và phân trang
            $tasks = Task::whereIn('id', $taskIds)
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'name', 'avatar');
                    },
                    'project',
                    'assignees'
                ])
                ->paginate($perPage);

            return response()->json([
                'tasks' => $tasks->items(),
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error searching tasks',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'estimated_time' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:normal,urgent',
            'progress' => 'required|integer|min:0|max:100',
            'creator_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'assignees' => 'required|array',
            'assignees.*.user_id' => 'required|exists:users,id',
            'assignees.*.role' => 'required|in:primary,member',
            'files.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $fileUrls = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'tasks',
                ]);
                $fileUrls[] = $uploadedFile->getSecurePath();
            }
        }

        $validated['file_urls'] = json_encode($fileUrls);
        $task = Task::create($validated);

        $task->assignees()->sync(
            collect($validated['assignees'])->mapWithKeys(function ($assignee) {
                return [$assignee['user_id'] => ['role' => $assignee['role']]];
            })->toArray()
        );

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task->load('assignees'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'estimated_time' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:normal,urgent',
            'progress' => 'required|integer|min:0|max:100',
            'creator_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'assignees' => 'required|array',
            'assignees.*.user_id' => 'required|exists:users,id',
            'assignees.*.role' => 'required|in:primary,member',
            'files.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $fileUrls = $task->file_urls ?? [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'tasks',
                ]);
                $fileUrls[] = $uploadedFile->getSecurePath();
            }
        }

        $validated['file_urls'] = json_encode($fileUrls);
        $task->update($validated);

        $task->assignees()->sync(
            collect($validated['assignees'])->mapWithKeys(function ($assignee) {
                return [$assignee['user_id'] => ['role' => $assignee['role']]];
            })->toArray()
        );

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->load('assignees'),
        ]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
