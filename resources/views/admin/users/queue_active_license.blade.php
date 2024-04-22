@extends('admin.app')

@section('title')All Users @endsection

@section('adminContent')
    <div>
        <div class="flex justify-end">
            <a href="{{ route('admin.users.index') }}" class=" text-gray-700"><div class="mt-8 text-right mt-2 xl:ml-2 xxl:ml-6 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 xl:py-1 xxl:py-2 xxxl:py-4 xxll:py-6 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold uppercase hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 ">Back</div></a>

        </div>
        <h2 class="mt-4 text-center text-3xl mb-2 font-heading font-semibold" >Tableau Active Viewer License</h2>
    </div>

    <!-- <h3 class="mt-8 flex justify-center text-lg leading-6 font-medium text-gray-700">
        Job Queue
    </h3>
    <div class="flex flex-col">
    <div class="mx-32">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border border-yellow-400 sm:rounded-md">
        <div class="overflow-y-auto scrollbar-w-2 scrollbar-track-gray-lighter scrollbar-thumb-rounded scrollbar-thumb-gray scrolling-touch max-h-screen">
            <table class="table-fixed relative min-w-full divide-y divide-gray-200">
            <thead class="">
                <tr>
                    <th class="bg-gray-200 sticky top-0 px-6 py-3 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                        <div>Queue Jobs</div> 
                    </th>
                    <th class="bg-gray-200 sticky top-0 px-6 py-3 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                        <div>Attempts</div> 
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            </table>
        </div>
        </div>
        </div>
    </div>
    </div> -->

    <!-- @if (session('success'))
        <div class="bg-green-100 mx-24 border rounded-md border-green-500 text-green-700 px-4 py-3" role="alert">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 mx-24 border rounded-md border-red-500 text-red-700 px-4 py-3" role="alert">
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif -->

    <h3 class="mt-8 flex justify-center text-lg leading-6 font-medium text-gray-700">
        View Active License Users
    </h3>
    <div class="flex flex-col">
    <div class=" mx-32 ">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border border-yellow-400 sm:rounded-md">
        <div class="overflow-y-auto scrollbar-w-2 scrollbar-track-gray-lighter scrollbar-thumb-rounded scrollbar-thumb-gray scrolling-touch max-h-screen">
            <table class="table-fixed relative min-w-full divide-y divide-gray-200">
            <thead class="">
                <tr>
                    <th class="bg-gray-200 sticky top-0 px-6 py-3 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                        <div>Email</div> 
                    </th>
                    <th class="bg-gray-200 sticky top-0 px-6 py-3 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                        <div>Site Role (Last Login)</div> 
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($xml_auth_users_users as $users)
                <tr class="bg-white">
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                        {{ $users['name'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                        
                        @if ($users['lastLogin'] == '')
                            {{ $users['siteRole'] }} 
                         @else
                            {{ $users['siteRole'] }} ({{ $users['lastLogin'] }})
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
        </div>
    </div>
    </div>
    </div>
@endsection
