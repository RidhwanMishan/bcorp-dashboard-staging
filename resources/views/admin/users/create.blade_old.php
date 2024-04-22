@extends('admin.app')

@section('title')Edit User @endsection

@section('adminContent')
    @if (session('error'))
        <div class="mt-8 bg-red-100 mx-24 border rounded-md border-red-500 text-red-700 px-4 py-3" role="alert">
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif
    <form class="px-16" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf
        <div >
            <div class="mt-1 pt-1">
                <h3 class="flex justify-center text-lg leading-6 font-medium text-gray-900">
                    Add User
                </h3>
{{--                <div class="mt-8 justify-center sm:col-span-6">--}}
{{--                    <label for="photo" class="flex justify-center text-sm leading-5 font-medium text-gray-700">--}}
{{--                        Photo--}}
{{--                    </label>--}}
{{--                    <div class=" items-center">--}}
{{--                        <div class="mt-4 flex justify-center">--}}
{{--                            <img class="inline-block h-16 w-16 rounded-full" src="{{ asset('images/profile.jpg') }}" alt="" />--}}
{{--                        </div>--}}
{{--                        <div class="flex justify-center">--}}
{{--                            <label class="cursor-pointer mt-6">--}}
{{--                                <span class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out" >Upload</span>--}}
{{--                                <input  type='file' class="hidden" name="profilePic" :multiple="multiple" :accept="accept" />--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="mt-8" >--}}
{{--                    <h3 class="flex justify-center text-lg leading-6 font-medium text-gray-900">--}}
{{--                        Information for {{ $user->name }}--}}
{{--                    </h3>--}}
{{--                </div>--}}
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-8">
                    <div class="sm:col-span-4">
                        <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700">
                            Full Name <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="name" type="text" name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('name') border-red-500 @enderror" />
                        </div>
                        @error('name')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                            Email address <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="email" type="email" name="email" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-500 @enderror" />
                        </div>
                        @error('email')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
{{--                    <div class="sm:col-span-4">--}}
{{--                        <label for="password" class="block text-sm font-medium leading-5 text-gray-700">--}}
{{--                            Password <span class="text-xs font-small text-red-600">*</span>--}}
{{--                        </label>--}}
{{--                        <div class="mt-1 rounded-md shadow-sm">--}}
{{--                            <input  id="password" type="password" name="password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-500 @enderror" />--}}
{{--                        </div>--}}
{{--                        @error('password')--}}
{{--                        <p class="text-red-500 text-xs italic mt-4">--}}
{{--                            {{ $message }}--}}
{{--                        </p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
                            Title <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="title" type="text" name="designation" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('designation') border-red-500 @enderror" />
                        </div>
                        @error('designation')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div class="sm:col-span-4">
                        <label for="department" class="block text-sm font-medium leading-5 text-gray-700">
                            Department <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="department" type="text" name="department" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('department') border-red-500 @enderror" />
                        </div>
                        @error('department')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div class="sm:col-span-4">
                        <label for="division" class="block text-sm font-medium leading-5 text-gray-700">
                            Division <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="division" type="text" name="division" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('division') border-red-500 @enderror" />
                        </div>
                        @error('division')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div class="sm:col-span-4">
                        <label for="landingPage" class="block text-sm font-medium leading-5 text-gray-700">
                            Landing Page <span class="text-xs font-small text-red-600">* REQUIRED</span>
                        </label>
{{--                        <div class="mt-1 rounded-md shadow-sm">--}}
{{--                            <input  value="{{ $user->landing_page }}" id="landingPage" type="text" name="landing_page" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('landing_page') border-red-500 @enderror" />--}}
{{--                        </div>--}}
{{--                        @error('landing_page')--}}
{{--                        <p class="text-red-500 text-xs italic mt-4">--}}
{{--                            {{ $message }}--}}
{{--                        </p>--}}
{{--                        @enderror--}}
                        <div class="mt-1 rounded-md shadow-sm">
                            <select name="landing_page" id="landing_page" class="form-select flex justify-center w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option value=""> </option>
                                <option value="admin.users.index" >admin.index</option>
                                <option value="main.ceo" >main.ceo</option>
                                <option value="people.overview" >people.overview</option>
                                <option value="people.attendance.tv" >people.attendance</option>
                                <option value="people.absenteeism" >people.absenteeism</option>
                                <option value="people.leaves.tv" >people.leaves</option>
                                <!-- <option value="people.overtime" >people.overtime</option> -->
                                <option value="people.expenses.overall" >people.expenses</option>
                                <option value="people.behaviour.overall" >people.behaviour</option>
                                <option value="people.labour" >people.labour.hours</option>

                                <option value="sale.overview" >sale.overview</option>
                                <option value="sale.management" >sale.management</option>
                                <option value="sale.renewal" >sale.renewal</option>
                                <option value="sale.asp" >sale.asp</option>
                                <option value="sale.araging" >sale.araging</option>
                                <option value="sale.supply" >sale.supply.demand</option>
                                <option value="sale.fulfilment" >sale.order.fulfilment</option>
                                <option value="sale.sales" >sale.sales.return</option>

                                <option value="finance.overview" >finance.overview</option>
                                <option value="finance.operations" >finance.operations</option>
                                <option value="finance.araging" >finance.araging</option>

                                <option value="procurement.overview" >procurement.overview</option>
                                
                                <option value="operations.labour" >operations.labour.hours</option>
                                <option value="operations.contracted" >operations.contracted.incoming.sales</option>
                                <option value="operations.production" >operations.production.control</option>
                                <option value="operations.supply" >operations.supply.demand</option>
                                <option value="operations.fulfilment" >operations.order.fulfilment</option>
                                <option value="operations.sales" >operations.sales.return</option>
                                <!-- <option value="supply.overview" >supply.overview</option> -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8 ">
                <div>
                    <h3 class="flex justify-center text-lg leading-6 font-medium text-gray-900">
                        Authorization
                    </h3>
                    <p class="flex justify-center mt-1 text-sm leading-5 text-gray-500">
                        User access availability
                    </p>
                </div>

                <div class="mt-6">
                    <div class="flex justify-center">
                        <fieldset class="">
                            <legend class="text-base font-medium text-gray-900">
                                Admin
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'admin')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Admin Page</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ml-8">
                            <legend class="text-base font-medium text-gray-900">
                                Main Dashboard
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="mainOverview" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'main_ceo')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="mainOverview" class="font-medium text-gray-700">Main Overview</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                People Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="peopleOverview" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'people_overview')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="peopleOverview" class="font-medium text-gray-700">Overview</label>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleAttendance" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_attendance')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleAttendance" class="font-medium text-gray-700">Attendance</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleAbsenteeism" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_absenteeism')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleAbsenteeism" class="font-medium text-gray-700">Absenteeism</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleLeaves" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_leaves')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleLeaves" class="font-medium text-gray-700">Leaves</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleOvertime" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_overtime')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out cursor-not-allowed" disabled />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleOvertime" class="font-medium text-red-700">Overtime</label>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleExpenses" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_expenses')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleExpenses" class="font-medium text-gray-700">Expenses</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleBehavior" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_behavior')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleBehavior" class="font-medium text-gray-700">Behavior</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleLabour" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_labour')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleLabour" class="font-medium text-gray-700">Labour Hours</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="peopleUpload" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'people_upload')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="peopleUpload" class="font-medium text-gray-700">Excel Upload</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                Sales Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="salesOverview" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'sales_overview')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="salesOverview" class="font-medium text-gray-700">Overview</label>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesManagement" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'sales_management')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesManagement" class="font-medium text-gray-700">Management</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesContractRenewal" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'sales_renewal')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesContractRenewal" class="font-medium text-gray-700">Contract Renewal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesCustomerProfitabilityManagement" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'sales_asp')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesCustomerProfitabilityManagement" class="font-medium text-gray-700">Customer ASP Management</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesAraging" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'sales_araging')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesAraging" class="font-medium text-gray-700">AR Aging</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesSupply" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'sales_supply_demand')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" disabled />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesSupply" class="font-medium text-gray-700">Supply & Demand Balancing</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesFulfilment" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'sales_order_fulfilment')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" disabled />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesFulfilment" class="font-medium text-gray-700">Order Fulfilment</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesReturn" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'sales_sales_return')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" disabled />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesReturn" class="font-medium text-gray-700">Sales Return</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="salesUpload" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'sales_upload')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" disabled />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="salesUpload" class="font-medium text-gray-700">Excel Upload</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                Finance Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="financeOverview" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'finance_overview')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="financeOverview" class="font-medium text-gray-700">Overview</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="financeOperation" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'finance_operations')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out cursor-not-allowed" disabled />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="financeOperation" class="font-medium text-red-700">Operation</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="financeAraging" name="roles[]"    type="checkbox" value="{{ $roles->where('name', 'finance_araging')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="financeAraging" class="font-medium text-gray-700">AR Aging</label>
                                    </div>
                                </div>
                            </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="financeUpload" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'finance_upload')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" disabled/>
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="financeUpload" class="font-medium text-gray-700">Excel Upload</label>
                                        </div>
                                    </div>
                                </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                Procurement Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="procurementOverview" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'procurement_overview')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out cursor-not-allowed" disabled />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="procurementOverview" class="font-medium text-red-700">Overview</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                Operation Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationLabour" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_labour')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationLabour" class="font-medium text-gray-700">Labour Hours</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationContracted" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_contracted')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationContracted" class="font-medium text-gray-700">Contracted Incoming Sales</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationProduction" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_production_control')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationProduction" class="font-medium text-gray-700">Production Control</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationSupply" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_supply_demand')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationSupply" class="font-medium text-gray-700">Supply Demand</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationFulfilment" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_order_fulfilment')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationFulfilment" class="font-medium text-gray-700">Order Fulfilment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="operationSales" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_sales_return')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="operationSales" class="font-medium text-gray-700">Sales Return</label>
                                    </div>
                                </div>
                            </div>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="operationUpload" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'operation_upload')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="operationUpload" class="font-medium text-gray-700">Excel Upload</label>
                                        </div>
                                    </div>
                                </div>
                        </fieldset>
                        <fieldset class="ml-8 ">
                            <!-- <legend class="text-base font-medium text-gray-900">
                                Supply Chain Division
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="supplyOverview" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'supply_overview')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out cursor-not-allowed" disabled />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="supplyOverview" class="font-medium text-red-700">Overview</label>
                                    </div>
                                </div>
                            </div> -->
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 mb-4 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
                <span class="ml-3 inline-flex rounded-md shadow-sm">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
              Back
            </a>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-red-600 ml-4 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">
          Add
        </button>
      </span>
            </div>
        </div>


    </form>



@endsection
