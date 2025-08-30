<div id="has-path" class="text-center">
    <div class="player-title">همین حالا پلی کن!</div>
    <div class="player-desc">
        میتونی دانلودش کنی! یا دوستات رو با اشتراک گذاری با همینجا گوش بدی
    </div>
</div>
<div id="has-not-path" class="text-center" style="display:none">
    <div class="player-title">درخواست شما با موفقیت ثبت شد</div>
    <div class="player-desc">به محض آماده شدن موزیک، لینکشو برات پیامک میکنیم.</div>
</div>

<!-- Banner with overlay text -->
<div class="music-banner-box">
    <img class="banner-img" src="{{ asset('assets/images/banner.png') }}" alt="banner"/>
</div>

<!-- Custom Audio Player -->
<div class="audio-player-custom" dir="ltr" id="player-wrapper" style="display:none">
    <audio id="audio-player"></audio>
    <div class="progress-row">
        <span class="progress-time" id="current-time">00:00</span>
        <div class="progress-bar-wrap">
            <div class="progress-bar" id="progress-bar">
                <div class="progress-bar-filled" id="progress-bar-filled"></div>
                <div class="progress-knob" id="progress-knob"></div>
            </div>
        </div>
        <span class="progress-time" id="total-duration">00:00</span>
    </div>
    <div class="player-controls-row">
        <button class="player-btn" id="prev-btn" title="قبلی" disabled>
            <svg width="34" height="34" viewBox="0 0 34 34">
                <polygon points="25,7 13,17 25,27" fill="#4A2A70"/>
                <rect x="8" y="7" width="3" height="20" fill="#4A2A70"/>
            </svg>
        </button>
        <button class="player-btn" id="play-btn" title="پخش">
            <svg id="play-icon" width="34" height="34" viewBox="0 0 34 34">
                <polygon points="10,7 28,17 10,27" fill="#4A2A70"/>
            </svg>
            <svg id="pause-icon" style="display:none" width="34" height="34" viewBox="0 0 34 34">
                <rect x="10" y="7" width="5" height="20" fill="#4A2A70"/>
                <rect x="19" y="7" width="5" height="20" fill="#4A2A70"/>
            </svg>
        </button>
        <button class="player-btn" id="next-btn" title="بعدی" disabled>
            <svg width="34" height="34" viewBox="0 0 34 34">
                <polygon points="9,7 21,17 9,27" fill="#4A2A70"/>
                <rect x="23" y="7" width="3" height="20" fill="#4A2A70"/>
            </svg>
        </button>
    </div>
</div>


<div class="player-actions-row" id="player-actions-row">
    <button class="player-btn" id="share-btn" title="اشتراک‌گذاری">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <!-- connection lines -->
            <path d="M9 12l6-3" stroke="#4A2A70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 12l6 3" stroke="#4A2A70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <!-- nodes -->
            <circle cx="18" cy="6" r="3" stroke="#4A2A70" stroke-width="2"/>
            <circle cx="6" cy="12" r="3" stroke="#4A2A70" stroke-width="2"/>
            <circle cx="18" cy="18" r="3" stroke="#4A2A70" stroke-width="2"/>
        </svg>

    </button>
    <button class="player-btn" id="download-btn" title="دانلود">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M12 4v12m0 0l4-4m-4 4l-4-4" stroke="#4A2A70" stroke-width="2" stroke-linecap="round"/>
            <rect x="4" y="20" width="16" height="2" rx="1" fill="#4A2A70"/>
        </svg>
    </button>
</div>

@include('components.footer')

<style>
    body {
        background-color: #fff;
        margin: 0;
        padding: 0;
        text-align: center;
        font-family: 'Kalameh', sans-serif;
        background-image: url({{asset('assets/images/bg.png')}});
        background-size: cover;
        background-repeat: repeat;
        background-position: center;
    }
</style>

<script>
    $(document).ready(function () {
        const audio = document.getElementById('audio-player');
        const playerWrapper = document.getElementById('player-wrapper');
        const hasNotPathMessage = document.getElementById('has-not-path');
        const hasPathMessage = document.getElementById('has-path');
        const playBtn = document.getElementById('play-btn');
        const playIcon = document.getElementById('play-icon');
        const pauseIcon = document.getElementById('pause-icon');
        const currentTimeSpan = document.getElementById('current-time');
        const durationSpan = document.getElementById('total-duration');
        const progressBar = document.getElementById('progress-bar');
        const progressBarFilled = document.getElementById('progress-bar-filled');
        const progressKnob = document.getElementById('progress-knob');
        const downloadBtn = document.getElementById('download-btn');
        const shareBtn = document.getElementById('share-btn');
        const playerActionsRow = document.getElementById('player-actions-row');

        const candidate = window.__DELIVER_MUSIC_PATH__ || null;
        if (candidate) {
            audio.src = candidate.match(/^https?:\/\//) ? candidate : (window.location.origin + (candidate.startsWith('/') ? candidate : ('/' + candidate)));
            playerWrapper.style.display = '';
            hasNotPathMessage.style.display = 'none';
            hasPathMessage.style.display = '';
            playerActionsRow.style.display = '';
        } else {
            playerWrapper.style.display = 'none';
            hasNotPathMessage.style.display = '';
            hasPathMessage.style.display = 'none';
            playerActionsRow.style.display = 'none';
        }

        function formatTime(secs) {
            if (isNaN(secs)) return "00:00";
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60);
            return (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s;
        }

        playBtn.onclick = function () {
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
        };
        audio.onplay = function () {
            playIcon.style.display = "none";
            pauseIcon.style.display = "inline";
        };
        audio.onpause = function () {
            playIcon.style.display = "inline";
            pauseIcon.style.display = "none";
        };

        audio.ontimeupdate = function () {
            currentTimeSpan.textContent = formatTime(audio.currentTime);
            const percent = audio.duration ? (audio.currentTime / audio.duration) : 0;
            progressBarFilled.style.width = (percent * 100) + "%";
            progressKnob.style.left = (percent * 100) + "%";
        };

        audio.onloadedmetadata = function () {
            durationSpan.textContent = formatTime(audio.duration);
            audio.ontimeupdate();
        };

        progressBar.onclick = function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = x / rect.width;
            audio.currentTime = percent * audio.duration;
        };

        let dragging = false;
        progressKnob.onmousedown = function () {
            dragging = true;
            document.body.style.userSelect = "none";
        };
        document.addEventListener('mousemove', function (e) {
            if (!dragging) return;
            const rect = progressBar.getBoundingClientRect();
            let x = e.clientX - rect.left;
            x = Math.max(0, Math.min(x, rect.width));
            const percent = x / rect.width;
            audio.currentTime = percent * audio.duration;
        });
        document.addEventListener('mouseup', function () {
            dragging = false;
            document.body.style.userSelect = "";
        });

        downloadBtn.onclick = function () {
            if (audio.src) {
                window.open(audio.src, '_blank');
            }
        };
        shareBtn.onclick = function () {
            if (navigator.share) {
                navigator.share({title: 'موزیک شما آماده است', text: 'همین حالا پلی کن!', url: audio.src});
            } else {
                alert('مرورگر شما از اشتراک‌گذاری پشتیبانی نمی‌کند.');
            }
        };
    });
</script>
