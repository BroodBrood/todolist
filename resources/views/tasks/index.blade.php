<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do – Focus & Rust</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face { font-family: 'Figtree'; src: local('Figtree'); font-display: swap; }
    </style>
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#1E3A8A">
    <meta name="description" content="Moderne, minimalistische takenlijst met focus op rust & overzicht.">
</head>
<body class="min-h-screen bg-brand-background text-brand-muted antialiased">
    <header class="bg-brand-primary text-white">
        <div class="container py-6 flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Jouw To-Do’s</h1>
            <nav class="flex gap-2" aria-label="Filter taken">
                @php($active = $filter ?? 'open')
                <a href="{{ route('tasks.index', ['filter' => 'open']) }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $active==='open' ? 'bg-white/15' : 'hover:bg-white/10' }}">Open</a>
                <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $active==='completed' ? 'bg-white/15' : 'hover:bg-white/10' }}">Afgerond</a>
                <a href="{{ route('tasks.index', ['filter' => 'all']) }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $active==='all' ? 'bg-white/15' : 'hover:bg-white/10' }}">Alle</a>
            </nav>
        </div>
    </header>

    <main class="container py-8 space-y-8">
        <section aria-labelledby="add-task-heading" class="bg-white rounded-xl shadow-sm border border-brand-accent/40">
            <h2 id="add-task-heading" class="sr-only">Nieuwe taak toevoegen</h2>
            <form action="{{ route('tasks.store') }}" method="POST" class="p-4 md:p-6 grid gap-3 md:grid-cols-12 items-end">
                @csrf
                <div class="md:col-span-6">
                    <label for="title" class="block text-sm font-medium mb-1">Taak</label>
                    <input type="text" name="title" id="title" required maxlength="255" placeholder="Wat wil je doen?" class="w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2">
                </div>
                <div class="md:col-span-3">
                    <label for="due_at" class="block text-sm font-medium mb-1">Deadline</label>
                    <input type="datetime-local" name="due_at" id="due_at" class="w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label for="priority" class="block text-sm font-medium mb-1">Prioriteit</label>
                    <select name="priority" id="priority" class="w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2">
                        <option value="1">Hoog</option>
                        <option value="2" selected>Middel</option>
                        <option value="3">Laag</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-brand-primary text-white px-3 py-2 font-medium hover:opacity-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Voeg toe</button>
                </div>
            </form>
        </section>

        <section aria-labelledby="task-list-heading" class="bg-white rounded-xl shadow-sm border border-brand-accent/40">
            <h2 id="task-list-heading" class="sr-only">Takenlijst</h2>
            <ul class="divide-y divide-brand-accent/40">
                @forelse($tasks as $task)
                    <li class="p-4 md:p-6 grid md:grid-cols-12 gap-4 items-center {{ $task->isDueSoon() ? 'bg-brand-warning/40' : '' }}">
                        <div class="md:col-span-1 flex items-center">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" aria-label="{{ $task->isCompleted ? 'Markeer als niet afgerond' : 'Markeer als afgerond' }}" class="h-5 w-5 rounded border border-brand-accent/70 flex items-center justify-center {{ $task->isCompleted ? 'bg-brand-primary text-white' : 'bg-white' }}">
                                    @if($task->isCompleted)
                                        ✓
                                    @endif
                                </button>
                            </form>
                        </div>
                        <div class="md:col-span-5">
                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="grid gap-2 md:grid-cols-6 items-center">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="title" value="{{ $task->title }}" class="md:col-span-6 w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2 {{ $task->isCompleted ? 'line-through text-brand-muted/60' : '' }}">
                                <input type="datetime-local" name="due_at" value="{{ $task->due_at ? $task->due_at->format('Y-m-d\TH:i') : '' }}" class="md:col-span-3 w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2">
                                <select name="priority" class="md:col-span-2 w-full rounded-md border border-brand-accent/70 focus:ring-2 focus:ring-brand-accent focus:border-brand-accent px-3 py-2">
                                    <option value="1" @selected($task->priority==1)>Hoog</option>
                                    <option value="2" @selected($task->priority==2)>Middel</option>
                                    <option value="3" @selected($task->priority==3)>Laag</option>
                                </select>
                                <div class="md:col-span-1 flex justify-end">
                                    <button type="submit" class="inline-flex items-center rounded-md bg-brand-accent text-brand-primary px-3 py-2 text-sm font-medium hover:opacity-90">Bewaar</button>
                                </div>
                            </form>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-between gap-3">
                            <div class="text-sm text-brand-muted">
                                @if($task->due_at)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="h-2 w-2 rounded-full {{ $task->isCompleted ? 'bg-brand-accent' : ($task->isDueSoon() ? 'bg-yellow-500' : 'bg-brand-primary') }}"></span>
                                        Deadline: {{ $task->due_at->translatedFormat('d M Y H:i') }}
                                    </span>
                                @else
                                    <span class="italic">Geen deadline</span>
                                @endif
                            </div>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Taak verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-md bg-white border border-red-200 text-red-700 px-3 py-2 text-sm font-medium hover:bg-red-50">Verwijder</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-brand-muted">Nog geen taken. Voeg er boven een toe.</li>
                @endforelse
            </ul>
        </section>
    </main>

    <footer class="container py-8 text-center text-sm text-brand-muted">
        Gemaakt met focus en rust.
    </footer>
</body>
</html>


