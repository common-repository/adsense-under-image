<?php
/*
Plugin Name: Adsense Under Image
Plugin URI: http://www.seoadventures.com/adsense-under-image
Description: This plugin places adsense under the first image in a post.
Author: jstroh
Version: 1.0
Author URI: http://www.seoadventures.com/

Changes:

12/17/07: Version 1.0

	Initial release.


*/

/*
Copyright (C) 2007 jstroh (jstroh AT gmail DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



function addAdsenseUnderImage($content)
{
if(is_single())
{
$opt_name = 'aui_adsense';

    // Read in existing option value from database
    $opt_val = stripslashes(get_option( $opt_name ));

    if($opt_val=="")
    {
$opt_val = '
<script type="text/javascript"><!--
google_ad_client = "pub-5496966632190476";
//468x60, created 12/15/07
google_ad_slot = "9723841850";
google_ad_width = 468;
google_ad_height = 60;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
';
    }

$adsense_code = $opt_val;

    $pattern = '/<img[^>]*>/';
    preg_match($pattern, $content, $matches);

    if(sizeof($matches)>0)
    {
        if(strstr($content,$matches[0]."</a>"))
            $content = str_replace($matches[0]."</a>",$matches[0]."</a>".$adsense_code,$content);
        else
            $content = str_replace($matches[0],$matches[0].$adsense_code,$content);
    }
}

    return $content;
}



add_filter('the_content','addAdsenseUnderImage');


// Hook for adding admin menus
add_action('admin_menu', 'aui_add_pages');

// action function for above hook
function aui_add_pages() {
    // Add a new submenu under Options:
    add_options_page('Adsense Under Image', 'Adsense Under Image', 8, 'auioptions', 'aui_options_page');
}

// aui_options_page() displays the page content for the Test Options submenu
function aui_options_page() {

    // variables for the field and option names 
    $opt_name = 'aui_adsense';
    $hidden_field_name = 'aui_submit_hidden';
    $data_field_name = 'aui_adsense';

    // Read in existing option value from database
    $opt_val = stripslashes(get_option( $opt_name ));

    if($opt_val=="")
    {
$opt_val = '
<script type="text/javascript"><!--
google_ad_client = "pub-5496966632190476";
//468x60, created 12/15/07
google_ad_slot = "9723841850";
google_ad_width = 468;
google_ad_height = 60;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
';
    }

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = stripslashes($_POST[ $data_field_name ]);

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'aui_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Menu Test Plugin Options', 'aui_trans_domain' ) . "</h2>";

    // options form
    
    ?>

<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Your Adsense Code:", 'aui_trans_domain' ); ?> <br />
<textarea cols="60" rows="20" name="<?php echo $data_field_name; ?>"><?php echo $opt_val; ?></textarea>
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'aui_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php

}

?>