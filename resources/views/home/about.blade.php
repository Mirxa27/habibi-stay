<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">About Us</h1>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Who We Are</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                    HabibiStay is an innovative platform that blends technology, real estate expertise, and exceptional hospitality to redefine property hosting and management.
                </p>
            </div>
        </div>
    </div>

    <!-- Mission & Vision Section -->
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Mission -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                        <p class="text-gray-600">
                            To empower property owners and investors to unlock the full potential of their properties while providing guests with unparalleled experiences.
                        </p>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                        <p class="text-gray-600">
                            To become the leading global platform for property management and hosting, enabling seamless connections between hosts, guests, and investors.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Our Values</h2>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Transparency -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Transparency</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We believe in complete transparency in our operations and communications.
                        </p>
                    </div>
                </div>

                <!-- Innovation -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Innovation</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We continuously innovate to provide cutting-edge solutions for our users.
                        </p>
                    </div>
                </div>

                <!-- Excellence -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Excellence</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We strive for excellence in customer service and user experience.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                <span class="block">Ready to experience the difference?</span>
                <span class="block text-indigo-600">Start your journey with HabibiStay today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 