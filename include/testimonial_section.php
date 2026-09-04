<?php
/**
 * include/testimonial_section.php
 * Karuda Computers — Section 9: 1-by-1 Interactive Sliding Testimonial Carousel
 */
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

// Fetch testimonials from DB
$testimonials_list = [];
if (isset($con) && $con) {
    $t_q = mysqli_query($con, "SELECT * FROM testi WHERE name IS NOT NULL AND name != '' AND message IS NOT NULL AND message != '' ORDER BY id DESC");
    if ($t_q && mysqli_num_rows($t_q) > 0) {
        while ($tr = mysqli_fetch_assoc($t_q)) {
            $testimonials_list[] = $tr;
        }
    }
}

// Fallback list if DB empty
if (empty($testimonials_list)) {
    $testimonials_list = [
        ['name' => 'Arun Karthik', 'design' => 'Senior 3D Animator & FX Artist', 'message' => 'Bought the Karuda Workstation Pro AI & 3D Render PC. Rendering Blender scenes with dual GPUs is insanely fast. Pristine cable management, genuine parts, and delivered within 48 hours. Karuda Computers is the best tech store!'],
        ['name' => 'Priya Ramachandran', 'design' => 'Esports Streamer & Content Creator', 'message' => 'Ordered the ASUS ROG Strix SCAR 18 gaming laptop and Samsung Odyssey G9 OLED monitor. The packaging was bulletproof and the setup works flawlessly at 240Hz. Highly recommended!'],
        ['name' => 'Vigneshwaran K.', 'design' => 'Lead Software Architect', 'message' => 'Upgraded my setup with an Intel Core i9-14900K, DDR5 RAM, and RTX 4080 Super. Everything came with original GST invoice and official manufacturer warranty. 100% genuine hardware!'],
        ['name' => 'Deepak Sundaram', 'design' => 'Full Stack Developer & Gamer', 'message' => 'Purchased the Karuda Hyperion RTX 4090 Gaming Rig. The liquid cooling tubes and cyan RGB lighting look out of this world! Runs 4K AAA games smoothly above 120 FPS. Fantastic service!'],
        ['name' => 'Kavitha Selvam', 'design' => 'UI/UX Design Lead', 'message' => 'Got the Lenovo Legion Slim 5 laptop and Logitech G PRO X Superlight mouse. Super lightweight, ultra-responsive, and perfect for both creative design work and gaming. Will buy all my tech from Karuda!']
    ];
}
?>
<style>
/* ══════════════════════════════════════════════════════════
   WHAT OUR CUSTOMERS SAY - FULL TESTIMONIALS CAROUSEL STYLES
   ══════════════════════════════════════════════════════════ */
.kc-testimonials-section {
    padding: 30px 0 !important;
    margin-top: 35px !important;
    margin-bottom: 45px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    position: relative !important;
}
.kc-testi-header {
    text-align: center !important;
    font-size: 22px !important;
    font-weight: 800 !important;
    color: #0F172A !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin: 0 0 28px 0 !important;
}
.kc-testi-main-wrapper {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
    width: 100% !important;
}
.kc-testi-arrow-btn {
    width: 44px !important;
    height: 44px !important;
    border-radius: 50% !important;
    background: #FFFFFF !important;
    border: 1.5px solid #CBD5E1 !important;
    color: #334155 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    flex-shrink: 0 !important;
    z-index: 10 !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06) !important;
}
.kc-testi-arrow-btn:hover {
    border-color: #0070F3 !important;
    color: #0070F3 !important;
    background: #F0F9FF !important;
    transform: scale(1.08) !important;
    box-shadow: 0 6px 18px rgba(0, 112, 243, 0.25) !important;
}
.kc-testi-slider-viewport {
    flex: 1 !important;
    overflow: hidden !important;
    position: relative !important;
    border-radius: 18px !important;
    padding: 8px 4px !important;
}
.kc-testi-slider-track {
    display: flex !important;
    gap: 20px !important;
    transition: transform 0.45s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    will-change: transform !important;
}
.kc-testi-slide-item {
    flex: 0 0 calc(33.333% - 13.33px) !important;
    width: calc(33.333% - 13.33px) !important;
    box-sizing: border-box !important;
}

/* Card Styling */
.kc-testi-card {
    background: #FFFFFF !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 16px !important;
    padding: 24px 22px !important;
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
    min-height: 205px !important;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04) !important;
    transition: all 0.25s ease !important;
    box-sizing: border-box !important;
}
.kc-testi-card:hover {
    border-color: #0070F3 !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 10px 25px rgba(0, 112, 243, 0.14) !important;
}
.kc-testi-avatar {
    width: 44px !important;
    height: 44px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%) !important;
    border: 1.5px solid #0070F3 !important;
    color: #0070F3 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 17px !important;
    margin-bottom: 14px !important;
    flex-shrink: 0 !important;
}
.kc-testi-quote {
    color: #334155 !important;
    font-size: 13.5px !important;
    line-height: 1.65 !important;
    margin: 0 0 18px 0 !important;
    flex: 1 !important;
    font-style: italic !important;
}
.kc-testi-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    padding-top: 12px !important;
    border-top: 1px solid #F1F5F9 !important;
    margin-top: auto !important;
}
.kc-testi-author-name {
    font-weight: 800 !important;
    font-size: 14px !important;
    color: #0F172A !important;
    margin: 0 0 2px 0 !important;
}
.kc-testi-author-role {
    font-size: 11.5px !important;
    color: #0070F3 !important;
    font-weight: 600 !important;
    margin: 0 !important;
}
.kc-testi-stars {
    color: #F59E0B !important;
    font-size: 11px !important;
    display: flex !important;
    gap: 3px !important;
}

