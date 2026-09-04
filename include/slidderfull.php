<?php
/**
 * include/slidderfull.php
 * Karuda Computers — Ajio.com Style Full-Width Image Slider Carousel
 */
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

// Banners Array (Full-bleed e-commerce banners like Ajio)
$ajio_banners = [
    [
        'img' => 'img/karuda_banner_laptops.jpg',
        'link' => 'allproducts.php?search=Laptops',
        'title' => 'Ultra Gaming Laptop Fest - Flat 35% Off'
    ],
    [
        'img' => 'img/karuda_banner_gpus.jpg',
        'link' => 'allproducts.php?search=Components',
        'title' => 'Karuda Hyperion RTX 4090 Custom Rigs - Official Warranty'
    ],
    [
        'img' => 'img/karuda_banner_monitors.jpg',
        'link' => 'allproducts.php?search=Monitors',
        'title' => 'Esports Curved OLED Displays - Up to 40% Off'
    ]
];
?>

<style>
/* ══════════════════════════════════════════════════════════
   AJIO STYLE FULL-WIDTH BANNER SLIDER
   ══════════════════════════════════════════════════════════ */
.kc-ajio-slider-section {
    position: relative;
    width: 100%;
    background: #090D16;
    padding: 16px 0;
    margin-bottom: 25px;
    overflow: hidden;
}

.kc-ajio-slider-container {
    position: relative;
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 15px;
}

.kc-ajio-slider-viewport {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    background: #020610;
}

.kc-ajio-slider-track {
    display: flex;
    width: 100%;
    transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
    will-change: transform;
}

.kc-ajio-slide {
    flex: 0 0 100%;
    width: 100%;
    box-sizing: border-box;
    position: relative;
}

.kc-ajio-slide-link {
    display: block;
    width: 100%;
    text-decoration: none;
    outline: none;
}

.kc-ajio-banner-img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    display: block;
    border-radius: 18px;
    transition: transform 0.4s ease;
}

.kc-ajio-slide-link:hover .kc-ajio-banner-img {
    transform: scale(1.015);
}

/* Nav Arrow Buttons (Ajio Floating Style) */
.kc-ajio-arrow-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.6);
    color: #0F172A;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}

.kc-btn-hero-blue {
    background: linear-gradient(135deg, #0070F3 0%, #0056B3 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 13px 30px;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    box-shadow: 0 6px 20px rgba(0, 112, 243, 0.45);
    transition: all 0.25s ease;
}
.kc-btn-hero-blue:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0, 188, 212, 0.55);
    background: linear-gradient(135deg, #0080FF 0%, #0070F3 100%);
}

.kc-btn-hero-outline {
    background: rgba(15, 23, 42, 0.6);
    border: 1.5px solid rgba(0, 188, 212, 0.4);
    color: #ffffff !important;
    border-radius: 10px;
    padding: 13px 26px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
}
.kc-btn-hero-outline:hover {
    border-color: #00BCD4;
    background: rgba(0, 188, 212, 0.15);
    color: #00BCD4 !important;
    transform: translateY(-2px);
}

