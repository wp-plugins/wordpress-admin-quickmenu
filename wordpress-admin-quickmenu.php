<?php
/*
Plugin Name: WordPress Admin Quick Menu
Plugin URI: http://www.thisismyurl.com/wordpress/plugins/wordpress-admin-quickmenu/
Description:  This simple WordPress plugin allows users to add quick menu items to the WordPress sidebar. It's designed to help webmasters have easy access to external pages such as Analytics and shopping carts in their WordPress admin panel.
Author: Christopher Ross
Version: 1.0.1
Author URI: http://www.thisismyurl.com
*/


/*  Copyright 2008  Christopher Ross  (email : info@thisismyurl.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$menuitem=get_option("quickmenu-1");
if (strlen($menuitem) < 10) {
	update_option("quickmenu-10", "Plugin Homepage||http://www.thisismyurl.com||10");
}

add_action('admin_menu', 'WordPressAdminQuickMenu_menu');

function WordPressAdminQuickMenu_menu() {
  add_menu_page('Quick Menu', 'Quick Menu', 10,'WordPressAdminQuickMenu.php', 'WordPressAdminQuickMenu_options');
	for ( $counter = 0; $counter <= 10; $counter += 1) {
		$menuitem=get_option("quickmenu-".$counter);
		if (strlen($menuitem) > 10) {
			$menu = explode("||",get_option("quickmenu-".$counter));
			add_submenu_page('WordPressAdminQuickMenu.php', $menu[0], $menu[0], $menu[2], $menu[1]);
		}
	}
}

function WordPressAdminQuickMenu_options() {

?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2>Admin Quick Menu Settings</h2>
    
    
    
    <div id="poststuff" class="metabox-holder">
    <div class="inner-sidebar">
    <div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
    
    <div id="sm_pnres" class="postbox">
    <h3 class="hndle"><span>About this Plugin:</span></h3>
    <div class="inside">
    <ul class='options'>
    <style>.options a {text-decoration:none;}</style>
    <li><a href="http://www.thisismyurl.com/wordpress/plugins/wordpress-admin-quickmenu/">Plugin Homepage</a></li>
    <li><a href="http://wordpress.org/extend/plugins/wordpress-admin-quickmenu/">Vote for this Plugin</a></li>
    <li><a href="http://forums.thisismyurl.com/">Support Forum</a></li>
    <li><a href="http://support.thisismyurl.com/">Report a Bug</a></li>
    <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5998895">Donate with PayPal</a></li>
    
    <?php 
	  	$pluginUpdate = file_get_contents('http://downloads.wordpress.org/plugin/wordpress-admin-quickmenu.zip');
	 	// scan for updates here
		// include menu item if found
	 ?>
    </ul>
    </div>
    </div>
    
    </div>
    </div>
    
    <div class="has-sidebar sm-padded" >
    
    <div id="post-body-content" class="has-sidebar-content">
    
    <div class="meta-box-sortabless">
    
    <!-- Rebuild Area -->
    <!-- Basic Options -->
    <div id="sm_basic_options" class="postbox">
    <h3 class="hndle"><span>Basic Options</span></h3>
    <div class="inside">
    <?
	
	
	
	if (isset($_POST['Submit'])) {
		for ( $counter = 0; $counter <= 10; $counter += 1) {
			update_option("quickmenu-".$counter, $_POST['name-'.$counter]."||".$_POST['url-'.$counter]."||".$_POST['security-'.$counter]);
		}
		echo "<div id='message' class='updated fade'><p><strong>Settings saved. Please <a href='admin.php?page=WordPressAdminQuickMenu.php'>reload</a> for settings to take affect.</strong></p></div>";
	}
	
	?>
    <form method="post">
    <?php
	echo "<table>";
	echo "<tr>";
	echo "<td style='width: 200px;'><strong>Menu Title</strong></td>";
	echo "<td style='width: 300px;'><strong>Hyperlink</strong></td>";
	echo "<td style='width: 100px;'><strong>Security</strong></td>";
	echo "</tr>";

	for ( $counter = 0; $counter <= 10; $counter += 1) {
	
		echo "<tr>";
		unset($menuitem);
		$menuitem=get_option("quickmenu-".$counter);
		if (strlen($menuitem) > 5) {
			$menu = explode("||",get_option("quickmenu-".$counter));
		}
		
		echo "<td><input type='text' name='name-$counter' id='name-$counter' value='".$menu[0]."' style='width: 150px;'></td>";
		echo "<td><input type='text' name='url-$counter' id='url-$counter'  value='".$menu[1]."' style='width: 280px;'></td>";
		echo "<td><select name='security-$counter' id='security-$counter'>";
		
		echo "<option value='10'";
		if (!$menu[2] && $a == 10) {echo ' selected';}
		echo ">Administrator</option>";
		echo "<option value='7'";
		if (!$menu[2] && $a == 7) {echo ' selected';}
		echo ">Editor</option>";
		echo "<option value='2'";
		if (!$menu[2] && $a == 2) {echo ' selected';}
		echo ">Author</option>";
		echo "<option value='1'";
		if (!$menu[2] && $a == 1) {echo ' selected';}
		echo ">Contributor</option>";
		if (!$menu[2] && $a == 0) {echo ' selected';}
		echo "<option value='0'";
		echo ">Subscriber</option>";


		echo "</select></td>";
		echo "</tr>";
	}
	echo "</table>";
	
	?>
    <p class="hndle">This Plugin using the <a href='http://codex.wordpress.org/Roles_and_Capabilities'>Roles_and_Capabilities</a> for Security Levels.</p>
    
    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
    </p>
	</form>

    </div>
    </div>



    
    <div id="sm_basic_options2" class="postbox">
      <h3 class="hndle"><span>Read Me File Contents</span></h3>
    <div class="inside">
      <?php 
	  $contents = file_get_contents('../wp-content/plugins/wordpress-admin-quickmenu/readme.txt');
	  $contents = str_replace("\n","<br>",$contents);
	  echo $contents;
	  ?>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</div>
<?php
}















?>