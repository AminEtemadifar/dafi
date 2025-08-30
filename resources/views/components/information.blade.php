<!-- Title -->
<h2>اطلاعاتتون رو کامل کنید</h2>

<form id="informationForm">
    <div>
        <div class="form-group">
            <label for="fullname">نام کوچک</label>
            <input type="text" id="fullname" name="fullname" class="input-field" placeholder="علی" required>
            <div id="fullname-error" class="error-message" style="display: none;"></div>
        </div>
        <div class="form-group">
            <label for="mobile">شماره موبایل</label>
            <input type="text" id="mobile" name="mobile" class="input-field" placeholder="شماره موبایل" required>
            <div id="mobile-error" class="error-message" style="display: none;"></div>
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

        // Reset previous errors
        $('.error-message').hide().text('');

        // Get input values
        var fullname = $('#fullname').val().trim();
        var mobile = $('#mobile').val().trim();

        // Convert Persian numbers to English numbers
        var persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        var englishNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        for (var i = 0; i < persianNumbers.length; i++) {
            mobile = mobile.replace(persianNumbers[i], englishNumbers[i]);
        }

        // Update the input field with converted numbers
        $('#mobile').val(mobile);

        // Validation flags
        var isValid = true;

        // Fullname validation (Persian characters only)
        var persianNameRegex = /^[\u0600-\u06FF\s]+$/;
        if (!fullname) {
            $('#fullname-error').text('نام الزامی است').show();
            isValid = false;
        } else if (!persianNameRegex.test(fullname)) {
            $('#fullname-error').text('نام باید به زبان فارسی وارد شود').show();
            isValid = false;
        }

        // Mobile validation (must start with 09 and be 11 digits)
        var mobileRegex = /^09\d{9}$/;
        if (!mobile) {
            $('#mobile-error').text('شماره موبایل الزامی است').show();
            isValid = false;
        } else if (!mobileRegex.test(mobile)) {
            $('#mobile-error').text('شماره موبایل باید با 09 شروع شده و 11 رقم باشد').show();
            isValid = false;
        }

        // If validation passes, submit the form
        if (isValid) {
            var formData = {
                fullname: fullname,
                mobile: mobile
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
        }
    });
});
</script>
