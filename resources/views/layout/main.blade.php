<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen bg-gradient-to-r from-sky-900 via-blue-950 to-slate-900 flex items-center justify-center">
    <div class="bg-blue-100 rounded-lg shadow-lg w-[95vw]  m-[4vh] p-6">

        <div class="flex h-full">

            <div class="w-1/5 bg-white rounded-2xl shadow-lg p-4 mr-4">
                <aside id="default-sidebar" class=" top-0 left-0 z-40  h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
                    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 ">
                        <div class="relative flex items-center justify-between p-4 mb-6 rounded-2xl bg-white/60 backdrop-blur-lg shadow-xl border border-white/30">
                            <div class="flex items-center gap-4">
                                <img src="data:image/png;base64,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" alt="external-user-web-flaticons-flat-flat-icons-2">
                                <div class="text-right">
                                    <h3 class="text-gray-800 font-bold text-sm">علی حسینی</h3>
                                    <p class="text-xs text-gray-500">مدیر سیستم</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <button id="notifBtn" class="relative group hover:text-blue-600 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white animate-ping"></span>
                                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">اعلان‌ها</span>
                                    </button>

                                    <div id="notifDropdown" class="hidden absolute left-1/2 -translate-x-1/2 mt-3 w-72 max-h-64 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                        <ul class="text-sm divide-y divide-gray-100">
                                            <li class="p-3 hover:bg-gray-50 flex gap-3">
                                                <span class="text-blue-500 text-lg">🔔</span>
                                                <div>
                                                    <p class="font-medium text-gray-800">کاربر جدید ثبت‌نام کرد</p>
                                                    <p class="text-xs text-gray-500">۲ دقیقه پیش</p>
                                                </div>
                                            </li>
                                            <li class="p-3 hover:bg-gray-50 flex gap-3">
                                                <span class="text-green-500 text-lg">✅</span>
                                                <div>
                                                    <p class="font-medium text-gray-800">پرداخت جدید دریافت شد</p>
                                                    <p class="text-xs text-gray-500">۵ دقیقه پیش</p>
                                                </div>
                                            </li>
                                            <li class="p-3 hover:bg-gray-50 flex gap-3">
                                                <span class="text-yellow-500 text-lg">⚠️</span>
                                                <div>
                                                    <p class="font-medium text-gray-800">درخواست پشتیبانی ثبت شد</p>
                                                    <p class="text-xs text-gray-500">۱۰ دقیقه پیش</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button class="relative group hover:text-red-500 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                    </svg>
                                    <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">خروج</span>
                                </button>
                            </div>
                        </div>

                        <ul class="space-y-4 font-medium">

                            <li>
                                <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 22 21" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                                    </svg>
                                    <span class="ms-3">داشبورد</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                                    <span class="ms-3">مدیریت کاربران</span>
                                </a>
                            </li>
                            <li>
                                <button
                                    class="flex items-center w-full p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group focus:outline-none"
                                    aria-expanded="false"
                                    aria-controls="submenu-1"
                                    id="menu-button-1"
                                    onclick="toggleSubmenu('submenu-1', this)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="flex-1 ms-3 text-right">تنظیمات</span>

                                    <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-300 transform group-[aria-expanded='true']:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul id="submenu-1" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out ps-8 mt-2 space-y-2" aria-labelledby="menu-button-1">
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست کالاها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست انواع بارگیرها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست بسته‌بندی</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست شرکت‌های بیمه</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <button
                                    class="flex items-center w-full p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group focus:outline-none"
                                    aria-expanded="false"
                                    aria-controls="submenu-2"
                                    id="menu-button-2"
                                    onclick="toggleSubmenu('submenu-2', this)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus-icon lucide-package-plus"><path d="M16 16h6"/><path d="M19 13v6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>

                                    <span class="flex-1 ms-3 text-right">اعلام بار</span>
                                    <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-300 transform group-[aria-expanded='true']:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>

                                </button>
                                <ul id="submenu-2" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out ps-8 mt-2 space-y-2" aria-labelledby="menu-button-2">
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست بار</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">مناقصه ی بار </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">رزرو بار </span>
                                        </a>
                                    </li>

                                </ul>
                            </li>


                            <li>
                                <button
                                    class="flex items-center w-full p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group focus:outline-none"
                                    aria-expanded="false"
                                    aria-controls="submenu-3"
                                    id="menu-button-3"
                                    onclick="toggleSubmenu('submenu-3', this)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package2-icon lucide-package-2"><path d="M12 3v6"/><path d="M16.76 3a2 2 0 0 1 1.8 1.1l2.23 4.479a2 2 0 0 1 .21.891V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.472a2 2 0 0 1 .211-.894L5.45 4.1A2 2 0 0 1 7.24 3z"/><path d="M3.054 9.013h17.893"/></svg>

                                    <span class="flex-1 ms-3 text-right">تحویل بار</span>
                                    <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-300 transform group-[aria-expanded='true']:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul id="submenu-3" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out ps-8 mt-2 space-y-2" aria-labelledby="menu-button-2">
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">لیست بار</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">بار های ازاد </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">رزرو های رانندگان</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">حواله شده ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">بارنامه شده ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 text-gray-700 hover:text-gray-900 hover:bg-blue-200 focus:bg-blue-300 rounded-lg focus:outline-none">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                            <span class="ms-2">تحویل شده ها</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck-icon lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>

                                    <span class="ms-3">مدیریت مکانیزم ها</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card-lanyard-icon lucide-id-card-lanyard"><path d="M13.5 8h-3"/><path d="m15 2-1 2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3"/><path d="M16.899 22A5 5 0 0 0 7.1 22"/><path d="m9 2 3 6"/><circle cx="12" cy="15" r="3"/></svg>
                                    <span class="ms-3">مدیریت رانندگان</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-blue-200 focus:bg-blue-300 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="ms-3">مدیریت شکایات</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>

            <div class="flex-1 flex flex-col space-y-6 p-8" >

                <div class="text-right text-3xl font-semibold text-blue-950 m-1">
                    @yield('header')
                </div>
                @yield('content')





            </div>

        </div>
    </div>
</div>


</body>
<script>
    document.querySelectorAll("button[aria-expanded]").forEach(button => {
        button.addEventListener("click", () => {
            const submenu = document.getElementById(button.getAttribute("aria-controls"));

            const isOpen = button.getAttribute("aria-expanded") === "true";

            if (isOpen) {
                submenu.style.maxHeight = null;
                button.setAttribute("aria-expanded", "false");
                button.querySelector("svg:last-child").style.transform = "rotate(0deg)";
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                button.setAttribute("aria-expanded", "true");
                button.querySelector("svg:last-child").style.transform = "rotate(180deg)";
            }
        });
    });
</script>
<script>
    const notifBtn = document.getElementById("notifBtn");
    const notifDropdown = document.getElementById("notifDropdown");

    notifBtn.addEventListener("click", () => {
        notifDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add("hidden");
        }
    });
</script>
    @yield('scripts')

</html>
