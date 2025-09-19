<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom – Log in of Registreer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-brand-background text-brand-muted antialiased">
    <main class="container py-16">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-brand-accent/40 p-6 space-y-4">
            <h1 class="text-2xl font-semibold text-brand-primary">Welkom</h1>
            <p class="text-sm">Log in of maak een account om je taken op te slaan.</p>
            <div class="flex gap-3">
                <a href="{{ route('login') }}" class="flex-1 text-center rounded-md bg-brand-primary text-white px-4 py-2">Inloggen</a>
                <a href="{{ route('register') }}" class="flex-1 text-center rounded-md bg-white border border-brand-accent text-brand-primary px-4 py-2">Registreren</a>
            </div>
        </div>
    </main>
</body>
</html>


