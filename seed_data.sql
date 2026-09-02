-- ============================================================
-- KARUDA HERBAL - SEED DATA
-- Database: av_herbal1
-- 5 Categories + 10 Products with 5 images each
-- Run via: seed_runner.php  OR  phpMyAdmin import
-- ============================================================

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- STEP 1: CLEAN OLD DEMO DATA (safe - only removes karuda demo rows)
-- ============================================================

-- Remove existing demo categories & products (comment out if you want to keep existing data)
-- DELETE FROM res_category WHERE c_name IN ('Immunity Boosters','Skin Care','Digestive Health','Hair Care','Stress & Sleep');
-- DELETE FROM dishes WHERE category IN ('Immunity Boosters','Skin Care','Digestive Health','Hair Care','Stress & Sleep');

-- ============================================================
-- STEP 2: INSERT 5 CATEGORIES
-- Columns: c_name, k1, k2, fpath (main image), icon (secondary/icon image)
-- ============================================================

INSERT INTO `res_category` (`c_name`, `k1`, `k2`, `fpath`, `icon`) VALUES
('Immunity Boosters',
 'immunity, tulsi, ashwagandha, antioxidants, herbal',
 'Products that boost immunity naturally using Ayurvedic herbs like Tulsi, Ashwagandha, Giloy, and Amla',
 'Res_img/dishes/cat_immunity.png',
 'Res_img/dishes/cat_immunity.png'),

('Skin Care',
 'skincare, neem, aloe vera, turmeric, glow, face',
 'Natural herbal skincare products made with Neem, Turmeric, Aloe Vera, Rose water for glowing skin',
 'Res_img/dishes/cat_skincare.png',
 'Res_img/dishes/cat_skincare.png'),

('Digestive Health',
 'digestion, ginger, triphala, gut health, ayurveda',
 'Herbal digestive aids using Triphala, Ginger, Fennel, Ajwain for gut wellness and digestion',
 'Res_img/dishes/cat_digestive.png',
 'Res_img/dishes/cat_digestive.png'),

('Hair Care',
 'hair, bhringraj, amla, neem, coconut, growth, oil',
 'Ayurvedic hair care products using Bhringraj, Amla, Coconut, Neem for hair growth and scalp health',
 'Res_img/dishes/cat_haircare.png',
 'Res_img/dishes/cat_haircare.png'),

('Stress & Sleep',
 'stress, sleep, brahmi, ashwagandha, anxiety, calm, relax',
 'Natural herbs for stress relief and better sleep including Ashwagandha, Brahmi, Shankhpushpi',
 'Res_img/dishes/cat_stress.png',
 'Res_img/dishes/cat_stress.png');

-- ============================================================
-- STEP 3: INSERT 10 PRODUCTS (2 per category, 5 images each)
-- Columns: rs_id, dish_name, description, img, img2, img3, img4, img5,
--          category, date_of_adding, subcate, cateid, brand_name,
--          no_items, barcode, age_range, ratings, best_before,
--          keywords, deliv_info, refund, deliv_mode, deliv_opt, status, barcode_img
-- ============================================================

-- ────────────────────────────────────────────────────────────
-- CATEGORY 1: Immunity Boosters (2 products)
-- ────────────────────────────────────────────────────────────

INSERT INTO `dishes` (
  `rs_id`, `dish_name`, `description`,
  `img`, `img2`, `img3`, `img4`, `img5`,
  `category`, `date_of_adding`, `subcate`, `cateid`,
  `brand_name`, `no_items`, `barcode`, `age_range`, `ratings`,
  `best_before`, `keywords`, `deliv_info`, `refund`,
  `deliv_mode`, `deliv_opt`, `status`, `barcode_img`
) VALUES (
  1001,
  'Ashwagandha Immunity Capsules',
  'Karuda Ashwagandha Immunity Capsules are made from pure Withania somnifera root extract, standardized to 5% withanolides. Ashwagandha is a powerful adaptogen herb used in Ayurveda for over 3000 years to strengthen the immune system, reduce stress, and boost energy. Each capsule contains 500mg of certified organic Ashwagandha root powder. Key Benefits: Boosts natural immunity, Reduces cortisol levels and stress, Improves energy and stamina, Supports adrenal health, Enhances cognitive function. Ingredients: Ashwagandha Root Extract (Withania somnifera) 500mg, Organic Black Pepper Extract (Bioperine) 5mg for enhanced absorption. Directions: Take 1-2 capsules daily with warm milk or water after meals. Best results seen in 4-6 weeks of regular use. Suitable for adults above 18 years.',
  'Res_img/dishes/prod_ashwagandha.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Immunity Boosters', '2026-09-02', 'Herbal Capsules', 1,
  'Karuda Herbals', '1', '671234-891234', 'Adult', 5,
  '12', 'ashwagandha, immunity, stress relief, energy booster, adaptogen',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),
