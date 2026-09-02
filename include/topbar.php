<style>
    .suggestions_container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        position: absolute;
        width: 100%;
        cursor: pointer;
        display: none;
        z-index: 10;
        background-color: white;
    }

    .top_search {
        position: relative;
    }

    .results_color {
        background-color: white;
        padding: 7px 0px;
        margin-left: 20px;
    }
</style>

<section class="hero_single version_5">
    <div class="wrapper">
        <div class="container">
            <div class="row justify-content-center pt-lg-5">
                <div class="col-xl-5 col-lg-6">
                    <h3>Find what you need!</h3>
                    <p>Discover top rated products and services around the world</p>

                    <!-- Mobile Search Form -->
                    <form action="search_results.php" method="GET" id="mobileSearchForm" autocomplete="off">
                        <div class="custom-search-input-3">
                            <div class="form-group top_search">
                                <input class="form-control" onkeyup="mobileSearchResults()" id="mobileSearchInput"
                                    name="searchquery" type="text" placeholder="What are you looking for..." required>
                                <i class="icon_search"></i>
                                <div class="text-dark bg-white suggestions_container no-gutters custom-search-input-3"
                                id="mobileSuggestions"></div>
                               
                            </div>

                            <select class="wide" onchange="limited_select()" name="cats" id="cats" required>
                                <option value="all">All Categories</option>
                                <?php
                                $sql1 = "SELECT * FROM `res_category` ORDER BY `c_name` ASC";
                                $result1 = $con->query($sql);
                                if ($result1->num_rows > 0) {
                                    while ($row1 = $result1->fetch_assoc()) { ?>
                                <option value="<?php echo $row1['c_name'] ?>"><?php echo $row1['c_name'] ?></option>
                                <?php }
                                } ?>
                            </select>
                            <input type="submit" name="submit1" value="Search">
                        </div>
                    </form>

                    <!-- Desktop Search Form -->
                    <form action="search_results.php" method="GET" id="myForm" autocomplete="off">
                        <div class="custom-search-input-2">
                            <div class="form-group top_search">
                                <input class="form-control" onkeyup="searchResults()" id="searchInput"
                                    name="searchquery" type="text" placeholder="What are you looking for..." required>
                                <i class="icon_search"></i>
                                <div class="text-dark bg-white suggestions_container no-gutters custom-search-input-2"
                                    id="suggestions"></div>
                            </div>

                            <select class="wide" onchange="limited_select()" name="cat" id="cat" required>
                                <option value="all">All Categories</option>
                                <?php
                                $sql = "SELECT * FROM `res_category` ORDER BY `c_name` ASC";
                                $result = $con->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?php echo $row['c_name'] ?>"><?php echo $row['c_name'] ?></option>
                                <?php } } ?>
                            </select>
                            <input type="submit" name="submit1" value="Search">
                            <input type="hidden" class="btn-submit">
                        </div>
                    </form>
                </div>

                <div class="col-xl-5 col-lg-6 text-right d-none d-lg-block">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-interval="100">
                                <img class="img-fluid" src="img/Vector_winter_round_design_with_spices_and_herbs__Decorative_colorful___-removebg-preview.png" alt="Slide">
                            </div>
                            <div class="carousel-item" data-interval="100">
                                <img class="img-fluid" src="img/Vector_winter_round_design_with_spices_and_herbs__Decorative_colorful___-removebg-preview.png" alt="Slide">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- JavaScript for Desktop Suggestions -->
<script>
    function searchResults() {
        let searchInput = document.getElementById('searchInput').value.trim();
        let category = document.getElementById('cat').value.trim();
        let suggestions = document.getElementById('suggestions');

        if (searchInput.length === 0) {
            suggestions.innerHTML = '';
            suggestions.style.display = 'none';
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open('GET', `searchHandler.php?search=${encodeURIComponent(searchInput)}&category=${encodeURIComponent(category)}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                let results = JSON.parse(xhr.responseText);
                if (results.status == true) {
                    suggestions.innerHTML = results.value;
                    suggestions.style.display = 'block';
                } else {
                    suggestions.style.display = 'none';
                }
            } else {
                console.error('Request failed. Status: ' + xhr.status);
            }
        };
        xhr.send();
    }

    document.addEventListener('click', function(event) {
        let suggestions = document.getElementById('suggestions');
        if (event.target != suggestions && !suggestions.contains(event.target)) {
            suggestions.style.display = 'none';
        }
    });

    function showTheResult(result) {
        document.getElementById('searchInput').value = result;
        document.getElementById('suggestions').style.display = 'none';
    }

    function limited_select() {
        document.getElementById('suggestions').style.display = 'none';
        document.getElementById('searchInput').value = '';
    }
</script>

<!-- JavaScript for Mobile Suggestions -->
<script>
    function mobileSearchResults() {
    let mobileSearchInput = document.getElementById('mobileSearchInput').value.trim();
    let category = document.getElementById('cats').value.trim();
    let mobileSuggestions = document.getElementById('mobileSuggestions');

    if (mobileSearchInput.length === 0) {
        mobileSuggestions.innerHTML = '';
        mobileSuggestions.style.display = 'none';
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET',
        `searchHandler.php?search=${encodeURIComponent(mobileSearchInput)}&category=${encodeURIComponent(category)}`, true
    );
    xhr.onload = function() {
        if (xhr.status === 200) {
            let results = JSON.parse(xhr.responseText);
            if (results.status == true) {
                mobileSuggestions.style.display = 'block';
                mobileSuggestions.innerHTML = results.value;
            } else {
                mobileSuggestions.innerHTML = '';
                mobileSuggestions.style.display = 'none';
            }
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.send();
}

document.addEventListener('click', function(event) {
    let mobileSuggestions = document.getElementById('mobileSuggestions');
    let targetElement = event.target;

    if (targetElement != mobileSuggestions && !mobileSuggestions.contains(targetElement)) {
        mobileSuggestions.style.display = 'none';
    }
});

function showTheResult(get) {
    document.getElementById('mobileSearchInput').value = get;
    document.getElementById('mobileSuggestions').style.display = 'none';
}

function limited_select() {
    document.getElementById('mobileSuggestions').style.display = 'none';
    document.getElementById('mobileSearchInput').value = '';
}

</script>
