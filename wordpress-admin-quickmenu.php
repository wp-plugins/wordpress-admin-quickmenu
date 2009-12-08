<?php
/*
Plugin Name: WordPress Admin Quick Menu
Plugin URI: http://regentware.com/software/web-based/wordpress-plugins/quick-menu/
Description:  This simple WordPress plugin allows users to add quick menu items to the WordPress sidebar. It's designed to help webmasters have easy access to external pages such as Analytics and shopping carts in their WordPress admin panel.
Author: Christopher Ross
Version: 1.1.0
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
	update_option("quickmenu-1", "Google Analytics||http://www.google.com/analytics||10");
	update_option("quickmenu-2", "Google AdSense||http://www.google.com/adsense||10");
	update_option("quickmenu-3", "Google Webmaster Tools||http://www.google.com/webmasters||10");
	update_option("quickmenu-4", "Google Adwords||http://adwords.google.com||10");
	update_option("quickmenu-5", "WordPress||http://www.wordpress.org||10");
	update_option("quickmenu-0", "Plugin Author||http://www.thisismyurl.com||10");
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







	/* Page Start */
	echo "
<div class='wrap'>
  <div id='icon-options-general' class='icon32'><br />
  </div>
  <h2>WordPress PHPInfo() Settings</h2>
    <div id='poststuff' class='metabox-holder has-right-sidebar'>
      <div id='side-info-column' class='inner-sidebar'>
        <div id='side-sortables' class='meta-box-sortables'>
          <div id='linksubmitdiv' class='postbox ' >
            <div class='handlediv' title='Click to toggle'><br />
            </div>
            <h3 class='hndle'><span>Plugin Details</span></h3>
            <div class='inside'>
              <div class='submitbox' id='submitlink'>
                <div id='minor-publishing'>
                  <div style='display:none;'>
                    <input type='submit' name='save' value='Save' />
                  </div>
                  <div id='minor-publishing-actions'>
                    <div id='preview-action'> </div>
                    <div class='clear'></div>
                  </div>
                  <div id='misc-publishing-actions'>
                    <div class='misc-pub-section misc-pub-section-last'>
                          <ul class='options' style='padding-left: 20px;'>
							<style>.options a {text-decoration:none;}</style>
							<li><a href='http://regentware.com/software/web-based/wordpress-plugins/quick-menu/'>Plugin Homepage</a></li>
							<li><a href='http://wordpress.org/extend/plugins/wordpress-admin-quickmenu/'>Vote for this Plugin</a></li>
							<li><a href='http://forums.thisismyurl.com/'>Support Forum</a></li>
							<li><a href='http://support.thisismyurl.com/'>Report a Bug</a></li>
							<li><a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5998895'>Donate</a></li>";
							
							
if (function_exists(zip_open)) {
	$file = "wordpress-admin-quickmenu";
			$lastupdate = get_option($file."-update");
		if (strlen($lastupdate )==0 || date("U")-$lastupdate > $lastupdate) {
			$pluginUpdate = file_get_contents('http://downloads.wordpress.org/plugin/'.$file.'.zip');
			$myFile = "../wp-content/uploads/cache-".$file.".zip";
			$fh = fopen($myFile, 'w') or die("can't open file");
			$stringData = $pluginUpdate;
			fwrite($fh, $stringData);
			fclose($fh);
			
			$zip = zip_open($myFile);
			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_name($zip_entry) == $file."/".$file.".php") {$size = zip_entry_filesize($zip_entry);}
			}
			zip_close($zip);
			unlink($myFile);
			
			if ($size != filesize("../wp-content/plugins/".$file."/".$file.".php")) {?>    
	
				<li>This plugin is out of date. <a href='http://downloads.wordpress.org/plugin/<?php echo $file;?>.zip'>Please <strong>download</strong> the latest version.</a></li>
	
	<?php
		} 
		update_option($file."-update", date('U'));
}}
							
					echo "		</ul>
                    </div>
                  </div>
                </div>
                <div id='major-publishing-actions'>
                  <div id='delete-action'> </div>
                  <div id='publishing-action'>
                  </div>
                  <div class='clear'></div>
                </div>
                <div class='clear'></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id='post-body'>
        <div id='post-body-content'>

          <div id='addressdiv' class='stuffbox'>
            <h3>
              <label for='link_url'>Settings</label>
            </h3>

            <div class='inside' style='font-size: 16px;'>";
	
	
	
	
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