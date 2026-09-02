<?php
/**
 * KARUDA COMPUTERS - COMPLETE COMPUTER SHOP SEED RUNNER
 * This script wipes all old herbal/skin care data and seeds:
 *  - 5 Computer Categories
 *  - 10 Detailed Computer Products (2 per category, 5 images each)
 *  - 10 Matching Price & Stock Records
 */

header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'av_herbal1';

$con = mysqli_connect($host, $user, $password, $dbname);
if (!$con) {
    die("<h2 style='color:red;'>DB Connection Failed: " . mysqli_connect_error() . "</h2>");
}
mysqli_set_charset($con, 'utf8');

// Disable Foreign Keys during seed
mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0;");

$logs = [];

// 1. TRUNCATE OLD DATA (Completely removes skin care & herbal products)
mysqli_query($con, "TRUNCATE TABLE res_category;");
mysqli_query($con, "TRUNCATE TABLE dishes;");
mysqli_query($con, "TRUNCATE TABLE price;");
$logs[] = "🗑️ Wiped out all legacy categories, herbal dishes, and price records.";

// 2. SEED 5 COMPUTER CATEGORIES
$categories = [
    [
        'c_id' => 1,
        'c_name' => 'Laptops & Notebooks',
        'k1' => 'laptops, gaming laptops, ultrabooks, notebooks, asus rog, lenovo legion, hp omen',
        'k2' => 'High-performance Gaming Laptops, Creator Workstation Notebooks, and Ultra-Portable Laptops from ASUS, Lenovo, HP, Dell & Apple.',
        'fpath' => 'Res_img/dishes/cat_laptop.png',
        'icon'  => 'Res_img/dishes/cat_laptop.png',
    ],
    [
        'c_id' => 2,
        'c_name' => 'Desktop PCs & Workstations',
        'k1' => 'desktop pcs, gaming rig, custom pc, workstation, ai pc, threadripper, rtx 4090',
        'k2' => 'Prebuilt Custom Gaming Rigs, AI Deep Learning Workstations, 3D Rendering Systems, and Compact Mini PCs with Liquid Cooling.',
        'fpath' => 'Res_img/dishes/cat_desktop.png',
        'icon'  => 'Res_img/dishes/cat_desktop.png',
    ],
    [
        'c_id' => 3,
        'c_name' => 'Components & Graphics Cards',
        'k1' => 'graphics cards, rtx 4080 super, intel core i9, processors, ddr5 ram, nvme ssd, motherboard',
        'k2' => 'Genuine NVIDIA RTX & AMD Radeon GPUs, Intel Core & AMD Ryzen Processors, High-Speed DDR5 RAM, and Gen4 NVMe SSDs.',
        'fpath' => 'Res_img/dishes/cat_component.png',
        'icon'  => 'Res_img/dishes/cat_component.png',
    ],
    [
        'c_id' => 4,
        'c_name' => 'Gaming Monitors & Displays',
        'k1' => 'gaming monitors, 4k oled, 240hz, 360hz esports, curved monitor, samsung odyssey, asus rog swift',
        'k2' => 'Ultra-wide 4K OLED Gaming Monitors, 240Hz/360Hz Esports Displays, and Color-Accurate Professional Workstation Screens.',
        'fpath' => 'Res_img/dishes/cat_monitor.png',
        'icon'  => 'Res_img/dishes/cat_monitor.png',
    ],
    [
        'c_id' => 5,
        'c_name' => 'Computer Accessories & Peripherals',
        'k1' => 'mechanical keyboard, wireless gaming mouse, corsair k100, logitech g pro, headsets, webcams',
        'k2' => 'RGB Mechanical Keyboards, Ultra-Lightweight Wireless Gaming Mice, Studio Headsets, 4K Webcams, and Cable Accessories.',
        'fpath' => 'Res_img/dishes/cat_accessory.png',
        'icon'  => 'Res_img/dishes/cat_accessory.png',
    ],
];

$cat_stmt = $con->prepare("INSERT INTO res_category (c_id, c_name, k1, k2, fpath, icon) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($categories as $cat) {
    $cat_stmt->bind_param("isssss", $cat['c_id'], $cat['c_name'], $cat['k1'], $cat['k2'], $cat['fpath'], $cat['icon']);
    if ($cat_stmt->execute()) {
        $logs[] = "✅ Category '{$cat['c_name']}' created successfully (ID: {$cat['c_id']}).";
    } else {
        $logs[] = "❌ Failed to create category '{$cat['c_name']}': " . $cat_stmt->error;
    }
}
$cat_stmt->close();

