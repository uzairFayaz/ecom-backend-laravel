@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Add Shipping Address</h1>
        <form action="{{ route('shipping_addresses.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="full_address">Full Address</label>
                <textarea name="full_address" class="form-control" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="city">City</label>
                <input type="text" name="city" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="state">State</label>
                <input type="text" name="state" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="zip_code">Zip Code</label>
                <input type="text" name="zip_code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
