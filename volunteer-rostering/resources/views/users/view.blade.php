
@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <div class="">
        <h1>User - {{ $user->name }}</h1>
        <div class="bordered-box cell">
            <h3 class="bordered-box-title">Details</h3>
            <div class="bordered-box-content">
                <a href="{{ env('APP_URL') }}/user/{{ $user->id }}/edit" class="button">Edit User</a>
                <a href="{{ env('APP_URL') }}/user/{{ $user->id }}/reset-password" class="button">Reset Password</a>
                <form method="post" action="{{ env('APP_URL') }}/user/{{ $user->id }}/delete" style="display: inline;">
                    @csrf
                    <button type="submit" class="clickable alert button" data-alert-text="delete user">Delete User</button>
                </form>
                <p><strong>Name: </strong>{{ $user->name }}</p>
                <p><strong>Email: </strong>{{ $user->email }}</p>
                <p><strong>Web Roles: </strong>{{ $user->getRoleNames() }}</p>
            </div>
        </div>
        <div class="bordered-box cell">
            <h3 class="bordered-box-title">Qualifications</h3>
            <div class="bordered-box-content">
                <table>
                    <tr>
                        <td>Qualification</td>
                        <td>Level</td>
                        <td>Actions</td>
                    </tr>
                @forelse($user->levels as $level)
                    <tr>
                        <td>{{ $level->qualification->name }}</td>
                        <td>{{ $level->name }}</td>
                        <td>
                            <form method="post" action="{{ env('APP_URL') }}/level/{{ $level->id }}/user/{{ $user->id }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="clickable alert button" data-alert-text="remove level from user">Remove Qualifciation from User</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <p>This user has no qualifications</p>
                @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
