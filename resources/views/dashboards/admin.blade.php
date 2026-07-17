<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head><meta charset="utf-8"><title>Dashboard</title>@vite(['resources/css/app.css'])</head>
<body class="h-full flex items-center justify-center text-white">
    <div class="text-center">
        <h1 class="text-2xl font-semibold capitalize">admin Dashboard</h1>
        <p class="text-neutral-400 mt-2">Signed in as {{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="text-sm underline text-neutral-400">Logout</button>
        </form>
    </div>
</body>
</html>
