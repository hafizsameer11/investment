@include('admin.layouts.head')


@include('admin.layouts.sidebar')

<!-- Start right Content here -->

@include('admin.layouts.header')

@yield('content')

@include('admin.layouts.footer')

<!-- Admin Chat Widget -->
@include('components.admin-chat-widget')

@include('admin.layouts.script')
<script src="{{ asset('assets/dashboard/js/admin-chat.js') }}"></script>