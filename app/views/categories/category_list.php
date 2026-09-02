<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
require ('include/header.php');
?>
<style>
.search_btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 0 8px 8px 0 !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
}

.search_btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    box-shadow: 0 4px 14px rgba(0, 188, 212, 0.4);
}

.category_s {
    height: 370px !important;
}

.strip .wrapper {
    padding: 20px 25px 1px 25px !important;
}


.suggestions_container_1 {
    border-radius: 0px !important;
    position: absolute;
    border: 1px solid #ddd;
    max-height: 200px;
    width: 95%;
    overflow-y: auto;
    z-index: 1000;
}

.suggestion-item {
    padding: 8px;
    cursor: pointer;
}

.highlighted {
    background-color: #e0e0e0;
}

.suggestions_container_1::-webkit-scrollbar {
    width: 8px;
}

.suggestions_container_1::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.suggestions_container_1::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}


.suggestion-item:hover {
    background-color: #f0f0f0;
}

.d-none {
    display: none;
}
</style>

<body>

    <div id="page">
        <header class="kc-header-container">
            <?php  require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>
        <?php
        function No_of_products($con, $cate_id){
            $select = mysqli_query($con,"SELECT * FROM dishes where cateid = '$cate_id'");
            return mysqli_num_rows($select);
        }

         $query1 = "SELECT * FROM res_category  order by c_id desc";
         $sqll = mysqli_query($con, $query1);
         $num_of = mysqli_num_rows($sqll);
         ?>

        <div class="sub_header_in sticky_header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-10">
                        <h5 class="text-white">All Categories</h5>
                    </div>

                    <div class="col-lg-7 col-md-8 col-2" style="position:relative">
                        <a href="#0" class="side_panel btn_search_mobile"></a>
                        <form id="search_find">
                            <div class="row no-gutters custom-search-input-2 inner">

                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <input class="form-control" name="category_name" id="search_cate"
                                            oninput="Search_category()" type="text"
                                            placeholder="What category are you looking for...">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <input type="submit" class="search_btn" value="Search">
                                </div>

                            </div>
                        </form>
                        <div class="text-dark bg-white suggestions_container_1 no-gutters custom-search-input-2 d-none"
                            id="suggestions_list"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="filters_listing version_2  sticky_horizontal">
            <div class="container">
                <ul class="clearfix">
                    <li>
                        <div class="switch-field">
                            <input type="radio" id="all" onclick="Get_Fil_result('all')" name="listing_filter"
                                value="all" checked>
                            <label for="all">All</label>
                            <input type="radio" id="popular" onclick="Get_Fil_result('Popular')" name="listing_filter"
                                disabled value="popular">
                            <label for="popular">Popular</label>
                            <input type="radio" id="latest" onclick="Get_Fil_result('Latest')" name="listing_filter"
                                value="latest">
                            <label for="latest">Latest</label>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-success" style="position:relative;top:2rem">Total of <span id="total_of">
                            <?=$num_of; ?> </span> categories available.</h5>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="container margin_60_35">
            <div class="row" id="search_results_add">
                <?php if(mysqli_num_rows($sqll)){
                 while ($r1 = mysqli_fetch_array($sqll)) { ?>
                <div class="col-xl-3 col-lg-6 col-md-6 ">
                    <div class="strip grid category_s">
                        <figure>
                            <a href="allcategories.php?cat=<?=$r1['c_id']; ?>"><img
                                    src="./avadmin/<?= $r1['fpath']; ?>"
                                    onerror="this.onerror=null; this.src='img/alter_img.jpg';" class="img-fluid h-100"
                                    alt="">
                                <div class="read_more"><span>View </span></div>
                            </a>
                        </figure>
                        <div class="wrapper ">
                            <h3><a href="allcategories.php?cat=<?=$r1['c_id']; ?>"
                                    class="text-success"><?= $r1['c_name']; ?></a></h3>
                            <small><?= No_of_products($con, $r1['c_id']) ?> Product(s) Available</small>
                        </div>
                        <div class="d-flex justify-content-center p-3">
                            <a href="allcategories.php?cat=<?=$r1['c_id']; ?>" class="btn_1 w-100">View Product
                                (s)</a>
                        </div>
                    </div>
                </div>
                <?php } }else{   ?>
                <div class="col-12 text-center">
                    <img src="./img/no_datas.png" class="img-fluid h-50" alt="">
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
</body>
<script>
// filters #
let loading_spinner = document.getElementById("loading_spinner");
let total_of = document.getElementById("total_of");

function Get_Fil_result(Formate) {
    const search_results_add = document.getElementById("search_results_add");
    const data = new FormData();
    data.append("Formate", Formate);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "category_search_find.php", true);
    loading_spinner.style.display = 'block';
    xhr.onload = function() {
        loading_spinner.style.display = 'none';
        const result = JSON.parse(this.responseText);
        if (result.status = 1) {
            search_results_add.innerHTML = result.response;
            total_of.textContent = result.total_of;
        } else {
            search_results_add.innerHTML = result.response;
            total_of.textContent = 0;
        }
    }
    xhr.send(data);
}

