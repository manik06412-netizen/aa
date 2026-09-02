<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — ULTRA MODERN FAQ SECTION
══════════════════════════════════════════════════════════ -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');

/* Scoped FAQ Accordion Styling */
.kc-faq-section {
    background: #F8FAFC !important;
    padding: 60px 0 75px !important;
    position: relative;
    font-family: 'Outfit', 'Poppins', sans-serif !important;
}
.kc-modern-heading {
    font-family: 'Outfit', 'Poppins', sans-serif !important;
    font-size: 34px !important;
    font-weight: 800 !important;
    color: #0B192C !important;
    letter-spacing: -0.5px !important;
    margin-top: 10px !important;
    margin-bottom: 12px !important;
}
.kc-heading-gradient {
    background: linear-gradient(135deg, #0070F3 0%, #00BCD4 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    font-weight: 900 !important;
}
.kc-section-sub {
    font-family: 'Outfit', 'Poppins', sans-serif !important;
    font-size: 15px !important;
    color: #64748B !important;
    max-width: 680px !important;
    margin: 0 auto !important;
    line-height: 1.6 !important;
}
.kc-faq-accordion {
    display: flex !important;
    flex-direction: column !important;
    gap: 16px !important;
    width: 100% !important;
}
.kc-faq-card {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    box-shadow: 0 4px 18px rgba(11, 25, 44, 0.04) !important;
    position: relative !important;
    display: block !important;
    width: 100% !important;
}
.kc-faq-card::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    bottom: 0 !important;
    width: 5px !important;
    background: linear-gradient(180deg, #0070F3, #00BCD4) !important;
    opacity: 0;
    transition: opacity 0.3s ease !important;
}
.kc-faq-card:hover {
    border-color: #93C5FD !important;
    box-shadow: 0 10px 28px rgba(0, 112, 243, 0.1) !important;
    transform: translateY(-2px);
}
.kc-faq-card.active-faq {
    border-color: #60A5FA !important;
    box-shadow: 0 12px 32px rgba(0, 112, 243, 0.12) !important;
}
.kc-faq-card.active-faq::before {
    opacity: 1 !important;
}

/* Header Button */
button.kc-faq-header {
    width: 100% !important;
    background: #ffffff !important;
    border: none !important;
    border-radius: 16px !important;
    padding: 20px 24px !important;
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 16px !important;
    cursor: pointer !important;
    text-align: left !important;
    outline: none !important;
    box-shadow: none !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    transition: all 0.25s ease !important;
}
button.kc-faq-header:focus {
    outline: none !important;
    box-shadow: none !important;
}
.kc-faq-question-wrap {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 16px !important;
    flex: 1 !important;
}
.kc-faq-q-num {
    font-family: 'Outfit', sans-serif !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    color: #0B192C !important;
    background: #EFF6FF !important;
    border: 1px solid #DBEAFE !important;
    width: 36px !important;
    height: 36px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
    transition: all 0.25s ease !important;
}
.kc-faq-card.active-faq .kc-faq-q-num {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    border-color: #0070F3 !important;
    box-shadow: 0 4px 12px rgba(0, 112, 243, 0.35) !important;
}
.kc-faq-question {
    font-family: 'Outfit', sans-serif !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #0B192C !important;
    margin: 0 !important;
    letter-spacing: -0.2px !important;
    transition: color 0.25s ease !important;
    line-height: 1.4 !important;
}
.kc-faq-card.active-faq .kc-faq-question,
button.kc-faq-header:hover .kc-faq-question {
    color: #0070F3 !important;
}

/* Toggle Icon */
.kc-faq-toggle-icon {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    background: #F8FAFC !important;
    border: 1px solid #E2E8F0 !important;
    color: #64748B !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 13px !important;
    flex-shrink: 0 !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
}
.kc-faq-card.active-faq .kc-faq-toggle-icon {
    background: #0B192C !important;
    border-color: #0B192C !important;
    color: #ffffff !important;
    transform: rotate(45deg) !important;
    box-shadow: 0 4px 12px rgba(11, 25, 44, 0.35) !important;
}

/* Answer Body */
.kc-faq-body {
    padding: 0 24px 22px 76px !important;
}
@media (max-width: 767px) {
    .kc-faq-body {
        padding: 0 18px 18px !important;
    }
}
.kc-faq-answer-inner {
    background: #F8FAFC !important;
    border-radius: 12px !important;
    padding: 18px 22px !important;
    border: 1px solid #F1F5F9 !important;
}
.kc-faq-answer-inner p {
    font-size: 14.5px !important;
    color: #475569 !important;
    line-height: 1.75 !important;
    margin: 0 !important;
}

/* Support Prompt Bar */
.kc-faq-support-bar {
    background: linear-gradient(135deg, #0B192C 0%, #1A365D 100%) !important;
    border-radius: 18px !important;
    padding: 24px 30px !important;
    border: 1px solid rgba(0, 188, 212, 0.25) !important;
    box-shadow: 0 10px 30px rgba(11, 25, 44, 0.15) !important;
    color: #ffffff !important;
}

/* Full Width Promo Ad Banner Card (Fetched from Admin) */
.kc-faq-ad-card {
    background: linear-gradient(135deg, #0B192C 0%, #1E3E62 100%) !important;
    border-radius: 20px !important;
    overflow: hidden !important;
    box-shadow: 0 15px 35px rgba(11, 25, 44, 0.25) !important;
    border: 1px solid rgba(0, 188, 212, 0.3) !important;
    position: relative !important;
}
.kc-ad-badge {
    display: inline-block !important;
    background: rgba(0, 112, 243, 0.25) !important;
    color: #38BDF8 !important;
    border: 1px solid #0070F3 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    padding: 4px 12px !important;
    border-radius: 20px !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    margin-bottom: 12px !important;
}
.kc-ad-title {
    font-family: 'Outfit', 'Poppins', sans-serif !important;
    font-size: 24px !important;
    font-weight: 900 !important;
    color: #ffffff !important;
    line-height: 1.3 !important;
    margin-bottom: 10px !important;
    text-transform: uppercase !important;
}
.kc-ad-sub {
    font-family: 'Outfit', 'Poppins', sans-serif !important;
    font-size: 14.5px !important;
    color: #94A3B8 !important;
    margin: 0 !important;
    line-height: 1.6 !important;
}
.kc-ad-btn {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    padding: 12px 28px !important;
    border-radius: 10px !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    box-shadow: 0 6px 20px rgba(0, 188, 212, 0.35) !important;
    transition: all 0.25s ease !important;
}
.kc-ad-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 25px rgba(0, 188, 212, 0.5) !important;
    color: #ffffff !important;
    text-decoration: none !important;
}
.kc-ad-img {
    max-height: 200px !important;
    border-radius: 12px !important;
    object-fit: cover !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
}
.kc-support-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(0, 188, 212, 0.15);
    border: 1px solid rgba(0, 188, 212, 0.35);
    color: #00BCD4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.kc-support-title {
    font-family: 'Outfit', sans-serif !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    margin: 0 0 3px !important;
}
.kc-support-sub {
    font-size: 13px !important;
    color: #94A3B8 !important;
    margin: 0 !important;
}
.kc-support-btn {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    padding: 12px 24px !important;
    border-radius: 10px !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    box-shadow: 0 4px 16px rgba(0, 188, 212, 0.4) !important;
    transition: all 0.25s ease !important;
    white-space: nowrap !important;
}
.kc-support-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(0, 188, 212, 0.6) !important;
    color: #ffffff !important;
    text-decoration: none !important;
}
</style>

