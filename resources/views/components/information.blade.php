<!-- Title -->
<h2>اطلاعاتتون رو کامل کنید</h2>

<form id="informationForm">
    <div>
        <div class="form-group">
            <label for="fullname">نام و نام خانوادگی</label>
            <input type="text" id="fullname" name="fullname" class="input-field" placeholder="نام و نام خانوادگی" required>
        </div>
        <div class="form-group">
            <label for="mobile">شماره موبایل</label>
            <input type="text" id="mobile" name="mobile" class="input-field" placeholder="شماره موبایل" required>
        </div>
        <button type="submit" class="btn-primary">ادامه بده</button>
        <button type="button" onclick="showComponent('welcome')" class="btn-secondary">بازگشت به مرحله قبل</button>
    </div>
</form>

@include('components.footer')

<script>
$(document).ready(function() {
    $('#informationForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            fullname: $('#fullname').val(),
            mobile: $('#mobile').val()
        };
        
        $.ajax({
            url: '/api/information',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Direct transition without success alert
                    loadComponent('otp');
                } else {
                    showError(response.message || 'خطا در ثبت اطلاعات');
                }
            },
            error: function(xhr) {
                let message = 'خطا در ارتباط با سرور';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showError(message);
            }
        });
    });
});
</script>
