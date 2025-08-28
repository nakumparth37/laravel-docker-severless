<!-- resources/views/admin/storage.blade.php -->
@extends('admin.dashboard')

@section('adminMainContant')
<div class="admin-content-continuer">
    <div class="mx-5 mt-5">
        <form method="POST" action="{{ route('changeStoreType')}}">
            @csrf
            <label for="storage_type">Choose type of storage system</label><br>

            <!-- Radio button for local -->
            <div class="form-check mt-3">
                <input class="form-check-input" type="radio" name="storage_type" id="local" value="local"
                    {{ old('storage_type', $storageType) == 'local' ? 'checked' : '' }}>
                <label class="form-check-label" for="local">
                    Local System Storage
                </label>
            </div>

            <!-- Radio button for s3 -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="storage_type" id="s3" value="s3"
                    {{ old('storage_type', $storageType) == 's3' ? 'checked' : '' }}>
                <label class="form-check-label" for="s3">
                    AWS S3 bucket
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Change storage system</button>
        </form>
    </div>
</div>
@endsection
