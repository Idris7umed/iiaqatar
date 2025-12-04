<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IIAQATAR') }} - @yield('title', 'Islamic Institute of Advanced Quality Assurance Training & Research')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                                IIAQATAR
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Home
                            </a>
                            <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                About
                            </a>
                            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Blog
                            </a>
                            <a href="{{ route('events.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Events
                            </a>
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Courses
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4">IIAQATAR</h3>
                        <p class="text-gray-400">Islamic Institute of Advanced Quality Assurance Training & Research</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                            <li><a href="{{ route('courses.index') }}" class="hover:text-white">Courses</a></li>
                            <li><a href="{{ route('events.index') }}" class="hover:text-white">Events</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li>Email: info@iiaqatar.org</li>
                            <li>Phone: +974 XXXX XXXX</li>
                            <li>Doha, Qatar</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} IIAQATAR. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
