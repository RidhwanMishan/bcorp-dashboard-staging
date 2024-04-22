@extends('admin.app')

@section('title')All Users @endsection

@section('adminContent')
    <div class="-my-2 py-2 overflow-x-auto sm:mx-2 sm:px-2 lg:mx-8 lg:px-4">
      <div class="flex justify-end">
          <a href="{{ route('berjaya') }}" class=" text-gray-700"><div class="mt-8 text-right mt-2 xl:ml-2 xxl:ml-6 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 xl:py-1 xxl:py-2 xxxl:py-4 xxll:py-6 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold uppercase hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 ">Dashboard</div></a>
          <a href="{{ route('signout') }}" class=" text-gray-700" onclick="event.preventDefault();
                                  document.getElementById('logout-form-big').submit();"><div class="mt-8 text-right mt-2 xl:ml-2 xxl:ml-6 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 xl:py-1 xxl:py-2 xxxl:py-4 xxll:py-6 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold uppercase hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 ">Log Out</div></a>
          <form id="logout-form-big" action="{{ route('signout') }}" method="GET" class="hidden">
              {{ csrf_field() }}
          </form>
      </div>
      <h2 class="mt-4 text-center text-3xl mb-2 font-heading font-semibold" >Admin functions</h2>

    </div>

    <div class="flex mt-4 mx-16 mb-8 justify-around">
        <div class="w-96 xxxl:w-120  px-4 mb-6 lg:mb-0">
            <a href="{{ route('admin.users.create') }}"><div class="h-full">
                    <div class="text-center p-4 mb-2 bg-gray-600 text-white rounded">
                        <h3 class="text-3xl leading-tight font-heading font-semibold">Register</h3>
                        <span class="leading-none" data-config-id="03-col4-header">For new user</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- <div class="w-96 xxxl:w-120  px-4 mb-6 lg:mb-0">
            <a href="#"><div class="h-full">
                    <div class="text-center p-4 mb-2 bg-indigo-600 text-white rounded">
                        <h3 class="text-3xl leading-tight font-heading font-semibold">Power BI</h3>
                        <span class="leading-none" data-config-id="03-col4-header">Power BI active license</span>
                    </div>
                </div>
            </a>
        </div> -->

    </div>

    @if (session('success'))
        <div class="bg-green-100 mx-24 border rounded-md border-green-500 text-green-700 px-4 py-3" role="alert">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 mx-24 border rounded-md border-red-500 text-red-700 px-4 py-3" role="alert">
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <h3 class="mt-8 flex justify-center text-lg leading-6 font-medium text-gray-700">
        View All Users
    </h3>
    <div class="mt-8 mb-8 flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:mx-2 sm:px-2 lg:mx-8 lg:px-4">
            
            <!-- SEARCH FORM -->
            <form method="GET" id="search_form" class="user-search"><input type="text" id="name" name="name" value="{{ !empty($_GET['name']) ? $_GET['name'] : '' }}" placeholder="Search name and press ENTER"></form>
            <!-- SEARCH FORM END -->

            <div class="align-middle inline-block min-w-full overflow-hidden">

                @isset($users)
                    <table ref={tableRef} class="max-w-full overflow-x-auto" style="margin: 0 auto 30px;">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <!-- <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Landing Page
                        </th> -->
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Admin
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Dashboard Page Access
                        </th>
                        <!--
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Sales Access
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Finance Access
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Procurement Access
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Supply Access
                        </th>-
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Operation Access
                        </th>
                      -->
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>

                    <tbody class="bg-white">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <div class="flex items-center">
                                <!-- <a href="{{ route('admin.users.show', $user->id) }}">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ asset('storage/'.$user->id.'/profilePic.jpg') }}" alt="" />
                                    </div>
                                </a> -->
                                <div class="ml-4">
                                <a href="{{ route('admin.users.show', $user->id) }}"><div class="text-sm leading-5 font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm leading-5 text-gray-500">{{ $user->email }}</div>
                                    <!-- <div class="text-sm leading-5 font-medium text-gray-700">{{ $user->designation }}</div> -->
                                    </a>
                                </div>
                            </div>
                        </td>

            <!-- <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-gray-800">
                        {{ $user->landing_page }}
                      </span>
            </td> -->
            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800  bg-red-100 text-red-800 ">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'admin')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                    @if($user->roles->contains('name', 'admin')) yes @else no @endif
              </span>
            </td>

            <td class="px-6 py-4 flex-wrap border-b border-gray-200">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'berjaya')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'berjaya')) yes @else no @endif
                      </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'hospitality_berjaya')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'hospitality_berjaya')) yes @else no @endif
                      </span>
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'property_berjaya')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'property_berjaya')) yes @else no @endif
                      </span>
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'retail_berjaya')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'retail_berjaya')) yes @else no @endif
                      </span>
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'service_berjaya')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'service_berjaya')) yes @else no @endif
                      </span>

                <!--
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_absenteeism')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_absenteeism')) yes @else no @endif
                      </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_leaves')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->roles->contains('name', 'people_leaves')) yes @else no @endif
                      </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_overtime')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_overtime')) yes @else no @endif
                      </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_expenses')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_expenses')) yes @else no @endif
                      </span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_behavior')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_behavior')) yes @else no @endif
                      </span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_labour')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_labour')) yes @else no @endif
                      </span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($user->roles->contains('name', 'people_upload')) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                       @if($user->roles->contains('name', 'people_upload')) yes @else no @endif
                      </span>
                -->
            </td>

            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline">Edit</a>
            </td>

            </tr>
            @endforeach
            </tbody>
            </table>
                    {{ $users->links() }}
            @endisset
        </div>
    </div>
    </div>


    {{--    <div class="flex items-center">--}}
{{--        <div class="md:w-1/2 md:mx-auto">--}}

{{--            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">--}}

{{--                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">--}}
{{--                    Users--}}
{{--                </div>--}}

{{--                <div class="w-full p-6">--}}
{{--                    <p class="text-gray-700">--}}

{{--                    <div class="flex flex-col">--}}
{{--                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">--}}
{{--                            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">--}}
{{--                                <table class="min-w-full">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                            ID--}}
{{--                                        </th>--}}
{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                            Name--}}
{{--                                        </th>--}}
{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                            Email--}}
{{--                                        </th>--}}
{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                            Roles--}}
{{--                                        </th>--}}
{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                            Action--}}
{{--                                        </th>--}}

{{--                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody class="bg-white">--}}
{{--                                    @foreach($users as $user)--}}
{{--                                    <tr>--}}
{{--                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium text-gray-900">--}}
{{--                                            {{ $user->id }}--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">--}}
{{--                                            {{ $user->name }}--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">--}}
{{--                                            {{ $user->email }}--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">--}}
{{--                                            {{ implode(', ',$user->roles->pluck('name')->toArray()) }}--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">--}}
{{--                                            @can('edit-users')--}}
{{--                                            <a href="{{ route('admin.users.edit',$user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>--}}
{{--                                            @endcan--}}

{{--                                            @can('delete-users')--}}
{{--                                            <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST">--}}
{{--                                                @csrf--}}
{{--                                                {{ method_field('DELETE') }}--}}

{{--                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">--}}
{{--                                                    Delete--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
{{--                                            @endcan--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $('#name').bind("enterKey",function(e){
       $('#search_form').submit();
    });

    $('#name').keyup(function(e){
        if(e.keyCode == 13)
        {
            $(this).trigger("enterKey");
        }
    });
  });
</script>
@endsection