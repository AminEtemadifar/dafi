<!-- Title -->
<h2>کد تایید رو وارد کن</h2>

<form id="otpForm">
    <div>
        <div class="form-group">
            <label for="otp">کد ارسال شده به شماره <span id="mobileNumber">09012959494</span> را وارد کنید</label>
            <input type="text" id="otp" name="otp" class="input-field" placeholder="- - - -" maxlength="4" required>
        </div>
        <button type="submit" class="btn-primary">تایید</button>
        <button type="button" onclick="showComponent('information')" class="btn-secondary">بازگشت به مرحله قبل</button>
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
    // Auto-focus on OTP input
    $('#otp').focus();
    
    // Auto-advance to next input (if we had multiple inputs)
    $('#otp').on('input', function() {
        var value = $(this).val();
        if (value.length === 4) {
            // Auto-submit when 4 digits are entered
            $('#otpForm').submit();
        }
    });
    
    $('#otpForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            otp: $('#otp').val()
        };
        
        $.ajax({
            url: '/api/verify-otp',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    loadComponent('coupon');
                } else {
                    alert(response.message || 'کد تایید اشتباه است');
                }
            },
            error: function() {
                alert('خطا در ارتباط با سرور');
            }
        });
    });
});
</script>
