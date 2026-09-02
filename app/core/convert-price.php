<?php
/**
 * Karuda Computers - Currency & Price Conversion Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = \App\Core\Database::getInstance();
$con = $db->getConnection();

if (isset($_POST['select_currency']) && $_POST['select_currency'] == 'true') {
    $_SESSION['selectedCurrency'] = $_POST['currency'];
    if (!empty($_SESSION['selectedCurrency'])) {
        echo "Sess_active";
        exit;
    }
}

if (!isset($_SESSION['selectedCurrency']) || empty($_SESSION['selectedCurrency'])) {
    $_SESSION['selectedCurrency'] = '₹';
}

if (!function_exists('getRatesFromDB')) {
    function getRatesFromDB($con) {
        $query = "SELECT currency, rate FROM exchange_rates";
        $stmt = mysqli_query($con, $query);
        $rates = [];
        if ($stmt) {
            while ($row = mysqli_fetch_array($stmt)) {
                $rates[$row['currency']] = $row['rate'];
            }
        }
        return $rates;
    }
}

if (!function_exists('convertPrice')) {
    function convertPrice($price, $fromCurrency, $toCurrency, $rates) {
        if ($fromCurrency === $toCurrency) {
            return $price;
        }
        if (!isset($rates[$fromCurrency]) || !isset($rates[$toCurrency])) {
            return $price;
        }
        $usdPrice = $price / $rates[$fromCurrency]; 
        return $usdPrice * $rates[$toCurrency];
    }
}

$rates = getRatesFromDB($con);

if (!function_exists('getCountriesFromDB')) {
    function getCountriesFromDB($con) {
        $query = "SELECT country_name, currency_code FROM countries ORDER BY country_name";
        $result = mysqli_query($con, $query);
        $countries = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $countries[] = $row;
            }
        }
        return $countries;
    }
}

$countries = getCountriesFromDB($con);

if (!function_exists('generateCountrySelectOptions')) {
    function generateCountrySelectOptions($countries) {
        $options = '';
        $cour_id = 0;
        foreach ($countries as $country) {
            $cour_id++;
            $options .= "<div class='col-lg-3 col-6 mb-2'>
                            <label class=' more_style w-100' onclick='CountrySelect($cour_id)' for='$cour_id'>{$country['country_name']}</label>
                            <input type='radio' class='d-none' id='country_$cour_id' name='country_select' value='{$country['currency_code']}'>
                        </div>";
        }
        return $options;
    }
}

$countryOptions = generateCountrySelectOptions($countries);