// 3. SEED 10 COMPUTER PRODUCTS (2 per category, 5 images each)
$products = [
    // ── Category 1: Laptops & Notebooks ──
    [
        'rs_id'       => 2001,
        'dish_name'   => 'ASUS ROG Strix SCAR 18 Gaming Laptop',
        'description' => 'Dominate the battlefield with the flagship ASUS ROG Strix SCAR 18. Powered by the 14th Gen Intel Core i9-14900HX processor and NVIDIA GeForce RTX 4090 16GB Laptop GPU with a max TGP of 175W. Features an 18-inch QHD+ 240Hz/3ms ROG Nebula HDR Mini LED Display with over 1100 nits peak brightness. Comes equipped with 32GB DDR5-5600MHz RAM and 2TB PCIe 4.0 NVMe M.2 Performance SSD in RAID 0. Conductonaut Extreme liquid metal cooling on CPU and GPU with a tri-fan thermal design ensures maximum sustained performance. Includes per-key RGB mechanical keyboard, Wi-Fi 6E, Thunderbolt 4, and 90Wh battery.',
        'img'         => 'Res_img/dishes/prod_laptop_1.jpg',
        'img2'        => 'Res_img/dishes/cat_laptop.png',
        'img3'        => 'Res_img/dishes/prod_laptop_1.jpg',
        'img4'        => 'Res_img/dishes/cat_laptop.png',
        'img5'        => 'Res_img/dishes/prod_laptop_1.jpg',
        'category'    => 'Laptops & Notebooks',
        'subcate'     => 'Gaming Laptops',
        'cateid'      => 1,
        'brand_name'  => 'ASUS ROG',
        'no_items'    => '1',
        'barcode'     => '889349-102001',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'asus rog, strix scar 18, rtx 4090 laptop, i9-14900hx, 240hz laptop, gaming laptop',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        // Price details
        'oprice'      => 329999,
        'pp'          => 289999,
        'discount'    => 12,
        'stock'       => 25,
    ],
    [
        'rs_id'       => 2002,
        'dish_name'   => 'Lenovo Legion Slim 5 Gen 9 Gaming Laptop',
        'description' => 'The Lenovo Legion Slim 5 Gen 9 strikes the ideal balance between extreme performance and portable gaming freedom. Powered by the AMD Ryzen 7 8845HS 8-Core processor with Ryzen AI technology and NVIDIA GeForce RTX 4070 8GB GDDR6 graphics. Features a stunning 16-inch WQXGA (2560x1600) 165Hz IPS display with 100% sRGB color accuracy, Dolby Vision, and NVIDIA G-SYNC. Loaded with 16GB DDR5 5600MHz RAM and a fast 1TB M.2 PCIe Gen4 NVMe SSD. Stay cool during intense sessions with Legion ColdFront 5.0 thermal tuning and dual 3D blade fans. Includes 4-zone RGB backlit keyboard, TrueStrike switches, and rapid charge 80Wh battery.',
        'img'         => 'Res_img/dishes/prod_laptop_2.jpg',
        'img2'        => 'Res_img/dishes/cat_laptop.png',
        'img3'        => 'Res_img/dishes/prod_laptop_2.jpg',
        'img4'        => 'Res_img/dishes/cat_laptop.png',
        'img5'        => 'Res_img/dishes/prod_laptop_2.jpg',
        'category'    => 'Laptops & Notebooks',
        'subcate'     => 'Thin & Light Gaming',
        'cateid'      => 1,
        'brand_name'  => 'Lenovo Legion',
        'no_items'    => '1',
        'barcode'     => '889349-102002',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'lenovo legion slim 5, ryzen 7 8845hs, rtx 4070, 165hz laptop, legion gaming',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 144999,
        'pp'          => 124999,
        'discount'    => 14,
        'stock'       => 40,
    ],

    // ── Category 2: Desktop PCs & Workstations ──
    [
        'rs_id'       => 2003,
        'dish_name'   => 'Karuda Hyperion RTX 4090 Custom Gaming Desktop',
        'description' => 'Unleash ultra-high FPS 4K gaming with the Karuda Hyperion Custom Gaming Beast. Configured with the unlocked Intel Core i9-14900K 24-Core processor, ASUS ROG Maximus Z790 Hero motherboard, and ROG Strix NVIDIA GeForce RTX 4090 24GB OC graphics card. Cooled by a 360mm ARGB AIO Liquid Cooler with magnetic levitation fans. Packed with 64GB Corsair Vengeance RGB DDR5 6000MHz memory and a 2TB Samsung 990 PRO NVMe Gen4 SSD (7450 MB/s). Housed in a dual-chamber tempered glass panoramic ARGB chassis with a 1000W 80+ Gold Fully Modular ATX 3.0 Power Supply. Pre-installed with Windows 11 Pro, stress-tested, and ready to plug & play.',
        'img'         => 'Res_img/dishes/prod_desktop_1.jpg',
        'img2'        => 'Res_img/dishes/cat_desktop.png',
        'img3'        => 'Res_img/dishes/prod_desktop_1.jpg',
        'img4'        => 'Res_img/dishes/cat_desktop.png',
        'img5'        => 'Res_img/dishes/prod_desktop_1.jpg',
        'category'    => 'Desktop PCs & Workstations',
        'subcate'     => 'Custom Gaming Rigs',
        'cateid'      => 2,
        'brand_name'  => 'Karuda Computers',
        'no_items'    => '1',
        'barcode'     => '889349-102003',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'karuda custom pc, rtx 4090 desktop, i9-14900k desktop, gaming pc, 64gb ddr5',
        'deliv_info'  => 'Custom packed in wooden crate with insured express shipping.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 399999,
        'pp'          => 349999,
        'discount'    => 13,
        'stock'       => 15,
    ],
    [
        'rs_id'       => 2004,
        'dish_name'   => 'Karuda Workstation Pro AI & 3D Render PC',
        'description' => 'Engineered for AI deep learning, 3D animation, VFX rendering, and CAD engineering. Powered by the massive AMD Ryzen Threadripper 7960X 24-Core/48-Thread CPU paired with DUAL NVIDIA GeForce RTX 4080 Super 16GB GPUs in SLI/NVLink configuration for multi-GPU CUDA acceleration. Features 128GB Quad-Channel ECC DDR5 Registered RAM, 4TB PCIe Gen4 NVMe M.2 SSD in RAID 0 for instant asset loads, and a custom hard-line liquid cooling loop for 24/7 continuous full-load rendering. Powered by a Corsair 1600W 80+ Titanium PSU inside a Lian Li O11 Dynamic XL Full Tower enclosure with 10GbE LAN.',
        'img'         => 'Res_img/dishes/prod_desktop_2.jpg',
        'img2'        => 'Res_img/dishes/cat_desktop.png',
        'img3'        => 'Res_img/dishes/prod_desktop_2.jpg',
        'img4'        => 'Res_img/dishes/cat_desktop.png',
        'img5'        => 'Res_img/dishes/prod_desktop_2.jpg',
        'category'    => 'Desktop PCs & Workstations',
        'subcate'     => 'AI & Render Workstations',
        'cateid'      => 2,
        'brand_name'  => 'Karuda Computers',
        'no_items'    => '1',
        'barcode'     => '889349-102004',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'ai workstation, threadripper 7960x, dual rtx 4080, 128gb ram, render pc',
        'deliv_info'  => 'Custom packed in wooden crate with insured express shipping.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 520000,
        'pp'          => 475000,
        'discount'    => 9,
        'stock'       => 10,
    ],

    // ── Category 3: Components & Graphics Cards ──
    [
        'rs_id'       => 2005,
        'dish_name'   => 'NVIDIA GeForce RTX 4080 Super 16GB OC GPU',
        'description' => 'Supercharge your graphics with the NVIDIA GeForce RTX 4080 Super 16GB GDDR6X OC Edition. Built on ultra-efficient NVIDIA Ada Lovelace architecture with 10,240 CUDA cores, 3rd gen Ray Tracing cores, and 4th gen Tensor cores. Supports DLSS 3.5 AI Frame Generation for maximum framerates in Cyberpunk 2077 and Alan Wake 2. Features a massive 3.5-slot heatsink with triple Axial-tech fans, diecast shroud, and vented backplate. Dual BIOS switch lets you choose between quiet and performance profiles. Requires 16-pin 12VHPWR connector (12VHPWR adapter included in box).',
        'img'         => 'Res_img/dishes/prod_gpu_1.jpg',
        'img2'        => 'Res_img/dishes/cat_component.png',
        'img3'        => 'Res_img/dishes/prod_gpu_1.jpg',
        'img4'        => 'Res_img/dishes/cat_component.png',
        'img5'        => 'Res_img/dishes/prod_gpu_1.jpg',
        'category'    => 'Components & Graphics Cards',
        'subcate'     => 'Graphics Cards (GPUs)',
        'cateid'      => 3,
        'brand_name'  => 'NVIDIA',
        'no_items'    => '1',
        'barcode'     => '889349-102005',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'rtx 4080 super, nvidia gpu, 16gb gddr6x, graphics card, dlss 3.5, 4k gaming',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 124999,
        'pp'          => 109999,
        'discount'    => 12,
        'stock'       => 30,
    ],
    [
        'rs_id'       => 2006,
        'dish_name'   => 'Intel Core i9-14900K Desktop Processor',
        'description' => 'The ultimate gaming and productivity processor. Intel Core i9-14900K features 24 cores (8 Performance-Cores + 16 Efficient-Cores) and 32 threads with max turbo speeds reaching a blistering 6.0 GHz thanks to Thermal Velocity Boost. Unlocked for extreme overclocking with Intel Extreme Tuning Utility (XTU). Equipped with 36MB Intel Smart Cache, 32MB L2 Cache, and PCIe 5.0 support with up to 20 lanes. Compatible with Intel 700-series and 600-series motherboards (LGA1700 socket). Integrated Intel UHD Graphics 770 included.',
        'img'         => 'Res_img/dishes/prod_cpu_1.jpg',
        'img2'        => 'Res_img/dishes/cat_component.png',
        'img3'        => 'Res_img/dishes/prod_cpu_1.jpg',
        'img4'        => 'Res_img/dishes/cat_component.png',
        'img5'        => 'Res_img/dishes/prod_cpu_1.jpg',
        'category'    => 'Components & Graphics Cards',
        'subcate'     => 'Processors (CPUs)',
        'cateid'      => 3,
        'brand_name'  => 'Intel',
        'no_items'    => '1',
        'barcode'     => '889349-102006',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'intel i9-14900k, 14th gen processor, 6.0 ghz cpu, lga1700, intel core i9',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 62999,
        'pp'          => 54999,
        'discount'    => 13,
        'stock'       => 50,
    ],

    // ── Category 4: Gaming Monitors & Displays ──
    [
        'rs_id'       => 2007,
        'dish_name'   => 'Samsung Odyssey G9 49" Curved OLED Monitor',
        'description' => 'Immerse yourself in infinite detail with the 49-inch Samsung Odyssey OLED G9. Features a revolutionary 1800R curved Quantum Dot OLED panel with Dual QHD resolution (5120x1440) in 32:9 ultra-wide aspect ratio. Delivers a blazing 240Hz refresh rate and near-instantaneous 0.03ms response time (GtG). Certified DisplayHDR True Black 400 offers pure deep blacks and unmatched color contrast. Built-in Neo Quantum Processor Pro optimizes every frame in real-time. Includes CoreSync RGB illumination, FreeSync Premium Pro, HDMI 2.1, DisplayPort 1.4, and height-adjustable stand.',
        'img'         => 'Res_img/dishes/prod_monitor_1.jpg',
        'img2'        => 'Res_img/dishes/cat_monitor.png',
        'img3'        => 'Res_img/dishes/prod_monitor_1.jpg',
        'img4'        => 'Res_img/dishes/cat_monitor.png',
        'img5'        => 'Res_img/dishes/prod_monitor_1.jpg',
        'category'    => 'Gaming Monitors & Displays',
        'subcate'     => 'Ultrawide OLED Monitors',
        'cateid'      => 4,
        'brand_name'  => 'Samsung',
        'no_items'    => '1',
        'barcode'     => '889349-102007',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'samsung odyssey g9, 49 inch oled monitor, 240hz curved monitor, 5120x1440, ultrawide display',
        'deliv_info'  => 'Shipped in heavy-duty reinforced monitor packaging with insurance.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 159999,
        'pp'          => 139999,
        'discount'    => 13,
        'stock'       => 20,
    ],
    [
        'rs_id'       => 2008,
        'dish_name'   => 'ASUS ROG Swift 27" 360Hz Esports Gaming Monitor',
        'description' => 'Built for professional esports competitors, the ASUS ROG Swift PG27AQN is the world\'s fastest 1440p esports gaming monitor. Features a 27-inch QHD (2560x1440) Ultrafast IPS panel running at an ultra-smooth 360Hz refresh rate with 0.5ms (GtG) response time. Built-in NVIDIA G-SYNC processor eliminates screen tearing while NVIDIA Reflex Latency Analyzer measures end-to-end system latency. DisplayHDR 600 certification with 98% DCI-P3 color gamut. Features ASUS Aura Sync RGB lighting and full ergonomic swivel/tilt/pivot adjustment.',
        'img'         => 'Res_img/dishes/prod_monitor_2.jpg',
        'img2'        => 'Res_img/dishes/cat_monitor.png',
        'img3'        => 'Res_img/dishes/prod_monitor_2.jpg',
        'img4'        => 'Res_img/dishes/cat_monitor.png',
        'img5'        => 'Res_img/dishes/prod_monitor_2.jpg',
        'category'    => 'Gaming Monitors & Displays',
        'subcate'     => '360Hz Esports Monitors',
        'cateid'      => 4,
        'brand_name'  => 'ASUS ROG',
        'no_items'    => '1',
        'barcode'     => '889349-102008',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'asus rog swift, 360hz monitor, 27 inch 1440p, esports gaming monitor, g-sync',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 79999,
        'pp'          => 68999,
        'discount'    => 14,
        'stock'       => 35,
    ],

    // ── Category 5: Computer Accessories & Peripherals ──
    [
        'rs_id'       => 2009,
        'dish_name'   => 'Corsair K100 RGB Mechanical Gaming Keyboard',
        'description' => 'The flagship Corsair K100 RGB combines cutting-edge optical-mechanical key switches with Corsair AXON Hyper-Processing Technology for 4,000Hz hyper-polling (4x faster response). Features CORSAIR OPX optical switches with ultra-fast 1.0mm actuation distance and 150 million keystroke durability. Built with a durable brushed aluminum frame, per-key RGB backlighting, and a 44-zone LightEdge surround. Includes a programmable iCUE Control Wheel, 6 dedicated macro keys with Elgato Stream Deck software integration, and a plush magnetic leatherette palm rest.',
        'img'         => 'Res_img/dishes/prod_acc_1.jpg',
        'img2'        => 'Res_img/dishes/cat_accessory.png',
        'img3'        => 'Res_img/dishes/prod_acc_1.jpg',
        'img4'        => 'Res_img/dishes/cat_accessory.png',
        'img5'        => 'Res_img/dishes/prod_acc_1.jpg',
        'category'    => 'Computer Accessories & Peripherals',
        'subcate'     => 'Mechanical Keyboards',
        'cateid'      => 5,
        'brand_name'  => 'Corsair',
        'no_items'    => '1',
        'barcode'     => '889349-102009',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'corsair k100 rgb, mechanical keyboard, opx switches, stream deck keyboard, gaming keyboard',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 22999,
        'pp'          => 18999,
        'discount'    => 17,
        'stock'       => 60,
    ],
    [
        'rs_id'       => 2010,
        'dish_name'   => 'Logitech G PRO X Superlight 2 Wireless Mouse',
        'description' => 'Engineered with top pro esports athletes, the Logitech G PRO X Superlight 2 weighs under 60 grams for effortless micro-adjustments. Driven by the groundbreaking HERO 2 Sensor with 32,000 DPI tracking, sub-micron accuracy, and over 500 IPS speed. Features LIGHTFORCE Hybrid Optical-Mechanical Switches combining optical speed with tactile mechanical feedback. Operates on pro-grade LIGHTSPEED wireless protocol offering up to 95 hours of battery life per charge over USB-C. Zero-additive PTFE feet deliver ultra-smooth glide across any mousepad surface.',
        'img'         => 'Res_img/dishes/prod_acc_2.jpg',
        'img2'        => 'Res_img/dishes/cat_accessory.png',
        'img3'        => 'Res_img/dishes/prod_acc_2.jpg',
        'img4'        => 'Res_img/dishes/cat_accessory.png',
        'img5'        => 'Res_img/dishes/prod_acc_2.jpg',
        'category'    => 'Computer Accessories & Peripherals',
        'subcate'     => 'Wireless Gaming Mice',
        'cateid'      => 5,
        'brand_name'  => 'Logitech G',
        'no_items'    => '1',
        'barcode'     => '889349-102010',
        'age_range'   => 'All',
        'ratings'     => 5,
        'best_before' => '36',
        'keywords'    => 'logitech g pro x superlight 2, wireless gaming mouse, hero 2 sensor, 60g mouse, esports mouse',
        'deliv_info'  => 'Free Express Delivery across India in 2-4 business days.',
        'refund'      => '7-Day Replacement Guarantee for manufacturing defects.',
        'deliv_mode'  => 'Express,Standard',
        'deliv_opt'   => 'Home Delivery',
        'status'      => 1,
        'oprice'      => 16999,
        'pp'          => 13999,
        'discount'    => 18,
        'stock'       => 75,
    ],
];

