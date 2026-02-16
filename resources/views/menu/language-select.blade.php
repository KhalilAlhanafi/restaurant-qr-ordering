<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Language - Choose Your Language</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');
    </style>
</head>
<body class="bg-gradient-to-br from-orange-500 via-red-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Welcome / مرحباً</h1>
                <p class="text-gray-400 text-sm">Table {{ $table->table_number }} / طاولة {{ $table->table_number }}</p>
            </div>

            <!-- Language Options -->
            <div class="p-8">
                <p class="text-center text-gray-600 mb-6 text-sm">Please select your preferred language<br>الرجاء اختيار لغتك المفضلة</p>

                <div class="space-y-4">
                    <!-- English Option -->
                    <a href="{{ route('language.set', ['locale' => 'en', 'redirect' => 'menu']) }}" 
                       class="group flex items-center p-5 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                            <span class="text-2xl font-bold text-blue-600">EN</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition-colors">English</h3>
                            <p class="text-sm text-gray-500">Continue in English</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Arabic Option -->
                    <a href="{{ route('language.set', ['locale' => 'ar', 'redirect' => 'menu']) }}" 
                       class="group flex items-center p-5 border-2 border-gray-200 rounded-2xl hover:border-green-500 hover:bg-green-50 transition-all duration-300">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-green-200 transition-colors">
                            <span class="text-2xl font-bold text-green-600">ع</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition-colors" style="font-family: 'Cairo', sans-serif;">العربية</h3>
                            <p class="text-sm text-gray-500" style="font-family: 'Cairo', sans-serif;">الاستمرار باللغة العربية</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-4 text-center border-t">
                <p class="text-xs text-gray-400">{{ config('app.name', 'Restaurant') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
