<?php
/*
Plugin Name: WordPress Admin Quick Menu
Plugin URI: http://thisismyurl.com/plugins/wordpress-admin-quick-menu/
Description:  This simple WordPress plugin allows users to add quick menu items to the WordPress sidebar. It's designed to help webmasters have easy access to external pages such as Analytics and shopping carts in their WordPress admin panel.
Author: Christopher Ross
Version: 1.2.9
Author URI: http://thisismyurl.com/
*/


/**
 * WordPress Admin Quick Menu core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/wordpress-admin-quick-menu/
 *
 * @package 		WordPress Admin Quick Menu
 * @copyright		Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		WordPress Admin Quick Menu 1.0
 */


if(!isset($timu)) $timu = new stdClass();
$timu->quick_menu_slug = 'WordPressAdminQuickMenu.php';


// Perform Redirect
function WordPressAdminQuickMenu_redirect() {
	//Header( "Location: " . $_GET['href'] );
	//die();
	?>
	<html>
		<head>
			<script type="text/javascript">
			var features = 	'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes';
			var newWindow = window.open('<?php echo $_GET['href']; ?>', '', features);
			if(typeof newWindow != 'undefined') window.location.replace('<?php echo wp_get_referer(); ?>');
			else window.location.replace('<?php echo $_GET['href']; ?>');
			</script>
		</head>
		<body></body>
	</html>
	<?php
	die();
} // WordPressAdminQuickMenu_redirect()
if(strlen($_GET['href']) > 0) {
	WordPressAdminQuickMenu_redirect();
}


add_action('admin_menu', 'WordPressAdminQuickMenu_menu');
function WordPressAdminQuickMenu_menu() {
	global $timu;

	add_menu_page('Quick Menu', 'Quick Menu', 10, $timu->quick_menu_slug, 'WordPressAdminQuickMenu_options');
	$qm_json = get_option('quickmenu');
	if($qm_json) {
		/*
		 New Data Format
		*/
		$qm = json_decode($qm_json);
		foreach($qm as $q) {
			add_submenu_page($timu->quick_menu_slug, $q->title, $q->title, $q->sec, 'admin.php?page='.$timu->quick_menu_slug.'&href='.rawurlencode($q->url));
		}
	}
	else {
		/*
		 Old Data Format
		*/
		for ( $counter = 0; $counter <= 10; $counter += 1) {
		$menuitem=get_option("quickmenu-".$counter);
			if (strlen(trim($menuitem)) > 0) {
				$menu = explode("||",get_option("quickmenu-".$counter));
				add_submenu_page($timu->quick_menu_slug, $menu[0], $menu[0], $menu[2], 'admin.php?page=' . $timu->quick_menu_slug . '&href='.rawurlencode($menu[1]));
			}
		}
	}
} // WordPressAdminQuickMenu_menu()


