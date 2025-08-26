@extends('admin.layouts.app')

@section('title', 'افزودن نام جدید')
@section('page-title', 'افزودن نام جدید')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.names.index') }}">نام ها</a></li>
    <li class="breadcrumb-item active">افزودن نام جدید</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">افزودن نام جدید</h4>
                <a href="{{ route('admin.names.index') }}" class="btn btn-sm btn-secondary float-left">
                    <i class="icon-arrow-left"></i> بازگشت
                </a>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.names.store') }}" enctype="multipart/form-data" id="createNameForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">نام <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" placeholder="مثلاً امیر" required
                                           value="{{ old('name') }}" autocomplete="off">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="music" class="form-label">فایل موسیقی <span class="text-danger">*</span></label>
                                    <div class="dropzone-container">
                                        <div class="dropzone" id="musicDropzone">
                                            <div class="dz-">
                                                <h6>فایل موسیقی را اینجا رها کنید</h6>
                                                <span class="text-muted">یا کلیک کنید تا فایل انتخاب کنید</span>
                                                <br>
                                            </div>
                                        </div>
                                        <input type="file" id="music" name="music" accept="audio/*" required style="display: none;">
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

                        <!-- Matching Submits Section -->
                        <div class="row" id="matchingSubmitsSection" style="display: none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">درخواست های مطابق (انتخابی)</label>
                                    <div class="alert alert-info">
                                        <i class="icon-info"></i>
                                        درخواست هایی که نامشان با نام وارد شده مطابقت دارد:
                                    </div>
                                    <div id="matchingSubmits" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        <div class="text-center text-muted">
                                            <i class="icon-loader icon-spin"></i>
                                            در حال جستجو...
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        با انتخاب این درخواست ها، پیامکی حاوی لینک موسیقی برای آن ها ارسال خواهد شد.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                        <i class="icon-save"></i> ثبت و ارسال پیامک
                                    </button>
                                    <a href="{{ route('admin.names.index') }}" class="btn btn-secondary btn-lg">
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

#matchingSubmits {
    background: #f8f9fa;
}

.submit-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    margin-bottom: 8px;
    background: white;
    transition: all 0.2s ease;
}

.submit-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.submit-item input[type="checkbox"] {
    margin-left: 10px;
}

.submit-item .submit-info {
    flex: 1;
}

.submit-item .submit-name {
    font-weight: 600;
    color: #495057;
}

.submit-item .submit-mobile {
    font-family: monospace;
    color: #6c757d;
    font-size: 14px;
}

.submit-item .submit-date {
    color: #6c757d;
    font-size: 12px;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
let searchTimer = null;
let selectedFile = null;

// Initialize Dropzone
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('musicDropzone');
    const fileInput = document.getElementById('music');

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

    // Name input with debounced search
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const name = this.value.trim();

        if (name.length === 0) {
            hideMatchingSubmits();
            return;
        }

        searchTimer = setTimeout(function() {
            searchMatchingSubmits(name);
        }, 500);
    });
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
    updateSubmitButton();

    // Update dropzone visual feedback
    const dropzone = document.getElementById('musicDropzone');
    dropzone.style.borderColor = '#28a745';
    dropzone.style.background = '#d4edda';

    // Add success icon
    const successIcon = document.createElement('div');
    successIcon.innerHTML = '<i class="icon-check-circle text-success" style="font-size: 24px; position: absolute; top: 10px; right: 10px;"></i>';
    successIcon.style.position = 'relative';
    dropzone.appendChild(successIcon);
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

    updateSubmitButton();

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

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const nameInput = document.getElementById('name');

    if (submitBtn && nameInput) {
        const name = nameInput.value.trim();

        if (name && selectedFile) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
}

function searchMatchingSubmits(name) {
    const section = document.getElementById('matchingSubmitsSection');
    const container = document.getElementById('matchingSubmits');

    if (section && container) {
        section.style.display = 'block';
        container.innerHTML = '<div class="text-center text-muted"><div class="loading-spinner"></div><br>در حال جستجو...</div>';

        fetch(`{{ route('admin.submits.byName') }}?name=${encodeURIComponent(name)}`)
            .then(response => response.json())
            .then(data => {
                if (data.items && data.items.length > 0) {
                    displayMatchingSubmits(data.items);
                } else {
                    container.innerHTML = '<div class="text-center text-muted">هیچ درخواستی با این نام یافت نشد.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center text-danger">خطا در دریافت اطلاعات</div>';
            });
    }
}

function displayMatchingSubmits(submits) {
    const container = document.getElementById('matchingSubmits');

    if (container) {
        const html = submits.map(submit => `
            <div class="submit-item">
                <input type="checkbox" name="submits[]" value="${submit.id}" id="submit_${submit.id}">
                <div class="submit-info">
                    <div class="submit-name">${submit.name}</div>
                    <div class="submit-mobile">${submit.mobile}</div>
                    <div class="submit-date">تاریخ درخواست: ${submit.created_at}</div>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }
}

function hideMatchingSubmits() {
    const section = document.getElementById('matchingSubmitsSection');
    if (section) {
        section.style.display = 'none';
    }
}

function showAlert(message, type = 'info') {
    // You can implement a toast notification here
    alert(message);
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createNameForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name');
            if (!name || !name.value.trim()) {
                e.preventDefault();
                showAlert('لطفاً نام را وارد کنید.', 'danger');
                return;
            }

            if (!selectedFile) {
                e.preventDefault();
                showAlert('لطفاً فایل موسیقی را انتخاب کنید.', 'danger');
                return;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<div class="loading-spinner"></div> در حال ثبت...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush
