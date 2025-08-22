@extends('layouts.app')

@section('content')
    <!-- Welcome Component -->
    <div id="welcome" class="component active">
        <!-- Hero Image -->
        <img src="{{ asset('assets/images/hero.png') }}" alt="هدفون و گل‌های بهاری" class="hero-image">

        <!-- Title -->
        <h2>این بهار، موسیقی رو از دل تو می‌سازیم.</h2>

        <!-- Main Button -->
        <button onclick="loadComponent('information')" class="btn-primary">شروع کنین</button>

        <!-- Steps Section -->
        <div class="steps-info">مراحل کار با هوش مصنوعی دافی</div>
        <div class="steps">
            <div class="step">
                <label>وارد کردن شماره تماس/ثبت نام</label>
                <span>با وارد کردن شماره، یه مسیر شخصی برای خلق موسیقی باز میکنی.</span>
            </div>
            <div class="step">
                <label>وارد کردن کد تایید و کد تخفیف</label>
                <span>کدت تایید رو وارد کن و اگه کد تخفیفم داری فرصت خوبی برای استفادست.</span>
            </div>
            <div class="step">
                <label>و گوش کن!</label>
                <span>موسیـقـی مخصـوص تو، با الگـوریتمی از چهره، حال و هوات و انتخاباتت ساخته شده!</span>
            </div>
        </div>

        @include('components.footer')
    </div>

    <!-- Information Component -->
    <div id="information" class="component">
        <div class="loading">در حال بارگذاری...</div>
    </div>

    <!-- OTP Component -->
    <div id="otp" class="component">
        <div class="loading">در حال بارگذاری...</div>
    </div>

    <!-- Coupon Component -->
    <div id="coupon" class="component">
        <div class="loading">در حال بارگذاری...</div>
    </div>

    <!-- Deliver Component -->
    <div id="deliver" class="component">
        <div class="loading">در حال بارگذاری...</div>
    </div>
@endsection
