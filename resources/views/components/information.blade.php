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

<!-- Footer -->
<div class="footer">
    <img src="{{ asset('assets/images/instagram.png') }}" alt="" >
    <span class="btn-instagram">دافی رو در اینستاگرام دنبال کنین</span>
    <img src="{{ asset('assets/images/Arrow-right.png') }}" style="padding-right: 7px" alt="" >
    <a href="#" class="btn-instagram">دنبال کردن</a>
</div>

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
                    loadComponent('otp');
                } else {
                    alert(response.message || 'خطا در ثبت اطلاعات');
                }
            },
            error: function() {
                alert('خطا در ارتباط با سرور');
            }
        });
    });
});
</script>