/* Right Showcase Card */
.kc-hero-showcase-card {
    position: relative;
    z-index: 10;
    border-radius: 20px;
    padding: 8px;
    background: rgba(15, 23, 42, 0.5);
    border: 1.5px solid rgba(0, 188, 212, 0.35);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.kc-hero-showcase-img {
    width: 100%;
    max-height: 390px;
    object-fit: cover;
    border-radius: 14px;
    display: block;
=======
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    z-index: 20;
    transition: all 0.25s ease;
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.kc-ajio-arrow-btn:hover {
    background: #ffffff;
    color: #0070F3;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 112, 243, 0.35);
}

.kc-ajio-arrow-prev {
    left: 28px;
>>>>>>> 5b48e76 (Restructure index page layout, dynamic category arrows, sticky navbar search, and autosuggest fixes)
}

.kc-ajio-arrow-next {
    right: 28px;
}

/* Slide Indicator Dots */
.kc-ajio-dots-wrapper {
    position: absolute;
    bottom: 16px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 20;
    background: rgba(15, 23, 42, 0.6);
    padding: 6px 14px;
    border-radius: 20px;
    backdrop-filter: blur(8px);
}

.kc-ajio-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
}

.kc-ajio-dot.active {
    width: 28px;
    border-radius: 10px;
    background: #0070F3;
    box-shadow: 0 0 10px rgba(0, 112, 243, 0.8);
}

/* Mobile Responsive Adjustments */
@media (max-width: 991px) {
    .kc-ajio-banner-img {
        height: 300px;
        border-radius: 14px;
    }
    .kc-ajio-arrow-btn {
        width: 38px;
        height: 38px;
        font-size: 14px;
    }
    .kc-ajio-arrow-prev { left: 18px; }
    .kc-ajio-arrow-next { right: 18px; }
}

@media (max-width: 576px) {
    .kc-ajio-slider-container {
        padding: 0 8px;
    }
    .kc-ajio-banner-img {
        height: 195px;
        border-radius: 12px;
    }
    .kc-ajio-arrow-btn {
        display: none; /* Hide side arrows on small mobile, rely on dots & swipe */
    }
    .kc-ajio-dots-wrapper {
        bottom: 10px;
        padding: 4px 10px;
        gap: 6px;
    }
    .kc-ajio-dot {
        width: 7px;
        height: 7px;
    }
    .kc-ajio-dot.active {
        width: 18px;
    }
}
</style>

<!-- ══════════════════════════════════════════════════════════
     FULL-WIDTH AJIO STYLE SLIDER MARKUP
══════════════════════════════════════════════════════════ -->
<section class="kc-ajio-slider-section">
    <div class="kc-ajio-slider-container">
        <div class="kc-ajio-slider-viewport" id="kcAjioViewport">
            
            <!-- Prev Arrow -->
            <button type="button" class="kc-ajio-arrow-btn kc-ajio-arrow-prev" id="kcAjioPrevBtn" aria-label="Previous Slide">
                <i class="fa fa-chevron-left"></i>
            </button>

            <!-- Slider Track -->
            <div class="kc-ajio-slider-track" id="kcAjioTrack">
                <?php foreach ($ajio_banners as $idx => $b): ?>
                <div class="kc-ajio-slide">
                    <a href="<?php echo htmlspecialchars($b['link']); ?>" class="kc-ajio-slide-link" title="<?php echo htmlspecialchars($b['title']); ?>">
                        <img src="<?php echo htmlspecialchars($b['img']); ?>" alt="<?php echo htmlspecialchars($b['title']); ?>" class="kc-ajio-banner-img">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Next Arrow -->
            <button type="button" class="kc-ajio-arrow-btn kc-ajio-arrow-next" id="kcAjioNextBtn" aria-label="Next Slide">
                <i class="fa fa-chevron-right"></i>
            </button>

            <!-- Indicator Dots -->
            <div class="kc-ajio-dots-wrapper" id="kcAjioDots">
                <?php foreach ($ajio_banners as $idx => $b): ?>
                <span class="kc-ajio-dot <?php echo ($idx === 0) ? 'active' : ''; ?>" data-index="<?php echo $idx; ?>"></span>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<script>
(function() {
    const track = document.getElementById('kcAjioTrack');
    const prevBtn = document.getElementById('kcAjioPrevBtn');
    const nextBtn = document.getElementById('kcAjioNextBtn');
    const viewport = document.getElementById('kcAjioViewport');
    const dots = document.querySelectorAll('.kc-ajio-dot');

    if (!track || !prevBtn || !nextBtn || !viewport) return;

    let currentIndex = 0;
    const slides = track.children;
    const totalSlides = slides.length;

    function goToSlide(index) {
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        currentIndex = index;

        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

        // Update dots
        dots.forEach((dot, i) => {
            if (i === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    nextBtn.addEventListener('click', function() {
        goToSlide(currentIndex + 1);
    });

    prevBtn.addEventListener('click', function() {
        goToSlide(currentIndex - 1);
    });

    dots.forEach((dot, i) => {
        dot.addEventListener('click', function() {
            goToSlide(i);
        });
    });

    // Auto-play sliding every 3.5 seconds
    let autoTimer = setInterval(function() {
        goToSlide(currentIndex + 1);
    }, 3500);

    // Pause on hover
    viewport.addEventListener('mouseenter', function() {
        clearInterval(autoTimer);
    });

    viewport.addEventListener('mouseleave', function() {
        clearInterval(autoTimer);
        autoTimer = setInterval(function() {
            goToSlide(currentIndex + 1);
        }, 3500);
    });
})();
</script>