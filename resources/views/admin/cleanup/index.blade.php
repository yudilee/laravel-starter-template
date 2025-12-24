@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1><i class="bi bi-trash3 me-2"></i>Data Cleanup</h1>
        <p class="text-muted">Clean up system tables (audit logs, sessions, scheduler logs)</p>
    </div>

    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Warning:</strong> This will permanently delete data from selected tables. This action cannot be undone.
    </div>

    <form action="{{ route('admin.cleanup.run') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete data from selected tables?')">
        @csrf
        <div class="row">
            @foreach($tables as $table)
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tables[]" value="{{ $table['table'] }}" id="table_{{ $table['table'] }}">
                            <label class="form-check-label" for="table_{{ $table['table'] }}">
                                <strong>{{ $table['name'] }}</strong>
                            </label>
                        </div>
                        <p class="text-muted small mb-1">{{ $table['description'] }}</p>
                        <span class="badge bg-secondary">{{ number_format($table['count']) }} records</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-danger mt-3">
            <i class="bi bi-trash me-1"></i>Clean Selected Tables
        </button>
    </form>
</div>
@endsection
