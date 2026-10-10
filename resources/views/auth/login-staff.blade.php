@extends('layouts.app')

@section('title', 'Masuk Staf')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Masuk Staf</h4>
                <p class="text-muted small mb-3">Khusus Admin, Owner, dan Kurir.</p>

                <form method="POST" action="{{ route('staff.login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-teal w-100">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection