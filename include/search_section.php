<?php
/**
 * include/search_section.php
 * Prominent Hero Search Bar Section with Dynamic Category Arrow Alignment
 */
?>

<style>
/* ══════════════════════════════════════════════════════════
   KARUDA COMPUTERS — HERO SEARCH SECTION & CATEGORY ARROWS
   ══════════════════════════════════════════════════════════ */
.kc-hero-search-section {
    background: linear-gradient(180deg, #F8FAFC 0%, #EFF6FF 50%, #F8FAFC 100%);
    padding: 0 15px 22px 15px;
    position: relative;
    border-bottom: 1px solid #E2E8F0;
    font-family: 'Outfit', 'Poppins', sans-serif;
    overflow: visible;
}

/* Ambient Backdrop Glow Orb */
.kc-hero-search-section::before {
    content: '';
    position: absolute;
    top: 60%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 680px;
    height: 180px;
    background: radial-gradient(circle, rgba(0, 112, 243, 0.12) 0%, rgba(0, 188, 212, 0.04) 50%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    filter: blur(40px);
    z-index: 1;
}

.kc-hero-search-container {
    max-width: 860px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 2;
}

/* ── Dynamic Category Arrow Overlay Container ── */
.kc-category-arrows-wrap {
    display: none !important;
}

/* Main Search Card with Glassmorphism & Shadow Glow */
.kc-hero-search-card {
    margin-top: 10px;
    position: relative;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 2px solid #38BDF8 !important; /* Soft Light Cyan Border */
    border-radius: 50px;
    padding: 4px 6px 4px 22px;
    box-shadow: 
        0 8px 25px rgba(0, 188, 212, 0.14),
        0 3px 12px rgba(11, 25, 44, 0.03);
    display: flex;
    align-items: center;
    transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.kc-hero-search-card:hover {
    border-color: #00BCD4 !important;
    box-shadow: 
        0 12px 35px rgba(0, 188, 212, 0.22),
        0 4px 16px rgba(11, 25, 44, 0.05);
    transform: translateY(-2px);
}

.kc-hero-search-card:focus-within {
    border-color: #003B95 !important;
    background: #FFFFFF;
    box-shadow: 
        0 14px 40px rgba(0, 37, 102, 0.22),
        0 0 0 4px rgba(0, 59, 149, 0.18) !important;
    transform: translateY(-2px);
}

.kc-hero-search-icon {
    font-size: 17px;
    color: #003B95;
    margin-right: 12px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.kc-hero-search-card:focus-within .kc-hero-search-icon {
    transform: scale(1.12) rotate(-10deg);
}

.kc-hero-search-input {
    flex: 1;
    border: none !important;
    outline: none !important;
    background: transparent !important;
    font-size: 15px;
    font-weight: 500;
    color: #0F172A;
    padding: 7px 0 !important;
    box-shadow: none !important;
}

.kc-hero-search-input::placeholder {
    color: #94A3B8;
    font-weight: 400;
    font-size: 14.5px;
}

.kc-hero-search-btn {
    background: linear-gradient(135deg, #003B95 0%, #002566 100%);
    color: #FFFFFF;
    border: none;
    border-radius: 40px;
    padding: 9px 26px;
    font-size: 14.5px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.28s cubic-bezier(0.2, 0.8, 0.2, 1);
    box-shadow: 0 4px 16px rgba(0, 37, 102, 0.35);
    flex-shrink: 0;
}

.kc-hero-search-btn:hover {
    background: #002566;
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 37, 102, 0.5);
}

.kc-hero-search-btn:active {
    transform: scale(0.98);
}

/* ── Live Auto-Suggest Floating Dropdown Panel ── */
.kc-hero-suggest-box {
    position: absolute;
    top: calc(100% + 12px);
    left: 0;
    right: 0;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(11, 25, 44, 0.16);
    z-index: 9999;
    display: none;
    overflow: hidden;
    text-align: left;
}

.kc-hero-suggest-box.active {
    display: block;
    animation: kcSuggestSlideDown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes kcSuggestSlideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.kc-suggest-cat-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px 18px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
}

.kc-suggest-cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    color: #0070F3;
    border: 1px solid #DBEAFE;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.2s ease;
}

.kc-suggest-cat-pill:hover {
    background: #0070F3;
    color: #FFFFFF;
    border-color: #0070F3;
}

.kc-suggest-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    text-decoration: none !important;
    color: #0F172A;
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.2s ease;
}

.kc-suggest-item:last-child {
    border-bottom: none;
}

.kc-suggest-item:hover {
    background: #F8FAFC;
}

.kc-suggest-thumb {
    width: 48px;
    height: 48px;
    object-fit: contain;
    border-radius: 8px;
    background: #F1F5F9;
    padding: 4px;
    flex-shrink: 0;
}

.kc-suggest-info {
    flex: 1;
    overflow: hidden;
}

.kc-suggest-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0F172A;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
}

.kc-suggest-title mark {
    background: #FEF08A;
    color: #0F172A;
    padding: 0 2px;
    border-radius: 3px;
}

.kc-suggest-meta {
    font-size: 12px;
    color: #64748B;
}

.kc-suggest-price-col {
    text-align: right;
    flex-shrink: 0;
}

.kc-suggest-price {
    font-size: 15px;
    font-weight: 800;
    color: #0070F3;
}

.kc-suggest-oldprice {
    font-size: 11px;
    color: #94A3B8;
    text-decoration: line-through;
    margin-left: 4px;
}

.kc-suggest-footer {
    padding: 12px 20px;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    text-align: center;
}

.kc-suggest-view-all {
    color: #0070F3;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.kc-suggest-view-all:hover {
    color: #0056B3;
    text-decoration: underline !important;
}

@media (max-width: 768px) {
    .kc-hero-search-section {
        padding: 0 12px 30px 12px;
    }
    .kc-category-arrows-wrap {
        display: none; /* hide arrows on mobile — categories scroll horizontally */
    }
    .kc-hero-search-card {
        padding: 5px 7px 5px 18px;
        border-radius: 35px;
    }
    .kc-hero-search-input {
        font-size: 15px;
    }
    .kc-hero-search-input::placeholder {
        font-size: 14px;
    }
    .kc-hero-search-btn {
        display: inline-flex !important;
        padding: 0 !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        justify-content: center !important;
        align-items: center !important;
        background: linear-gradient(135deg, #00BCD4 0%, #0070F3 100%) !important;
        box-shadow: 0 4px 12px rgba(0, 188, 212, 0.4) !important;
        flex-shrink: 0 !important;
    }
    .kc-hero-search-btn span {
        display: none !important;
    }
    .kc-hero-search-btn i {
        font-size: 14px !important;
        margin: 0 !important;
    }
}
</style>

<section class="kc-hero-search-section">
    <div class="kc-hero-search-container">
        
        <!-- Central Search Bar Form -->
        <form action="allproducts.php" method="GET" class="kc-hero-search-card" id="kcHeroSearchForm">
            <i class="fa fa-search kc-hero-search-icon"></i>
            <input 
                type="text" 
                name="search" 
                id="kcHeroSearchInput"
                class="kc-hero-search-input" 
                placeholder="What would you like to search?" 
                autocomplete="off"
                required
            />
            <button type="submit" class="kc-hero-search-btn" aria-label="Search">
                <i class="fa fa-search"></i>
                <span>Search</span>
            </button>
        </form>

        <!-- Live Autosuggest Floating Panel -->
        <div class="kc-hero-suggest-box" id="kcHeroSuggestBox"></div>
    </div>
</section>

<script>
(function() {
    const input = document.getElementById('kcHeroSearchInput');
    const suggestBox = document.getElementById('kcHeroSuggestBox');
    const heroSection = document.querySelector('.kc-hero-search-section');
    let timer = null;

    // ── Dynamic Curved Arrow Connector Renderer (Fan/Funnel Style) ──
    function drawCategoryArrows() {
        const svg = document.getElementById('kcDynamicArrowsSvg');
        const searchCard = document.getElementById('kcHeroSearchForm');
        const catItems = document.querySelectorAll('.uls-cat-item-wrap');

        if (!svg || !searchCard || catItems.length === 0) return;

        const svgRect = svg.getBoundingClientRect();
        const searchRect = searchCard.getBoundingClientRect();

        if (svgRect.width === 0 || svgRect.height === 0) return;

        // All arrows: uniform electric blue
        const palette = [
            { stroke: '#0070F3', dash: '6 4' },
            { stroke: '#0070F3', dash: '5 5' },
            { stroke: '#0070F3', dash: '7 3' },
            { stroke: '#0070F3', dash: '4 5' },
            { stroke: '#0070F3', dash: '6 4' },
            { stroke: '#0070F3', dash: '5 4' },
        ];

        // Single shared arrowhead marker (all blue)
        let pathsHtml = `
            <defs>
                <marker id="kcArrowhead" viewBox="0 0 10 10" refX="8" refY="5"
                        markerWidth="5" markerHeight="5" orient="auto-start-reverse">
                    <path d="M 0 2 L 8 5 L 0 8 z" fill="#0070F3" opacity="0.9"/>
                </marker>
            </defs>
        `;

        const markerIds = ['kcArrowhead', 'kcArrowhead', 'kcArrowhead',
                           'kcArrowhead', 'kcArrowhead', 'kcArrowhead'];

        const totalCats = catItems.length;

        // All curves converge to the CENTER of the search bar top edge
        const searchCenterX = (searchRect.left - svgRect.left) + searchRect.width / 2;
        const endY = (searchRect.top - svgRect.top) + 4;

        catItems.forEach((cat, index) => {
            const catRect = cat.getBoundingClientRect();

            // Start: bottom-centre of category circle
            const startX = (catRect.left + catRect.width / 2) - svgRect.left;
            const startY = (catRect.bottom - 6) - svgRect.top;

            // End: converge to search bar center (fan/funnel effect)
            const endX = searchCenterX;

            /* Cubic Bézier control points for flowing fan-curve look:
             *  cp1 — drops straight down from the category (tangent = vertical)
             *  cp2 — comes from straight above the end point (tangent = vertical at bottom)
             * This gives a smooth S/arc shape like ink flowing downward.
             */
            const dy = endY - startY;
            const cp1x = startX;                          // same X as start → vertical drop
            const cp1y = startY + dy * 0.50;              // midway down, still over category
            const cp2x = endX + (startX - endX) * 0.18;  // slight lean toward start X
            const cp2y = endY - dy * 0.25;                // come in from slightly above

            const col = palette[index % palette.length];
            const markerId = markerIds[index % markerIds.length];

            pathsHtml += `
                <path d="M ${startX.toFixed(1)} ${startY.toFixed(1)}
                          C ${cp1x.toFixed(1)} ${cp1y.toFixed(1)},
                            ${cp2x.toFixed(1)} ${cp2y.toFixed(1)},
                            ${endX.toFixed(1)} ${endY.toFixed(1)}"
                      class="kc-arrow-path"
                      stroke="${col.stroke}"
                      stroke-width="2"
                      stroke-dasharray="${col.dash}"
                      opacity="0.82"
                      marker-end="url(#${markerId})" />
            `;
        });

        svg.innerHTML = pathsHtml;
    }

    // Initial draw & bind events
    window.addEventListener('load', drawCategoryArrows);
    window.addEventListener('resize', drawCategoryArrows);

    const track = document.getElementById('ulsCatTrack');
    if (track) {
        track.addEventListener('scroll', drawCategoryArrows);
    }

    setTimeout(drawCategoryArrows, 100);
    setTimeout(drawCategoryArrows, 500);

    if (!input || !suggestBox) return;

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    function highlightMatch(text, query) {
        if (!text || !query) return escapeHtml(text);
        const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp('(' + escaped + ')', 'gi');
        return escapeHtml(text).replace(regex, '<mark>$1</mark>');
    }

    let selectedIndex = -1;

    // ── Re-open suggest box on focus if query exists ──
    input.addEventListener('focus', function() {
        if (this.value.trim().length >= 1 && suggestBox.children.length > 0) {
            suggestBox.classList.add('active');
        }
    });

    // ── Live Auto-Suggest Search Handler ──
    input.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 1) {
            suggestBox.classList.remove('active');
            suggestBox.innerHTML = '';
            selectedIndex = -1;
            return;
        }

        timer = setTimeout(function() {
            fetch('api_search.php?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if ((!data.results || data.results.length === 0) && (!data.categories || data.categories.length === 0)) {
                        suggestBox.innerHTML = `
                            <div style="padding:22px 16px;text-align:center;color:#64748b;">
                                <i class="fa fa-search" style="font-size:22px;margin-bottom:8px;color:#94a3b8;"></i>
                                <p style="margin:0;font-size:14px;font-weight:600;">No products found for "<strong>${escapeHtml(q)}</strong>"</p>
                                <small style="color:#94a3b8;font-size:12px;">Try searching by brand like Asus, Lenovo, HP, Dell</small>
                            </div>
                        `;
                        suggestBox.classList.add('active');
                        selectedIndex = -1;
                        return;
                    }

                    let html = '';

                    // Categories Suggestions Pills
                    if (data.categories && data.categories.length > 0) {
                        html += `<div class="kc-suggest-cat-pills">`;
                        data.categories.forEach(cat => {
                            html += `
                                <a href="${cat.url}" class="kc-suggest-cat-pill">
                                    <i class="fa fa-folder-open"></i> ${escapeHtml(cat.name)}
                                </a>
                            `;
                        });
                        html += `</div>`;
                    }

                    // Product Results
                    if (data.results && data.results.length > 0) {
                        data.results.slice(0, 6).forEach((item, idx) => {
                            const imgSrc = item.image ? item.image : 'ovi-logo.png';
                            const priceFormatted = item.price ? ('₹' + item.price) : '';
                            const oldPriceFormatted = item.old_price ? ('₹' + item.old_price) : '';

                            html += `
                                <a href="${item.url}" class="kc-suggest-item" data-index="${idx}">
                                    <img src="${imgSrc}" alt="${escapeHtml(item.name)}" class="kc-suggest-thumb" onerror="this.src='ovi-logo.png'">
                                    <div class="kc-suggest-info">
                                        <div class="kc-suggest-title">${highlightMatch(item.name, q)}</div>
                                        <div class="kc-suggest-meta">${escapeHtml(item.category || 'Hardware')}</div>
                                    </div>
                                    <div class="kc-suggest-price-col">
                                        <div class="kc-suggest-price">${priceFormatted} ${oldPriceFormatted ? `<span class="kc-suggest-oldprice">${oldPriceFormatted}</span>` : ''}</div>
                                    </div>
                                </a>
                            `;
                        });
                    }

                    // Footer Link
                    html += `
                        <div class="kc-suggest-footer">
                            <a href="allproducts.php?search=${encodeURIComponent(q)}" class="kc-suggest-view-all">
                                See all matching results (${data.count || 0}) <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    `;

                    suggestBox.innerHTML = html;
                    suggestBox.classList.add('active');
                    selectedIndex = -1;
                })
                .catch(err => {
                    console.error('Search fetch error:', err);
                });
        }, 180);
    });

    // ── Keyboard Navigation ──
    input.addEventListener('keydown', function(e) {
        const items = suggestBox.querySelectorAll('.kc-suggest-item');
        if (!items.length || !suggestBox.classList.contains('active')) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateSelection(items);
        } else if (e.key === 'Enter' && selectedIndex >= 0 && items[selectedIndex]) {
            e.preventDefault();
            window.location.href = items[selectedIndex].href;
        } else if (e.key === 'Escape') {
            suggestBox.classList.remove('active');
        }
    });

    function updateSelection(items) {
        items.forEach((item, idx) => {
            if (idx === selectedIndex) {
                item.style.background = '#EFF6FF';
                item.style.borderLeft = '3px solid #0070F3';
            } else {
                item.style.background = 'transparent';
                item.style.borderLeft = 'none';
            }
        });
    }

    // ── Close suggest box ONLY on explicit outside click ──
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestBox.contains(e.target)) {
            suggestBox.classList.remove('active');
        }
    });

    // ── Sticky Scroll behavior ──
    const navSearchCol = document.querySelector('.uls-search-col');
    if (navSearchCol && heroSection) {
        window.addEventListener('scroll', function() {
            const rect = heroSection.getBoundingClientRect();
            if (rect.bottom < 60) {
                navSearchCol.classList.add('kc-show-top-search');
            } else {
                navSearchCol.classList.remove('kc-show-top-search');
            }
        }, { passive: true });
    }
})();
</script>