@media (max-width: 991px) {
    .kc-testi-slide-item {
        flex: 0 0 calc(50% - 10px) !important;
        width: calc(50% - 10px) !important;
    }
}

@media (max-width: 640px) {
    .kc-testi-slider-track {
        gap: 0px !important;
    }
    .kc-testi-slide-item {
        flex: 0 0 100% !important;
        width: 100% !important;
        min-width: 100% !important;
        box-sizing: border-box !important;
    }
    .kc-testi-card {
        padding: 18px 16px !important;
        min-height: 180px !important;
    }
    .kc-testi-quote {
        font-size: 12.5px !important;
    }
    .kc-testi-arrow-btn {
        width: 34px !important;
        height: 34px !important;
        font-size: 13px !important;
    }
}
</style>

<section class="kc-testimonials-section container">
    <h2 class="kc-testi-header">WHAT OUR CUSTOMERS SAY</h2>
    
    <div class="kc-testi-main-wrapper">
        <!-- Prev Arrow Button -->
        <button type="button" class="kc-testi-arrow-btn" id="kcTestiPrevBtn" aria-label="Previous Testimonials">
            <i class="fa fa-chevron-left"></i>
        </button>

        <!-- Sliding Viewport -->
        <div class="kc-testi-slider-viewport" id="kcTestiViewport">
            <div class="kc-testi-slider-track" id="kcTestiTrack">
                <?php foreach ($testimonials_list as $t): 
                    $tname = htmlspecialchars($t['name']);
                    $tdesign = !empty($t['design']) ? htmlspecialchars($t['design']) : 'Verified Customer';
                    $tmsg = htmlspecialchars(trim($t['message']));
                ?>
                <div class="kc-testi-slide-item">
                    <div class="kc-testi-card">
                        <div class="kc-testi-avatar">
                            <i class="fa fa-user"></i>
                        </div>
                        <p class="kc-testi-quote">
                            "<?php echo $tmsg; ?>"
                        </p>
                        <div class="kc-testi-footer">
                            <div>
                                <h4 class="kc-testi-author-name"><?php echo $tname; ?></h4>
                                <p class="kc-testi-author-role"><?php echo $tdesign; ?></p>
                            </div>
                            <div class="kc-testi-stars">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Next Arrow Button -->
        <button type="button" class="kc-testi-arrow-btn" id="kcTestiNextBtn" aria-label="Next Testimonials">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>
</section>

<script>
(function() {
    const track = document.getElementById('kcTestiTrack');
    const prevBtn = document.getElementById('kcTestiPrevBtn');
    const nextBtn = document.getElementById('kcTestiNextBtn');
    const viewport = document.getElementById('kcTestiViewport');

    if (!track || !prevBtn || !nextBtn || !viewport) return;

    let currentIndex = 0;
    const slides = track.children;
    const totalSlides = slides.length;

    function getVisibleSlides() {
        const width = window.innerWidth;
        if (width <= 640) return 1;
        if (width <= 991) return 2;
        return 3;
    }

    function getGap() {
        return window.innerWidth <= 640 ? 0 : 20;
    }

    function updateSliderPosition() {
        const visible = getVisibleSlides();
        const maxIndex = Math.max(0, totalSlides - visible);
        if (currentIndex > maxIndex) currentIndex = 0;
        if (currentIndex < 0) currentIndex = maxIndex;

        const gap = getGap();
        const slideWidth = slides[0].offsetWidth + gap;
        track.style.transform = 'translateX(-' + (currentIndex * slideWidth) + 'px)';
    }

    nextBtn.addEventListener('click', function() {
        const visible = getVisibleSlides();
        const maxIndex = Math.max(0, totalSlides - visible);
        if (currentIndex >= maxIndex) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        updateSliderPosition();
    });

    prevBtn.addEventListener('click', function() {
        const visible = getVisibleSlides();
        const maxIndex = Math.max(0, totalSlides - visible);
        if (currentIndex <= 0) {
            currentIndex = maxIndex;
        } else {
            currentIndex--;
        }
        updateSliderPosition();
    });

    // Auto-slide every 3.5 seconds (1 by 1)
    let autoTimer = setInterval(function() {
        nextBtn.click();
    }, 3500);

    // Pause auto-slide on hover
    viewport.addEventListener('mouseenter', function() { clearInterval(autoTimer); });
    viewport.addEventListener('mouseleave', function() {
        clearInterval(autoTimer);
        autoTimer = setInterval(function() { nextBtn.click(); }, 3500);
    });

    window.addEventListener('resize', updateSliderPosition);
})();
</script>
