@include('admin.layouts.head')


@include('admin.layouts.sidebar')

<!-- Start right Content here -->

@include('admin.layouts.header')

@yield('content')

@include('admin.layouts.footer')

@include('admin.layouts.script')