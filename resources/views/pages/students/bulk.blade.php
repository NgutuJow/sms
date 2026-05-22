@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">
                    Bulk Student Import
                </h4>

                <a href="{{ route('bulk-import.template') }}"
                   class="btn btn-success">
                    Download CSV Template
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('bulk-import.upload') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-4">
                    <label class="form-label">
                        Upload CSV File
                    </label>

                    <input type="file"
                           name="file"
                           class="form-control"
                           required>
                </div>

                <button class="btn btn-primary">
                    Import Students
                </button>

            </form>

        </div>
    </div>

</div>

@endsection