$prod_stmt = $con->prepare(
    "INSERT INTO dishes (
        rs_id, dish_name, description, img, img2, img3, img4, img5,
        category, date_of_adding, subcate, cateid, brand_name, no_items,
        barcode, age_range, ratings, best_before, keywords, deliv_info,
        refund, deliv_mode, deliv_opt, status, barcode_img
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$price_stmt = $con->prepare(
    "INSERT INTO price (
        pcode, pname, oprice, pp, qn, wg, gst, total_stock, discount, s_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$date = date('d-m-Y');

foreach ($products as $p) {
    $barcode_img = '';
    
    // 1. Insert into dishes table
    $prod_stmt->bind_param(
        "isssssssssssisssisssssssi",
        $p['rs_id'], $p['dish_name'], $p['description'],
        $p['img'], $p['img2'], $p['img3'], $p['img4'], $p['img5'],
        $p['category'], $date, $p['subcate'], $p['cateid'],
        $p['brand_name'], $p['no_items'], $p['barcode'],
        $p['age_range'], $p['ratings'], $p['best_before'],
        $p['keywords'], $p['deliv_info'], $p['refund'],
        $p['deliv_mode'], $p['deliv_opt'], $p['status'],
        $barcode_img
    );

    if ($prod_stmt->execute()) {
        $logs[] = "💻 Product '{$p['dish_name']}' created (ID: {$p['rs_id']}).";
    } else {
        $logs[] = "❌ Failed product '{$p['dish_name']}': " . $prod_stmt->error;
    }

    // 2. Insert into price table
    $pcode = (string)$p['rs_id'];
    $pname = 'Standard Edition';
    $qn = '1 Unit';
    $wg = '2.5 kg';
    $gst = '18';
    $s_status = '1';

    $price_stmt->bind_param(
        "ssiiisssis",
        $pcode, $pname, $p['oprice'], $p['pp'],
        $qn, $wg, $gst, $p['stock'], $p['discount'], $s_status
    );

    if ($price_stmt->execute()) {
        $logs[] = "💰 Price record created for '{$p['dish_name']}' (Price: ₹" . number_format($p['pp']) . ").";
    } else {
        $logs[] = "❌ Failed price for '{$p['dish_name']}': " . $price_stmt->error;
    }
}

$prod_stmt->close();
$price_stmt->close();

// Re-enable Foreign Keys
mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 1;");
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Karuda Computer Seeding Complete</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0f172a; color: #e2e8f0; padding: 40px; }
        .box { background: #1e293b; max-width: 800px; margin: 0 auto; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        h1 { color: #38bdf8; margin-top: 0; }
        .log { background: #0f172a; padding: 10px 15px; border-radius: 8px; margin: 6px 0; font-size: 13.5px; border-left: 3px solid #0284c7; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 20px; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🖥️ Karuda Computers — Shop Data Seed Complete</h1>
        <p style="color:#94a3b8">Wiped all old skin care data and inserted 5 Computer Categories + 10 Detailed Products with pricing!</p>
        <hr style="border-color:#334155; margin: 20px 0;">
        <?php foreach ($logs as $log): ?>
            <div class="log"><?php echo htmlspecialchars($log); ?></div>
        <?php endforeach; ?>
        <br>
        <a href="allproducts.php" class="btn">🚀 View Live Products Page</a>
        &nbsp;
        <a href="admin1/adm_all_menu.php" class="btn" style="background:#16a34a">⚙️ Open Admin1 Panel</a>
    </div>
</body>
</html>