(
  1002,
  'Giloy Tulsi Immunity Syrup',
  'Karuda Giloy Tulsi Immunity Syrup is a potent Ayurvedic formulation combining the goodness of Giloy (Tinospora cordifolia), Holy Basil (Tulsi), and Amla (Indian Gooseberry). This triple-action immunity booster strengthens the body's natural defense system, fights infections, and supports respiratory health. Key Benefits: Powerful antioxidant protection, Fights common cold, cough, and viral infections, Improves respiratory function, Rich in Vitamin C from Amla, Detoxifies blood and liver. Ingredients: Giloy Extract 200mg, Tulsi Extract 150mg, Amla Extract 100mg, Ginger Extract 50mg per 10ml. Directions: Take 10ml (2 teaspoons) with equal water twice daily before meals. Can be given to children above 5 years in half dose with doctor's advice. Shake well before use.',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Res_img/dishes/cat_immunity.png',
  'Immunity Boosters', '2026-09-02', 'Herbal Syrup', 1,
  'Karuda Herbals', '1', '671235-891235', 'All', 4,
  '6', 'giloy, tulsi, amla, immunity syrup, cold relief',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),

-- ────────────────────────────────────────────────────────────
-- CATEGORY 2: Skin Care (2 products)
-- ────────────────────────────────────────────────────────────
(
  1003,
  'Neem Turmeric Face Wash',
  'Karuda Neem Turmeric Face Wash is an advanced Ayurvedic face cleanser formulated with wild-crafted Neem leaf extract and 24-karat gold-grade Turmeric (Curcumin). This gentle yet powerful face wash deeply cleanses pores, removes excess oil, fights acne-causing bacteria, and leaves skin bright and refreshed. Key Benefits: Deep pore cleansing without stripping natural oils, Fights acne, pimples, and blackheads, Anti-bacterial and anti-fungal properties of Neem, Turmeric brightens skin tone and reduces blemishes, Suitable for all skin types including oily and combination skin. Ingredients: Neem Leaf Extract 2%, Turmeric Extract (Curcumin 95%) 1%, Aloe Vera Gel, Tea Tree Oil, Chamomile Flower Extract, SLS-free base. Directions: Apply small amount on wet face, lather gently in circular motion for 60 seconds, rinse with cool water. Use twice daily for best results.',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Skin Care', '2026-09-02', 'Face Wash', 2,
  'Karuda Naturals', '1', '671236-891236', 'Adult', 5,
  '12', 'neem face wash, turmeric, acne, pimples, herbal cleanser',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),
(
  1004,
  'Rose Aloe Vera Skin Glow Cream',
  'Karuda Rose Aloe Vera Skin Glow Cream is a luxurious Ayurvedic day moisturizer crafted from cold-pressed Rose water, organic Aloe Vera, Kumkumadi Tailam (rare saffron oil blend), and Sandalwood extract. This non-greasy, fast-absorbing cream provides 24-hour hydration, reduces dark spots, and gives natural radiance. Key Benefits: Intense 24-hour hydration, Reduces dark spots, pigmentation, and uneven skin tone, Kumkumadi Tailam promotes natural glow, Anti-aging – reduces fine lines and wrinkles, SPF 15 protection from sun damage. Ingredients: Rose Water, Aloe Vera Gel 20%, Kumkumadi Tailam 5%, Sandalwood Extract, Vitamin E, Hyaluronic Acid, Shea Butter. Directions: Apply small amount on cleansed face and neck. Massage in upward circular motion until absorbed. Use morning and evening. Patch test recommended for sensitive skin.',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Res_img/dishes/cat_skincare.png',
  'Skin Care', '2026-09-02', 'Moisturizer', 2,
  'Karuda Naturals', '1', '671237-891237', 'Adult', 4,
  '12', 'rose cream, aloe vera, glow, moisturizer, kumkumadi, skin care',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),

-- ────────────────────────────────────────────────────────────
-- CATEGORY 3: Digestive Health (2 products)
-- ────────────────────────────────────────────────────────────
(
  1005,
  'Triphala Gut Wellness Tablets',
  'Karuda Triphala Gut Wellness Tablets are formulated using the ancient Ayurvedic trinity of Amalaki (Emblica officinalis), Bibhitaki (Terminalia bellirica), and Haritaki (Terminalia chebula). Triphala is considered the "King of Herbs" in Ayurveda for digestive health. This standardized formula supports healthy bowel movements, improves gut flora, and enhances nutrient absorption. Key Benefits: Gentle natural laxative – relieves constipation without dependency, Improves gut microbiome and intestinal health, Powerful antioxidant – 1 tablet equals antioxidant power of 3 fruits, Supports liver and pancreas function, Aids in weight management by improving metabolism. Ingredients: Amalaki Extract 200mg, Bibhitaki Extract 200mg, Haritaki Extract 200mg, Trikatu (Pepper blend) 10mg. Directions: Take 2 tablets with warm water at bedtime. Start with 1 tablet for first week. Can be used long-term safely.',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Digestive Health', '2026-09-02', 'Digestive Tablets', 3,
  'Karuda Herbals', '1', '671238-891238', 'Adult', 5,
  '24', 'triphala, constipation, gut health, digestion, bowel',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),
(
  1006,
  'Ginger Fennel Digestive Tea',
  'Karuda Ginger Fennel Digestive Tea is a soothing herbal infusion crafted from dried Ginger root, Fennel seeds, Cardamom, Ajwain (Carom seeds), and Peppermint leaves. This caffeine-free herbal tea is the perfect after-meal companion to aid digestion, relieve bloating, and soothe the stomach. Key Benefits: Relieves bloating, gas, and indigestion instantly, Soothes stomach cramps and IBS symptoms, Ginger reduces nausea and morning sickness, Fennel freshens breath naturally, Caffeine-free – safe for pregnant women (in moderation). Ingredients: Ginger Root 40%, Fennel Seeds 25%, Cardamom 15%, Ajwain 10%, Peppermint Leaves 10%. Each sachet contains 2.5g of premium whole-herb blend. Directions: Steep 1 tea bag in 200ml of hot water (90°C) for 5-7 minutes. May add honey to taste. Drink after meals, 2-3 cups daily.',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Res_img/dishes/cat_digestive.png',
  'Digestive Health', '2026-09-02', 'Herbal Tea', 3,
  'Karuda Teas', '1', '671239-891239', 'All', 4,
  '12', 'ginger tea, fennel, digestion tea, bloating relief, herbal tea',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),

-- ────────────────────────────────────────────────────────────
-- CATEGORY 4: Hair Care (2 products)
-- ────────────────────────────────────────────────────────────
(
  1007,
  'Bhringraj Amla Hair Growth Oil',
  'Karuda Bhringraj Amla Hair Growth Oil is an Ayurvedic hair tonic prepared using traditional cold-infusion method. King herb Bhringraj (Eclipta alba) is slow-cooked with Amla (Indian Gooseberry), Brahmi, Coconut oil, and Sesame oil for 48 hours to create a deeply nourishing scalp oil. Clinically tested to reduce hair fall by 60% in 8 weeks. Key Benefits: Reduces hair fall and breakage, Stimulates new hair growth from dormant follicles, Prevents premature greying, Deep conditions and adds shine, Treats dandruff and dry scalp. Ingredients: Bhringraj Extract 20%, Amla Extract 15%, Brahmi Extract 10%, Virgin Coconut Oil, Cold-pressed Sesame Oil, Castor Oil, Vitamin E. Directions: Warm slightly and apply to scalp. Massage for 10-15 minutes using fingertips. Leave for minimum 2 hours or overnight. Wash with mild shampoo. Use 2-3 times per week.',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Hair Care', '2026-09-02', 'Hair Oil', 4,
  'Karuda Naturals', '1', '671240-891240', 'Adult', 5,
  '24', 'bhringraj oil, hair growth oil, amla, hair fall, scalp care',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),
