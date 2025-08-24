<div id="discountBox" class="discount-box">
    <!-- Title -->
    <h2>این آخرین قدمه!</h2>

    <form id="couponForm">
        <div>
            <div class="form-group">
                <label for="coupon-input">کد تخفیف خودت رو وارد کن و بدون هزینه موزیکتو تحویل بگیر</label>
                <input type="text" id="coupon-input" name="coupon" class="input-field" placeholder="کد تخفیف">
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
    <form id="paymentStartForm" method="POST" action="/payment/start" style="display:inline;">
        @csrf
        <button type="submit" class="btn-primary">پرداخت</button>
    </form>
    <button onclick="showComponent('otp')" class="btn-secondary">بازگشت به مرحله قبل</button>
</div>

<div class="checkbox-container font-semibold">
    <input type="checkbox" id="hasDiscount">
    <label for="hasDiscount">
        <span class="custom-checkbox"></span>
        <span class="checkbox-label-text">آیا کد تخفیف ندارید؟</span>
    </label>
</div>

@include('components.footer')

<script>
$(document).ready(function() {
    const checkbox = document.getElementById('hasDiscount');
    const discountBox = document.getElementById('discountBox');
    const payBox = document.getElementById('payBox');

    function updateUI() {
        if (checkbox.checked) {
            payBox.classList.add('show');
            discountBox.classList.add('hidden');
        } else {
            payBox.classList.remove('show');
            discountBox.classList.remove('hidden');
        }
    }

    updateUI();
    checkbox.addEventListener('change', updateUI);

    $('#couponForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            coupon: $('#coupon-input').val()
        };

        $.ajax({
            url: '/api/verify-coupon',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Pass music path to deliver component via global state
                    window.__DELIVER_MUSIC_PATH__ = response.music_path || null;
                    loadComponent('deliver');
                } else {
                    showError(response.message || 'کد تخفیف نامعتبر است');
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
