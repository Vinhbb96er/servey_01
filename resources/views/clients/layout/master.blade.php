@include('clients.layout.header')
<div class="main-content">
    @yield('content')
</div>
@if (!Auth::guard()->check())
    @include('clients.user.auth.register')
    @include('clients.user.auth.login')
    @include('clients.user.auth.forgot-password')
@endif
@include('clients.feedback.create')
@include('clients.layout.footer')
