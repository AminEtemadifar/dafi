<div id="discountBox" class="discount-box">
    <!-- Title -->
    <h2>این آخرین قدمه!</h2>

    <form id="couponForm">
        <div>
            <div class="form-group">
                <label for="coupon">کد تخفیف خودت رو وارد کن و بدون هزینه موزیکتو تحویل بگیر</label>
                <input type="text" id="coupon" name="coupon" class="input-field" placeholder="کد تخفیف">
            </div>
            <button type="submit" class="btn-primary">تایید</button>
            <button type="button" onclick="showComponent('otp')" class="btn-secondary">بازگشت به مرحله قبل</button>
        </div>
    </form>
</div>

<div id="payBox" class="pay-box">
    <h2>این آخرین قدمه!</h2>

    <div class="form-group">
        <label>پرداخت رو انجام بده یا با به کد تخفیف موزيكتو بدون هزینه تحویل بگیر.</label>
        <div class="price">۱۰۰.۰۰۰ ریال</div>
    </div>
    <button onclick="loadComponent('deliver')" class="btn-primary">پرداخت</button>
    <button onclick="showComponent('otp')" class="btn-secondary">بازگشت به مرحله قبل</button>
</div>

<div class="checkbox-container font-semibold">
    <input type="checkbox" id="hasDiscount">
    <label for="hasDiscount">
        <span class="custom-checkbox"></span>
        <span class="checkbox-label-text">آیا کد تخفیف ندارید؟</span>
    </label>
</div>

<!-- Footer -->
<div class="footer">
    <img src="{{ asset('assets/images/instagram.png') }}" alt="" >
    <span class="btn-instagram">دافی رو در اینستاگرام دنبال کنین</span>
    <img src="{{ asset('assets/images/Arrow-right.png') }}" style="padding-right: 7px" alt="" >
    <a href="#" class="btn-instagram">دنبال کردن</a>
</div>

<script>
$(document).ready(function() {
    const checkbox = document.getElementById('hasDiscount');
    const discountBox = document.getElementById('discountBox');
    const payBox = document.getElementById('payBox');

    function updateUI() {
        if (checkbox.checked) {
            // Checkbox is active - show payBox and discount input, disable discountBox
            payBox.classList.add('show');
            discountBox.classList.add('hidden');
        } else {
            // Checkbox is inactive - hide payBox and discount input, enable discountBox
            payBox.classList.remove('show');
            discountBox.classList.remove('hidden');
        }
    }

    // Initial state
    updateUI();

    // Add event listener
    checkbox.addEventListener('change', updateUI);
    
    $('#couponForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            coupon: $('#coupon').val()
        };
        
        $.ajax({
            url: '/api/verify-coupon',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    loadComponent('deliver');
                } else {
                    alert(response.message || 'کد تخفیف نامعتبر است');
                }
            },
            error: function() {
                alert('خطا در ارتباط با سرور');
            }
        });
    });
});
</script>
