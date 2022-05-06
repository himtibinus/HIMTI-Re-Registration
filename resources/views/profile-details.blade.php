@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Your Profile Details</h3>
        <ul>
            <li><b>User ID:</b> {{ $user->id }}</li>
            <li><b>Email:</b> {{ $user->email }}</li>
            <li><b>Name:</b> {{ $user->name }}</li>
            <li><b>University:</b> {{ $user->university ?? 'None/Uncategorized' }}</li>
            @foreach ($user_properties as $property)
                <li><b>{{ $property->name }}:</b> {{ $property->value }}</li>
            @endforeach
        </ul>
    </div>
    <a href="/Registration" class="btn btn-outline-dark">Re Registration</a>
@endsection
