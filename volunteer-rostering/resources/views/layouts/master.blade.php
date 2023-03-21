<!DOCTYPE html>
@php
use App\Models\Company;
$company = Company::firstOrCreate([
    'id' => 1
]);
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $company->name }} - @yield('title')</title>

        @include('layouts.parts.foundation-includes')
        @include('layouts.parts.font-awesome-includes')
        @include('layouts.parts.full-calendar-includes')

        <script src="{{ env('APP_URL') }}/assets/app.js"></script>
        <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/app.css">

    </head>
    <body class="antialiased">
        @section('topbar')
            @if ($company->primary_colour != "")
            <div class="name-bar" style="background-color: {{ $company->primary_colour }}">
                <p class="name" style="color: {{ $company->secondary_colour }}"><strong>{{ $company->name }}</strong></p>
            </div>
            @endif
        <div class="top-bar" id="responsive-menu">
            <div class="top-bar-left">
                <ul class="dropdown menu" data-dropdown-menu>
                    <li class="menu-text"><a href="{{ env('APP_URL') }}/" style="all: unset;" class="clickable">{{ $company->name }}</a></li>
                    @auth
                        @if (auth()->user()->hasPermission(['Roster Clerk', 'Administrator']))
                    <li>
                        <a href="{{ env('APP_URL') }}/workingTT/list">Working Timetables</a>
                    </li>
                        @endif
                    <li>
                        <a href="{{ env('APP_URL') }}/personal-calendar">My Calendar</a>
                    </li>
                        @if (auth()->user()->hasPermission(['Training Officer', 'Administrator']))
                    <li>
                        <a href="{{ env('APP_URL') }}/qualification/list">Qualifications</a>
                    </li>
                        @endif
                        @if (auth()->user()->hasPermission(['Administrator']))
                    <li>
                        <a href="{{ env('APP_URL') }}/users">Users</a>
                    </li>
                        @endif
                        @if (auth()->user()->hasPermission(['Administrator']))
                    <li>
                        <a href="{{ env('APP_URL') }}/company/edit">Company Details</a>
                    </li>
                        @endif
                    @endauth
                </ul>
            </div>
            <div class="top-bar-right">
                <ul class="dropdown menu" data-dropdown-menu>
                    @auth
                    <li>
                        <a href="{{ env('APP_URL') }}/change-details">{{ auth()->user()->name }}</a>
                    </li>
                    {{--<li>
                        <a href="{{ env('APP_URL') }}/reset-password">Reset Password</a>
                    </li>--}}
                    <li>
                        <form id="logout-form" method="POST" action="{{ env('APP_URL') }}/logout">
                            @csrf
                            <a id="logout-btn">Logout</a>
                        </form>
                    </li>
                    @else
                    <li>
                        <a href="{{ env('APP_URL') }}/login">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
        @show

        <div class="grid-x content grid-padding-x">
            @if(session()->has('messages'))
                <div class="cell messages large-10 medium-10 small-12">
                    <p class="cell message"><i class="fa-solid fa-x" id="close-message"></i>{{ session()->get('messages') }}</p>
                </div>
            @endif
            <div class="page-content cell large-10 medium-10 small-12">
                @yield('content')
            </div>
        </div>
    </body>
</html>