<section class="kc-faq-section py-5">
    <div class="container">
        
        <!-- Section Header -->
        <div class="kc-section-header text-center mb-5">
            <span class="kc-badge-pill">
                <i class="fa fa-circle-question"></i> Help & Clarifications
            </span>
            <h2 class="kc-modern-heading">
                Frequently Asked <span class="kc-heading-gradient">Questions</span>
            </h2>
            <p class="kc-section-sub">
                Quick answers to common queries regarding genuine hardware, custom PC builds, warranty coverage, and nationwide delivery.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">
                <div class="kc-faq-accordion" id="kc_faq_accordion">
                    <?php
                    $faqs_list = [];
                    if (isset($faqs) && is_array($faqs) && !empty($faqs)) {
                        $faqs_list = $faqs;
                    } else {
                        $sql = "SELECT * FROM tbl_faq ORDER BY faq_id ASC LIMIT 6";
                        $query = mysqli_query($con, $sql);
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($r = mysqli_fetch_assoc($query)) {
                                $faqs_list[] = $r;
                            }
                        }
                    }

                    // Fallback if no database records
                    if (empty($faqs_list)) {
                        $faqs_list = [
                            [
                                'faq_id' => 1,
                                'faq_title' => 'What payment methods do you accept?',
                                'faq_content' => 'We accept all major Credit/Debit cards (Visa, Mastercard, RuPay), UPI (Google Pay, PhonePe, Paytm, BHIM), Net Banking, and secure EMI options.'
                            ],
                            [
                                'faq_id' => 2,
                                'faq_title' => 'Can I upgrade my RAM, SSD, or graphics card after purchase?',
                                'faq_content' => 'Yes! We design all our prebuilt desktop systems with upgradeability in mind. Our support team can advise you on compatible future upgrades at any time.'
                            ],
                            [
                                'faq_id' => 3,
                                'faq_title' => 'What is the standard delivery timeline for orders across India?',
                                'faq_content' => 'Orders are securely packed with multi-layer protective packaging and dispatched within 24–48 hours. Standard courier delivery takes 3–5 business days with real-time tracking.'
                            ],
                            [
                                'faq_id' => 4,
                                'faq_title' => 'What warranty support is provided on laptops, GPUs, and processors?',
                                'faq_content' => 'All products come with 100% official manufacturer warranty (typically 1 to 3 years depending on the brand and part). You can claim warranty at any authorized service center or via Karuda Computers support.'
                            ],
                            [
                                'faq_id' => 5,
                                'faq_title' => 'Do you provide Custom PC Building services for gaming and workstations?',
                                'faq_content' => 'Absolutely. Our technical experts assist in part selection, compatibility verification, high-airflow assembly, cable management, BIOS configuration, stress-testing, and OS installation.'
                            ],
                            [
                                'faq_id' => 6,
                                'faq_title' => 'Are all computers, laptops and parts sold by Karuda Computers 100% genuine?',
                                'faq_content' => 'Yes! We only source brand-new, 100% genuine products directly from authorized global manufacturers including HP, ASUS, Lenovo, Intel, AMD, NVIDIA, and Dell, backed by official brand warranties.'
                            ]
                        ];
                    }

                    $idx = 0;
                    foreach ($faqs_list as $faq):
                        $idx++;
                        $fid = !empty($faq['faq_id']) ? $faq['faq_id'] : $idx;
                        $title = !empty($faq['faq_title']) ? $faq['faq_title'] : ($faq['q'] ?? '');
                        $content = !empty($faq['faq_content']) ? $faq['faq_content'] : ($faq['a'] ?? '');
                        $isOpen = ($idx === 1);
                    ?>
                    <div class="kc-faq-card <?php echo $isOpen ? 'active-faq' : ''; ?>" id="faq_wrap_<?php echo $fid; ?>">
                        <button class="kc-faq-header <?php echo $isOpen ? '' : 'collapsed'; ?>" 
                                type="button" 
                                onclick="toggleFaq('<?php echo $fid; ?>')">
                            <div class="kc-faq-question-wrap">
                                <span class="kc-faq-q-num"><?php echo str_pad($idx, 2, '0', STR_PAD_LEFT); ?></span>
                                <span class="kc-faq-question"><?php echo htmlspecialchars($title); ?></span>
                            </div>
                            <span class="kc-faq-toggle-icon">
                                <i class="fa fa-plus"></i>
                            </span>
                        </button>
                        
                        <div id="faq_body_<?php echo $fid; ?>" 
                             class="kc-faq-collapse-body"
                             style="<?php echo $isOpen ? 'display:block;' : 'display:none;'; ?>">
                            <div class="kc-faq-body">
                                <div class="kc-faq-answer-inner">
                                    <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Dynamic Admin Advertisement Card Section -->
                <?php
                // Fetch dynamic advertisement banner set from Admin Panel
                $ad_data = null;
                if (isset($con) && $con) {
                    // Check adds table first
                    $ad_q = mysqli_query($con, "SELECT * FROM adds ORDER BY id DESC LIMIT 1");
                    if ($ad_q && mysqli_num_rows($ad_q) > 0) {
                        $ad_row = mysqli_fetch_assoc($ad_q);
                        if (!empty($ad_row['fpath'])) {
                            $ad_data = [
                                'title' => 'SPECIAL TECH PROMO',
                                'subtitle' => 'Exclusive Deals on Laptops, Custom PCs & Computer Hardware',
                                'img' => resolve_image_url($ad_row['fpath']),
                                'link' => 'allproducts.php',
                                'badge' => 'ADMIN FEATURED AD'
                            ];
                        }
                    }
                    // Fallback to banner table if no adds table row
                    if (!$ad_data) {
                        $ban_q = mysqli_query($con, "SELECT * FROM banner ORDER BY id DESC LIMIT 1");
                        if ($ban_q && mysqli_num_rows($ban_q) > 0) {
                            $b_row = mysqli_fetch_assoc($ban_q);
                            $ad_data = [
                                'title' => !empty($b_row['k1']) ? strip_tags($b_row['k1']) : 'KARUDA COMPUTERS SPECIAL OFFER',
                                'subtitle' => !empty($b_row['k3']) ? $b_row['k3'] : 'Upgrade your setup with genuine high-performance computer hardware.',
                                'img' => !empty($b_row['fpath']) ? resolve_image_url($b_row['fpath']) : 'img/karuda_hero_gaming_pc.jpg',
                                'link' => !empty($b_row['link']) ? $b_row['link'] : 'allproducts.php',
                                'badge' => !empty($b_row['k2']) ? $b_row['k2'] : 'EXCLUSIVE PROMO'
                            ];
                        }
                    }
                }

                // Default Fallback Card if DB returns empty
                if (!$ad_data) {
                    $ad_data = [
                        'title' => 'BUILD YOUR CUSTOM GAMING PC TODAY',
                        'subtitle' => 'Get up to 25% OFF on RTX GPUs, Intel i9 Processors & DDR5 RAM + Free Nationwide Express Delivery!',
                        'img' => 'img/karuda_hero_gaming_pc.jpg',
                        'link' => 'allproducts.php',
                        'badge' => 'EXCLUSIVE TECH PROMO'
                    ];
                }
                ?>
                <div class="kc-faq-ad-card mt-5 mb-4">
                    <div class="kc-ad-card-inner">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-6 p-4 p-lg-5">
                                <span class="kc-ad-badge"><i class="fa fa-fire text-warning mr-1"></i> <?php echo htmlspecialchars($ad_data['badge']); ?></span>
                                <h3 class="kc-ad-title"><?php echo htmlspecialchars($ad_data['title']); ?></h3>
                                <p class="kc-ad-sub"><?php echo htmlspecialchars($ad_data['subtitle']); ?></p>
                                <div class="mt-4">
                                    <a href="<?php echo htmlspecialchars($ad_data['link']); ?>" class="kc-ad-btn">
                                        <span>EXPLORE DEALS</span> <i class="fa fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6 text-center p-3">
                                <img src="<?php echo htmlspecialchars($ad_data['img']); ?>" alt="Advertisement Promo" class="kc-ad-img img-fluid" onerror="this.src='img/karuda_hero_gaming_pc.jpg'">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Support Banner -->
                <div class="kc-faq-support-bar mt-4">
                    <div class="d-flex align-items-center flex-wrap justify-content-between" style="gap:15px;">
                        <div class="d-flex align-items-center" style="gap:14px;">
                            <div class="kc-support-icon">
                                <i class="fa fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="kc-support-title">Still have questions or need custom hardware guidance?</h4>
                                <p class="kc-support-sub">Our PC specialists are available 7 days a week to help you choose the right specs.</p>
                            </div>
                        </div>
                        <a href="contact.php" class="kc-support-btn">
                            <span>Get Tech Consultation</span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
function toggleFaq(fid) {
    var body = document.getElementById('faq_body_' + fid);
    var wrap = document.getElementById('faq_wrap_' + fid);
    if (!body || !wrap) return;

    var isCurrentlyOpen = wrap.classList.contains('active-faq');

    // Close all other FAQs
    document.querySelectorAll('.kc-faq-card').forEach(function(card) {
        card.classList.remove('active-faq');
    });
    document.querySelectorAll('.kc-faq-collapse-body').forEach(function(b) {
        b.style.display = 'none';
    });

    // If it was closed, open it now
    if (!isCurrentlyOpen) {
        wrap.classList.add('active-faq');
        body.style.display = 'block';
    }
}
</script>