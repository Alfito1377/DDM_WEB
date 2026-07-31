<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', 'Checkpoint - PT. Sage Maslahat')</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
      tailwind.config = {
          theme: {
              extend: {
                  fontFamily: {
                      sans: ['Inter', 'sans-serif'],
                  },
                  colors: {
                      brand: {
                          50: '#f0fdf4',
                          100: '#dcfce7',
                          500: '#22c55e',
                          600: '#16a34a',
                          700: '#15803d',
                          800: '#166534',
                          900: '#14532d',
                      }
                  }
              }
          }
      }
  </script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen flex flex-col">
  
  <main class="flex-grow">
    @yield('content')
  </main>

  @stack('scripts')
</body>
</html>