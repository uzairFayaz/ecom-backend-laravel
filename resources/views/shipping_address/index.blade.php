@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Your Shipping Addresses</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('shipping_addresses.create') }}" class="btn btn-primary mb-3">Add Address</a>
        @if ($addresses->isEmpty())
            <p>No addresses found.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip Code</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($addresses as $address)
                    <tr>
                        <td>{{ $address->full_address }}</td>
                        <td>{{ $address->city }}</td>
                        <td>{{ $address->state }}</td>
                        <td>{{ $address->zip_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
