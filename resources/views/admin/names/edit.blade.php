@extends('admin.layouts.app')

@section('title', 'ویرایش نام')
@section('page-title', 'ویرایش نام')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.names.index') }}">نام ها</a></li>
    <li class="breadcrumb-item active">ویرایش نام</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">ویرایش نام: {{ $name->name }}</h4>
                <a href="{{ route('admin.names.show', $name->id) }}" class="btn btn-sm btn-secondary float-left">
                    <i class="icon-arrow-left"></i> بازگشت
                </a>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.names.update', $name->id) }}" enctype="multipart/form-data" id="editNameForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">نام <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" placeholder="مثلاً امیر" required
                                           value="{{ old('name', $name->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="music" class="form-label">فایل موسیقی جدید (اختیاری)</label>
                                    <div class="dropzone-container">
                                        <div class="dropzone" id="musicDropzone">
                                            <div class="dz-">
                                                <h6>فایل موسیقی جدید را اینجا رها کنید</h6>
                                                <span class="text-muted">یا کلیک کنید تا فایل انتخاب کنید</span>
                                                <br>
                                            </div>
                                        </div>
                                        <input type="file" id="music" name="music" accept="audio/*" style="display: none;">
                                        <div id="fileInfo" class="mt-2" style="display: none;">
                                            <div class="alert alert-info">
                                                <i class="icon-check-circle"></i>
                                                <span id="fileName"></span>
                                                <button type="button" class="btn btn-sm btn-outline-danger float-left" onclick="removeFile()">
                                                    <i class="icon-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('music')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current File Info -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>فایل فعلی:</label>
                                    <div class="border rounded p-3">
                                        <p class="mb-2"><strong>مسیر:</strong> {{ $name->path }}</p>
                                        @if(Storage::disk('public')->exists($name->path))
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $name->path) }}" type="audio/mpeg">
                                                مرورگر شما از پخش صدا پشتیبانی نمی کند.
                                            </audio>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="icon-alert-triangle"></i>
                                                فایل موسیقی یافت نشد!
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="icon-save"></i> بروزرسانی
                                    </button>
                                    <a href="{{ route('admin.names.show', $name->id) }}" class="btn btn-secondary btn-lg">
                                        <i class="icon-x"></i> انصراف
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.dropzone-container {
    position: relative;
}

.dropzone {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 25px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropzone:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.dropzone.dz-drag-hover {
    border-color: #28a745;
    background: #d4edda;
}

.dropzone .dz-message {
    color: #6c757d;
    width: 100%;
}

.dropzone .dz-message i {
    color: #007bff;
    margin-bottom: 10px;
    display: block;
}

.dropzone .dz-message h6 {
    margin: 10px 0 8px 0;
    color: #495057;
    font-size: 14px;
}

.dropzone .dz-message span {
    font-size: 13px;
}

.dropzone .dz-message small {
    font-size: 11px;
    line-height: 1.4;
}

/* Dropzone.js custom styles */
.dropzone .dz-preview {
    margin: 5px;
}

.dropzone .dz-preview .dz-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
}

.dropzone .dz-preview .dz-details {
    padding: 5px;
    font-size: 12px;
}

.dropzone .dz-preview .dz-success-mark,
.dropzone .dz-preview .dz-error-mark {
    top: 15px;
    right: 15px;
}

.dropzone .dz-preview .dz-success-mark svg,
.dropzone .dz-preview .dz-error-mark svg {
    width: 20px;
    height: 20px;
}

.dropzone .dz-preview .dz-progress {
    height: 4px;
    border-radius: 2px;
}

.dropzone .dz-preview .dz-progress .dz-upload {
    background: #007bff;
}

.dropzone .dz-preview .dz-error-message {
    font-size: 11px;
    padding: 5px;
    border-radius: 4px;
}
</style>
@endpush

@push('scripts')
<script>
let selectedFile = null;

// Initialize Dropzone
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('musicDropzone');
    const fileInput = document.getElementById('music');

    if (dropzone && fileInput) {
        // Click to select file
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop events
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('dz-drag-hover');
        });

        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dz-drag-hover');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dz-drag-hover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // File input change
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
    }
});

function handleFile(file) {
    // Validate file type
    const allowedTypes = ['audio/mpeg', 'audio/wav', 'audio/ogg'];
    if (!allowedTypes.includes(file.type)) {
        showAlert('خطا: فقط فایل های صوتی MP3، WAV و OGG پشتیبانی می شوند.', 'danger');
        return;
    }

    // Validate file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        showAlert('خطا: حجم فایل نباید بیشتر از 10 مگابایت باشد.', 'danger');
        return;
    }

    selectedFile = file;
    showFileInfo(file);

    // Update dropzone visual feedback
    const dropzone = document.getElementById('musicDropzone');
    if (dropzone) {
        dropzone.style.borderColor = '#28a745';
        dropzone.style.background = '#d4edda';

        // Add success icon
        const successIcon = document.createElement('div');
        successIcon.innerHTML = '<i class="icon-check-circle text-success" style="font-size: 24px; position: absolute; top: 10px; right: 10px;"></i>';
        successIcon.style.position = 'relative';
        dropzone.appendChild(successIcon);
    }
}

function showFileInfo(file) {
    const fileNameElement = document.getElementById('fileName');
    const fileInfoElement = document.getElementById('fileInfo');

    if (fileNameElement && fileInfoElement) {
        fileNameElement.textContent = file.name;
        fileInfoElement.style.display = 'block';
    }
}

function removeFile() {
    selectedFile = null;
    const fileInfoElement = document.getElementById('fileInfo');
    const fileInput = document.getElementById('music');

    if (fileInfoElement && fileInput) {
        fileInfoElement.style.display = 'none';
        fileInput.value = '';
    }

    // Reset dropzone visual state
    const dropzone = document.getElementById('musicDropzone');
    if (dropzone) {
        dropzone.style.borderColor = '#ccc';
        dropzone.style.background = '#f8f9fa';

        // Remove success icon
        const successIcon = dropzone.querySelector('.icon-check-circle');
        if (successIcon && successIcon.parentElement) {
            successIcon.parentElement.remove();
        }
    }
}

function showAlert(message, type = 'info') {
    // You can implement a toast notification here
    alert(message);
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editNameForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name');
            if (!name || !name.value.trim()) {
                e.preventDefault();
                showAlert('لطفاً نام را وارد کنید.', 'danger');
                return;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<div class="loading-spinner"></div> در حال بروزرسانی...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush
