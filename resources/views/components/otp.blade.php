<!-- Title -->
<h2>کد تایید رو وارد کن</h2>

<form id="otpForm">
    <div>
        <div class="form-group">
            <label for="otp-input">کد ارسال شده به شماره <span id="mobileNumber">@php echo session('user_mobile') @endphp</span> را وارد کنید</label>
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
                    // Direct transition without success alert
                    loadComponent('coupon');
                } else {
                    showError(response.message || 'کد تایید اشتباه است');
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