(
  1008,
  'Neem Shikakai Herbal Shampoo',
  'Karuda Neem Shikakai Herbal Shampoo is a sulfate-free, paraben-free hair cleanser formulated with ancient Indian hair care herbs. Shikakai (Acacia concinna) gently cleanses hair without stripping its natural moisture, while Neem controls dandruff and scalp infections. This shampoo is pH-balanced and suitable for all hair types including color-treated hair. Key Benefits: Gentle sulfate-free cleansing preserves natural oils, Shikakai naturally detangles and adds shine, Neem controls dandruff and itchy scalp, Reetha creates natural lather without chemicals, Strengthens hair roots and prevents breakage. Ingredients: Shikakai Extract 5%, Neem Leaf Extract 3%, Reetha Extract 3%, Methi (Fenugreek) Extract 2%, Aloe Vera Gel 10%, Panthenol (Vitamin B5), Silk Proteins. Directions: Wet hair thoroughly. Apply shampoo, lather and massage scalp for 2 minutes. Rinse well with water. Use 2-3 times a week.',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Res_img/dishes/cat_haircare.png',
  'Hair Care', '2026-09-02', 'Shampoo', 4,
  'Karuda Naturals', '1', '671241-891241', 'All', 4,
  '18', 'neem shampoo, shikakai, dandruff, hair wash, herbal shampoo',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),

-- ────────────────────────────────────────────────────────────
-- CATEGORY 5: Stress & Sleep (2 products)
-- ────────────────────────────────────────────────────────────
(
  1009,
  'Brahmi Shankhpushpi Mind Booster',
  'Karuda Brahmi Shankhpushpi Mind Booster is a premium nootropic formula combining Brahmi (Bacopa monnieri), Shankhpushpi, Jatamansi, and Ashwagandha. These four herbs are revered in Ayurveda as "Medhya Rasayanas" (brain rejuvenators). This formula improves memory, focus, and cognitive function while reducing mental fatigue and anxiety. Key Benefits: Improves memory retention and recall, Enhances focus and concentration, Reduces anxiety, mental fatigue, and brain fog, Supports healthy sleep patterns, Protects neurons from oxidative damage. Ingredients: Brahmi Extract (Bacopa monnieri) 300mg, Shankhpushpi Extract 150mg, Jatamansi Extract 100mg, Ashwagandha Extract 100mg, Saffron (Kesar) 5mg. Directions: Take 1 capsule twice daily with warm milk. Best taken in morning and 1 hour before study or work. Can be taken by students from 15 years onwards.',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Stress & Sleep', '2026-09-02', 'Brain Supplement', 5,
  'Karuda Herbals', '1', '671242-891242', 'Adult', 5,
  '12', 'brahmi, shankhpushpi, memory booster, focus, stress relief, anxiety',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
),
(
  1010,
  'Ashwagandha Sleep & Calm Gummies',
  'Karuda Ashwagandha Sleep & Calm Gummies are delicious tropical-flavored gummies formulated with KSM-66 Ashwagandha (the highest-concentration full-spectrum root extract), Melatonin, L-Theanine, and Chamomile extract. These gummies help you fall asleep faster, sleep deeper, and wake up refreshed – without grogginess or dependency. Key Benefits: Falls asleep 60% faster – clinically proven, Improves deep sleep quality and duration, Reduces stress hormones (cortisol) naturally, Non-habit forming – safe for daily use, Delicious mixed berry flavor – no herb aftertaste. Ingredients per 2 gummies: KSM-66 Ashwagandha 300mg, Melatonin 0.5mg, L-Theanine 100mg, Chamomile Extract 50mg, Passionflower Extract 50mg. Sugar-free, Vegan, Gluten-free. Directions: Take 2 gummies 30-60 minutes before bedtime. Do not exceed 4 gummies per day. Not suitable for pregnant or nursing women.',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Res_img/dishes/cat_stress.png',
  'Stress & Sleep', '2026-09-02', 'Sleep Supplement', 5,
  'Karuda Herbals', '1', '671243-891243', 'Adult', 5,
  '12', 'ashwagandha gummies, sleep aid, calm, melatonin, stress, anxiety relief',
  'Free shipping above Rs.499', 'Refundable within 7 days if unopened',
  'Standard,Express', 'Home Delivery', 1, ''
);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DONE! 5 categories + 10 products inserted successfully.
-- ============================================================
