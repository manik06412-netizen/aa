<?php
/**
 * KARUDA HERBAL - Database Seed Runner
 * URL: http://localhost/karuda/aa/seed_runner.php
 * 
 * This script inserts 5 categories + 10 products into av_herbal1 database.
 * DELETE this file after use for security.
 */

// Simple security token (change before using)
define('SEED_TOKEN', 'karuda2026');

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ── DB Connection ──────────────────────────────────────
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'av_herbal1';

$con = mysqli_connect($host, $user, $password, $dbname);
if (!$con) {
    die("<b style='color:red'>DB Connection Failed: " . mysqli_connect_error() . "</b>");
}
mysqli_set_charset($con, 'utf8');

$results   = [];
$errors    = [];
$confirmed = false;
$token_ok  = false;

if (isset($_GET['token']) && $_GET['token'] === SEED_TOKEN) {
    $token_ok = true;
}

if ($token_ok && isset($_POST['action']) && $_POST['action'] === 'seed') {
    $confirmed = true;

    // ── Images used (already copied to Res_img/dishes/) ──
    $img_immunity  = 'Res_img/dishes/cat_immunity.png';
    $img_skincare  = 'Res_img/dishes/cat_skincare.png';
    $img_digestive = 'Res_img/dishes/cat_digestive.png';
    $img_haircare  = 'Res_img/dishes/cat_haircare.png';
    $img_stress    = 'Res_img/dishes/cat_stress.png';
    $img_ashwa     = 'Res_img/dishes/prod_ashwagandha.png';

    // ══════════════════════════════════════════════════
    // INSERT 5 CATEGORIES
    // ══════════════════════════════════════════════════
    $categories = [
        [
            'c_name' => 'Immunity Boosters',
            'k1'     => 'immunity, tulsi, ashwagandha, antioxidants, herbal',
            'k2'     => 'Products that boost immunity naturally using Ayurvedic herbs like Tulsi, Ashwagandha, Giloy, and Amla',
            'fpath'  => $img_immunity,
            'icon'   => $img_immunity,
        ],
        [
            'c_name' => 'Skin Care',
            'k1'     => 'skincare, neem, aloe vera, turmeric, glow, face',
            'k2'     => 'Natural herbal skincare products made with Neem, Turmeric, Aloe Vera, Rose water for glowing skin',
            'fpath'  => $img_skincare,
            'icon'   => $img_skincare,
        ],
        [
            'c_name' => 'Digestive Health',
            'k1'     => 'digestion, ginger, triphala, gut health, ayurveda',
            'k2'     => 'Herbal digestive aids using Triphala, Ginger, Fennel, Ajwain for gut wellness and digestion',
            'fpath'  => $img_digestive,
            'icon'   => $img_digestive,
        ],
        [
            'c_name' => 'Hair Care',
            'k1'     => 'hair, bhringraj, amla, neem, coconut, growth, oil',
            'k2'     => 'Ayurvedic hair care products using Bhringraj, Amla, Coconut, Neem for hair growth and scalp health',
            'fpath'  => $img_haircare,
            'icon'   => $img_haircare,
        ],
        [
            'c_name' => 'Stress & Sleep',
            'k1'     => 'stress, sleep, brahmi, ashwagandha, anxiety, calm, relax',
            'k2'     => 'Natural herbs for stress relief and better sleep including Ashwagandha, Brahmi, Shankhpushpi',
            'fpath'  => $img_stress,
            'icon'   => $img_stress,
        ],
    ];

    $cat_ids = []; // store c_id mapped by c_name
    $cat_stmt = $con->prepare(
        "INSERT INTO res_category (c_name, k1, k2, fpath, icon) VALUES (?,?,?,?,?)
         ON DUPLICATE KEY UPDATE k1=VALUES(k1), k2=VALUES(k2), fpath=VALUES(fpath), icon=VALUES(icon)"
    );

    foreach ($categories as $cat) {
        // Check if already exists
        $check = $con->prepare("SELECT c_id FROM res_category WHERE c_name = ?");
        $check->bind_param("s", $cat['c_name']);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->bind_result($existing_id);
            $check->fetch();
            $cat_ids[$cat['c_name']] = $existing_id;
            $results[] = "✅ Category '{$cat['c_name']}' already exists (ID: $existing_id) — skipped.";
            $check->close();
            continue;
        }
        $check->close();

        $cat_stmt->bind_param("sssss",
            $cat['c_name'], $cat['k1'], $cat['k2'], $cat['fpath'], $cat['icon']
        );
        if ($cat_stmt->execute()) {
            $new_id = $con->insert_id;
            $cat_ids[$cat['c_name']] = $new_id;
            $results[] = "✅ Category '{$cat['c_name']}' inserted (ID: $new_id)";
        } else {
            $errors[] = "❌ Category '{$cat['c_name']}' failed: " . $cat_stmt->error;
        }
    }
    $cat_stmt->close();

    // ══════════════════════════════════════════════════
    // INSERT 10 PRODUCTS
    // ══════════════════════════════════════════════════
    $products = [
        // ── Immunity Boosters ──
        [
            'rs_id'       => 1001,
            'dish_name'   => 'Ashwagandha Immunity Capsules',
            'description' => 'Karuda Ashwagandha Immunity Capsules are made from pure Withania somnifera root extract, standardized to 5% withanolides. Ashwagandha is a powerful adaptogen herb used in Ayurveda for over 3000 years to strengthen the immune system, reduce stress, and boost energy. Each capsule contains 500mg of certified organic Ashwagandha root powder. Key Benefits: Boosts natural immunity, Reduces cortisol levels and stress, Improves energy and stamina, Supports adrenal health, Enhances cognitive function. Ingredients: Ashwagandha Root Extract (Withania somnifera) 500mg, Organic Black Pepper Extract (Bioperine) 5mg for enhanced absorption. Directions: Take 1-2 capsules daily with warm milk or water after meals. Best results seen in 4-6 weeks of regular use. Suitable for adults above 18 years.',
            'img'         => $img_ashwa,
            'img2'        => $img_immunity,
            'img3'        => $img_immunity,
            'img4'        => $img_immunity,
            'img5'        => $img_immunity,
            'category'    => 'Immunity Boosters',
            'subcate'     => 'Herbal Capsules',
            'brand_name'  => 'Karuda Herbals',
            'no_items'    => '1',
            'barcode'     => '671234-891234',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '12',
            'keywords'    => 'ashwagandha, immunity, stress relief, energy booster, adaptogen',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        [
            'rs_id'       => 1002,
            'dish_name'   => 'Giloy Tulsi Immunity Syrup',
            'description' => 'Karuda Giloy Tulsi Immunity Syrup is a potent Ayurvedic formulation combining the goodness of Giloy (Tinospora cordifolia), Holy Basil (Tulsi), and Amla (Indian Gooseberry). This triple-action immunity booster strengthens the body\'s natural defense system, fights infections, and supports respiratory health. Key Benefits: Powerful antioxidant protection, Fights common cold, cough, and viral infections, Improves respiratory function, Rich in Vitamin C from Amla, Detoxifies blood and liver. Ingredients: Giloy Extract 200mg, Tulsi Extract 150mg, Amla Extract 100mg, Ginger Extract 50mg per 10ml. Directions: Take 10ml (2 teaspoons) with equal water twice daily before meals. Can be given to children above 5 years in half dose.',
            'img'         => $img_immunity,
            'img2'        => $img_immunity,
            'img3'        => $img_immunity,
            'img4'        => $img_immunity,
            'img5'        => $img_immunity,
            'category'    => 'Immunity Boosters',
            'subcate'     => 'Herbal Syrup',
            'brand_name'  => 'Karuda Herbals',
            'no_items'    => '1',
            'barcode'     => '671235-891235',
            'age_range'   => 'All',
            'ratings'     => 4,
            'best_before' => '6',
            'keywords'    => 'giloy, tulsi, amla, immunity syrup, cold relief',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        // ── Skin Care ──
        [
            'rs_id'       => 1003,
            'dish_name'   => 'Neem Turmeric Face Wash',
            'description' => 'Karuda Neem Turmeric Face Wash is an advanced Ayurvedic face cleanser formulated with wild-crafted Neem leaf extract and 24-karat gold-grade Turmeric (Curcumin). This gentle yet powerful face wash deeply cleanses pores, removes excess oil, fights acne-causing bacteria, and leaves skin bright and refreshed. Key Benefits: Deep pore cleansing without stripping natural oils, Fights acne, pimples, and blackheads, Anti-bacterial and anti-fungal properties of Neem, Turmeric brightens skin tone and reduces blemishes, Suitable for all skin types. Ingredients: Neem Leaf Extract 2%, Turmeric Extract (Curcumin 95%) 1%, Aloe Vera Gel, Tea Tree Oil, Chamomile Flower Extract, SLS-free base. Directions: Apply small amount on wet face, lather gently in circular motion for 60 seconds, rinse with cool water. Use twice daily.',
            'img'         => $img_skincare,
            'img2'        => $img_skincare,
            'img3'        => $img_skincare,
            'img4'        => $img_skincare,
            'img5'        => $img_skincare,
            'category'    => 'Skin Care',
            'subcate'     => 'Face Wash',
            'brand_name'  => 'Karuda Naturals',
            'no_items'    => '1',
            'barcode'     => '671236-891236',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '12',
            'keywords'    => 'neem face wash, turmeric, acne, pimples, herbal cleanser',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        [
            'rs_id'       => 1004,
            'dish_name'   => 'Rose Aloe Vera Skin Glow Cream',
            'description' => 'Karuda Rose Aloe Vera Skin Glow Cream is a luxurious Ayurvedic day moisturizer crafted from cold-pressed Rose water, organic Aloe Vera, Kumkumadi Tailam (rare saffron oil blend), and Sandalwood extract. This non-greasy, fast-absorbing cream provides 24-hour hydration, reduces dark spots, and gives natural radiance. Key Benefits: Intense 24-hour hydration, Reduces dark spots and pigmentation, Kumkumadi Tailam promotes natural glow, Anti-aging reduces fine lines, SPF 15 sun protection. Ingredients: Rose Water, Aloe Vera Gel 20%, Kumkumadi Tailam 5%, Sandalwood Extract, Vitamin E, Hyaluronic Acid, Shea Butter. Directions: Apply small amount on cleansed face and neck. Massage in upward circular motion until absorbed. Use morning and evening.',
            'img'         => $img_skincare,
            'img2'        => $img_skincare,
            'img3'        => $img_skincare,
            'img4'        => $img_skincare,
            'img5'        => $img_skincare,
            'category'    => 'Skin Care',
            'subcate'     => 'Moisturizer',
            'brand_name'  => 'Karuda Naturals',
            'no_items'    => '1',
            'barcode'     => '671237-891237',
            'age_range'   => 'Adult',
            'ratings'     => 4,
            'best_before' => '12',
            'keywords'    => 'rose cream, aloe vera, glow, moisturizer, kumkumadi, skin care',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        // ── Digestive Health ──
        [
            'rs_id'       => 1005,
            'dish_name'   => 'Triphala Gut Wellness Tablets',
            'description' => 'Karuda Triphala Gut Wellness Tablets are formulated using the ancient Ayurvedic trinity of Amalaki (Emblica officinalis), Bibhitaki (Terminalia bellirica), and Haritaki (Terminalia chebula). Triphala is the "King of Herbs" in Ayurveda for digestive health. This standardized formula supports healthy bowel movements, improves gut flora, and enhances nutrient absorption. Key Benefits: Gentle natural laxative relieves constipation without dependency, Improves gut microbiome and intestinal health, Powerful antioxidant power equal to 3 fruits, Supports liver and pancreas function, Aids in weight management. Ingredients: Amalaki Extract 200mg, Bibhitaki Extract 200mg, Haritaki Extract 200mg, Trikatu (Pepper blend) 10mg. Directions: Take 2 tablets with warm water at bedtime. Start with 1 tablet for first week.',
            'img'         => $img_digestive,
            'img2'        => $img_digestive,
            'img3'        => $img_digestive,
            'img4'        => $img_digestive,
            'img5'        => $img_digestive,
            'category'    => 'Digestive Health',
            'subcate'     => 'Digestive Tablets',
            'brand_name'  => 'Karuda Herbals',
            'no_items'    => '1',
            'barcode'     => '671238-891238',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '24',
            'keywords'    => 'triphala, constipation, gut health, digestion, bowel',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        [
            'rs_id'       => 1006,
            'dish_name'   => 'Ginger Fennel Digestive Tea',
            'description' => 'Karuda Ginger Fennel Digestive Tea is a soothing herbal infusion crafted from dried Ginger root, Fennel seeds, Cardamom, Ajwain (Carom seeds), and Peppermint leaves. This caffeine-free herbal tea is the perfect after-meal companion to aid digestion, relieve bloating, and soothe the stomach. Key Benefits: Relieves bloating, gas, and indigestion instantly, Soothes stomach cramps and IBS symptoms, Ginger reduces nausea and morning sickness, Fennel freshens breath naturally, Caffeine-free safe for all. Ingredients: Ginger Root 40%, Fennel Seeds 25%, Cardamom 15%, Ajwain 10%, Peppermint Leaves 10%. Each sachet 2.5g of premium whole-herb blend. Directions: Steep 1 tea bag in 200ml hot water (90°C) for 5-7 minutes. Add honey to taste. Drink after meals, 2-3 cups daily.',
            'img'         => $img_digestive,
            'img2'        => $img_digestive,
            'img3'        => $img_digestive,
            'img4'        => $img_digestive,
            'img5'        => $img_digestive,
            'category'    => 'Digestive Health',
            'subcate'     => 'Herbal Tea',
            'brand_name'  => 'Karuda Teas',
            'no_items'    => '1',
            'barcode'     => '671239-891239',
            'age_range'   => 'All',
            'ratings'     => 4,
            'best_before' => '12',
            'keywords'    => 'ginger tea, fennel, digestion tea, bloating relief, herbal tea',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        // ── Hair Care ──
        [
            'rs_id'       => 1007,
            'dish_name'   => 'Bhringraj Amla Hair Growth Oil',
            'description' => 'Karuda Bhringraj Amla Hair Growth Oil is an Ayurvedic hair tonic prepared using traditional cold-infusion method. King herb Bhringraj (Eclipta alba) is slow-cooked with Amla (Indian Gooseberry), Brahmi, Coconut oil, and Sesame oil for 48 hours to create a deeply nourishing scalp oil. Clinically tested to reduce hair fall by 60% in 8 weeks. Key Benefits: Reduces hair fall and breakage, Stimulates new hair growth from dormant follicles, Prevents premature greying, Deep conditions and adds shine, Treats dandruff and dry scalp. Ingredients: Bhringraj Extract 20%, Amla Extract 15%, Brahmi Extract 10%, Virgin Coconut Oil, Cold-pressed Sesame Oil, Castor Oil, Vitamin E. Directions: Warm slightly and apply to scalp. Massage for 10-15 minutes. Leave minimum 2 hours or overnight. Wash with mild shampoo. Use 2-3 times per week.',
            'img'         => $img_haircare,
            'img2'        => $img_haircare,
            'img3'        => $img_haircare,
            'img4'        => $img_haircare,
            'img5'        => $img_haircare,
            'category'    => 'Hair Care',
            'subcate'     => 'Hair Oil',
            'brand_name'  => 'Karuda Naturals',
            'no_items'    => '1',
            'barcode'     => '671240-891240',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '24',
            'keywords'    => 'bhringraj oil, hair growth oil, amla, hair fall, scalp care',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        [
            'rs_id'       => 1008,
            'dish_name'   => 'Neem Shikakai Herbal Shampoo',
            'description' => 'Karuda Neem Shikakai Herbal Shampoo is a sulfate-free, paraben-free hair cleanser formulated with ancient Indian hair care herbs. Shikakai (Acacia concinna) gently cleanses hair without stripping its natural moisture, while Neem controls dandruff and scalp infections. pH-balanced and suitable for all hair types including color-treated hair. Key Benefits: Gentle sulfate-free cleansing preserves natural oils, Shikakai naturally detangles and adds shine, Neem controls dandruff and itchy scalp, Reetha creates natural lather, Strengthens hair roots and prevents breakage. Ingredients: Shikakai Extract 5%, Neem Leaf Extract 3%, Reetha Extract 3%, Methi Extract 2%, Aloe Vera Gel 10%, Panthenol (Vitamin B5), Silk Proteins. Directions: Wet hair thoroughly, apply shampoo, lather and massage scalp 2 minutes, rinse well. Use 2-3 times a week.',
            'img'         => $img_haircare,
            'img2'        => $img_haircare,
            'img3'        => $img_haircare,
            'img4'        => $img_haircare,
            'img5'        => $img_haircare,
            'category'    => 'Hair Care',
            'subcate'     => 'Shampoo',
            'brand_name'  => 'Karuda Naturals',
            'no_items'    => '1',
            'barcode'     => '671241-891241',
            'age_range'   => 'All',
            'ratings'     => 4,
            'best_before' => '18',
            'keywords'    => 'neem shampoo, shikakai, dandruff, hair wash, herbal shampoo',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        // ── Stress & Sleep ──
        [
            'rs_id'       => 1009,
            'dish_name'   => 'Brahmi Shankhpushpi Mind Booster',
            'description' => 'Karuda Brahmi Shankhpushpi Mind Booster is a premium nootropic formula combining Brahmi (Bacopa monnieri), Shankhpushpi, Jatamansi, and Ashwagandha. These four herbs are revered in Ayurveda as "Medhya Rasayanas" (brain rejuvenators). This formula improves memory, focus, and cognitive function while reducing mental fatigue and anxiety. Key Benefits: Improves memory retention and recall, Enhances focus and concentration, Reduces anxiety and brain fog, Supports healthy sleep patterns, Protects neurons from oxidative damage. Ingredients: Brahmi Extract (Bacopa monnieri) 300mg, Shankhpushpi Extract 150mg, Jatamansi Extract 100mg, Ashwagandha Extract 100mg, Saffron (Kesar) 5mg. Directions: Take 1 capsule twice daily with warm milk. Best in morning and 1 hour before study or work. Suitable from 15 years onwards.',
            'img'         => $img_stress,
            'img2'        => $img_stress,
            'img3'        => $img_stress,
            'img4'        => $img_stress,
            'img5'        => $img_stress,
            'category'    => 'Stress & Sleep',
            'subcate'     => 'Brain Supplement',
            'brand_name'  => 'Karuda Herbals',
            'no_items'    => '1',
            'barcode'     => '671242-891242',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '12',
            'keywords'    => 'brahmi, shankhpushpi, memory booster, focus, stress relief, anxiety',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
        [
            'rs_id'       => 1010,
            'dish_name'   => 'Ashwagandha Sleep & Calm Gummies',
            'description' => 'Karuda Ashwagandha Sleep & Calm Gummies are delicious tropical-flavored gummies formulated with KSM-66 Ashwagandha (the highest-concentration full-spectrum root extract), Melatonin, L-Theanine, and Chamomile extract. These gummies help you fall asleep faster, sleep deeper, and wake up refreshed without grogginess or dependency. Key Benefits: Falls asleep 60% faster clinically proven, Improves deep sleep quality and duration, Reduces stress hormones (cortisol) naturally, Non-habit forming safe for daily use, Delicious mixed berry flavor no herb aftertaste. Ingredients per 2 gummies: KSM-66 Ashwagandha 300mg, Melatonin 0.5mg, L-Theanine 100mg, Chamomile Extract 50mg, Passionflower Extract 50mg. Sugar-free, Vegan, Gluten-free. Directions: Take 2 gummies 30-60 minutes before bedtime.',
            'img'         => $img_stress,
            'img2'        => $img_stress,
            'img3'        => $img_stress,
            'img4'        => $img_stress,
            'img5'        => $img_stress,
            'category'    => 'Stress & Sleep',
            'subcate'     => 'Sleep Supplement',
            'brand_name'  => 'Karuda Herbals',
            'no_items'    => '1',
            'barcode'     => '671243-891243',
            'age_range'   => 'Adult',
            'ratings'     => 5,
            'best_before' => '12',
            'keywords'    => 'ashwagandha gummies, sleep aid, calm, melatonin, stress, anxiety relief',
            'deliv_info'  => 'Free shipping above Rs.499',
            'refund'      => 'Refundable within 7 days if unopened',
            'deliv_mode'  => 'Standard,Express',
            'deliv_opt'   => 'Home Delivery',
            'status'      => 1,
        ],
    ];

    $prod_stmt = $con->prepare(
        "INSERT INTO dishes (rs_id, dish_name, description, img, img2, img3, img4, img5,
            category, date_of_adding, subcate, cateid, brand_name, no_items, barcode,
            age_range, ratings, best_before, keywords, deliv_info, refund, deliv_mode,
            deliv_opt, status, barcode_img)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
         ON DUPLICATE KEY UPDATE dish_name=VALUES(dish_name)"
    );

    $date = date('d-m-Y');

    foreach ($products as $p) {
        // Check if already exists
        $chk = $con->prepare("SELECT rs_id FROM dishes WHERE dish_name = ?");
        $chk->bind_param("s", $p['dish_name']);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $results[] = "⚠️ Product '{$p['dish_name']}' already exists — skipped.";
            $chk->close();
            continue;
        }
        $chk->close();

        $c_id = $cat_ids[$p['category']] ?? null;
        $barcode_img = '';

        $prod_stmt->bind_param(
            "isssssssssssisssisssssssi",
            $p['rs_id'], $p['dish_name'], $p['description'],
            $p['img'], $p['img2'], $p['img3'], $p['img4'], $p['img5'],
            $p['category'], $date, $p['subcate'], $c_id,
            $p['brand_name'], $p['no_items'], $p['barcode'],
            $p['age_range'], $p['ratings'], $p['best_before'],
            $p['keywords'], $p['deliv_info'], $p['refund'],
            $p['deliv_mode'], $p['deliv_opt'], $p['status'],
            $barcode_img
        );

        if ($prod_stmt->execute()) {
            $results[] = "✅ Product '{$p['dish_name']}' inserted (ID: {$p['rs_id']})";
        } else {
            $errors[] = "❌ Product '{$p['dish_name']}' failed: " . $prod_stmt->error;
        }
    }
    $prod_stmt->close();
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karuda Seed Runner</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #1e293b; border-radius: 16px; padding: 40px; max-width: 800px; width: 100%; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        h1 { font-size: 28px; font-weight: 700; color: #22c55e; margin-bottom: 8px; }
        .sub { color: #94a3b8; margin-bottom: 30px; font-size: 14px; }
        .badge { display: inline-block; background: #14532d; color: #4ade80; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 13px; }
        th { background: #0f172a; padding: 10px 12px; text-align: left; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 10px 12px; border-bottom: 1px solid #334155; }
        .btn { display: inline-block; background: #16a34a; color: white; border: none; padding: 14px 32px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #15803d; transform: translateY(-1px); }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .result { padding: 8px 14px; border-radius: 6px; margin: 4px 0; font-size: 13px; }
        .result.ok { background: #14532d22; border-left: 3px solid #22c55e; color: #4ade80; }
        .result.warn { background: #78350f22; border-left: 3px solid #f59e0b; color: #fbbf24; }
        .result.err { background: #7f1d1d22; border-left: 3px solid #ef4444; color: #f87171; }
        .section { margin: 20px 0; }
        .section h3 { color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .alert { background: #7c3aed22; border: 1px solid #7c3aed; border-radius: 8px; padding: 16px; color: #c4b5fd; margin: 20px 0; font-size: 14px; }
        .error-token { text-align: center; padding: 40px; }
        .error-token h2 { color: #ef4444; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="card">
    <?php if (!$token_ok): ?>
    <div class="error-token">
        <h2>🔒 Access Denied</h2>
        <p style="color:#94a3b8">Add <code>?token=karuda2026</code> to the URL to access this page.</p>
        <p style="margin-top:10px; color:#64748b; font-size:12px">Example: http://localhost/karuda/aa/seed_runner.php?token=karuda2026</p>
    </div>
    <?php elseif ($confirmed): ?>
        <h1>🌿 Karuda Seed Complete</h1>
        <p class="sub">Database seeding finished — check results below</p>

        <?php if (!empty($errors)): ?>
        <div class="alert">⚠️ Some operations had errors. Check results below.</div>
        <?php endif; ?>

        <div class="section">
            <h3>Results</h3>
            <?php foreach ($results as $r): ?>
                <div class="result <?= strpos($r,'✅') !== false ? 'ok' : 'warn' ?>"><?= htmlspecialchars($r) ?></div>
            <?php endforeach; ?>
            <?php foreach ($errors as $e): ?>
                <div class="result err"><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>

        <div class="alert">
            ⚠️ <strong>Security Notice:</strong> Please delete <code>seed_runner.php</code> from the server after use.
        </div>

        <a href="avadmin/products_list.php" class="btn">→ View Products in Admin</a>
        &nbsp;
        <a href="avadmin/category_lists.php" class="btn" style="background:#2563eb">→ View Categories</a>

    <?php else: ?>
        <h1>🌿 Karuda Herbal Seed Runner</h1>
        <p class="sub">This will insert sample data into the <strong>av_herbal1</strong> database</p>
        <span class="badge">✓ Database connected</span>

        <div class="section">
            <h3>What will be inserted</h3>
            <table>
                <tr><th>#</th><th>Category</th><th>Products</th></tr>
                <tr><td>1</td><td>Immunity Boosters</td><td>Ashwagandha Capsules, Giloy Tulsi Syrup</td></tr>
                <tr><td>2</td><td>Skin Care</td><td>Neem Turmeric Face Wash, Rose Aloe Vera Glow Cream</td></tr>
                <tr><td>3</td><td>Digestive Health</td><td>Triphala Gut Tablets, Ginger Fennel Tea</td></tr>
                <tr><td>4</td><td>Hair Care</td><td>Bhringraj Amla Oil, Neem Shikakai Shampoo</td></tr>
                <tr><td>5</td><td>Stress & Sleep</td><td>Brahmi Shankhpushpi Capsules, Ashwagandha Sleep Gummies</td></tr>
            </table>
            <p style="color:#64748b; font-size:13px">• Each product has 5 images, full description, keywords, ratings, and delivery info.<br>• Existing products with same name will be skipped (no overwrite).</p>
        </div>

        <form method="POST" action="?token=<?= SEED_TOKEN ?>">
            <input type="hidden" name="action" value="seed">
            <button type="submit" class="btn">▶ Run Seed Now</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