// search results #
document.getElementById("search_find").addEventListener("submit", function(e) {
    e.preventDefault();
    const search_results_add = document.getElementById("search_results_add");

    const data = new FormData(this);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "category_search_find.php", true);
    loading_spinner.style.display = 'block';
    xhr.onload = function() {
        loading_spinner.style.display = 'none';
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const result = JSON.parse(this.responseText);
                if (result.status === 1) {
                    search_results_add.innerHTML = result.response;
                    total_of.textContent = result.total_of;
                } else {
                    search_results_add.innerHTML = '<p>No results found or an error occurred.</p>';
                    total_of.textContent = 0;
                }
            } catch (e) {
                search_results_add.innerHTML = '<p>Error parsing response.</p>';
            }
        } else {
            search_results_add.innerHTML = '<p>Server error.</p>';
        }
    };

    xhr.onerror = function() {
        search_results_add.innerHTML = '<p>Network error occurred.</p>';
    };

    xhr.send(data);
});



let highlightedIndex = -1;
const suggestionsList = document.getElementById("suggestions_list");
const searchInput = document.getElementById("search_cate");

function Search_category() {
    let searchCategory = searchInput.value;
    let data = new FormData();
    data.append("search", searchCategory);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "category_search.php", true);
    xhr.onload = function() {
        let result = JSON.parse(this.responseText);
        suggestionsList.innerHTML = '';

        if (Array.isArray(result) && result.length > 0) {
            suggestionsList.classList.remove("d-none");
            result.forEach(function(item, index) {
                let suggestionItem = document.createElement("div");
                suggestionItem.className = "suggestion-item";
                suggestionItem.textContent = item;
                suggestionItem.setAttribute("data-index", index);
                suggestionItem.addEventListener("click", function() {
                    selectItem(index);
                });
                suggestionsList.appendChild(suggestionItem);
            });
        } else {
            suggestionsList.classList.add("d-none");
        }
    };
    xhr.send(data);
}

function handleKeyboardNavigation(event) {
    let items = suggestionsList.getElementsByClassName("suggestion-item");

    if (event.key === "ArrowDown") {
        highlightedIndex = Math.min(highlightedIndex + 1, items.length - 1);
    } else if (event.key === "ArrowUp") {
        highlightedIndex = Math.max(highlightedIndex - 1, 0);
    } else if (event.key === "Enter" && highlightedIndex >= 0) {
        selectItem(highlightedIndex);
        return;
    }

    updateHighlightedItem(items);
}

function updateHighlightedItem(items) {
    Array.from(items).forEach(item => item.classList.remove("highlighted"));

    if (highlightedIndex >= 0 && highlightedIndex < items.length) {
        items[highlightedIndex].classList.add("highlighted");
        items[highlightedIndex].scrollIntoView({
            block: 'nearest'
        });
    }
}

function selectItem(index) {
    let items = suggestionsList.getElementsByClassName("suggestion-item");
    if (index >= 0 && index < items.length) {
        searchInput.value = items[index].textContent;
        suggestionsList.classList.add("d-none");
        highlightedIndex = -1;
    }
}

function hideSuggestionsOnClickOutside(event) {
    if (!suggestionsList.contains(event.target) && event.target !== searchInput) {
        suggestionsList.classList.add("d-none");
    }
}

searchInput.addEventListener("keydown", handleKeyboardNavigation);
document.addEventListener("click",
    hideSuggestionsOnClickOutside);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<footer id="footer">
    <?php include('include/footer.php'); ?>
</footer>

<?php include ('include/sign_footer.php'); ?>



</html>