<template>
<div>
    <div class="flex w-full items-center md:flex-row flex-col justify-between space-x-0 space-y-2 md:space-y-0 md:space-x-3 p-3 bg-gray-100 rounded-md">
        <div :class="is_importing ? 'opacity-50 w-full' : 'w-full'">
            <label for="xml-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 ">
                <span class="w-full md:w-1/3 xl:w-2/5 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <span class="mx-auto" v-text="is_importing == true ? 'Importing...' : 'Import'"></span>
                </span>
                <input v-if="is_importing != true" @change="uploadXmlFile" id="xml-upload" accept=".xml" name="xml-upload" type="file" class="sr-only">
            </label>
        </div>
        <div class="flex w-full md:w-2/3 xl:w-3/5 text-center items-center justify-center md:flex-row flex-col space-x-0 space-y-2 md:space-y-0 md:space-x-2">
            <button :disabled="employees.length == 0" @click="exportShowingData()" type="button" :class="[btnClass, 'w-full text-center', employees.length == 0 ? 'opacity-50 cursor-not-allowed' : '']">
                <span class="mx-auto">Export Showing</span>
            </button>
            <button :disabled="employees.length == 0" @click="exportAllData()" type="button" :class="[btnClass, 'w-full', employees.length == 0 ? 'opacity-50 cursor-not-allowed' : '']">
                <span class="mx-auto">Export All</span>
            </button>
        </div>
    </div>

    <div v-if="employees.length > 0">
        <div class="flex flex-col mt-3">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Birthdate
                                    </th>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        position
                                    </th>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pay Type
                                    </th>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Salary
                                    </th>
                                    <th nowrap scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>

                                    <th nowrap scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(employee, index) in employees" :key="'employee_' + index" class="bg-white">
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" v-text="employee.name"></td>
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" v-text="employee.birthdate"></td>
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" v-text="employee.position"></td>
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" v-text="employee.pay_type"></td>
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" v-text="employee.salary"></td>
                                    <td nowrap class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a :href="departmentSlug == 'any'? '/employee/' +employee.department.slug: '#'" class="text-indigo-600 hover:text-indigo-900" v-text="employee.department.name"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white py-3 flex items-center justify-between border-t border-gray-200">
            <div class="sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <div>
                        <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" v-model="per_page">
                            <option>select </option>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button class="cursor-pointer" @click="paginate(link.url, index)"
                          :class="{'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active == true, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': link.active != true, 'relative inline-flex items-center px-2 py-2 rounded-sm border text-sm font-medium': true, 'cursor-not-allowed opacity-50': link.url == null }"
                          v-for="(link, index) in links">
                            <span v-if="link.hasOwnProperty('is_loading')">
                                <svg class="animate-spin h-3 w-3 text-black mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg></span>
                            <span v-else class="" v-html="link.label"></span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="is_empty == true" class="flex flex-col items-center justify-center mt-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-md font-thin text-gray-700">Oops, There is no employees!</span>
    </div>
    <div v-else class="mx-auto mt-40">
        <svg class="animate-spin h-5 w-5 text-black mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            btnClass: "inline-flex text-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",
            employees: [],
            links: [],
            per_page: 10,
            current_page: 1,
            is_empty: false,
            current_url: "",
            is_init_page_loading: true,
            is_loading: false,
            is_importing: false,
        };
    },
    props: {
        departmentSlug: {
            type: String,
            default: "any"
        }
    },
    mounted() {
        const page = new URL(location.href).searchParams.get("page") || 1;
        const url = "/employee/data?page=" + page;
        this.getEmployees(url);
    },
    methods: {
        getEmployees: function(url) {
            if (!url || this.is_loading == true) {
                return;
            }
            this.is_loading = true;
            const apiRequestUrl = url + "&per_page=" + this.per_page + "&department_slug=" + this.departmentSlug;
            this.current_url = apiRequestUrl;
            axios
                .get(apiRequestUrl)
                .then(response => {
                    this.is_init_page_loading = false;
                    if (response.data.employees.length > 0) {
                        this.is_empty = true.false;
                        this.employees = response.data.employees;
                        this.links = response.data.meta.links;
                        this.current_page = response.data.meta.current_page;
                    } else {
                        this.is_empty = true;
                    }
                })
                .catch(error => {
                  //  console.log("catch: ", error);
                    // we can handle error
                })
                .then(() => {
                    this.is_loading = false;
                });
        },
        paginate: function(url, index) {
            if (url) {
                Vue.set(this.links, index, {
                    url: null,
                    label: "",
                    active: false,
                    is_loading: true
                });
                this.getEmployees(url);
            }
        },
        exportShowingData: function() {
            window.open("/employee/export/range?page=" + this.current_page + "&per_page=" + this.per_page + "&department_slug=" + this.departmentSlug);

        },
        exportAllData: function() {
            window.open("/employee/export/all")
        },
        uploadXmlFile(event) {
            if (this.is_importing == true) {
                return
            }
            this.is_importing = true
            let file = event.target.files[0];
            const apiRequestUrl = "/employee/import/xml"
            var formData = new FormData();
            formData.append("xml_file", file);
            axios.post(apiRequestUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    const status = response.data.status
                    if (status == "success") {
                        // const employees = response.data.employees
                        location.reload();
                    } else {
                        // handle errors
                        alert("Somthing went wrong!")
                    }
                })
                .catch(error => {
                    // handle errors
                    // console.log("file response: ", error)
                }).then(() => {
                    this.is_importing = false
                })
        }
    },
    watch: {
        per_page: function() {
            const url = "/employee/data?page=1";
            this.getEmployees(url);
        }
    }
};
</script>
