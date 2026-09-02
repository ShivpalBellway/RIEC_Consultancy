<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'REIAC')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a2f5e', // Dark navy
                        gold: '#c89b2a', // Gold/amber
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
    @stack('styles')
</head>

<body class="font-sans text-gray-800">

   @include('partials.web.header')

<style>
    html{
        scroll-behavior: smooth;
    }

    /* Scrollbar Width */
    ::-webkit-scrollbar{
        width: 8px;
    }

    /* Transparent Track */
    ::-webkit-scrollbar-track{
        background: transparent;
    }

    /* Hidden by default */
    ::-webkit-scrollbar-thumb{
        background: rgba(26, 47, 94, 0);
        border-radius: 999px;
        transition: background .3s ease;
    }

    /* Show on hover */
    body:hover::-webkit-scrollbar-thumb{
        background: rgba(26, 47, 94, 0.85);
    }

    body:hover::-webkit-scrollbar-thumb:hover{
        background: #1a2f5e;
    }

    /* Hide arrows */
    ::-webkit-scrollbar-button{
        display: none;
        width: 0;
        height: 0;
    }

    /* Firefox */
    *{
        scrollbar-width: thin;
        scrollbar-color: rgba(26,47,94,.85) transparent;
    }

</style>

    <main>
        @yield('content')
    </main>

    @include('partials.web.footer')

    @stack('scripts')
</body>

</html>

