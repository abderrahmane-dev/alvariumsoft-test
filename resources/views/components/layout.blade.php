<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   <title>{{ config("app.name") }}</title>
</head>

<body>
   <div class="min-h-screen bg-white">
      <nav class="bg-white border-b border-gray-200">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
               <div class="flex">
                  <div class="flex-shrink-0 flex items-center">
                     <h1 class="text-xl font-black">{{ config("app.name") }}</h1>
                  </div>
                  <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                     <a href="{{ Route::currentRouteName() == "employee.view" || Route::currentRouteName() == "employee.by_department" ? "#" : route("employee.view") }}"
                        class="{{ Route::currentRouteName() == "employee.view" || Route::currentRouteName() == "employee.by_department" ? "border-indigo-500 text-gray-900" : "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                        aria-current="page">
                        Employees
                     </a>
                     <a href="{{ Route::currentRouteName() == "department.view" ? "#" :  route("department.view") }}"
                        class="{{ Route::currentRouteName() == "department.view" ? "border-indigo-500 text-gray-900" : "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                        aria-current="page">
                        Departments
                     </a>
                  </div>
               </div>
            </div>
         </div>

         <div class="sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">


               <a href="{{ Route::currentRouteName() == "employee.view" || Route::currentRouteName() == "employee.by_department" ? "#" : route("employee.view") }}"
                  class="{{ Route::currentRouteName() == "employee.view"  || Route::currentRouteName() == "employee.by_department" ? "bg-indigo-50 border-indigo-500 text-indigo-700" : "border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800"}} block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                  aria-current="page">
                  Employees
               </a>
               <a href="{{ Route::currentRouteName() == "department.view" ? "#" :  route("department.view") }}"
                  class="{{ Route::currentRouteName() == "department.view" ? "bg-indigo-50 border-indigo-500 text-indigo-700" : "border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800"}}bg-indigo-50 border-indigo-500 text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                  aria-current="page">
                  Departments
               </a>
            </div>
         </div>
      </nav>

      <div>
         <main>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <div class="px-4 py-8 sm:px-0">
                  {{ $slot }}
               </div>
            </div>
         </main>
      </div>
   </div>
   <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>