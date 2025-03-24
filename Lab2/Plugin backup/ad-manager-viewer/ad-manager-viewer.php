<?php
/*
* Plugin Name: Ad manager viewer
* Description: Adds advertisement manager panel and ads to each post
* Version: 1.0
*/

function get_ads() {
    #check if 'ads_list' option exists, if not, create it with an empty array
    if (false === get_option('ads_list')) {
        add_option('ads_list', []); #create 'ads_list' with an empty array
    }

    return get_option('ads_list', []); #retrieve the ads list (default: empty array)
}


function add_ad($ad) {
    $ads = get_ads();
    $ads[] = $ad;
    update_option('ads_list', $ads);
}

function remove_ad($ad) {
    $ads = get_ads();
    
    if (in_array($ad, $ads)) {
        $ads = array_filter($ads, function($a) use ($ad) {
            return $a !== $ad;
        });
        update_option('ads_list', $ads);
    } else {
        echo '<div class="notice notice-failure is-dismissible"><p>Ad not found.</p></div>';
    }
}

function remove_all_ads() {
    delete_option('ads_list');
}


function amv_admin_page(){
    #handle form submissions
    if  (isset($_POST['action']) && (isset($_POST['ad-text']))) {

            $ad_text = $_POST['ad-text'];
            if (!empty($ad_text)) {
                if ($_POST['action'] === "create-ad") {
                    add_ad($ad_text);
                } elseif ($_POST['action'] === "remove-ad") {
                    remove_ad($ad_text);
                } 
            }
    }
    if ( isset($_POST['action'])) {
        if ($_POST['action'] === "remove-all_ads") {
            remove_all_ads();
        }
    }
   ?>

    <form method="POST">
        Add an ad with text:
        <input type="text" name="ad-text">
        <input type="submit" name="action" value="create-ad">
    </form>

    <form method="POST">
        Remove ad with text:
        <input type="text" name="ad-text">
        <input type="submit" name="action" value="remove-ad">
    </form>

    <form method="POST">
        Remove all ads:
        <input type="hidden" name="ad-text">
        <input type="submit" name="action" value="remove-all_ads">
    </form>

    <?php
        #display current ads
        $ads = get_ads();
    ?>
    
    <h2>Current Ads</h2>
    <ul>
        <?php foreach ($ads as $ad): ?>
            <li><div class="ad"><?php echo htmlspecialchars($ad); ?></div></li>
        <?php endforeach; ?>
    </ul>

   <?php
   echo '<pre>';
   print_r($ads);
   echo '</pre>';
   
}

#add ads after clicking on a post
function amv_add_random_ad($title) {
    $ads = get_ads();
    if (is_single() && !empty($ads) &&  is_main_query()) {
        $random_ad = $ads[array_rand($ads)];
        $ad_html = $ad_html = '<div class="ad">' . $random_ad . '</div>';
        $title = $title . $ad_html;
    }
    return $title;
}
add_action('the_title', 'amv_add_random_ad');

#add custom settings page
function amv_admin_actions_register_menu(){
    add_options_page("ad-manager-viewer", "Ad manager", 'manage_options', "amv", "amv_admin_page");
}
add_action('admin_menu', 'amv_admin_actions_register_menu'); 

function amv_register_styles(){
 #register style
 wp_register_style('amv_styles', plugins_url('/css/style.css', __FILE__));
 #enable style (load in meta of html)
 wp_enqueue_style('amv_styles');
}
add_action('init', 'amv_register_styles'); 
?>