function WordPressAdminQuickMenu_options() {
	global $timu;

	if (!empty($_POST)) {
		if(isset($_POST['Clear'])) {
			/*
			 Clear All Data
			*/
			delete_option('quickmenu');
			for($i = 0; $i <= 10; $i++) {
				delete_option('quickmenu-'.$i);
			}
		}
		else {
			$qm = array();
			for($i = 0; $i < (count($_POST) - 1) / 3; $i++) {
				if(strlen(trim($_POST['url-'.$i])) > 0) {
					$qm[$i] = new stdClass();
					$qm[$i]->title = $_POST['name-'.$i];
					$qm[$i]->url = $_POST['url-'.$i];
					$qm[$i]->sec = (int)$_POST['security-'.$i];
				}
			}
			$qm_json = json_encode($qm);
			update_option('quickmenu', $qm_json);
		}
		// Reload page so that the new settings take effect
		echo '<script type="text/javascript">window.location.reload();</script>';
	}

	$qm_json = get_option('quickmenu');
	if(!$qm_json) {
		/*
		 This is the first run since the upgrade
		 Or the first run ever
		*/
		$qm = array();
		for($i = 0; $i <= 10; $i++) {
			$qm_old = get_option('quickmenu-'.$i);
			if($qm_old) {
				$menu = explode('||', $qm_old);
				if(strlen(trim($menu[1])) > 0) {
					$idx = count($qm);
					$qm[$idx] = new stdClass();
					$qm[$idx]->title = $menu[0];
					$qm[$idx]->url = $menu[1];
					$qm[$idx]->sec = (int)$menu[2];
				}
			}
		}
		if(empty($qm)) {
			/*
			 Defaults
			*/
			$qm[0] = new stdClass();
			$qm[0]->title = 'Google Analytics';
			$qm[0]->url = 'http://www.google.com/analytics';
			$qm[0]->sec = 10;
			$qm[1] = new stdClass();
			$qm[1]->title = 'Google AdSense';
			$qm[1]->url = 'http://www.google.com/adsense';
			$qm[1]->sec = 10;
			$qm[2] = new stdClass();
			$qm[2]->title = 'Google Webmaster Tools';
			$qm[2]->url = 'http://www.google.com/webmasters';
			$qm[2]->sec = 10;
			$qm[3] = new stdClass();
			$qm[3]->title = 'Google Adwords';
			$qm[3]->url = 'http://adwords.google.com';
			$qm[3]->sec = 10;
			$qm[4] = new stdClass();
			$qm[4]->title = 'WordPress';
			$qm[4]->url = 'http://www.wordpress.org';
			$qm[4]->sec = 10;
			$qm[5] = new stdClass();
			$qm[5]->title = 'Plugin Author';
			$qm[5]->url = 'http://thisismyurl.com/';
			$qm[5]->sec = 10;
			$qm[6] = new stdClass();
			$qm[6]->title = 'Make Donation';
			$qm[6]->url = 'http://thisismyurl.com/plugins/wordpress-admin-quick-menu/';
			$qm[6]->sec = 10;
		}
		$qm_json = json_encode($qm);
		update_option('quickmenu', $qm_json);
	}
	else {
		$qm = json_decode($qm_json);
	}

	/* Page Start */
	?>
	<div class='wrap'>
		<div id='icon-options-general' class='icon32'><br />
		</div>
		<h2>WordPress Quick Menu Settings</h2>
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
												<li><a href='http://thisismyurl.com/plugins/wordpress-admin-quick-menu/'>Plugin Homepage</a></li>
												<li><a href='http://wordpress.org/extend/plugins/wordpress-admin-quickmenu/'>Vote for this Plugin</a></li>
												<li><a href='http://wordpress.org/tags/wordpress-admin-quickmenu'>Support Forum</a></li>
												<li><a href='http://thisismyurl.com/plugins/wordpress-admin-quick-menu/'>Donate</a></li>
											</ul>
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
						<div class='inside' style='font-size: 16px;'>
							<?php
							// If JavaScript reload fails, display reload message
							if (isset($_POST['Submit'])) { ?>
							<div id="message" class="updated fade"><p><strong>Settings saved. Please <a href="admin.php?page=<?php echo $timu->quick_menu_slug; ?>">reload</a> for settings to take effect.</strong></p></div>
							<?php } ?>
							<form id="quickmenu_form" method="post">
								<table>
									<tr>
										<td style='width:200px;'><strong>Menu Title</strong></td>
										<td style='width:300px;'><strong>Hyperlink</strong></td>
										<td style='width:100px;'><strong>Security</strong></td>
									</tr>
									<?php
									$i = 0;
									foreach($qm as $q) {
										// Pulled Records
										?>
										<tr id="tr-<?php echo $i; ?>">
											<td>
												<input type="text" name="name-<?php echo $i; ?>" value="<?php echo $q->title; ?>" style="width:150px;" />
											</td>
											<td>
												<input type="text" name="url-<?php echo $i; ?>" value="<?php echo $q->url; ?>" style="width:280px;" />
											</td>
											<td>
												<select name="security-<?php echo $i; ?>" id="security-<?php echo $i; ?>">
													<option value="10" <?php if($q->sec == 10) echo 'selected="selected"'; ?>>Administrator</option>
													<option value="7" <?php if($q->sec == 7) echo 'selected="selected"'; ?>>Editor</option>
													<option value="2" <?php if($q->sec == 2) echo 'selected="selected"'; ?>>Author</option>
													<option value="1" <?php if($q->sec == 1) echo 'selected="selected"'; ?>>Contributor</option>
													<option value="0" <?php if($q->sec == 0) echo 'selected="selected"'; ?>>Subscriber</option>
												</select>
											</td>
											<td>
												<a href="#" id="delete-<?php echo $i; ?>" class="quickmenu-delete" style="color:red;padding-left:1em;">Delete</a>
											</td>
										</tr>
										<?
										$i++;
									}
									// New Record
									?>
									<tr>
										<td>
											<input type="text" name="name-<?php echo $i; ?>" value="" style="width:150px;" />
										</td>
										<td>
											<input type="text" name="url-<?php echo $i; ?>" value="" style="width:280px;" />
										</td>
										<td>
											<select name="security-<?php echo $i; ?>" id="security-<?php echo $i; ?>">
												<option value="10" selected="selected">Administrator</option>
												<option value="7">Editor</option>
												<option value="2">Author</option>
												<option value="1">Contributor</option>
												<option value="0">Subscriber</option>
											</select>
										</td>
									</tr>
								</table>
								<p class="hndle">This Plugin is using the <a href='http://codex.wordpress.org/Roles_and_Capabilities'>Roles_and_Capabilities</a> for Security Levels.</p>
								<p class="submit">
									<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
									<input type="submit" name="Clear" class="button-primary" value="Clear All Data" />
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
				</div><!-- #post-body-content -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.quickmenu-delete').bind('click', function(e) {
				e.preventDefault();
				var i = this.id.substr('delete-'.length);
				$('input', $('tr#tr-' + i)).val('');
				$('#quickmenu_form').submit();
			})
		});
		</script>
	</div><!-- .wrap -->
</div><!-- #wpbody-content -->
<?php
} // WordPressAdminQuickMenu_options()
?>