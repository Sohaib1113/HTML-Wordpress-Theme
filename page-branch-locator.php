<?php
/*
Template Name: Branch Locator
*/if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['country']) && !empty($_GET['state']) && !empty($_GET['city'])) {
    $selected_country = $_GET['country'];
    $selected_state = $_GET['state'];
    $selected_city = $_GET['city'];

    $args = array(
        'post_type' => 'branch_locator',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'country',
                'field' => 'term_id',
                'terms' => $selected_country,
            ),
            array(
                'taxonomy' => 'state',
                'field' => 'term_id',
                'terms' => $selected_state,
            ),
            array(
                'taxonomy' => 'city',
                'field' => 'term_id',
                'terms' => $selected_city,
            ),
        ),
    );

    $branch_locator_query = new WP_Query($args);
    
    if ($branch_locator_query->have_posts()) {
        echo '<h3>Branches Found:</h3><ul>';
        while ($branch_locator_query->have_posts()) {
            $branch_locator_query->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>No branches found for the selected location.</p>';
    }
}


get_header(); // Include the header part of your theme
?>

<div class="branch-locator-container">
    <form method="GET" action="" class="branch-locator-form">
        <h2>Locate Us</h2>

        <div class="locator-options">
            <input type="radio" id="mirae_asset" name="locator" value="mirae_asset" checked>
            <label for="mirae_asset">Mirae Asset Offices</label>

            <input type="radio" id="kfintech" name="locator" value="kfintech">
            <label for="kfintech">KFintech Investor Service Center</label>

            <input type="radio" id="distributor" name="locator" value="distributor">
            <label for="distributor">Locate Your Distributor</label>
        </div>

        <div class="form-group">
            <label for="country">Select Country</label>
            <select name="country" id="country">
                <option value="">Select Country</option>
                <?php
                $countries = get_terms(array(
                    'taxonomy' => 'country',
                    'hide_empty' => false,
                ));
                foreach ($countries as $country) {
                    echo '<option value="' . esc_attr($country->term_id) . '">' . esc_html($country->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="state">Select State</label>
            <select name="state" id="state">
                <option value="">Select State</option>
                <?php
                $states = get_terms(array(
                    'taxonomy' => 'state',
                    'hide_empty' => false,
                ));
                foreach ($states as $state) {
                    echo '<option value="' . esc_attr($state->term_id) . '">' . esc_html($state->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="city">Select City</label>
            <select name="city" id="city">
                <option value="">Select City</option>
                <?php
                $cities = get_terms(array(
                    'taxonomy' => 'city',
                    'hide_empty' => false,
                ));
                foreach ($cities as $city) {
                    echo '<option value="' . esc_attr($city->term_id) . '">' . esc_html($city->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" value="Search" class="search-button">
        </div>
    </form>
</div>

<?php
get_footer(); // Include the footer part of your theme
?>
