<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    <title>Testimonial Slider</title>
   <style>
body.testi {
   
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px; /* Adjust as needed */
    margin: 0 auto; /* Centers the container */
    padding: 20px; /* Add padding for spacing */
}

.main_title_2 {
    text-align: center;
    margin-bottom: 2px; 
}

.mySwiper {
    height: 80vh;
    display: flex;
    align-items: center;
}

.card_testi {
    text-align: center;
    height: 230px;
    width: 300px;
    padding: 10px 30px;
    border-radius: 10px;
    background-color: #E6F9E6;
    box-shadow: rgb(149 157 165 / 20%) 0px 8px 24px;
}

.swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center; 
}

.card_testi img {
    width: 130px;
    border-radius: 50%;
    border: 1px solid green;
    height: 200px;
}

h3, h4 {
    margin-bottom: 0;
    margin-top: 0;
}

p {
    
    line-height: 20px;
    color: #222222;
    margin-top: 10px;
    justify-content: center;
}

h3 {
    font-size: 16px;
}

h4 {
    font-size: 12px;
    margin-top: 10px;
    color: green;
}

.swiper-button-next, .swiper-rtl .swiper-button-prev {
    color: green;
}

.swiper-button-next:after, .swiper-button-prev:after {
    font-size: 32px;
}

.review i {
    color: orange;
}

   </style>
<body class="testi">
 
	<div class="container ">
   
                <div class="main_title_2">
                    <h2 >Testimonials</h2>
                    <p >"See What Others Are Saying About Us"</p>
                </div>
            
     <div class="swiper mySwiper ">
        <div class="swiper-wrapper">
          
            <?php
            include "dbconnect.php";
            $query = "SELECT * FROM testi";
            $result = mysqli_query($con, $query);
            $first = true; // Variable to set the first carousel item to active
            while ($row = mysqli_fetch_array($result)) {
                $name = $row['name'];
                $designation = $row['design'];
                $message = $row['message'];
                $activeClass = $first ? 'active' : ''; // Add 'active' class to the first item
                $first = false;
        ?>
           <div class="swiper-slide">
          <div class="card card_testi">
          <h3><?php echo $name; ?></h3>
          <h4><?php echo $designation; ?></h4>
			
                <p><?php echo $message; ?></p>
               
                </div>
                </div>
            <?php } ?>
          
      
        </div>
      </div>
      </div>    


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    var slider = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    breakpoints: {
        480: {
            slidesPerView: 2,
            spaceBetween: 40,
        },
        640: {
            slidesPerView: 3,
            spaceBetween: 50,
        },
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 2000,
        disableOnInteraction: false, // Keeps autoplay running after user interactions
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true, // Allows clicking on pagination bullets
    },
});

</script>
</body>
</html>