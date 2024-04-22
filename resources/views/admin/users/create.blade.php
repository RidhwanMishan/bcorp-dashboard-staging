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

                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
                            Password <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="password" type="password" name="password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-500 @enderror" />
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!--div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
                            Power BI Email <span class="text-xs font-small text-red-600">*</span>
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input  id="power_bi_email" type="text" name="power_bi_email" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('power_bi_email') border-red-500 @enderror" />
                        </div>
                        @error('power_bi_email')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div-->
                  
                    <div class="sm:col-span-4">
                        <label for="landingPage" class="block text-sm font-medium leading-5 text-gray-700">
                            Landing Page <span class="text-xs font-small text-red-600">* REQUIRED</span>
                        </label>

                        <div class="mt-1 rounded-md shadow-sm">
                            <select name="landing_page" id="landing_page" class="form-select flex justify-center w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option value=""> </option>
                                <option value="admin.users.index" >admin.index</option>

                                <option value="berjaya" >berjaya</option>
                                <option value="hospitality" >hospitality</option>
                                <option value="property" >property</option>
                                <option value="retail" >retail</option>
                                <option value="services" >service</option>
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
            
                        <fieldset class="ml-8 ">
                            <legend class="text-base font-medium text-gray-900">
                                Vertical
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="berjaya" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'berjaya')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="berjaya" class="font-medium text-gray-700">Berjaya Group</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="hospitality" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'hospitality_berjaya')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="hospitality" class="font-medium text-gray-700">Hospitality Berjaya</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="property" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'property_berjaya')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="property" class="font-medium text-gray-700">Property Berjaya</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="retail_berjaya" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'retail_berjaya')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="retail_berjaya" class="font-medium text-gray-700">Retail Berjaya</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                        <div class="absolute flex items-center h-5">
                                            <input  id="service" name="roles[]"   type="checkbox" value="{{ $roles->where('name', 'service_berjaya')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="pl-6 text-sm leading-5">
                                            <label for="service" class="font-medium text-gray-700">Service Berjaya</label>
                                        </div>
                                    </div>
                    
        
                            </div>
                        </fieldset>

                        <fieldset class="ml-8">
                            <legend class="text-base font-medium text-gray-900">
                                Retail
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'retail.nonfood_cosway')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Cosway MY</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'retail.nonfood_coswaytw')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Cosway TW</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'retail.nonfood_coswayhk')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Cosway HK</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'retail.food_countryfarms')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Country Farms</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="ml-8">
                            <legend class="text-base font-medium text-gray-900">
                                Services
                            </legend>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.env_enviro_holdings')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Enviro Holdings</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.env_enviroparks')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">EnviroParks</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.logi_secure')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">Secure Express</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.digi_redtone')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">REDtone</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.digi_nist')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">NIST</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.digi_nis')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">NIS</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.digi_bloyalty')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">BLoyalty</label>
                                    </div>
                                </div>
                                <div class="relative flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input  id="admin" name="roles[]" type="checkbox" value="{{ $roles->where('name', 'services.digi_bloyaltyltd')->first()->id
 }}" class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out"  />
                                    </div>
                                    <div class="pl-6 text-sm leading-5">
                                        <label for="admin" class="font-medium text-gray-700">BLoyalty Ltd</label>
                                    </div>
                                </div>
                            </div>
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
