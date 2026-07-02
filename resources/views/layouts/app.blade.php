<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>PT.Sage Maslahat</title>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    @include('partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.navbar')

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>