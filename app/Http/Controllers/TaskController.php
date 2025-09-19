<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->string('filter')->toString() ?: 'open';

        $query = Task::query()->where('user_id', Auth::id());

        if ($filter === 'completed') {
            $query->whereNotNull('completed_at');
        } elseif ($filter === 'all') {
            // no filter
        } else {
            $filter = 'open';
            $query->whereNull('completed_at');
        }

        $tasks = $query->orderByRaw('completed_at is null desc')
            ->orderByRaw('due_at is null asc')
            ->orderBy('due_at')
            ->orderBy('priority')
            ->orderByDesc('id')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filter' => $filter,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_at' => ['nullable', 'date'],
            'priority' => ['required', 'integer', 'between:1,3'],
        ]);

        $validated['user_id'] = Auth::id();
        Task::create($validated);

        return back();
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_at' => ['nullable', 'date'],
            'priority' => ['required', 'integer', 'between:1,3'],
        ]);

        $this->authorizeTask($task);
        $task->update($validated);

        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorizeTask($task);
        $task->delete();
        return back();
    }

    public function toggle(Task $task): RedirectResponse
    {
        $this->authorizeTask($task);
        if ($task->completed_at) {
            $task->completed_at = null;
        } else {
            $task->completed_at = CarbonImmutable::now();
        }

        $task->save();

        return back();
    }

    protected function authorizeTask(Task $task): void
    {
        abort_if($task->user_id !== Auth::id(), 403);
    }
}


