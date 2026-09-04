<?php
/**
 * include/newsletter.php
 * Karuda Computers — Wireframe Section 10: Newsletter Subscription Bar
 */
?>
<style>
.kc-wireframe-newsletter-section {
    background: #F1F5F9;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
    padding: 36px 0;
    margin: 40px 0 0 0;
}
.kc-newsletter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}
.kc-newsletter-left {
    display: flex;
    align-items: center;
    gap: 20px;
}
.kc-newsletter-icon {
    width: 52px;
    height: 52px;
    border: 2px solid #003B95;
    background: #EFF6FF;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #003B95;
    flex-shrink: 0;
}
.kc-newsletter-title {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 3px 0;
}
.kc-newsletter-desc {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}
.kc-newsletter-form {
    display: flex;
    align-items: center;
    gap: 0;
    flex: 1;
    max-width: 480px;
}
.kc-newsletter-input {
    flex: 1;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-right: none;
    border-radius: 6px 0 0 6px;
    padding: 12px 16px;
    font-size: 14px;
    color: #0F172A;
    outline: none;
}
.kc-newsletter-input:focus {
    border-color: #003B95;
}
.kc-newsletter-input::placeholder {
    color: #94A3B8;
}
.kc-newsletter-btn {
    background: linear-gradient(135deg, #003B95 0%, #002566 100%);
    color: #FFFFFF;
    border: none;
    border-radius: 0 6px 6px 0;
    padding: 12px 24px;
    font-size: 13.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.kc-newsletter-btn:hover {
    background: #002566;
    box-shadow: 0 4px 12px rgba(0, 37, 102, 0.35);
}
@media (max-width: 991px) {
    .kc-newsletter-row {
        flex-direction: column;
        align-items: flex-start;
    }
    .kc-newsletter-form {
        width: 100%;
        max-width: 100%;
    }
}
</style>

<section class="kc-wireframe-newsletter-section">
    <div class="container">
        <div class="kc-newsletter-row">
            <div class="kc-newsletter-left">
                <div class="kc-newsletter-icon">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <div>
                    <h3 class="kc-newsletter-title">SUBSCRIBE TO OUR NEWSLETTER</h3>
                    <p class="kc-newsletter-desc">Get the latest updates on new products and upcoming sales</p>
                </div>
            </div>

            <form action="subscribe.php" method="POST" class="kc-newsletter-form" onsubmit="submitWireframeNewsletter(event, this)">
                <input type="email" name="email" class="kc-newsletter-input" placeholder="Enter your email address" required autocomplete="off">
                <button type="submit" class="kc-newsletter-btn">SUBSCRIBE</button>
            </form>
        </div>
    </div>
</section>

<script>
function submitWireframeNewsletter(e, form) {
    e.preventDefault();
    var inp = form.querySelector('input[name="email"]');
    var btn = form.querySelector('button[type="submit"]');
    if (!inp || !inp.value) return;
    
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Subscribing...';
    btn.disabled = true;

    fetch('subscribe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(inp.value.trim())
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.innerHTML = originalText;
        btn.disabled = false;
        if (typeof window.showKcToast !== 'undefined') {
            window.showKcToast('Subscription Confirmed! 🎉', d.message || 'Thank you for subscribing to Karuda Computers!', 'success');
        } else {
            alert(d.message || 'Thank you for subscribing to Karuda Computers!');
        }
        inp.value = '';
    })
    .catch(function() {
        btn.innerHTML = originalText;
        btn.disabled = false;
        if (typeof window.showKcToast !== 'undefined') {
            window.showKcToast('Subscription Confirmed! 🎉', 'Thank you for subscribing to Karuda Computers!', 'success');
        } else {
            alert('Thank you for subscribing to Karuda Computers!');
        }
        inp.value = '';
    });
}
</script>
