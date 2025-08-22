<!-- Title -->
<h2>کد تایید رو وارد کن</h2>

<form id="otpForm">
    <div>
        <div class="form-group">
            <label for="otp-input">کد ارسال شده به شماره <span id="mobileNumber">09012959494</span> را وارد کنید</label>
            <input type="text" id="otp-input" name="otp" class="input-field" placeholder="- - - -" maxlength="4" required>
        </div>
        <button type="submit" class="btn-primary">تایید</button>
        <button type="button" onclick="showComponent('information')" class="btn-secondary">بازگشت به مرحله قبل</button>
    </div>
</form>

@include('components.footer')

<script>
$(document).ready(function() {
    $('#otp-input').focus();

    $('#otp-input').on('input', function() {
        var value = $(this).val();
        if (value.length === 4) {
            $('#otpForm').submit();
        }
    });

    $('#otpForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            otp: $('#otp-input').val()
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
