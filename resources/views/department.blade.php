<x-layout>
   <div id="app" class="">
      <!-- This example requires Tailwind CSS v2.0+ -->
      <div class="flex flex-col">
         <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
               <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                        <tr>
                           <th scope="col"
                              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                              Name
                           </th>
                           <th scope="col"
                              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                              Slug
                           </th>
                           <th scope="col"
                              class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                              Created at
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse ($departments as $department)
                        <tr class="bg-white">
                           <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                              <a href="{{ route("employee.by_department", ["department_slug" => $department->slug]) }}"
                                 class="text-indigo-600 hover:text-indigo-900">{{ $department->name }}</a>
                           </td>

                           <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              {{ $department->slug }}
                           </td>
                           <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                              {{ $department->created_at }} UTC
                           </td>
                        </tr>
                        @empty
                        <div class="flex flex-col items-center justify-center mb-6">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-200" fill="none"
                           viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                           </svg>
                           <span class="text-md font-thin text-gray-700">Oops, There is no departments!</span>
                        </div>
                        @endforelse
                     </tbody>
                  </table>
                  @if($departments->hasPages())
                  <div class="px-3 py-2">
                     {{ $departments->links() }}
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>

   </div>
</x-layout>
