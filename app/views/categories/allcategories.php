<?php
if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(0);
require ('include/header.php');
?>
<style>
#page {
    background-color: #f8fafc;
}
.kc-category-header {
    background: radial-gradient(circle at 50% 30%, #1a365d 0%, #0b192c 60%, #060d17 100%);
    padding: 50px 0;
    text-align: center;
    color: white;
    margin-bottom: 35px;
    border-bottom: 1px solid rgba(0, 188, 212, 0.2);
}
.kc-category-header h1 {
    font-weight: 800;
    margin-bottom: 10px;
    font-size: 2.3rem;
    letter-spacing: -0.5px;
    text-transform: uppercase;
}
.kc-category-header p {
    font-size: 1.05rem;
    color: #94A3B8;
    max-width: 600px;
    margin: 0 auto;
}
.kc-cat-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #f1f5f9;
}
.kc-cat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 112, 243, 0.15);
    border-color: #0070F3;
}
.kc-cat-img-wrap {
    height: 200px;
    overflow: hidden;
    position: relative;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kc-cat-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.kc-cat-card:hover .kc-cat-img-wrap img {
    transform: scale(1.05);
}
.kc-cat-body {
    padding: 20px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.kc-cat-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0;
    text-decoration: none !important;
}
.kc-cat-card:hover .kc-cat-title {
    color: #0070F3;
}
.kc-cat-link {
    text-decoration: none !important;
    display: block;
    height: 100%;
}
</style>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <div class="sub_header_in">
            <div class="container text-center">
                <h1>Shop by Category</h1>
                <p>Explore our extensive collection of high-performance laptops, gaming desktops, components, and tech accessories.</p>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): 
                        $img = !empty($cat['fpath']) ? $cat['fpath'] : 'img/default_category.jpg';
                        $resolved_img = (file_exists('avadmin/'.$img) ? 'avadmin/'.$img : (file_exists('admin/'.$img) ? 'admin/'.$img : $img));
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                        <a href="allproducts.php?cat=<?php echo htmlspecialchars($cat['c_id']); ?>" class="kc-cat-link">
                            <div class="kc-cat-card">
                                <div class="kc-cat-img-wrap">
                                    <img src="<?php echo htmlspecialchars($resolved_img); ?>" alt="<?php echo htmlspecialchars($cat['c_name']); ?>" onerror="this.src='img/products/hp_laptop.jpg'">
                                </div>
                                <div class="kc-cat-body">
                                    <h3 class="kc-cat-title"><?php echo htmlspecialchars($cat['c_name']); ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-folder-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No categories available at the moment.</h4>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <footer id="footer">
        <?php include('include/footer.php'); ?>
    </footer>
    <?php include ('include/sign_footer.php'); ?>
</body>
</html>