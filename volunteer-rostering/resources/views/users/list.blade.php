@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <div class="">
        <h1>Users</h1>
        <a class="button">Qualifications</a>
        <h3>Approved Users</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td><a href="{{ env('APP_URL') }}/user/{{ $user->id }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames() }}</td>
                    <td class="actions-list">
                        <a href="{{ env('APP_URL') }}/user/{{ $user->id }}/edit"><i class="fa-solid fa-pen"></i></a>
                        <form method="post" action="{{ env('APP_URL') }}/user/{{ $user->id }}/delete" style="display: inline;">
                            @csrf
                            <button type="submit" class="clickable alert" data-alert-text="delete user"><i class="fa-solid fa-x"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <h3>Unapproved Users</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            @foreach ($unapprovedUsers as $unapprovedUser)
                <tr>
                    <td><a href="{{ env('APP_URL') }}/user/{{ $unapprovedUser->id }}">{{ $unapprovedUser->name }}</a></td>
                    <td>{{ $unapprovedUser->email }}</td>
                    <td>{{ $unapprovedUser->getRoleNames() }}</td>
                    <td class="actions-list">
                        <form method="post" action="{{ env('APP_URL') }}/user/{{ $unapprovedUser->id }}/approve" style="display: inline;">
                            @csrf
                            <button type="submit" class="clickable"><i class="fa-solid fa-check"></i></button>
                        </form>
                        <form method="post" action="{{ env('APP_URL') }}/user/{{ $unapprovedUser->id }}/delete" style="display: inline;">
                            @csrf
                            <button type="submit" class="clickable alert" data-alert-text="delete user"><i class="fa-solid fa-x"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
