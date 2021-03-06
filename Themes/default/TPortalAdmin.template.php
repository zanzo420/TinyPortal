<?php
/**
 * @package TinyPortal
 * @version 2.0.0
 * @author IchBin - http://www.tinyportal.net
 * @founder Bloc
 * @license MPL 2.0
 *
 * The contents of this file are subject to the Mozilla Public License Version 2.0
 * (the "License"); you may not use this package except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Copyright (C) 2019 - The TinyPortal Team
 *
 */

// ** Sections ** (ordered like in the admin screen):
// TP Admin Main overview page
// General Settings page
// Frontpage Settings page
// Articles page
// Articles in category Page
// Article Categories page
// Add category Page
// Category List Page
// Edit Article Category Page
// Uncategorized articles Page
// Article Settings Page
// Article Submissions Page
// Illustrative article icons to be used in category layouts Page
// Panel Settings Page
// Block Settings Page
// Add Block Page
// Edit Block Page (including settings per block type)
// Block Access Page
// Menu Manager Page
// Menu Manager Page: single menus
// Add Menu / Add Menu item Page
// Edit menu item Page

function getElementById($id,$url){

$html = new DOMDocument();
$html->loadHtmlFile($url); //Pull the contents at a URL, or file name

$xpath = new DOMXPath($html); // So we can use XPath...

return($xpath->query("//*[@id='$id']")->item(0)); // Return the first item in element matching our id.

}

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<div id="tpadmin" class="tborder">
        <div class="title_bar">
            <h3 class="titlebg">'.$txt['tp-tpadmin'].'</h3>
        </div>
		<div style="padding-top: 5px;">';

	$go = isset($context['TPortal']['subaction']) ? 'template_' . $context['TPortal']['subaction'] : '';

	if($go == 'template_blocks' && isset($_GET['latest'])) {
		$go = 'template_latestblocks';
		$param = 'html';
	}
	elseif($go == 'template_credits') {
		$go = 'template_tpcredits';
		$param = '';
	}
	elseif($go == 'template_categories' && !empty($_GET['cu']) && is_numeric($_GET['cu'])) {
		$go = 'template_editcategory';
		$param = '';
	}
	elseif($go == 'template_blocks' && isset($_GET['overview'])) {
		$go = 'template_blockoverview';
		$param = '';
	}
	elseif($go == 'template_blocks' && isset($_GET['addblock'])) {
		$go = 'template_addblock';
		$param = '';
	}
	else {
		$param = '';
    }

	call_user_func($go, $param);

	echo '
		</div><p class="clearthefloat"></p>
<script>
$(document).ready( function() {
var $clickme = $(".clickme"),
    $box = $(".box");

$box.hide();

$clickme.click( function(e) {
    $(this).text(($(this).text() === "'.$txt['tp-hide'].'" ? "'.$txt['tp-more'].'" : "'.$txt['tp-hide'].'")).next(".box").slideToggle();
    e.preventDefault();
});
});
</script>
	</div>';
}

// TP Admin Main overview page
function template_overview()
{
	global $context, $settings, $txt, $boardurl;

	echo '
	<div id="tp_overview" class="windowbg">';

	if(is_array($context['admin_tabs']) && count($context['admin_tabs']) > 0 ) {
		echo '<ul>';
		foreach($context['admin_tabs'] as $ad => $tab) {
			$tabs = array();
			foreach($tab as $t => $tb) {
				echo '<li><a href="' . $tb['href'] . '"><img style="margin-bottom: 8px;" src="' . $settings['tp_images_url'] . '/TPov_' . strtolower($t) . '.png" alt="TPov_' . strtolower($t) . '" /><br><b>'.$tb['title'].'</b></a></li>';
            }
		}
		echo '</ul>';
	}
	echo '</div>';
}

// General Settings page
function template_settings()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $smcFunc;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="settings">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-generalsettings'] . '</h3></div>
		<div id="settings" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helpsettings'] , '</div><div></div>
				<div class="windowbg noup">
					<div class="formtable padding-div">
						<!-- START non responsive themes form -->
							<div>
						       <div class="font-strong">'.$txt['tp-formres'].'</div>';
						       $tm=explode(",",$context['TPortal']['resp']);
						   echo '<input name="tp_resp" id="tp_resp" type="checkbox" value="0"><label for="tp_resp">'.$txt['tp-deselectthemes'].'</label><br><br> ';
							foreach($context['TPallthem'] as $them) {
					              if(TP_SMF21) {
									echo '
										  <img class="theme_icon" alt="*" src="'.$them['path'].'/thumbnail.png" />
										  <input name="tp_resp'.$them['id'].'" id="tp_resp'.$them['id'].'" type="checkbox" value="'.$them['id'].'" ';
										}
								  else {
									echo '
										  <img class="theme_icon" alt="*" src="'.$them['path'].'/thumbnail.gif" />
										  <input name="tp_resp'.$them['id'].'" id="tp_resp'.$them['id'].'" type="checkbox" value="'.$them['id'].'" ';
										}
					              if(in_array($them['id'],$tm)) {
					                echo ' checked="checked" ';
					              }
					              echo '><label for="tp_resp'.$them['id'].'">'.$them['name'].'</label><br>';
						       }
						       echo'<br><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'">
					        </div>
						<!-- END non responsive themes form -->
							<br><hr>

				<dl class="settings">
					<dt>
						<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-frontpagetitle2'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_frontpage_title">', $txt['tp-frontpagetitle'], '</label>
					</dt>
					<dd>
						<input size="50" name="tp_frontpage_title" id="tp_frontpage_title"type="text" value="' , !empty($context['TPortal']['frontpage_title']) ? $context['TPortal']['frontpage_title'] : '' , '">
					</dd>
					<dt>
						', $txt['tp-redirectforum'], '
					</dt>
					<dd>
						<input name="tp_redirectforum" id="tp_redirectforum1" type="radio" value="1" ' , $context['TPortal']['redirectforum']=='1' ? 'checked' : '' , '><label for="tp_redirectforum1"> '.$txt['tp-redirectforum1'].'</label>
					</dd>
					<dd>
						<input name="tp_redirectforum" id="tp_redirectforum2" type="radio" value="0" ' , $context['TPortal']['redirectforum']=='0' ? 'checked' : '' , '><label for="tp_redirectforum2"> '.$txt['tp-redirectforum2'].'</label>
					</dd>
					<dt>
						<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-hideadminmenudesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_hideadminmenu">', $txt['tp-hideadminmenu'], '</label>
					</dt>
					<dd>
						<input id="tp_hideadminmenu" name="tp_hideadminmenu" type="checkbox" value="1" ' , $context['TPortal']['hideadminmenu']=='1' ? 'checked' : '' , '>
					</dd>
				</dl>
					<hr>
				<dl class="settings">
					<dt>
						<label for="tp_useroundframepanels">', $txt['tp-useroundframepanels'], '</label>
					</dt>
					<dd>
						<input id="tp_useroundframepanels" name="tp_useroundframepanels" type="checkbox" value="1" ' , $context['TPortal']['useroundframepanels']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_showcollapse">', $txt['tp-hidecollapse'], '</label>
					</dt>
					<dd>
						<input id="tp_showcollapse" name="tp_showcollapse" type="checkbox" value="1" ' , $context['TPortal']['showcollapse']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_blocks_edithide">', $txt['tp-hideediticon'], '</label>
					</dt>
					<dd>
						<input id="tp_blocks_edithide" name="tp_blocks_edithide" type="checkbox" value="1" ' , $context['TPortal']['blocks_edithide']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_uselangoption">', $txt['tp-uselangoption'], '</label>
					</dt>
					<dd>
						<input id="tp_uselangoption" name="tp_uselangoption" type="checkbox" value="1" ' , $context['TPortal']['uselangoption']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-use_groupcolordesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_use_groupcolor">', $txt['tp-use_groupcolor'], '</label>
					</dt>
					<dd>
						<input id="tp_use_groupcolor" name="tp_use_groupcolor" type="checkbox" value="1" ' , $context['TPortal']['use_groupcolor']=='1' ? 'checked' : '' , '>
					</dd>
				</dl>
					<hr>
				<dl class="settings">
					<dt>
						<label for="tp_maxstars">', $txt['tp-maxrating'], '</label>
					</dt>
					<dd>
						<input name="tp_maxstars" id="tp_maxstars" size="4" type="text" value="'.$context['TPortal']['maxstars'].'">
					</dd>
					<dt>
						<label for="tp_showstars">', $txt['tp-stars'], '</label>
					</dt>
					<dd>
						<input id="tp_showstars" name="tp_showstars" type="checkbox" value="1" ' , $context['TPortal']['showstars']=='1' ? 'checked' : '' , '>
					</dd>
				</dl>
					<hr>
				<dl class="settings">
					<dt>
						<label for="tp_oldsidebar">', $txt['tp-useoldsidebar'], '</label>
					</dt>
					<dd>
						<input id="tp_oldsidebar" name="tp_oldsidebar" type="checkbox" value="1" ' , $context['TPortal']['oldsidebar']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_admin_showblocks">', $txt['tp-admin_showblocks'], '</label>
					</dt>
					<dd>
						<input id="tp_admin_showblocks" name="tp_admin_showblocks" type="checkbox" value="1" ' , $context['TPortal']['admin_showblocks']=='1' ? 'checked' : '' , '>
					</dd>
					<dt>
						<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-imageproxycheckdesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_imageproxycheck">', $txt['tp-imageproxycheck'], '</label>
					</dt>
					<dd>
						<input id="tp_imageproxycheck" name="tp_imageproxycheck" type="checkbox" value="1" ' , $context['TPortal']['imageproxycheck'] == '1' ? 'checked' : '' , '>
					</dd>';
                    db_extend('extra');
                    if(version_compare($smcFunc['db_get_version'](), '5.6', '>=')) {
                        echo '
                        <dt>
                            <a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-fulltextsearchdesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_fulltextsearch">', $txt['tp-fulltextsearch'], '</label>
                        </dt>
                        <dd>
                            <input id="tp_fulltextsearch" name="tp_fulltextsearch" type="checkbox" value="1" ' , $context['TPortal']['fulltextsearch']=='1' ? 'checked' : '' , '>
                        </dd>';
                    }
					echo '
					<dt>
						<a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-disabletemplateevaldesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_disable_template_eval">', $txt['tp-disabletemplateeval'], '</label>
					</dt>
					<dd>
                        <input id="tp_disable_template_eval" name="tp_disable_template_eval" type="checkbox" value="1" ' , $context['TPortal']['disable_template_eval']=='1' ? 'checked' : '' , '>
					</dd>
                    <dt>
						<a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-imageuploadpathdesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_image_upload_path">', $txt['tp-imageuploadpath'], '</label>
					</dt>
					<dd>
						<input size="50" name="tp_image_upload_path" id="tp_image_upload_path" type="text" value="' , !empty($context['TPortal']['image_upload_path']) ? $context['TPortal']['image_upload_path'] : '' , '">
					</dd>
                    <dt>
						<a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-downloaduploadpathdesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_download_upload_path">', $txt['tp-downloaduploadpath'], '</label>
					</dt>
					<dd>
						<input size="50" name="tp_download_upload_path" id="tp_download_upload_path" type="text" value="' , !empty($context['TPortal']['download_upload_path']) ? $context['TPortal']['download_upload_path'] : '' , '">
					</dd>
                    <dt>
						<a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-blockcodeuploadpathdesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_blockcode_upload_path">', $txt['tp-blockcodeuploadpath'], '</label>
					</dt>
					<dd>
						<input size="50" name="tp_blockcode_upload_path" id="tp_blockcode_upload_path" type="text" value="' , !empty($context['TPortal']['blockcode_upload_path']) ? $context['TPortal']['blockcode_upload_path'] : '' , '">
					</dd>
                    <dt>
						<a href="', $scripturl, '?action=helpadmin;help=', $txt['tp-copyrightremovaldesc'], '" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_copyrightremoval">', $txt['tp-copyrightremoval'], '</label>
					</dt>
					<dd>
						<input size="50" name="tp_copyrightremoval" id="tp_copyrightremoval" type="text" value="' , !empty($context['TPortal']['copyrightremoval']) ? $context['TPortal']['copyrightremoval'] : '' , '">
					</dd>
				</dl>
					<div class="padding-div;"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
				</div>
			</div>
		</div>
	</form>';
}

// Frontpage Settings page
function template_frontpage()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $smcFunc;

		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="frontpage">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-frontpage_settings'] . '</h3></div>
		<div id="frontpage-settings" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helpfrontpage'] , '</div><div></div>
			<div class="windowbg noup">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt>
							', $txt['tp-whattoshow'], '
						</dt>
						<dd>
							<input name="tp_front_type" id="tp_front_type1" type="radio" value="forum_selected" ' , $context['TPortal']['front_type']=='forum_selected' ? 'checked' : '' , '><label for="tp_front_type1"> '.$txt['tp-selectedforum'].'</label><br>
							<input name="tp_front_type" id="tp_front_type2" type="radio" value="forum_selected_articles" ' , $context['TPortal']['front_type']=='forum_selected_articles' ? 'checked' : '' , '><label for="tp_front_type2"> '.$txt['tp-selectbothforum'].'</label><br>
							<input name="tp_front_type" id="tp_front_type3" type="radio" value="forum_only" ' , $context['TPortal']['front_type']=='forum_only' ? 'checked' : '' , '><label for="tp_front_type3"> '.$txt['tp-onlyforum'].'</label><br>
							<input name="tp_front_type" id="tp_front_type4" type="radio" value="forum_articles" ' , $context['TPortal']['front_type']=='forum_articles' ? 'checked' : '' , '><label for="tp_front_type4"> '.$txt['tp-bothforum'].'</label><br>
							<input name="tp_front_type" id="tp_front_type5" type="radio" value="articles_only" ' , $context['TPortal']['front_type']=='articles_only' ? 'checked' : '' , '><label for="tp_front_type5"> '.$txt['tp-onlyarticles'].'</label><br>
							<input name="tp_front_type" id="tp_front_type6" type="radio" value="single_page"  ' , $context['TPortal']['front_type']=='single_page' ? 'checked' : '' , '><label for="tp_front_type6"> '.$txt['tp-singlepage'].'</label><br>
							<input name="tp_front_type" id="tp_front_type7" type="radio" value="frontblock"  ' , $context['TPortal']['front_type']=='frontblock' ? 'checked' : '' , '><label for="tp_front_type7"> '.$txt['tp-frontblocks'].'</label><br>
							<input name="tp_front_type" id="tp_front_type8" type="radio" value="boardindex"  ' , $context['TPortal']['front_type']=='boardindex' ? 'checked' : '' , '><label for="tp_front_type8"> '.$txt['tp-boardindex'].'</label><br>
							<input name="tp_front_type" id="tp_front_type9" type="radio" value="module"  ' , $context['TPortal']['front_type']=='module' ? 'checked' : '' , '><label for="tp_front_type9"> '.$txt['tp-frontmodule'].'</label><br>
							<hr />
							<div style="padding-left: 2em;"></div>';
			echo '      <br></dd>
						<dt>
							', $txt['tp-frontblockoption'], '
						</dt>
						<dd>
							<input name="tp_frontblock_type" id="tp_frontblock_type1" type="radio" value="single"  ' , $context['TPortal']['frontblock_type']=='single' ? 'checked' : '' , '><label for="tp_frontblock_type1"> '.$txt['tp-frontblocksingle'].'</label><br>
							<input name="tp_frontblock_type" id="tp_frontblock_type2" type="radio" value="first"  ' , $context['TPortal']['frontblock_type']=='first' ? 'checked' : '' , '><label for="tp_frontblock_type2"> '.$txt['tp-frontblockfirst'].'</label><br>
							<input name="tp_frontblock_type" id="tp_frontblock_type3" type="radio" value="last"  ' , $context['TPortal']['frontblock_type']=='last' ? 'checked' : '' , '><label for="tp_frontblock_type3"> '.$txt['tp-frontblocklast'].'</label><br><br>
						</dd>
						<dt>
							<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-frontpageoptionsdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp-frontpageoptions">',$txt['tp-frontpageoptions'],'</label>
						</dt>
						<dd>
							<input name="tp_frontpage_visual_left" id="tp_frontpage_visual_left" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['left']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_left"> ',$txt['tp-displayleftpanel'],'</label><br>
							<input name="tp_frontpage_visual_right" id="tp_frontpage_visual_right" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['right']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_right"> ',$txt['tp-displayrightpanel'],'</label><br>
							<input name="tp_frontpage_visual_top" id="tp_frontpage_visual_top" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['top']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_top"> ',$txt['tp-displaytoppanel'],'</label><br>
							<input name="tp_frontpage_visual_center" id="tp_frontpage_visual_center" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['center']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_center"> ',$txt['tp-displaycenterpanel'],'</label><br>
							<input name="tp_frontpage_visual_lower" id="tp_frontpage_visual_lower" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['lower']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_lower"> ',$txt['tp-displaylowerpanel'],'</label><br>
							<input name="tp_frontpage_visual_bottom" id="tp_frontpage_visual_bottom" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['bottom']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_bottom"> ',$txt['tp-displaybottompanel'],'</label><br>
							<input name="tp_frontpage_visual_header" id="tp_frontpage_visual_header" type="checkbox" value="1" ' , $context['TPortal']['frontpage_visualopts_admin']['header']>0 ? 'checked' : '' , '><label for="tp_frontpage_visual_header"> ',$txt['tp-displaynews'],'</label><br><br>
						</dd>

					</dl>
					<hr>
					<div><strong>', $txt['tp-frontpage_layout'], '</strong></div>
					<div>
						<div class="tpartlayoutfp"><input name="tp_frontpage_layout" id="tp_frontpage_layout1" type="radio" value="1" ' ,
						$context['TPortal']['frontpage_layout']<2 ? 'checked' : '' , '><label for="tp_frontpage_layout1"> A ' ,
						$context['TPortal']['frontpage_layout']<2 ? '' : '' , '
								<br><img style="margin-top:5px" src="' .$settings['tp_images_url']. '/edit_art_cat_a.png"/></label>
						</div>
						<div class="tpartlayoutfp"><input name="tp_frontpage_layout" id="tp_frontpage_layout2" type="radio" value="2" ' ,
						$context['TPortal']['frontpage_layout']==2 ? 'checked' : '' , '><label for="tp_frontpage_layout2"> B ' ,
						$context['TPortal']['frontpage_layout']==2 ? '' : '' , '
							<br><img style="margin-top:5px" src="' .$settings['tp_images_url']. '/edit_art_cat_b.png"/></label>
						</div>
						<div class="tpartlayoutfp"><input name="tp_frontpage_layout" id="tp_frontpage_layout3" type="radio" value="3" ' ,
						$context['TPortal']['frontpage_layout']==3 ? 'checked' : '' , '><label for="tp_frontpage_layout3"> C ' ,
						$context['TPortal']['frontpage_layout']==3 ? '' : '' , '
							<br><img style="margin-top:5px" src="' .$settings['tp_images_url']. '/edit_art_cat_c.png"/></label>
						</div>
						<div class="tpartlayoutfp"><input name="tp_frontpage_layout" id="tp_frontpage_layout4" type="radio" value="4" ' ,
						$context['TPortal']['frontpage_layout']==4 ? 'checked' : '' , '><label for="tp_frontpage_layout4"> D ' ,
						$context['TPortal']['frontpage_layout']==4 ? '' : '' , '
							<br><img style="margin-top:5px" src="' .$settings['tp_images_url']. '/edit_art_cat_d.png"/></label>
						</div>
						<br style="clear: both;" /><br>
					</div>
					<div>
						<strong>', $txt['tp-articlelayouts'], '</strong>
					</div>
					<div>';	foreach($context['TPortal']['admin_layoutboxes'] as $box)
								echo '
									<div class="tpartlayouttype">
										<input type="radio" name="tp_frontpage_catlayout" id="tp_frontpage_catlayout'.$box['value'].'" value="'.$box['value'].'"' , $context['TPortal']['frontpage_catlayout']==$box['value'] ? ' checked="checked"' : '' , '><label for="tp_frontpage_catlayout'.$box['value'].'">
										'.$box['label'].'<br><img style="margin: 4px 4px 4px 10px;" src="' , $settings['tp_images_url'] , '/TPcatlayout'.$box['value'].'.png" alt="tplayout'.$box['value'].'" /></label>
									</div>';

							if(empty($context['TPortal']['frontpage_template']))
								$context['TPortal']['frontpage_template'] = '
					<span class="upperframe"><span></span></span>
					<div class="roundframe">
						<div class="title_bar">
							<h3 class="titlebg"><span class="left"></span>{article_shortdate} {article_title} </h3>
						</div>
						<div style="padding: 0; overflow: hidden;">
							{article_avatar}
							<div class="article_info">
								{article_category}
								{article_author}
								{article_date}
								{article_views}
								{article_rating}
								{article_options}
							</div>
							<div class="article_padding">{article_text}</div>
							{article_bookmark}
							{article_boardnews}
							{article_moreauthor}
							{article_morelinks}
						</div>
					</div>
					<span class="lowerframe" style="margin-bottom: 5px;"></span>';
							echo '<br style="clear: both;" />
				</div>
				<div>
					<h4><a href="', $scripturl, '?action=helpadmin;help=',$txt['reset_custom_template_layoutdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>', $txt['reset_custom_template_layout'] ,'</h4>
					<textarea id="tp_customlayout" name="tp_frontpage_template">' . $context['TPortal']['frontpage_template'] . '</textarea><br><br>
				</div>
				<hr>
					<dl class="settings">
						<dt>
							<label for="tp_frontpage_limit">', $txt['tp-numberofposts'], '</label>
						</dt>
						<dd>
						  <input name="tp_frontpage_limit" id="tp_frontpage_limit" size="5" maxlength="5" type="text" value="' ,$context['TPortal']['frontpage_limit'], '"><br><br>
						</dd>
						<dt>
							<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-sortingoptionsdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_frontpage_usorting">',$txt['tp-sortingoptions'],'</label>
						</dt>
						<dd>
							<select name="tp_frontpage_usorting" id="tp_frontpage_usorting">
								<option value="date"' , $context['TPortal']['frontpage_visualopts_admin']['sort']=='date' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions1'] , '</option>
								<option value="author_id"' , $context['TPortal']['frontpage_visualopts_admin']['sort']=='author_id' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions2'] , '</option>
								<option value="parse"' , $context['TPortal']['frontpage_visualopts_admin']['sort']=='parse' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions3'] , '</option>
								<option value="id"' , $context['TPortal']['frontpage_visualopts_admin']['sort']=='id' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions4'] , '</option>
							</select>&nbsp;
							<select name="tp_frontpage_sorting_order">
								<option value="desc"' , $context['TPortal']['frontpage_visualopts_admin']['sortorder']=='desc' ? ' selected="selected"' : '' , '>' , $txt['tp-sortdirection1'] , '</option>
								<option value="asc"' , $context['TPortal']['frontpage_visualopts_admin']['sortorder']=='asc' ? ' selected="selected"' : '' , '>' , $txt['tp-sortdirection2'] , '</option>
							</select>
						</dd>
					</dl>
					<hr>
					<dl class="settings">
						<dt>
							<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-allowguestsdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>', $txt['tp-allowguests'], '
						</dt>
						<dd>
							  <input name="tp_allow_guestnews" type="radio" value="1" ' , $context['TPortal']['allow_guestnews']==1 ? 'checked' : '' , '> '.$txt['tp-yes'].'
							  <input name="tp_allow_guestnews" type="radio" value="0" ' , $context['TPortal']['allow_guestnews']==0 ? 'checked' : '' , '> '.$txt['tp-no'].'
						</dd>
						<dt>
							<label for="field_name">', $txt['tp-showforumposts'], '</label>
						</dt>
						<dd>';
		echo '
							<select size="5" name="tp_ssiboard" multiple="multiple">';
            if(is_countable($context['TPortal']['boards'])) {
                $tn = count($context['TPortal']['boards']);
            }
            else {
                $tn = 0;
            }

            for($n=0 ; $n<$tn; $n++) {
                echo '
								<option value="'.$context['TPortal']['boards'][$n]['id'].'"' , is_array($context['TPortal']['SSI_boards']) && in_array($context['TPortal']['boards'][$n]['id'] , $context['TPortal']['SSI_boards']) ? ' selected="selected"' : '' , '>'.$context['TPortal']['boards'][$n]['name'].'</option>';
            }
		
		echo '
							</select><br><br>
						</dd>
						<dt>
							<label for="tp_frontpage_limit_len">', $txt['tp-lengthofposts'], '</label>
						</dt>
						<dd>
						  <input name="tp_frontpage_limit_len" id="tp_frontpage_limit_len" size="5" maxlength="5" type="text" value="' ,$context['TPortal']['frontpage_limit_len'], '"><br><br>
						</dd>
						<dt>
							<label for="tp_forumposts_avatar">', $txt['tp-forumposts_avatar'], '</label>
						</dt>
						<dd>	
							<input id="tp_forumposts_avatar" name="tp_forumposts_avatar" type="checkbox" value="1" ' , $context['TPortal']['forumposts_avatar']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_use_attachment">', $txt['tp-useattachment'], '</label>
						</dt>
						<dd>
							<input id="tp_use_attachment" name="tp_use_attachment" type="checkbox" value="1" ' , $context['TPortal']['use_attachment']=='1' ? 'checked' : '' , '>
						</dd>
					</dl>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Articles page
function template_articles()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post" enctype="multipart/form-data" onsubmit="syncTextarea();">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="articles">
		<div class="cat_bar"><h3 class="catbg">' , $txt['tp-articles'] , !empty($context['TPortal']['categoryNAME']) ? $txt['tp-incategory']. ' ' . $context['TPortal']['categoryNAME'].' ' : '' ,  '</h3></div>
		<div id="edit-articles" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helparticles'] , '</div><div></div>
			<div class="windowbg noup padding-div">';

	if(isset($context['TPortal']['cats']) && count($context['TPortal']['cats'])>0)
	{
		echo '
		<table class="table_grid tp_grid" style="width:100%">
		<thead>
			<tr class="title_bar titlebg2">
			<th scope="col" class="articles">
				<div>
					<div class="pos float-items" style="width:65%;"><strong>' , $txt['tp-name'] , '</strong></div>
					<div class="title-admin-area float-items tpcenter" style="width:15%;"><strong>' , $txt['tp-articles'] , '</strong></div>
					<div class="title-admin-area float-items tpcenter" style="width:20%;"><strong>' , $txt['tp-actions'] , '</strong></div>
					<p class="clearthefloat"></p>
				</div>
			</th>
			</tr>
		</thead>
		<tbody>';
		$alt=true;
		foreach($context['TPortal']['cats'] as $c => $cat)
		{
			if(in_array($cat['parent'],$context['TPortal']['basecats']))
			{
				echo '
			<tr class="windowbg">
			<td class="articles">
				<div>
					<div style="width:65%;" class="float-items">
					  <a href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$cat['id'].'">' , $cat['name'] , '</a>
					</div>
					<div style="width:15%;" class="float-items tpcenter">' , isset($context['TPortal']['cats_count'][$cat['id']]) ? $context['TPortal']['cats_count'][$cat['id']] : '0' , '</div>
					<div style="width:20%;" class="float-items tpcenter">
						<a href="' . $scripturl . '?cat=' . $cat['id'] . '"><img src="' . $settings['tp_images_url'] . '/TPfilter.png" alt="" /></a>
						<a href="' . $scripturl . '?action=tpadmin;sa=categories;cu=' . $cat['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '"><img src="' . $settings['tp_images_url'] . '/TPmodify.png" alt="" /></a>
					</div><p class="clearthefloat"></p>
				</div>';
				// check if we got children
				foreach($context['TPortal']['cats'] as $d => $subcat)
				{
					if($subcat['parent']==$cat['id'])
					{
						echo '
				<div>
					<div style="width:65%;" class="float-items">&nbsp;&nbsp;<img src="' . $settings['tp_images_url'] . '/TPtree_article.png" alt="" />
						<a href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$subcat['id'].'">' , $subcat['name'] , '</a>
					</div>
					<div style="width:15%;" class="float-items tpcenter">' , isset($context['TPortal']['cats_count'][$subcat['id']]) ? $context['TPortal']['cats_count'][$subcat['id']] : '0' , '</div>
					<div style="width:20%;" class="float-items tpcenter">&nbsp;</div>
					<p class="clearthefloat"></p>
				</div>';
					}
				}
	echo '
			</td>
			</tr>';
				$alt = !$alt;
			}
		}
	echo '
		</tbody>
	</table><br>';
	}
	// Articles in category Page
	if(isset($context['TPortal']['arts']))
	{
		echo '
	<table class="table_grid tp_grid" style="width:100%">
		<thead>
			<tr class="title_bar titlebg2">
			<th scope="col" class="articles">
				<div class="catbg3">
					<div style="width:7%;" class="pos float-items">' , $context['TPortal']['sort']=='parse' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-position'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-position'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=parse"><strong>' , $txt['tp-pos'] , '</strong></a></div>
					<div style="width:25%;" class="name float-items">' , $context['TPortal']['sort']=='subject' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-subject'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-subject'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=subject"><strong>' , $txt['tp-name'] , '</strong></a></div>
					<div style="width:10%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=author_id"><strong>' , $txt['tp-author'] , '</strong></a></div>
					<div style="width:20%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=date"><strong>' , $txt['tp-date'] , '</strong></a></div>
					<div style="width:25%;" class="title-admin-area float-items">
						' , $context['TPortal']['sort']=='off' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-active'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-active'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=off"><img src="' . $settings['tp_images_url'] . '/TPactive2.png" alt="" /></a>
						' , $context['TPortal']['sort']=='frontpage' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-frontpage'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-frontpage'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=frontpage"><img src="' . $settings['tp_images_url'] . '/TPfront.png" alt="*" /></a>
						' , $context['TPortal']['sort']=='sticky' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-sticky'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-sticky'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=sticky"><img src="' . $settings['tp_images_url'] . '/TPsticky1.png" alt="" /></a>
						' , $context['TPortal']['sort']=='locked' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-locked'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-locked'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=locked"><img src="' . $settings['tp_images_url'] . '/TPlock1.png" alt="" /></a>
					</div>
					<div style="width:13%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=type"><strong>' , $txt['tp-type'] , '</strong></a></div>
					<p class="clearthefloat"></p>
			 </div>
			</th>
			</tr>
		</thead>
		<tbody> ';

		foreach($context['TPortal']['arts'] as $a => $alink)
		{
			$alink['pos'] = $alink['pos']=='' ? 0 : $alink['pos'];
			$catty = $alink['category'];

			echo '
			<tr class="windowbg">
			<td class="articles">
				<div>
					<div style="width:7%;" class="adm-pos float-items">
						<a name="article'.$alink['id'].'"></a><input type="text" size="2" value="'.$alink['pos'].'" name="tp_article_pos'.$alink['id'].'" />
					</div>
					<div style="width:25%;" class="adm-name float-items">
						' , $alink['locked']==0 ? '<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article=' . $alink['id'] . '">' . $alink['subject'].'</a>' : '&nbsp;' . $alink['subject'] , '
					</div>
					<a href="" class="clickme">'.$txt['tp-more'].'</a>
					<div class="box" style="width:68%;float:left;">
						<div style="width:14.8%;" class="smalltext fullwidth-on-res-layout float-items">
							<div id="show-on-respnsive-layout"> ' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . 	'/TPsort_down.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=author_id"><strong>' , $txt['tp-author'] , '</strong></a>
							</div>
							<div id="size-on-respnsive-layout"><a href="' . $scripturl . '?action=profile;u=' , $alink['author_id'], '">'.$alink['author'] .'</a>
							</div>
						</div>
						<div style="width:29.8%;" class="smalltext fullwidth-on-res-layout float-items">
							<div id="show-on-respnsive-layout"> ' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=date"><strong>' , $txt['tp-date'] , '</strong></a>
							</div>
							<div id="size-on-respnsive-layout">' , timeformat($alink['date']) , '</div>
						</div>
						<div style="width:37.5%;" class="smalltext fullwidth-on-res-layout float-items">
							<div id="show-on-respnsive-layout" style="margin-top:0.5%;"><strong>'.$txt['tp-editarticleoptions2'].'</strong></div>
							<div id="size-on-respnsive-layout">
								<img style="cursor: pointer;" class="toggleActive" id="artActive' .$alink['id']. '" title="'.$txt['tp-activate'].'" src="' .$settings['tp_images_url']. '/TPactive' , $alink['off']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-activate'].'"  />
								<a href="',$scripturl, '?page=',$alink['id'],'"><img title="'.$txt['tp-preview'].'" src="' .$settings['tp_images_url']. '/TPfilter.png" alt="" /></a>
								' , $alink['locked']==0 ?
								'<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article='.$alink['id']. '"><img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm.png" alt="'.$txt['tp-edit'].'"  /></a>' : '<img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm2.png" alt="'.$txt['tp-islocked'].'"  />' , '
								<img style="cursor: pointer;" class="toggleFront" id="artFront' .$alink['id']. '" title="'.$txt['tp-setfrontpage'].'" src="' .$settings['tp_images_url']. '/TPfront' , $alink['frontpage']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-setfrontpage'].'"  />
								<img style="cursor: pointer;" class="toggleSticky" id="artSticky' .$alink['id']. '" title="'.$txt['tp-setsticky'].'" src="' .$settings['tp_images_url']. '/TPsticky' , $alink['sticky']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setsticky'].'"  />
								<img style="cursor: pointer;" class="toggleLock" id="artLock' .$alink['id']. '" title="'.$txt['tp-setlock'].'" src="' .$settings['tp_images_url']. '/TPlock' , $alink['locked']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setlock'].'"  />
								<img style="cursor: pointer;" class="toggleFeatured" id="artFeatured' .$alink['id']. '" title="'.$txt['tp-featured'].'" src="' .$settings['tp_images_url']. '/TPflag' , $alink['featured']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-featured'].'"  />
							</div>
						</div>
						<div style="width:7%;" class="smalltext fullwidth-on-res-layout float-items">
							<div id="show-on-respnsive-layout">
							' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=articles;cu='.$context['TPortal']['categoryID'].';sort=type"><strong>' , $txt['tp-type'] , '</strong></a>
							</div>
							<div style="text-transform:uppercase;">' , empty($alink['type']) ? 'html' : $alink['type'] , '</div>
						</div>
						<div style="width:6%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
							<div id="show-on-respnsive-layout"><strong>'.$txt['tp-delete'].'</strong></div>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';artdelete=' .$alink['id'] , !empty($_GET['cu']) ? ';cu=' . $_GET['cu'] : '' , '" onclick="javascript:return confirm(\''.$txt['tp-articleconfirmdelete'].'\')">
							<img title="'.$txt['tp-delete'].'" src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /></a>
						</div>
						<p class="clearthefloat"></p>
					</div>
					<p class="clearthefloat"></p>
				</div>
			</td>
			</tr>';
			}
	echo '
		</tbody>
	</table>';
			if( !empty($context['TPortal']['pageindex']))
				echo '
								<div class="middletext padding-div">
									'.$context['TPortal']['pageindex'].'
								</div>';
			echo '
							<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'">
						<input name="tpadmin_form_category" type="hidden" value="' . $catty . '"></div>';
	}
	else
		echo '
				<div class="padding-div"></div>';

		echo '
		</div></div>
	</form>';
}

// Article Categories page
function template_categories()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post" enctype="multipart/form-data" onsubmit="syncTextarea();">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="categories">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-tabs5'] . '</h3></div>
		<div id="edit-category" class="admintable admin-area">
			<div class="windowbg noup padding-div">
				<table class="table_grid tp_grid" style="width:100%">
				<thead>
					<tr class="title_bar titlebg2">
					<th scope="col">
						<div>
							<div class="float-items" style="width:120px;"><strong>' , $txt['tp-actions'] , '</strong></div>
							<div class="float-items" style="max-width:76%;"><strong>' , $txt['tp-name'] , '</strong></div>
							<p class="clearthefloat"></p>
						</div>
					</th>
					</tr>
				</thead>
				<tbody>';

		if(isset($context['TPortal']['editcats']) && count($context['TPortal']['editcats'])>0)
		{
			$alt=true;
			foreach($context['TPortal']['editcats'] as $c => $cat)
			{
				echo '
					<tr class="windowbg">
					<td class="articles">
						<div>';

				echo '
							<div class="float-items" style="width:120px;">
								<a href="' . $scripturl . '?cat=' . $cat['id'] . '"><img src="' . $settings['tp_images_url'] . '/TPfilter.png" alt="" /></a>
								<a href="' . $scripturl . '?action=tpadmin;sa=addcategory;child;cu=' . $cat['id'] . '" title="' . $txt['tp-addsubcategory'] . '"><img src="' . $settings['tp_images_url'] . '/TPadd.png" alt="" /></a>
								<a href="' . $scripturl . '?action=tpadmin;sa=addcategory;copy;cu=' . $cat['id'] . '" title="' . $txt['tp-copycategory'] . '"><img src="' . $settings['tp_images_url'] . '/TPcopy.png" alt="" /></a>
								&nbsp;&nbsp;<a href="' . $scripturl . '?action=tpadmin;catdelete='.$cat['id'].';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="javascript:return confirm(\''.$txt['tp-confirmcat1'].'  \n'.$txt['tp-confirmcat2'].'\')" title="' . $txt['tp-delete'] . '"><img src="' . $settings['tp_images_url'] . '/TPdelete2.png" alt="" /></a>
							</div>
							<div class="float-items' , '" style="max-width:76%;">
								' , str_repeat("-",$cat['indent']) , '
								<a href="' . $scripturl . '?action=tpadmin;sa=categories;cu='.$cat['id'].'">' , $cat['name'] , '</a>
								' , isset($context['TPortal']['cats_count'][$cat['id']]) ? '<a href="' . $scripturl. '?action=tpadmin;sa=articles;cu='.$cat['id'].'">(' . ($context['TPortal']['cats_count'][$cat['id']]>1 ? $txt['tp-total'] : $txt['tp-article']) . ' '.$context['TPortal']['cats_count'][$cat['id']].')</a>' : '' , '
							</div>
							<p class="clearthefloat"></p>
						</div>
					</td>
					</tr>';
				$alt = !$alt;
			}
		}
				echo '
				</tbody>
				</table>';
		echo '
				<br>
				<div class="padding-div;">
					<input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'">
				</div>
			</div>
		</div>
	</form>';
}

// Add category Page
function template_addcategory()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	if(isset($_GET['cu']) && is_numeric($_GET['cu']))
		$currcat = $_GET['cu'];

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="addcategory">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-addcategory'] . '</h3></div>
		<div id="new-category" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helpaddcategory'] , '</div><div></div>
			<div class="windowbg noup ">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt>
							<b><label for="tp_cat_name">'.$txt['tp-name'].'</label>:</b>
						</dt>
						<dd>
							<input name="tp_cat_name" id="tp_cat_name" type="text" value=""><br><br>
						</dd>';
			// set up category to be sub of
			echo '
						<dt>
							<b><label for="tp_cat_parent">'.$txt['tp-subcatof'].'</label></b>
						</dt>
						<dd>
							<select size="1" name="tp_cat_parent" id="tp_cat_parent">
								<option value="0">'.$txt['tp-none2'].'</option>';
			if(isset($context['TPortal']['editcats'])){
				foreach($context['TPortal']['editcats'] as $s => $submg ){
						echo '
							<option value="'.$submg['id'].'"' , isset($currcat) && $submg['id']==$currcat ? ' selected="selected"' : '' , '>'. str_repeat("-",$submg['indent']) .' '.$submg['name'].'</option>';
				}
			}
			echo '
							</select><input name="newcategory" type="hidden" value="1">
						<dd>
					</dl>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Category List Page
function template_clist()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

		echo '
	<form  accept-charset="', $context['character_set'], '" name="TPadmin" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="clist">
		<div class="cat_bar"><h3 class="catbg">TinyPortal - '.$txt['tp-generalsettings'].'</h3></div>
		<div id="clist" class="admintable admin-area">
			<div class="windowbg noup">
				<div class="padding-div"><strong>'.$txt['tp-clist'].'</strong></div>
				<div class="padding-div">';

		$clist = explode(',',$context['TPortal']['cat_list']);
		echo '
					<input name="tp_clist-1" type="hidden" value="-1">';
		foreach($context['TPortal']['catnames'] as $ta => $val){
			echo '
					<input name="tp_clist'.$ta.'" type="checkbox" value="'.$ta.'"';
			if(in_array($ta, $clist))
				echo ' checked';
			echo '>  '.html_entity_decode($val).'<br>';
		}
		echo '
					<br><input type="checkbox" onclick="invertAll(this, this.form, \'tp_clist\');" />  '.$txt['tp-checkall'].'
				</div><br>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="send"></div>
			</div>
		</div>
	</form>';
}

// Edit Article Category Page
function template_editcategory()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $smcFunc;

		$mg = $context['TPortal']['editcategory'];
		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="editcategory">
		<input name="tpadmin_form_id" type="hidden" value="' . $mg['id'] . '">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-editcategory'] . '</h3></div>
		<div id="edit-art-category" class="admintable admin-area">
			<div class="windowbg noup">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt>
							<label for="tp_category_value1">', $txt['tp-name'], '</label>
						</dt>
						<dd>
							<input size="60" name="tp_category_value1" id="tp_category_value1" type="text" value="' ,html_entity_decode($mg['value1']), '">
						<dd>
						<dt>
							<label for="tp_category_value2">', $txt['tp-parent'], '</label>
						</dt>
						<dd>
							<select name="tp_category_value2" id="tp_category_value2" style="max-width:100%;">
								<option value="0"' , $mg['value2']==0 || $mg['value2']=='9999' ? ' selected="selected"' : '' , '>' , $txt['tp-noname'] , '</option>';
				foreach($context['TPortal']['editcats'] as $b => $parent) {
					if($parent['id']!= $mg['id'])
						echo '
								<option value="' . $parent['id'] . '"' , $parent['id']==$mg['value2'] ? ' selected="selected"' : '' , '>' , str_repeat("-",$parent['indent']) ,' ' , html_entity_decode($parent['name']) , '</option>';
				}
					echo '
							</select>
						<dd>
						<dt>
							<label for="tp_category_sort">', $txt['tp-sorting'], '</label>
						</dt>
						<dd>
							<select name="tp_category_sort" id="tp_category_sort">
								<option value="date"' , isset($mg['sort']) && $mg['sort']=='date' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions1'] , '</option>
								<option value="author_id"' , isset($mg['sort']) && $mg['sort']=='author_id' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions2'] , '</option>
								<option value="parse"' , isset($mg['sort']) && $mg['sort']=='parse' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions3'] , '</option>
								<option value="id"' , isset($mg['sort']) && $mg['sort']=='id' ? ' selected="selected"' : '' , '>' , $txt['tp-sortoptions4'] , '</option>
							</select>
							<select name="tp_category_sortorder">
								<option value="desc"' , isset($mg['sortorder']) && $mg['sortorder']=='desc' ? ' selected="selected"' : '' , '>' , $txt['tp-sortdirection1'] , '</option>
								<option value="asc"' , isset($mg['sortorder']) && $mg['sortorder']=='asc' ? ' selected="selected"' : '' , '>' , $txt['tp-sortdirection2'] , '</option>
							</select>
						<dd>
						<dt>
							<label for="tp_category_articlecount">', $txt['tp-articlecount'], '</label>
						</dt>
						<dd>
							<input size="6" name="tp_category_articlecount" id="tp_category_articlecount" type="text" value="' , empty($mg['articlecount']) ? $context['TPortal']['frontpage_limit'] : $mg['articlecount']  , '">
						<dd>
						<dt>
							<label for="tp_category_value8">', $txt['tp-shortname'], '</label>
						</dt>
						<dd>
							<input size="20" name="tp_category_value8" id="tp_category_value8" type="text" value="' , isset($mg['value8']) ? $mg['value8'] : '' , '">
						</dd>
					</dl>
					<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
					<hr>
					<div>
						<div><strong>', $txt['tp-catlayouts'], '</strong></div>

						<div class="tpartlayoutfp"><input name="tp_category_layout" id="tp_category_layout1" type="radio" value="1" ' ,
							$mg['layout']==1 ? 'checked' : '' , '> A ' ,
							$mg['layout']==1 ? '' : '' , '
							<div class="tborder" style="margin-top: 5px;">
								 <label for="tp_category_layout1"><img src="' .$settings['tp_images_url']. '/edit_art_cat_a.png"/></label>
							</div>
						</div>
						<div class="tpartlayoutfp"><input name="tp_category_layout" id="tp_category_layout2" type="radio" value="2" ' ,
							$mg['layout']==2 ? 'checked' : '' , '> B ' ,
							$mg['layout']==2 ? '' : '' , '
							<div class="tborder" style="margin-top: 5px;">
								<label for="tp_category_layout2"><img src="' .$settings['tp_images_url']. '/edit_art_cat_b.png"/></label>
							</div>
						</div>
						<div class="tpartlayoutfp"><input name="tp_category_layout" id="tp_category_layout3" type="radio" value="3" ' ,
							$mg['layout']==3 ? 'checked' : '' , '> C ' ,
							$mg['layout']==3 ? '' : '' , '
							<div class="tborder" style="margin-top: 5px;">
								<label for="tp_category_layout3"><img src="' .$settings['tp_images_url']. '/edit_art_cat_c.png"/></label>
							</div>
						</div>
						<div class="tpartlayoutfp"><input name="tp_category_layout" id="tp_category_layout4" type="radio" value="4" ' ,
							$mg['layout']==4 ? 'checked' : '' , '> D ' ,
							$mg['layout']==4 ? '' : '' , '
							<div class="tborder" style="margin-top: 5px;">
								<label for="tp_category_layout4"><img src="' .$settings['tp_images_url']. '/edit_art_cat_d.png"/></label>
							</div>
						</div>
						<p class="clearthefloat"></p><br>
					</div>
					<div>
						<div><strong>'.$txt['tp-articlelayouts']. ':</strong></div>
						<div>';
			foreach($context['TPortal']['admin_layoutboxes'] as $box)
				echo '
							<div class="tpartlayouttype">
								<input type="radio" name="tp_category_catlayout" id="tp_category_catlayout'.$box['value'].'" value="'.$box['value'].'"' , $mg['catlayout']==$box['value'] ? ' checked="checked"' : '' , '>
								<label for="tp_category_catlayout'.$box['value'].'">'.$box['label'].'<br><img style="margin: 4px 4px 4px 10px;" src="' , $settings['tp_images_url'] , '/TPcatlayout'.$box['value'].'.png" alt="tplayout'.$box['value'].'" /></label>
							</div>';
				if(empty($mg['value9']))
					$mg['value9'] = '
							<div class="tparticle">
								<div class="cat_bar">
									<h3 class="catbg"><span class="left"></span>{article_shortdate} {article_title} {article_category}</h3>
								</div>
								<div class="windowbg2">
									<span class="topslice"><span></span></span>
									<div class="content">
										{article_avatar}
										<div class="article_info">
											{article_author}
											{article_date}
											{article_views}
											{article_rating}
											{article_options}
										</div>
										<div class="article_padding">{article_text}</div>
										<div class="article_padding">{article_moreauthor}</div>
										<div class="article_padding">{article_bookmark}</div>
										<div class="article_padding">{article_morelinks}</div>
										<div class="article_padding">{article_comments}</div>
									</div>
									<span class="botslice"><span></span></span>
								</div>
							</div>';
				echo '	</div>
						<br style="clear: both;" />
						<h4><a href="', $scripturl, '?action=helpadmin;help=',$txt['reset_custom_template_layoutdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>', $txt['reset_custom_template_layout'] ,'</h4>
						<textarea id="tp_customlayout" name="tp_category_value9">' . $mg['value9'] . '</textarea><br><br>
					</div>
					<hr>
					<dl class="settings">
						<dt>
							', $txt['tp-showchilds'], '
						</dt>
						<dd>
							<input name="tp_category_showchild" type="radio" value="1"' , (isset($mg['showchild']) && $mg['showchild']==1) ? ' checked="checked"' : '' , '> ' , $txt['tp-yes'] , '
							<input name="tp_category_showchild" type="radio" value="0"' , ((isset($mg['showchild']) && $mg['showchild']==0) || !isset($mg['showchild'])) ? ' checked="checked"' : '' , '> ' , $txt['tp-no'] , '<br><br>
						<dd>
						<dt>
							<strong>', $txt['tp-allpanels'], '</strong>
						</dt>
						<dt>
							<label for="tp_category_leftpanel">', $txt['tp-displayleftpanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_leftpanel" id="tp_category_leftpanel" value="1"' , !empty($mg['leftpanel']) ? ' checked="checked"' : '' ,' />
						<dd>
						<dt>
							<label for="tp_category_rightpanel">', $txt['tp-displayrightpanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_rightpanel" id="tp_category_rightpanel" value="1"' , !empty($mg['rightpanel']) ? ' checked="checked"' : '' ,' />
						<dd>
						<dt>
							<label for="tp_category_toppanel">', $txt['tp-displaytoppanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_toppanel" id="tp_category_toppanel" value="1"' , !empty($mg['toppanel']) ? ' checked="checked"' : '' ,' />
						<dd>
						<dt>
							<label for="tp_category_bottompanel">', $txt['tp-displaybottompanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_bottompanel" id="tp_category_bottompanel" value="1"' , !empty($mg['bottompanel']) ? ' checked="checked"' : '' ,' />
						<dd>
						<dt>
							<label for="tp_category_centerpanel">', $txt['tp-displaycenterpanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_centerpanel" id="tp_category_centerpanel" value="1"' , !empty($mg['centerpanel']) ? ' checked="checked"' : '' ,' />
						<dd>
						<dt>
							<label for="tp_category_lowerpanel">', $txt['tp-displaylowerpanel'], '</label>
						</dt>
						<dd>
							<input type="checkbox" name="tp_category_lowerpanel" id="tp_category_lowerpanel" value="1"' , !empty($mg['lowerpanel']) ? ' checked="checked"' : '' ,' />
						<dd>
					</dl>
					<dl class="settings">
						<dt>
							<span class="font-strong">'.$txt['tp-allowedgroups']. '</span>
						</dt>
						<dd>
							<div class="tp_largelist2">';
			// loop through and set membergroups
			$tg=explode(',',$mg['value3']);
			foreach($context['TPmembergroups'] as $g) {
				if($g['posts']=='-1' && $g['id']!='1') {
					echo '<input name="tp_category_group_'.$g['id'].'" id="'.$g['name'].'" type="checkbox" value="'.$mg['id'].'"';
					if(in_array($g['id'],$tg))
						echo ' checked';
					echo '><label for="'.$g['name'].'"> '.$g['name'].' </label><br>';
				}
			}
			// if none is chosen, have a control value
				echo '
							</div><br>
							<input type="checkbox" id="tp_catgroup-2" onclick="invertAll(this, this.form, \'tp_category_group\');" /><label for="tp_catgroup-2"> '.$txt['tp-checkall'].'</label><input name="tp_catgroup-2" type="hidden" value="'.$mg['id'].'">
						</dd>
					</dl>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Uncategorized articles Page
function template_strays()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post" enctype="multipart/form-data" onsubmit="syncTextarea();">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="strays">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-uncategorised2'] . '</h3></div>
		<div id="uncategorized" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helpstrays'] , '</div><div></div>';
	if(isset($context['TPortal']['arts_nocat'])) {
		echo '
			<div class="windowbg noup padding-div">
				<div>
					<table class="table_grid tp_grid" style="width:100%">
					<thead>
						<tr class="title_bar titlebg2">
						<th scope="col">
							<div>
								<div style="width:7%;" class="pos float-items">' , $context['TPortal']['sort']=='parse' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-position'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-position'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=parse"><strong>' , $txt['tp-pos'] , '</strong></a></div>
								<div style="width:25%;" class="name float-items">' , $context['TPortal']['sort']=='subject' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-subject'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-subject'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=subject"><strong>' , $txt['tp-name'] , '</strong></a></div>
								<div style="width:10%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=author_id"><strong>' , $txt['tp-author'] , '</strong></a></div>
								<div style="width:20%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=date"><strong>' , $txt['tp-date'] , '</strong></a></div>
								<div style="width:25%;" class="title-admin-area float-items">
									' , $context['TPortal']['sort']=='off' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-active'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-active'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=off"><img src="' . $settings['tp_images_url'] . '/TPactive2.png" alt="" /></a>
									' , $context['TPortal']['sort']=='frontpage' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-frontpage'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-frontpage'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=frontpage"><img src="' . $settings['tp_images_url'] . '/TPfront.png" alt="*" /></a>
									' , $context['TPortal']['sort']=='sticky' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-sticky'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-sticky'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=sticky"><img src="' . $settings['tp_images_url'] . '/TPsticky1.png" alt="" /></a>
									' , $context['TPortal']['sort']=='locked' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-locked'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-locked'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=locked"><img src="' . $settings['tp_images_url'] . '/TPlock1.png" alt="" /></a>
								</div>
								<div style="width:13%;" class="title-admin-area float-items"> ' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=type"><strong>' , $txt['tp-type'] , '</strong></a></div>
								<p class="clearthefloat"></p>
							</div>
						</th>
						</tr>
					</thead>
					<tbody>';

		foreach($context['TPortal']['arts_nocat'] as $a => $alink) {
			$alink['pos'] = $alink['pos']=='' ? 0 : $alink['pos'];
			$catty = $alink['category'];

			echo '
						<tr class="windowbg">
						<td class="articles">
							<div>
								<div style="width:7%;max-width:100%;" class="adm-pos float-items">
									<a name="article'.$alink['id'].'"></a><input type="text" size="2" value="'.$alink['pos'].'" name="tp_article_pos'.$alink['id'].'" />
								</div>
								<div style="width:25%;" class="adm-name float-items">
									' , $alink['locked']==0 ? '<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article=' . $alink['id'] . '">' . (!empty($alink['subject']) ? $alink['subject'] : $txt['tp-noname']) . '</a>' : '&nbsp;' . (!empty($alink['subject']) ? $alink['subject'] : $txt['tp-noname']) , '
								</div>
								<a href="" class="clickme">'.$txt['tp-more'].'</a>
								<div class="box" style="width:68%;float:left;">
									<div style="width:14.8%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout">
											' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=author_id"><strong>' , $txt['tp-author'] , '</strong></a>
										</div>
										<div id="size-on-respnsive-layout">
											<a href="' . $scripturl . '?action=profile;u=' , $alink['author_id'], '">'.$alink['author'] .'</a>
										</div>
									</div>
									<div style="width:29.8%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout">
											' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=date"><strong>' , $txt['tp-date'] , '</strong></a>
										</div>
										<div id="size-on-respnsive-layout">' , timeformat($alink['date']) , '</div>
									</div>
									<div style="width:36%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout" style="margin-top:0.5%;"><strong>'.$txt['tp-editarticleoptions2'].'</strong></div>
										<div id="size-on-respnsive-layout">
											<img style="cursor: pointer;" class="toggleActive" id="artActive' .$alink['id']. '" title="'.$txt['tp-activate'].'" src="' .$settings['tp_images_url']. '/TPactive' , $alink['off']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-activate'].'"  />
											<a href="',$scripturl, '?page=',$alink['id'],'"><img title="'.$txt['tp-preview'].'" src="' .$settings['tp_images_url']. '/TPfilter.png" alt="" /></a>
											' , $alink['locked']==0 ?
											'<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article='.$alink['id']. '"><img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm.png" alt="'.$txt['tp-edit'].'"  /></a>' : '<img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm2.png" alt="'.$txt['tp-islocked'].'"  />' , '
											<img style="cursor: pointer;" class="toggleFront" id="artFront' .$alink['id']. '" title="'.$txt['tp-setfrontpage'].'" src="' .$settings['tp_images_url']. '/TPfront' , $alink['frontpage']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-setfrontpage'].'"  />
											<img style="cursor: pointer;" class="toggleSticky" id="artSticky' .$alink['id']. '" title="'.$txt['tp-setsticky'].'" src="' .$settings['tp_images_url']. '/TPsticky' , $alink['sticky']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setsticky'].'"  />
											<img style="cursor: pointer;" class="toggleLock" id="artLock' .$alink['id']. '" title="'.$txt['tp-setlock'].'" src="' .$settings['tp_images_url']. '/TPlock' , $alink['locked']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setlock'].'"  />
											<img style="cursor: pointer;" class="toggleFeatured" id="artFeatured' .$alink['id']. '" title="'.$txt['tp-featured'].'" src="' .$settings['tp_images_url']. '/TPflag' , $alink['featured']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-featured'].'"  />
										</div>
									</div>
									<div style="width:7%;text-transform:uppercase;" class="smalltext fullwidth-on-res-layout float-items tpcenter" >
										<div id="show-on-respnsive-layout">
										' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=strays;sort=type"><strong>' , $txt['tp-type'] , '</strong></a>
										</div>
										' , empty($alink['type']) ? 'html' : $alink['type'] , '
									</div>
									<div style="width:6%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
										<div id="show-on-respnsive-layout"><strong>'.$txt['tp-delete'].'<strong></div>
										<a href="' . $scripturl . '?action=tpadmin;cu=-1;' . $context['session_var'] . '=' . $context['session_id'].';artdelete=' .$alink['id']. '" onclick="javascript:return confirm(\''.$txt['tp-articleconfirmdelete'].'\')">
										<img title="'.$txt['tp-delete'].'" src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /></a>
									</div>
									<div style="width:4%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
										<div id="show-on-respnsive-layout">'.$txt['tp-select'].'</div>
										<input type="checkbox" name="tp_article_stray'.$alink['id'].'" value="1"  />
									</div>
									<p class="clearthefloat"></p>
							  </div>
							  <p class="clearthefloat"></p>
						</div>
						</td>
						</tr>';
		}
			echo '
					</tbody>
					</table>';
			if( !empty($context['TPortal']['pageindex'])) {
				echo '
					<div class="middletext padding-div">
						'.$context['TPortal']['pageindex'].'
					</div>';
            }
			echo '
				</div>';

		if(isset($context['TPortal']['allcats'])) {
			echo '
				<p style="text-align: right;padding:1%;max-width:100%;">
				<select name="tp_article_cat">
					<option value="0">' . $txt['tp-createnew'] . '</option>';
			foreach($context['TPortal']['allcats'] as $submg) {
  					echo '
						<option value="'.$submg['id'].'">',  ( isset($submg['indent']) && $submg['indent'] > 1 ) ? str_repeat("-", ($submg['indent']-1)) : '' , ' '. $txt['tp-assignto'] . $submg['name'].'</option>';
            }
			echo '
				</select>
				<input name="tp_article_new" value="" size="40"  /> &nbsp;
				</p>';
		}
		echo '
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>';
	}
	else {
		echo '
			<div class="windowbg2">
				<div class="windowbg3"></div>
			</div>';
    }
	echo '
		</div>
	</form>';
}

// Article Settings Page
function template_artsettings()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $date;

		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="artsettings">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-articlesettings'] . '</h3></div>
		<div id="article-settings" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helpartsettings'] , '</div><div></div>
			<div class="windowbg noup">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt>
							<label for="tp_use_wysiwyg">', $txt['tp-usewysiwyg'], '</label>
						</dt>
						<dd>
							<input id="tp_use_wysiwyg" name="tp_use_wysiwyg" type="checkbox" value="1" ' , $context['TPortal']['use_wysiwyg']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_editorheight">', $txt['tp-editorheight'], '</label>
						</dt>
						<dd>
							<input name="tp_editorheight" id="tp_editorheight" type="text" size="4" value="' , $context['TPortal']['editorheight'] , '" />
						</dd>
						<dt>
							<label for="tp_use_dragdrop">', $txt['tp-usedragdrop'], '</label>
						</dt>
						<dd>
							<input id="tp_use_dragdrop" name="tp_use_dragdrop" type="checkbox" value="1" ' , $context['TPortal']['use_dragdrop']=='1' ? 'checked' : '' , '>
						</dd>
					</dl>
					<hr>
					<dl class="settings">
						<dt>
							<label for="tp_hide_editarticle_link">', $txt['tp-hidearticle-link'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_editarticle_link" name="tp_hide_editarticle_link" type="checkbox" value="1" ' , $context['TPortal']['hide_editarticle_link']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_print_articles">'.$txt['tp-printarticles'].'&nbsp;&nbsp;<img src="' . $settings['tp_images_url'] . '/TPprint.png" alt="" /></label>
						</dt>
						<dd>
							<input id="tp_print_articles" name="tp_print_articles" type="checkbox" value="1" ' , $context['TPortal']['print_articles']=='1' ? 'checked' : '' , '>
						</dd>
                        <dt>
							<label for="tp_allow_links_article_comments">', $txt['tp-allow-links-article-comments'], '</label>
						</dt>
						<dd>
							<input id="tp_allow_links_article_comments" name="tp_allow_links_article_comments" type="checkbox" value="1" ' , $context['TPortal']['allow_links_article_comments']=='1' ? 'checked' : '' , '>
						</dd>
					</dl>
					<hr>
					<dl class="settings">
						<dt>
							<label for="tp_hide_article_facebook">', $txt['tp-hidearticle-facebook'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_facebook" name="tp_hide_article_facebook" type="checkbox" value="1" ' , $context['TPortal']['hide_article_facebook']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hide_article_twitter">', $txt['tp-hidearticle-twitter'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_twitter" name="tp_hide_article_twitter" type="checkbox" value="1" ' , $context['TPortal']['hide_article_twitter']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hide_article_reddit">', $txt['tp-hidearticle-reddit'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_reddit" name="tp_hide_article_reddit" type="checkbox" value="1" ' , $context['TPortal']['hide_article_reddit']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hide_article_digg">', $txt['tp-hidearticle-digg'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_digg" name="tp_hide_article_digg" type="checkbox" value="1" ' , $context['TPortal']['hide_article_digg']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hide_article_delicious">', $txt['tp-hidearticle-delicious'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_delicious" name="tp_hide_article_delicious" type="checkbox" value="1" ' , $context['TPortal']['hide_article_delicious']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hide_article_stumbleupon">', $txt['tp-hidearticle-stumbleupon'], '</label>
						</dt>
						<dd>
							<input id="tp_hide_article_stumbleupon" name="tp_hide_article_stumbleupon" type="checkbox" value="1" ' , $context['TPortal']['hide_article_stumbleupon']=='1' ? 'checked' : '' , '>
						</dd>
					</dl>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Article Submissions Page
function template_submission()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post" enctype="multipart/form-data" onsubmit="syncTextarea();">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="submission">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-submissionsettings']  . '</h3></div>
		<div id="submissions" class="admintable admin-area">
			<div class="windowbg noup padding-div">';
	if(isset($context['TPortal']['arts_submissions']))
	{
		echo '
				<table class="table_grid tp_grid" style="width:100%">
					<thead>
						<tr class="title_bar titlebg2">
						<th scope="col" class="articles">
							<div class="catbg3">
								<div style="width:7%;" class="pos float-items"><strong>'.$txt['tp-select'].'</strong></div>
								<div style="width:25%;" class="name float-items"><strong>' , $context['TPortal']['sort']=='subject' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_up.png" alt="'.$txt['tp-sort-on-subject'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-subject'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=subject">' , $txt['tp-name'] , '</a></strong></div>
								<div style="width:10%;" class="title-admin-area float-items"><strong> ' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_up.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=author_id">' , $txt['tp-author'] , '</a></strong></div>
								<div style="width:20%;" class="title-admin-area float-items"><strong> ' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=date">' , $txt['tp-date'] , '</a></strong></div>
								<div style="width:25%;" class="title-admin-area float-items"><strong>&nbsp;</strong></div>
								<div style="width:13%;" class="title-admin-area float-items"><strong> ' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_up.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=type">' , $txt['tp-type'] , '</a></strong></div>
							    <p class="clearthefloat"></p>
							</div>
						</th>
						</tr>
					</thead>
					<tbody>';

		foreach($context['TPortal']['arts_submissions'] as $a => $alink)
		{
			$alink['pos'] = $alink['pos']=='' ? 0 : $alink['pos'];
			$catty = $alink['category'];

			echo '
						<tr class="windowbg">
						<td class="articles">
							<div>
								<div style="width:7%;" class="adm-pos float-items">
									<input type="checkbox" name="tp_article_submission'.$alink['id'].'" value="1"  />
								</div>
								<div style="width:25%;" class="adm-name float-items">
									' , $alink['locked']==0 ? '<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article=' . $alink['id'] . '"> ' . (!empty($alink['subject']) ? $alink['subject'] : $txt['tp-noname']) . '</a>' : '&nbsp;' . (!empty($alink['subject']) ? $alink['subject'] : $txt['tp-noname']) , '
								</div>
								<a href="" class="clickme">'.$txt['tp-more'].'</a>
								<div class="box" style="width:68%;float:left;">
									<div style="width:14.8%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout"> ' , $context['TPortal']['sort']=='author_id' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-author'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-author'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=author_id"><strong>' , $txt['tp-author'] , '</strong></a></div>
										<div id="size-on-respnsive-layout"><a href="' . $scripturl . '?action=profile;u=' , $alink['author_id'], '">'.$alink['author'] .'</a></div>
									</div>
									<div style="width:29.8%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout"> ' , $context['TPortal']['sort']=='date' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-date'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-date'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=date"><strong>' , $txt['tp-date'] , '</strong></a></div>
										<div id="size-on-respnsive-layout">' , timeformat($alink['date']) , '</div>
									</div>
									<div style="text-align:left;width:37.5%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout" style="margin-top:0.5%;"><strong>'.$txt['tp-editarticleoptions2'].'</strong></div>
										<div id="size-on-respnsive-layout">
										<img style="cursor: pointer;" class="toggleActive" id="artActive' .$alink['id']. '" title="'.$txt['tp-activate'].'" src="' .$settings['tp_images_url']. '/TPactive' , $alink['off']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-activate'].'"  />
										<a href="',$scripturl, '?page=',$alink['id'],'"><img title="'.$txt['tp-preview'].'" src="' .$settings['tp_images_url']. '/TPfilter.png" alt="" /></a>
										' , $alink['locked']==0 ?
										'<a href="' . $scripturl . '?action=tpadmin;sa=editarticle;article='.$alink['id']. '"><img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm.png" alt="'.$txt['tp-edit'].'"  /></a>' : '<img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm.png" alt="'.$txt['tp-islocked'].'"  />' , '
										<img style="cursor: pointer;" class="toggleFront" id="artFront' .$alink['id']. '" title="'.$txt['tp-setfrontpage'].'" src="' .$settings['tp_images_url']. '/TPfront' , $alink['frontpage']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-setfrontpage'].'"  />
										<img style="cursor: pointer;" class="toggleSticky" id="artSticky' .$alink['id']. '" title="'.$txt['tp-setsticky'].'" src="' .$settings['tp_images_url']. '/TPsticky' , $alink['sticky']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setsticky'].'"  />
										<img style="cursor: pointer;" class="toggleLock" id="artLock' .$alink['id']. '" title="'.$txt['tp-setlock'].'" src="' .$settings['tp_images_url']. '/TPlock' , $alink['locked']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setlock'].'"  />
									<img style="cursor: pointer;" class="toggleFeatured" id="artFeatured' .$alink['id']. '" title="'.$txt['tp-featured'].'" src="' .$settings['tp_images_url']. '/TPflag' , $alink['featured']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-featured'].'"  />
									</div>
								</div>
								<div class="smalltext fullwidth-on-res-layout float-items" style="text-align:center;width:7%;text-transform:uppercase;">
									<div id="show-on-respnsive-layout"> ' , $context['TPortal']['sort']=='type' ? '<img src="' . $settings['tp_images_url'] . '/TPsort_down.png" alt="'.$txt['tp-sort-on-type'].'" /> ' : '' , '<a title="'.$txt['tp-sort-on-type'].'" href="' . $scripturl . '?action=tpadmin;sa=submission;sort=type"><strong>' , $txt['tp-type'] , '</strong></a></div>
									' , empty($alink['type']) ? 'html' : $alink['type'] , '
									</div>
									<div style="text-align:center;width:6%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout"><strong>'.$txt['tp-delete'].'</strong></div>
										<a href="' . $scripturl . '?action=tpadmin;cu=-1;' . $context['session_var'] . '=' . $context['session_id'].';artdelete=' .$alink['id']. '" onclick="javascript:return confirm(\''.$txt['tp-articleconfirmdelete'].'\')">
										<img title="'.$txt['tp-delete'].'" src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /></a>
									</div>
									<p class="clearthefloat"></p>
								</div>
								<p class="clearthefloat"></p>
							</div>
						</td>
						</tr>';
		}
			echo '
					</tbody>
				</table>';
			
			if( !empty($context['TPortal']['pageindex']))
				echo '
				<div class="middletext padding-div">
					<strong>'.$context['TPortal']['pageindex'].'</strong>
				</div>';

		if(isset($context['TPortal']['allcats']))
		{
			echo '	
				<div class="padding-div">
					<p style="text-align: center;">
					<select name="tp_article_cat">
						<option value="0">' . $txt['tp-createnew2'] . '</option>';
			foreach($context['TPortal']['allcats'] as $submg)
  					echo '
						<option value="'.$submg['id'].'">'. $txt['tp-approveto'] . $submg['name'].'</option>';
			echo '
					</select>
					<input name="tp_article_new" value="" size="40" /> &nbsp;
					</p>
				</div>';
		}
		echo '
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>';
	}
	else
		echo '
			<div class="windowbg2">
				<div class="padding-div">'.$txt['tp-nosubmissions'].'</div>
				<div class="padding-div">&nbsp;</div>
			</div>';

		echo '
		</div>
	</form>';
}

// Illustrative article icons to be used in category layouts Page
function template_articons()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

		tp_collectArticleIcons();

		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" enctype="multipart/form-data" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="articons">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-adminicons7'] . '</h3></div>
		<div id="article-icons-pictures" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-adminiconsinfo'] , '</div><div></div>
				<div class="windowbg noup padding-div">
				<div class="formtable"><br>
					<dl class="settings">
						<dt>
							', $txt['tp-adminicons6'], '<br>
						</dt>
						<dd>
							<input type="file" name="tp_article_newillustration" />
						</dd>
					</dl>
					<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
					<hr><br>';
					
				$alt=true;
		if(count($context['TPortal']['articons']['illustrations'])>0)
		{
			foreach($context['TPortal']['articons']['illustrations'] as $icon)
			{
				echo '
					<div class="smalltext padding-div" style="float:left;">
						<div class="article_icon" style="background: top right url(' . $icon['background'] . ') no-repeat;"></div>
						<input type="checkbox" name="artillustration'.$icon['id'].'" id="artillustration'.$icon['id'].'" style="vertical-align: top;" value="'.$icon['file'].'"  /> <label style="vertical-align: top;"  for="artiillustration'.$icon['id'].'">'.$txt['tp-remove'].'</label>
					</div>
							';
				$alt = !$alt;
			}
		}

		echo '
					<p class="clearthefloat"></p>
					<hr>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Panel Settings Page
function template_panels()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="panels">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-panelsettings'] . '</h3></div>
			<div id="panels-admin" class="admintable admin-area">
			<div class="information smalltext">' , $txt['tp-helppanels'] , '</div><div></div>
			<div class="windowbg noup">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt>
							<strong>'.$txt['tp-hidebarsall'].'</strong>
						</dt>
`						<dt>
							<label for="tp_hidebars_admin_only">', $txt['tp-hidebarsadminonly'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_admin_only" name="tp_hidebars_admin_only" type="checkbox" value="1" ' , $context['TPortal']['hidebars_admin_only']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hidebars_profile">', $txt['tp-hidebarsprofile'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_profile" name="tp_hidebars_profile" type="checkbox" value="1" ' , $context['TPortal']['hidebars_profile']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hidebars_pm">', $txt['tp-hidebarspm'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_pm" name="tp_hidebars_pm" type="checkbox" value="1" ' , $context['TPortal']['hidebars_pm']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hidebars_memberlist">', $txt['tp-hidebarsmemberlist'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_memberlist" name="tp_hidebars_memberlist" type="checkbox" value="1" ' , $context['TPortal']['hidebars_memberlist']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hidebars_search">', $txt['tp-hidebarssearch'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_search" name="tp_hidebars_search" type="checkbox" value="1" ' , $context['TPortal']['hidebars_search']=='1' ? 'checked' : '' , '>
						</dd>
						<dt>
							<label for="tp_hidebars_calendar">', $txt['tp-hidebarscalendar'], '</label>
						</dt>
						<dd>
							<input id="tp_hidebars_calendar" name="tp_hidebars_calendar" type="checkbox" value="1" ' , $context['TPortal']['hidebars_calendar']=='1' ? 'checked' : '' , '>
						</dd>
					</dl>
					<dl class="settings">
						<dt>
							<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-hidebarscustomdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="tp_hidebars_custom">'.$txt['tp-hidebarscustom'].'</label>
						</dt>
						<dd>
							<textarea cols="40" style="width: 94%; height: 100px;" name="tp_hidebars_custom" id="tp_hidebars_custom">' . $context['TPortal']['hidebars_custom'].'</textarea>
						</dd>
					</dl>
					<dl class="settings">
						<dt>
							<label for="tp_padding">'.$txt['tp-padding_between'].'</label>
						</dt>
						<dd>
							<input name="tp_padding" id="tp_padding" size="5" maxlength="5" type="text" value="' ,$context['TPortal']['padding'], '">
							<span class="smalltext">'.$txt['tp-inpixels'].'</span>
						</dd>
					</dl>
					<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
				</div>
				<hr>';

	$allpanels = array('left','right','top','center','front','lower','bottom');
	$alternate = true;

	if(function_exists('ctheme_tp_getblockstyles'))
		$types = ctheme_tp_getblockstyles();
	if(TP_SMF21)
		$types = tp_getblockstyles21();
	else
		$types = tp_getblockstyles();

	foreach($allpanels as $pa => $panl)
	{
		echo '
				<div id="panels-options" class="padding-div">
					<div class="title_bar"><h3 class="titlebg">';
		if($panl!='front')
			echo $txt['tp-'.$panl.'panel'].'</h3></div>
					<a name="'.$panl.'"></a><br>
					<img style="margin: 5px;" src="' .$settings['tp_images_url']. '/TPpanel_'.$panl.'' , $context['TPortal']['admin'.$panl.'panel'] ? '' : '_off' , '.png" alt="" />';
		else
			echo $txt['tp-'.$panl.'panel'].'</h3></div>
					<a name="'.$panl.'"></a><br>
					<img style="margin: 5px;" src="' .$settings['tp_images_url']. '/TPpanel_'.$panl.'.png" alt="" />';
		echo '
					<br>
				<div>
				<dl class="settings">';
		if($panl!='front')
		{
			if(in_array($panl, array("left","right")))
				echo '
					<dt>
						<label for="tp_'.$panl.'bar_width">'.$txt['tp-panelwidth'].'</label>
					</dt>
					<dd>
						<input id="tp_'.$panl.'bar_width" name="tp_'.$panl.'bar_width" size="5" maxlength="5" type="text" value="' , $context['TPortal'][$panl. 'bar_width'] , '"><br>
					</dd>';
				echo '
					<dt>
						<label for="tp_'.$panl.'panel">'.$txt['tp-use'.$panl.'panel'].'</label>
					</dt>
					<dd>
						<input id="tp_'.$panl.'panel" name="tp_'.$panl.'panel" type="radio" value="1" ' , $context['TPortal']['admin'.$panl.'panel']==1 ? 'checked' : '' , '> '.$txt['tp-on'].'
						<input name="tp_'.$panl.'panel" type="radio" value="0" ' , $context['TPortal']['admin'.$panl.'panel']==0 ? 'checked' : '' , '> '.$txt['tp-off'].'<br>
					</dd>
					<dt>
						<label for="tp_hide_'.$panl.'bar_forum">'.$txt['tp-hide_'.$panl.'bar_forum'].'</label>
					</dt>
					<dd>
						<input id="tp_hide_'.$panl.'bar_forum" name="tp_hide_'.$panl.'bar_forum" type="radio" value="1" ' , $context['TPortal']['hide_'.$panl.'bar_forum']==1 ? 'checked' : '' , '> '.$txt['tp-yes'].'
						<input name="tp_hide_'.$panl.'bar_forum" type="radio" value="0" ' , $context['TPortal']['hide_'.$panl.'bar_forum']==0 ? 'checked' : '' , '> '.$txt['tp-no'].'
						<br><br>
					</dd>';
		}
		echo '
					<dt>
						<label for="tp_block_layout_'.$panl.'1">'.$txt['tp-vertical'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'1" name="tp_block_layout_'.$panl.'" type="radio" value="vert" ' , $context['TPortal']['block_layout_'.$panl]=='vert' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_block_layout_'.$panl.'2">'.$txt['tp-horisontal'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'2" name="tp_block_layout_'.$panl.'" type="radio" value="horiz" ' , $context['TPortal']['block_layout_'.$panl]=='horiz' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_block_layout_'.$panl.'3">'.$txt['tp-horisontal2cols'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'3" name="tp_block_layout_'.$panl.'" type="radio" value="horiz2" ' , $context['TPortal']['block_layout_'.$panl]=='horiz2' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_block_layout_'.$panl.'4">'.$txt['tp-horisontal3cols'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'4" name="tp_block_layout_'.$panl.'" type="radio" value="horiz3" ' , $context['TPortal']['block_layout_'.$panl]=='horiz3' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_block_layout_'.$panl.'5">'.$txt['tp-horisontal4cols'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'5" name="tp_block_layout_'.$panl.'" type="radio" value="horiz4" ' , $context['TPortal']['block_layout_'.$panl]=='horiz4' ? 'checked' : '' , '>
					</dd>
					<dt>
						<label for="tp_block_layout_'.$panl.'6">'.$txt['tp-grid'].'</label>
					</dt>
					<dd>
						<input id="tp_block_layout_'.$panl.'6" name="tp_block_layout_'.$panl.'" type="radio" value="grid" ' , $context['TPortal']['block_layout_'.$panl]=='grid' ? 'checked' : '' , '>
					</dd>
					<dt>&nbsp;</dt>
					<dd>
						<hr><p>
						<input name="tp_blockgrid_'.$panl.'" id="tp_blockgrid_'.$panl.'1" type="radio" value="colspan3" ' , $context['TPortal']['blockgrid_'.$panl]=='colspan3' ? 'checked' : '' , ' /><label for="tp_blockgrid_'.$panl.'1"><img src="' .$settings['tp_images_url']. '/TPgrid1.png" alt="colspan3" /></label>
						<input name="tp_blockgrid_'.$panl.'" id="tp_blockgrid_'.$panl.'2" type="radio" value="rowspan1" ' , $context['TPortal']['blockgrid_'.$panl]=='rowspan1' ? 'checked' : '' , ' /><label for="tp_blockgrid_'.$panl.'2"><img src="' .$settings['tp_images_url']. '/TPgrid2.png" alt="rowspan1" /></label></p>
					</dd>
				</dl>
				<dl class="settings">
					<dt>
						<label for="tp_blockwidth_'.$panl.'">'.$txt['tp-blockwidth'].':</label>
					</dt>
					<dd>
						<input name="tp_blockwidth_'.$panl.'" id="tp_blockwidth_'.$panl.'" size="5" maxlength="5" type="text" value="' ,$context['TPortal']['blockwidth_'.$panl], '"><br>
					</dd>
					<dt>
						<label for="tp_blockheight_'.$panl.'">'.$txt['tp-blockheight'].':</label>
					</dt>
					<dd>
						<input name="tp_blockheight_'.$panl.'" id="tp_blockheight_'.$panl.'" size="5" maxlength="5" type="text" value="' ,$context['TPortal']['blockheight_'.$panl], '">
					</dd>
				</dl>
				<a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-panelstylehelpdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label>'.$txt['tp-panelstylehelp'].'</label>
				<div class="panels-optionsbg">';

			foreach($types as $blo => $bl)
				echo '
					<div class="panels-options">
						<div class="smalltext" style="padding: 4px 0;">
							<input name="tp_panelstyle_'.$panl.'" id="tp_panelstyle_'.$panl.''.$blo.'" type="radio" value="'.$blo.'" ' , $context['TPortal']['panelstyle_'.$panl]==$blo ? 'checked' : '' , '><label for="tp_panelstyle_'.$panl.''.$blo.'">
							<span' , $context['TPortal']['panelstyle_'.$panl]==$blo ? ' style="color: red;">' : '>' , $bl['class'] , '</span></label>
						</div>
						' . $bl['code_title_left'] . 'title'. $bl['code_title_right'].'
						' . $bl['code_top'] . 'body' . $bl['code_bottom'] . '
					</div>';
			echo '
				</div>
			</div>
			<hr>
		</div>';
		$alternate = !$alternate;
	}

		echo '
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// All the blocks (is this still used?)
function template_latestblocks()
{
	tp_latestblockcodes();
}

// Block Settings Page
function template_blocks()
{
	global $context, $settings, $txt, $scripturl;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="blocks">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-blocksettings'] . '</h3></div>
		<div id="all-the-blocks" class="admintable admin-area">
			<div class="windowbg noup padding-div">';

		$side=array('left','right','top','center','front','lower','bottom');
		$sd=array('lb','rb','tb','cb','fb','lob','bb');

		for($i=0 ; $i<7 ; $i++)
		{
			echo '
				<div class="title_bar"><h3 class="titlebg">
					<b>'.$txt['tp-'.$side[$i].'sideblocks'].'</b>
					<a href="'.$scripturl.'?action=tpadmin;addblock=' . $side[$i] . ';' . $context['session_var'] . '=' . $context['session_id'].'">
					<span style="float: right;">[' , $txt['tp-addblock'] , ']</span></a></h3>
				</div>';
			if(isset($context['TPortal']['admin' . $side[$i].'panel']) && $context['TPortal']['admin' . $side[$i].'panel']==0 && $side[$i]!='front')
				echo '
				<div class="windowbg2">
					<div class="tborder error smalltext" style="padding: 2px;"><a style="color: red;" href="' . $scripturl.'?action=tpadmin;sa=panels">',$txt['tp-panelclosed'] , '</a></div>
				</div>';

			if(isset($context['TPortal']['admin_'.$side[$i].'block']['blocks']))
				$tn=count($context['TPortal']['admin_'.$side[$i].'block']['blocks']);
			else
				$tn=0;

			if($tn>0)
				echo '
				<table class="table_grid tp_grid" style="width:100%">
					<thead>
						<tr class="title_bar titlebg2">
						<th scope="col" class="blocks">
							<div>
								<div style="width:10%;" class="smalltext pos float-items"><strong>'.$txt['tp-pos'].'</strong></div>
								<div style="width:20%;" class="smalltext name float-items"><strong>'.$txt['tp-title'].'</strong></div>
								<div style="width:20%;" class="smalltext title-admin-area float-items" ><strong>'.$txt['tp-type'].'</strong></div>
								<div style="width:10%;" class="smalltext title-admin-area float-items tpcenter"><strong>'.$txt['tp-activate'].'</strong></div>
								<div style="width:20%;" class="smalltext title-admin-area float-items tpcenter"><strong>'.$txt['tp-move'].'</strong></div>
								<div style="width:10%;" class="smalltext title-admin-area float-items tpcenter"><strong>'.$txt['tp-editsave'].'</strong></div>
								<div style="width:10%;" class="smalltext title-admin-area float-items tpcenter"><strong>'.$txt['tp-delete'].'</strong></div>
								<p class="clearthefloat"></p>
							</div>
						</th>
						</tr>
					</thead>
					<tbody>';

			$n=0;
			if($tn>0)
			{
				foreach($context['TPortal']['admin_'.$side[$i].'block']['blocks'] as $lblock)
				{
					$newtitle = TPgetlangOption($lblock['lang'], $context['user']['language']);
					if(empty($newtitle))
						$newtitle = $lblock['title'];

					if(!$lblock['loose'])
						$class="windowbg3";
					else{
						$class='windowbg';
					}
					echo '
						<tr class="',$class,'">
						<td class="blocks">
						<div id="blocksDiv">
							<div style="width:10%;" class="adm-pos float-items">', ($lblock['editgroups']!='' && $lblock['editgroups']!='-2') ? '#' : '' ,'
								<input name="pos' .$lblock['id']. '" type="text" size="3" maxlength="3" value="' .($n*10). '">
								<a name="block' .$lblock['id']. '"></a>';
					echo '
								<a class="tpbut" title="'.$txt['tp-sortdown'].'" href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';addpos=' .$lblock['id']. '"><img src="' .$settings['tp_images_url']. '/TPsort_down.png" value="' .(($n*10)+11). '" /></a>';

					if($n>0)
						echo '
								<a class="tpbut" title="'.$txt['tp-sortup'].'"  href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';subpos=' .$lblock['id']. '"><img src="' .$settings['tp_images_url']. '/TPsort_up.png" value="' .(($n*10)-11). '" /></a>';

					echo '
							</div>
						<div style="width:20%;max-width:100%;" class="adm-name float-items">
						     <input name="title' .$lblock['id']. '" type="text" size="25" value="' .html_entity_decode($newtitle). '">
						</div>
						<div style="width:20%;" class="fullwidth-on-res-layout block-opt float-items">
						    <div id="show-on-respnsive-layout">
								<div class="smalltext"><strong>'.$txt['tp-type'].'</strong></div>
							</div>
							<select size="1" name="type' .$lblock['id']. '">
								<option value="0"' ,$lblock['type']=='no' ? ' selected' : '' , '>', $txt['tp-blocktype0'] , '</option>
								<option value="18"' ,$lblock['type']=='articlebox' ? ' selected' : '' , '>', $txt['tp-blocktype18'] , '</option>
								<option value="19"' ,$lblock['type']=='categorybox' ? ' selected' : '' , '>', $txt['tp-blocktype19'] , '</option>
								<option value="14"' ,$lblock['type']=='module' ? ' selected' : '' , '>', $txt['tp-blocktype14'] , '</option>
								<option value="5"' ,$lblock['type']=='html' ? ' selected' : '' , '>', $txt['tp-blocktype5'] , '</option>
								<option value="11"' ,$lblock['type']=='scriptbox' ? ' selected' : '' , '>', $txt['tp-blocktype11'] , '</option>
								<option value="10"' ,$lblock['type']=='phpbox' ? ' selected' : '' , '>', $txt['tp-blocktype10'] , '</option>
								<option value="9"' ,$lblock['type']=='catmenu' ? ' selected' : '' , '>', $txt['tp-blocktype9'] , '</option>
								<option value="2"' ,$lblock['type']=='newsbox' ? ' selected' : '' , '>', $txt['tp-blocktype2'] , '</option>
								<option value="6"' ,$lblock['type']=='onlinebox' ? ' selected' : '' , '>', $txt['tp-blocktype6'] , '</option>
								<option value="12"' ,$lblock['type']=='recentbox' ? ' selected' : '' , '>', $txt['tp-blocktype12'] , '</option>
								<option value="15"' ,$lblock['type']=='rss' ? ' selected' : '' , '>', $txt['tp-blocktype15'] , '</option>
								<option value="4"' ,$lblock['type']=='searchbox' ? ' selected' : '' , '>', $txt['tp-blocktype4'] , '</option>
								<option value="16"' ,$lblock['type']=='sitemap' ? ' selected' : '' , '>', $txt['tp-blocktype16'] , '</option>
								<option value="13"' ,$lblock['type']=='ssi' ? ' selected' : '' , '>', $txt['tp-blocktype13'] , '</option>
								<option value="3"' ,$lblock['type']=='statsbox' ? ' selected' : '' , '>', $txt['tp-blocktype3'] , '</option>
								<option value="7"' ,$lblock['type']=='themebox' ? ' selected' : '' , '>', $txt['tp-blocktype7'] , '</option>
								<option value="1"' ,$lblock['type']=='userbox' ? ' selected' : '' , '>', $txt['tp-blocktype1'] , '</option>
								<option value="20"' ,$lblock['type']=='tpmodulebox' ? ' selected' : '' , '>', $txt['tp-blocktype20'] , '</option>';
			// theme hooks
			if(function_exists('ctheme_tp_blocks'))
			{
				ctheme_tp_blocks('listblocktypes2', $lblock['type']);
			}
				echo '	</select>
						</div>
						<div style="width:10%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
						    <div id="show-on-respnsive-layout"><strong>'.$txt['tp-activate'].'</strong></div>
							&nbsp;<a name="'.$lblock['id'].'"></a>
						    <img class="toggleButton" id="blockonbutton' .$lblock['id']. '" title="'.$txt['tp-activate'].'" src="' .$settings['tp_images_url']. '/TP' , $lblock['off']=='0' ? 'active2' : 'active1' , '.png" alt="'.$txt['tp-activate'].'"  />';
				echo '
						</div>
						<div style="width:20%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
							<div id="show-on-respnsive-layout"><strong>'.$txt['tp-move'].'</strong></div>';

					switch($side[$i]){
						case 'left':
 							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'right':
 							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'center':
							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'front':
							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'bottom':
							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'top':
							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocklower=' .$lblock['id']. '"><img title="'.$txt['tp-movelower'].'" src="' .$settings['tp_images_url']. '/TPselect_lower.png" alt="'.$txt['tp-movelower'].'" /></a>';
							break;
						case 'lower':
 							echo '
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockleft=' .$lblock['id']. '"><img title="'.$txt['tp-moveleft'].'" src="' .$settings['tp_images_url']. '/TPselect_left.png" alt="'.$txt['tp-moveleft'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockright=' .$lblock['id']. '"><img title="'.$txt['tp-moveright'].'" src="' .$settings['tp_images_url']. '/TPselect_right.png" alt="'.$txt['tp-moveright'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockcenter=' .$lblock['id']. '"><img title="'.$txt['tp-movecenter'].'" src="' .$settings['tp_images_url']. '/TPselect_upper.png" alt="'.$txt['tp-movecenter'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockfront=' .$lblock['id']. '"><img title="'.$txt['tp-movefront'].'" src="' .$settings['tp_images_url']. '/TPselect_front.png" alt="'.$txt['tp-movefront'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockbottom=' .$lblock['id']. '"><img title="'.$txt['tp-movedown'].'" src="' .$settings['tp_images_url']. '/TPselect_bottom.png" alt="'.$txt['tp-movedown'].'" /></a>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blocktop=' .$lblock['id']. '"><img title="'.$txt['tp-moveup'].'" src="' .$settings['tp_images_url']. '/TPselect_top.png" alt="'.$txt['tp-moveup'].'" /></a>';
							break;
					}
					echo '
						</div>
						<div  style="width:10%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
						    <div id="show-on-respnsive-layout"><strong>'.$txt['tp-editsave'].'</strong></div>
							<a href="' . $scripturl . '?action=tportal&sa=editblock&id=' .$lblock['id']. ';' . $context['session_var'] . '=' . $context['session_id'].'"><img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPmodify.png" alt="'.$txt['tp-edit'].'"  /></a>';
					echo '
							<input class="tpbut" style="height:16px; vertical-align:top;" type="image" src="' .$settings['tp_images_url']. '/TPsave.png" alt="'.$txt['tp-send'].'" value="�" onClick="javascript: submit();">';
					echo '
						</div>
	                    <div style="width:10%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
						    <div id="show-on-respnsive-layout"><strong>'.$txt['tp-delete'].'</strong></div>
							<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';blockdelete=' .$lblock['id']. '" onclick="javascript:return confirm(\''.$txt['tp-blockconfirmdelete'].'\')"><img title="'.$txt['tp-delete'].'"  src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /></a>
						</div>
						<p class="clearthefloat"></p>
					</div>
					</td>
					</tr>';
					if($lblock['type']=='recentbox'){
						// check to see if it is numeric
						if(!is_numeric($lblock['body']))
							$lblock['body']='10';
						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								'.$txt['tp-numberofrecenttopics'].'<input size=4 name="blockbody' .$lblock['id']. '" value="' .$lblock['body']. '">
							</div>
						</div>
					</td>
					</tr>';
					}
					elseif($lblock['type']=='ssi'){
						// SSI block..which function?
						if(!in_array($lblock['body'],array('recentpoll','toppoll','topposters','topboards','topreplies','topviews','calendar')))
							$lblock['body']='';
						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								<select name="blockbody' .$lblock['id']. '">
									<option value="" ' , $lblock['body']=='' ? 'selected' : '' , '>' .$txt['tp-none-'].'</option>';
						echo '
									<option value="recentpoll" ' , $lblock['body']=='recentpoll' ? 'selected' : '' , '>'.$txt['tp-ssi-recentpoll'].'</option>';
						echo '
									<option value="toppoll" ' , $lblock['body']=='toppoll' ? 'selected' : '' , '>'.$txt['tp-ssi-toppoll'].'</option>';
						echo '
									<option value="topboards" ' , $lblock['body']=='topboards' ? 'selected' : '' , '>'.$txt['tp-ssi-topboards'].'</option>';
						echo '
									<option value="topposters" ' , $lblock['body']=='topposters' ? 'selected' : '' , '>'.$txt['tp-ssi-topposters'].'</option>';
						echo '
									<option value="topreplies" ' , $lblock['body']=='topreplies' ? 'selected' : '' , '>'.$txt['tp-ssi-topreplies'].'</option>';
						echo '
									<option value="topviews" ' , $lblock['body']=='topviews' ? 'selected' : '' , '>'.$txt['tp-ssi-topviews'].'</option>';
						echo '
									<option value="calendar" ' , $lblock['body']=='calendar' ? 'selected' : '' , '>'.$txt['tp-ssi-calendar'].'</option>
								</select>
							</div>
						</div>
					</td>
					</tr>';
					}
					elseif($lblock['type']=='rss'){
						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								'.$txt['tp-rssblock'].'<input style="width: 75%;" name="blockbody' .$lblock['id']. '" value="' .$lblock['body']. '">
							</div>
						</div>
					</td>
					</tr>';
					}
					elseif($lblock['type']=='module'){
						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								<select name="blockbody' .$lblock['id']. '">
									<option value="dl-stats" ' , $lblock['body']=='dl-stats' ? 'selected' : '' , '>' .$txt['tp-module1'].'</option>
									<option value="dl-stats2" ' , $lblock['body']=='dl-stats2' ? 'selected' : '' , '>' .$txt['tp-module2'].'</option>
									<option value="dl-stats3" ' , $lblock['body']=='dl-stats3' ? 'selected' : '' , '>' .$txt['tp-module3'].'</option>
									<option value="dl-stats4" ' , $lblock['body']=='dl-stats4' ? 'selected' : '' , '>' .$txt['tp-module4'].'</option>
									<option value="dl-stats5" ' , $lblock['body']=='dl-stats5' ? 'selected' : '' , '>' .$txt['tp-module5'].'</option>
									<option value="dl-stats6" ' , $lblock['body']=='dl-stats6' ? 'selected' : '' , '>' .$txt['tp-module6'].'</option>
									<option value="dl-stats7" ' , $lblock['body']=='dl-stats7' ? 'selected' : '' , '>' .$txt['tp-module7'].'</option>
									<option value="dl-stats8" ' , $lblock['body']=='dl-stats8' ? 'selected' : '' , '>' .$txt['tp-module8'].'</option>
									<option value="dl-stats9" ' , $lblock['body']=='dl-stats9' ? 'selected' : '' , '>' .$txt['tp-module9'].'</option>
								</select>
							</div>
						</div>
					</td>
					</tr>';
					}
					elseif($lblock['type']=='articlebox'){
						// check to see if it is numeric
						if(!is_numeric($lblock['body']))
							$lblock['body']='';
						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								<select name="blockbody' .$lblock['id']. '">';
				foreach($context['TPortal']['edit_articles'] as $article){
					echo '
									<option value="'.$article['id'].'" ' ,$lblock['body']==$article['id'] ? ' selected' : '' ,' >'. html_entity_decode($article['subject'],ENT_QUOTES).'</option>';
				}
						echo '
								</select>
							</div>
						</div>
					</td>
					</tr>';
					}
					elseif($lblock['type']=='categorybox'){
						// check to see if it is numeric
						if(!is_numeric($lblock['body']))
							$lblock['body']='';

						echo '
					<tr class="windowbg">
					<td class="blocks">
						<div>
							<div class="padding-div tpcenter">
								<select name="blockbody' .$lblock['id']. '">';
					if(isset($context['TPortal']['catnames']) && count($context['TPortal']['catnames'])>0)
					{
						foreach($context['TPortal']['catnames'] as $cat => $val)
						{
							echo '
									<option value="'.$cat.'" ' , $lblock['body']==$cat ? ' selected' : '' ,' >'.html_entity_decode($val).'</option>';
						}
					}
					echo '
								</select>
							</div>
						</div>
					</td>
					</tr>';
					}
					$n++;
				}
			echo '
					</tbody>
				</table>';
			}
		}
		echo '
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Add block Page
function template_addblock()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	$side = $_GET['addblock'];
	$panels = array('','left','right','top','center','front','lower','bottom');

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" enctype="multipart/form-data" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="addblock">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-addblock'] . '</h3></div>
		<div id="add-block" class="admintable admin-area">
			<div class="windowbg2">
				<div class="formtable padding-div">
					<dl class="settings">
						<dt><h3>' , $txt['tp-title'] , ':</h3>
						</dt>
						<dd>
							<input type="input" name="tp_addblocktitle" size="50" value="" />
						</dd>
					</dl>
					<dl class="settings">
						<dt><h3>' , $txt['tp-choosepanel'] , '</h3></dt>
						<dd>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel1" value="1" ' , $side=='left' ? 'checked' : '' , ' /><label for="tp_addblockpanel1"">' . $txt['tp-leftpanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel2" value="2" ' , $side=='right' ? 'checked' : '' , ' /><label for="tp_addblockpanel2"">' . $txt['tp-rightpanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel6" value="6" ' , $side=='top' ? 'checked' : '' , ' /><label for="tp_addblockpanel6"">' . $txt['tp-toppanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel3" value="3" ' , $side=='upper' || $side=='center' ? 'checked' : '' , ' /><label for="tp_addblockpanel3"">' . $txt['tp-centerpanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel4" value="4" ' , $side=='front' ? 'checked' : '' , ' /><label for="tp_addblockpanel4"">' . $txt['tp-frontpanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel7" value="7" ' , $side=='lower' ? 'checked' : '' , ' /><label for="tp_addblockpanel7"">' . $txt['tp-lowerpanel'] . '</label><br>
							<input type="radio" name="tp_addblockpanel" id="tp_addblockpanel5" value="5" ' , $side=='bottom' ? 'checked' : '' , ' /><label for="tp_addblockpanel5"">' . $txt['tp-bottompanel'] . '</label><br>
						</dd>
					</dl>
					<hr>
					<dl class="settings">
						<dt><h3>' , $txt['tp-chooseblock'] , '</h3></dt>
						<dd>
							<div class="tp_largelist2">
								<input type="radio" name="tp_addblock" id="tp_addblock18" value="18" checked /><label for="tp_addblock18">' . $txt['tp-blocktype18'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock19" value="19" /><label for="tp_addblock19">' . $txt['tp-blocktype19'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock14" value="14" /><label for="tp_addblock14">' . $txt['tp-blocktype14'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock5" value="5" /><label for="tp_addblock5">' . $txt['tp-blocktype5'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock11" value="11" /><label for="tp_addblock11">' . $txt['tp-blocktype11'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock10" value="10" /><label for="tp_addblock10">' . $txt['tp-blocktype10'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock9" value="9" /><label for="tp_addblock9">' . $txt['tp-blocktype9'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock2" value="2" /><label for="tp_addblock2">' . $txt['tp-blocktype2'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock6" value="6" /><label for="tp_addblock6">' . $txt['tp-blocktype6'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock12" value="12" /><label for="tp_addblock12">' . $txt['tp-blocktype12'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock15" value="15" /><label for="tp_addblock15">' . $txt['tp-blocktype15'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock4" value="4" /><label for="tp_addblock4">' . $txt['tp-blocktype4'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock16" value="16" /><label for="tp_addblock16">' . $txt['tp-blocktype16'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock13" value="13" /><label for="tp_addblock13">' . $txt['tp-blocktype13'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock3" value="3" /><label for="tp_addblock3">' . $txt['tp-blocktype3'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock7" value="7" /><label for="tp_addblock7">' . $txt['tp-blocktype7'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock1" value="1" /><label for="tp_addblock1">' . $txt['tp-blocktype1'] . '</label><br>
								<input type="radio" name="tp_addblock" id="tp_addblock20" value="20" /><label for="tp_addblock20">' . $txt['tp-blocktype20'] . '</label><br>
							</div>
						</dd>
					</dl>';
			// theme hooks
			if(function_exists('ctheme_tp_blocks'))
			{
				ctheme_tp_blocks('listaddblocktypes');
			}
					echo '
					<dl class="settings">
						<dt><h3>' , $txt['tp-chooseblocktype'] , '</h3></dt>
						<dd>
							<div class="tp_largelist2">';

		foreach($context['TPortal']['blockcodes'] as $bc)
			echo '
						<div class="padding-div">
							<input type="radio" name="tp_addblock" id="tp_addblock' . $bc['name'].'" value="' . $bc['file']. '"  />
							<label for="tp_addblock' . $bc['name'].'"><b>' . $bc['name'].'</b> ' . $txt['tp-by'] . ' ' . $bc['author'] . '</b></label>
							<div style="margin: 4px 0; padding-left: 24px;" class="smalltext">' , $bc['text'] , '</div>
						</div>';

		echo '
							</div>
						</dd>
					</dl>
					<dl class="settings">
						<dt><h3 class="padding-div">' , $txt['tp-chooseblockcopy'] , '</h3></dt>
						<dd>
							<div class="tp_largelist2">';

		foreach($context['TPortal']['copyblocks'] as $bc)
			echo '
						<div class="padding-div">
							<input type="radio" name="tp_addblock" id="tp_addblock_' . $bc['id']. '" value="mb_' . $bc['id']. '"  /><label for="tp_addblock_' . $bc['id']. '">' . $bc['title'].' </label>[' . $panels[$bc['bar']] . ']
						</div>';

		echo ' 				</div>
						</dd>
					</dl>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}

// Block Access Page
function template_blockoverview()
{
	global $context, $settings, $txt, $boardurl, $scripturl;

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="blockoverview">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-blockoverview'] . '</h3></div><div></div>
		<div id="blocks-overview" class="admintable admin-area windowbg noup">
			<div class="content">';

		$side=array('','left','right','top','center','front','lower','bottom');

		if(allowedTo('tp_blocks') && isset($context['TPortal']['blockoverview']))
		{
			// list by block or by membergroup?
			if(!isset($_GET['grp']))
			{
				foreach($context['TPortal']['blockoverview'] as $block)
				{
					echo '
				<div class="tp_col8">
					<p><a href="' . $scripturl . '?action=tportal&sa=editblock&id='.$block['id'].';' . $context['session_var'] . '=' . $context['session_id'].'" title="'.$txt['tp-edit'].'"><b>' . $block['title'] . '</b></a> ( ' . $txt['tp-blocktype' . $block['type']] . ' | ' . $txt['tp-' .$side[$block['bar']]] . ')</p>
					<hr>
					<div id="tp'.$block['id'].'" style="overflow: hidden;">
						<input type="hidden" value="control" name="' . rand(10000,19999) .'tpblock'.$block['id'].'" />';

					foreach($context['TPmembergroups'] as $grp)
						echo '
						<input type="checkbox" id="tpb' . $block['id'] . '' . $grp['id'].'" value="' . $grp['id'].'" ' , in_array($grp['id'],$block['access']) ? 'checked="checked" ' : '' , ' name="' . rand(10000,19999) .'tpblock'.$block['id'].'" /><label for="tpb' . $block['id'] . '' . $grp['id'].'"> '. $grp['name'].'</label><br>';

					echo '
					</div>
					<br><input id="toggletpb'.$block['id'].'" type="checkbox" onclick="invertAll(this, this.form, \'tpb'.$block['id'].'\');" /><label for="toggletpb'.$block['id'].'">'.$txt['tp-checkall'],'</label><br><br>
				</div>';
				}
			}
		}
		echo '
			</div>
			<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
		</div>
	</form>';
}

// Menu Manager Page
function template_menubox()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

		// is it a single menu?
		if(isset($_GET['mid']))
		{
			$mid=is_numeric($_GET['mid']) ? $_GET['mid'] : 0;
			echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="menuitems">
		<input name="tp_menuid" type="hidden" value="'.$mid.'">
		<div class="cat_bar"><h3 class="catbg">'.$txt['tp-menumanager'].' - '.$context['TPortal']['menus'][$mid]['name'] . '  <a href="' . $scripturl . '?action=tpadmin;sa=addmenu;mid=' , (isset($_GET['mid']) && is_numeric($_GET['mid'])) ? $_GET['mid'] : 0 , '">['.$txt['tp-addmenuitem'].']</a></h3></div>
		<div id="menu-manager" class="admintable admin-area bigger-width">
		<div class="information smalltext">' , $txt['tp-helpmenuitems'] , '</div><div></div>
			<div class="windowbg noup padding-div">
			<table class="table_grid tp_grid" style="width:100%">
				<thead>
					<tr class="title_bar titlebg2">
					<th scope="col" class="menuitems">			
						<div class="font-strong">
							<div style="width:7%;" class="pos float-items">'.$txt['tp-pos'].'</div>
							<div style="width:15%;" class="name float-items">'.$txt['tp-title'].'</div>
							<div style="width:10%;" class="title-admin-area float-items">'.$txt['tp-type'].'</div>
							<div style="width:12%;" class="title-admin-area float-items tpcenter">'.$txt['tp-on'].' '.$txt['tp-off'].' '.$txt['tp-edit'].' </div>
							<div style="width:15%;" class="title-admin-area float-items">'.$txt['tp-item'].'</div>
							<div style="width:18%;" class="title-admin-area float-items">'.$txt['tp-sub_item'].'</div>
							<div style="width:15%;" class="title-admin-area float-items">'.$txt['tp-sitemap_on'].'</div>
							<div style="width:7%;" class="title-admin-area float-items">'.$txt['tp-delete'].' </div>
							<p class="clearthefloat"></p>
						</div>
					</th>
					</tr>
				</thead>
				<tbody>';
			if(!empty($context['TPortal']['menubox'][$mid]))
			{
				$tn=sizeof($context['TPortal']['menubox'][$mid]);
				$n=1;
				foreach($context['TPortal']['menubox'][$mid] as $lbox){
					echo '
					<tr class="windowbg' , $lbox['off']=='0' ? '' : '' , '">
					<td class="blocks">
						<div>
							<div style="width:7%;" class="adm-pos float-items">
								<input name="menu_pos' .$lbox['id']. '" type="text" size="4" value="' . (empty($lbox['subtype']) ? '0' :  $lbox['subtype']) . '">
							</div>
							<div style="width:15%;" class="adm-name float-items">
								<a href="' . $scripturl . '?action=tpadmin;linkedit=' .$lbox['id']. ';' . $context['session_var'] . '=' . $context['session_id'].'">' .$lbox['name']. '</a>
							</div>
							<a href="" class="clickme">'.$txt['tp-more'].'</a>
							<div class="box" style="width:78%;float:left;">
								<div style="width:13%;" class="smalltext fullwidth-on-res-layout float-items">
									<div id="show-on-respnsive-layout"><strong>'.$txt['tp-type'].'</strong></div>';
				if($lbox['type']=='cats')
					echo $txt['tp-category'];
				elseif($lbox['type']=='arti')
					echo $txt['tp-article'];
				elseif($lbox['type']=='head')
					echo $txt['tp-header'];
				elseif($lbox['type']=='spac')
					echo $txt['tp-spacer'];
				elseif($lbox['type']=='menu')
					echo $txt['tp-menu'];
				else
					echo $txt['tp-link'];

				echo '
								</div>
								<div style="width:15%;" class="smalltext fullwidth-on-res-layout float-items tpcenter">
									<div id="show-on-respnsive-layout"><strong>'.$txt['tp-on'].' '.$txt['tp-off'].' '.$txt['tp-edit'].'</strong></div>
									<a href="' . $scripturl . '?action=tpadmin;linkon=' .$lbox['id']. ';mid=' , (isset($_GET['mid']) && is_numeric($_GET['mid'])) ? $_GET['mid'] : 0 , ';' . $context['session_var'] . '=' . $context['session_id'].'"><img title="'.$txt['tp-activate'].'" src="' .$settings['tp_images_url']. '/TPgreen' , $lbox['off']!=0 ? '2' : '' , '.png" alt="'.$txt['tp-activate'].'"  /></a>
									<a href="' . $scripturl . '?action=tpadmin;linkoff=' .$lbox['id']. ';mid=' , (isset($_GET['mid']) && is_numeric($_GET['mid'])) ? $_GET['mid'] : 0 , ';' . $context['session_var'] . '=' . $context['session_id'].'"><img title="'.$txt['tp-deactivate'].'" src="' .$settings['tp_images_url']. '/TPred' , $lbox['off']==0 ? '2' : '' , '.png" alt="'.$txt['tp-deactivate'].'"  /></a>
									<a href="' . $scripturl . '?action=tpadmin;linkedit=' .$lbox['id']. ';mid=' , (isset($_GET['mid']) && is_numeric($_GET['mid'])) ? $_GET['mid'] : 0 , ';' . $context['session_var'] . '=' . $context['session_id'].'"><img title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPconfig_sm.png" alt="'.$txt['tp-edit'].'"  /></a>
								</div>
								<div style="width:19.2%; overflow:hidden;" class="smalltext fullwidth-on-res-layout float-items">
									<div id="show-on-respnsive-layout"><strong>'.$txt['tp-item'].'</strong></div>
									<div id="size-on-respnsive-layout">';
					if($lbox['type']=='cats'){
						// is it a cats ( category)?
						foreach($context['TPortal']['editcats'] as $bmg)
						{
							if($lbox['IDtype']==$bmg['id'])
								echo html_entity_decode($bmg['name']);
						}
					}
					elseif($lbox['type']=='arti'){
						// or a arti (article)?
						foreach($context['TPortal']['edit_articles'] as $bmg)
						{
							if($lbox['IDtype']==$bmg['id'])
								echo html_entity_decode($bmg['subject']);
						}
					}
					elseif($lbox['type']=='head'){
						// or a head (header)?
						echo ' ';
					}
					elseif($lbox['type']=='spac'){
						echo ' ';
					}
                    elseif($lbox['type']=='menu'){
						echo '<span title="'.$lbox['IDtype'].'">'.$lbox['IDtype'].'</span>';
					}
					else{
						// its a link then.
						echo '<span title="'.$lbox['IDtype'].'">'.$lbox['IDtype'].'</span>';
					}

					echo '
									</div>
								</div>
									<div style="width:23%;" class="smalltext fullwidth-on-res-layout float-items">';			      
					if($lbox['type']!=='menu'){
						echo '
										<div id="show-on-respnsive-layout"><strong>'.$txt['tp-sub_item'].'</strong></div>
										<input name="menu_sub' .$lbox['id']. '" type="radio" value="0" ' , $lbox['sub']=='0' ? 'checked' : '' ,'>
										<input name="menu_sub' .$lbox['id']. '" type="radio" value="1" ' , $lbox['sub']=='1' ? 'checked' : '' ,'>
										<input name="menu_sub' .$lbox['id']. '" type="radio" value="2" ' , $lbox['sub']=='2' ? 'checked' : '' ,'>
										<input name="menu_sub' .$lbox['id']. '" type="radio" value="3" ' , $lbox['sub']=='3' ? 'checked' : '' ,'>';
					}
					 else {
						echo '
										<div id="show-on-respnsive-layout">'.$txt['tp-sub_item'].'</div>
										'.$txt['tp-none-'].'';
					}
						echo '			
									</div>
									<div style="width:23%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout"><strong>'.$txt['tp-sitemap_on'].'</strong></div>
										<input name="tp_menu_sitemap' .$lbox['id']. '" type="radio" value="1" ' , in_array($lbox['id'],$context['TPortal']['sitemap']) ? 'checked' : '' ,'>' . $txt['tp-yes'] .'
										<input name="tp_menu_sitemap' .$lbox['id']. '" type="radio" value="0" ' , !in_array($lbox['id'],$context['TPortal']['sitemap']) ? 'checked' : '' ,'> ' . $txt['tp-no'] . '
									</div>
									<div style="width:5%;" class="smalltext fullwidth-on-res-layout float-items">
										<div id="show-on-respnsive-layout"><strong>'.$txt['tp-delete'].'</strong></div>
										<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';linkdelete=' .$lbox['id']. '" onclick="javascript:return confirm(\''.$txt['tp-suremenu'].'\')"><img title="'.$txt['tp-delete'].'" src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /></a>
									</div>
									<p class="clearthefloat"></p>
								</div>
								<p class="clearthefloat"></p>
							</div>
						</td>
						</tr>';
					$n++;
				}
			}
		echo '
				</tbody>
			</table>';
		}

// Menu Manager Page: single menus
		else
		{
			echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="menus">
		<div class="cat_bar"><h3 class="catbg">'.$txt['tp-menumanager'].' <a href="' . $scripturl . '?action=tpadmin;sa=addmenu;fullmenu">['.$txt['tp-addmenu'].']</a></h3></div>
		<div id="single-menus" class="admintable admin-area">
			<div class="windowbg noup padding-div">';

			foreach($context['TPortal']['menus'] as $mbox)
			{
			if($mbox['id']==0)
				echo '
				<table class="table_grid tp_grid" style="width:100%">
				<tbody>
					<tr class="windowbg">
					<td class="menu">
						<div style="padding-div"><br>
							<dl class="settings">
								<dt>
									<strong><i>' . $txt['tp-internalmenu'] . '</i></strong><br>
								</dt>
								<dd>
									<a href="' . $scripturl . '?action=tpadmin;sa=menubox;mid=0"><img height="16" title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPedit.png" alt="'.$txt['tp-edit'].'"  /><strong>' .$txt['tp-edit'].'</strong></a><br>
								</dd>
							</dl>
						</div>
					</td>
					</tr>';
			else
				echo '
					<tr class="windowbg">
					<td class="menu">
						<div style="padding-div"><br>
							<dl class="settings">
								<dt>
									<input name="tp_menu_name' .$mbox['id']. '" type="text" size="40" value="' .$mbox['name']. '"><br>
								</dt>
								<dd>
									<a href="' . $scripturl . '?action=tpadmin;sa=menubox;mid=' .$mbox['id']. '"><img height="16px"; title="'.$txt['tp-edit'].'" src="' .$settings['tp_images_url']. '/TPedit.png" alt="'.$txt['tp-edit'].'"  /><strong> '.$txt['tp-edit'].'</strong></a> &nbsp;&nbsp;&nbsp;
									<a href="' . $scripturl . '?action=tpadmin;' . $context['session_var'] . '=' . $context['session_id'].';linkdelete=' .$mbox['id']. ';fullmenu" onclick="javascript:return confirm(\''.$txt['tp-suremenu'].'\')"><img height="16px" title="'.$txt['tp-delete'].'" src="' .$settings['tp_images_url']. '/TPdelete2.png" alt="'.$txt['tp-delete'].'"  /> <strong>'.$txt['tp-delete'].'</strong></a><br>
								</dd>
							</dl>
						</div>
					</td>
					</tr>';
			}
		}
		echo '
				</tbody>
				</table>
				<div><br>
					<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
				</div>
			</div>
		</div>
	</form>';
}

// Add Menu / Add Menu item Page
function template_addmenu()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

	// Add a Menu item Page
	if(!isset($_GET['fullmenu'])) {
		// Just default this for now...
		$context['TPortal']['editmenuitem']['sub']      = 0;
		$context['TPortal']['editmenuitem']['newlink']  = '0';
		$context['TPortal']['editmenuitem']['type']     = 'cats';
		$context['TPortal']['editmenuitem']['position'] = 'home';
		$context['TPortal']['editmenuitem']['menuicon'] = 'tinyportal/menu_tpmenu.png';

		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadminmenu" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="menuaddsingle">
		<div class="cat_bar"><h3 class="catbg">'.$txt['tp-addmenu'].'</h3></div>
		<input name="newmenu" type="hidden" value="1">
		<input name="tp_menu_menuid" type="hidden" value="' , (isset($_GET['mid']) && is_numeric($_GET['mid'])) ? $_GET['mid'] : 0 , '">';

		template_menucore();
	}

	// Add Menu Page
	else {
		// get the menu ID
		if(isset($_GET['mid']) && is_numeric($_GET['mid']))
			$mid = $_GET['mid'];
		else
			$mid = 0;

		echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="menuadd">
		<div class="cat_bar"><h3 class="catbg">'.$txt['tp-addmenu'].'</h3></div>
		<div id="add-menu" class="admintable admin-area">
			<div class="windowbg noup padding-div">
				<dl class="settings">
					<dt><label for="tp_menu_title"><h4>'.$txt['tp-title'].'</h4><label>
					</dt>
					<dd><input name="tp_menu_title" id="tp_menu_title" type="text" size="40" value=""><br>
					</dd>
				</dl>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
	}
}

// Edit menu item Page
function template_linkmanager()
{
    global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language;

		echo '    
	<form accept-charset="', $context['character_set'], '" name="tpadminmenu" action="' . $scripturl . '?action=tpadmin" method="post">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="singlemenuedit">
		<input name="tpadmin_form_id" type="hidden" value="'.$context['TPortal']['editmenuitem']['id'].'">
		<div class="cat_bar"><h3 class="catbg">'.$txt['tp-editmenu'].'</h3></div>';

    template_menucore();
}

function template_menucore()
{
    global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $forum_version;

    echo'
		<div id="new-item" class="admintable admin-area edit-menu-item">
		<div class="information smalltext">' , $txt['tp-helpmenuitems'] , '</div><div></div>
		<div class="windowbg noup padding-div">
			<dl class="settings">
				<dt>
					<label for="tp_menu_name"><b>'.$txt['tp-title'].'</b></label>
				</dt>
				<dd>
					<input id="tp_menu_name" name="tp_menu_name" type="text" size="40" value="', isset($context['TPortal']['editmenuitem']['name']) ? $context['TPortal']['editmenuitem']['name'] : ''  ,'">
				</dd>
			</dl>	
			<dl class="settings">
				<dt>
					<label for="tp_menu_type"><b>'.$txt['tp-type'].'</b></label>
				</dt>
				<dd>
					<select size="1" name="tp_menu_type" id="tp_menu_type">
						<option value="cats" ', $context['TPortal']['editmenuitem']['type']=='cats' ? 'selected' : '', '>'.$txt['tp-category'].'</option>
						<option value="arti" ', $context['TPortal']['editmenuitem']['type']=='arti' ? 'selected' : '', '>'.$txt['tp-article'].'</option>
						<option value="link" ', $context['TPortal']['editmenuitem']['type']=='link' ? 'selected' : '', '>'.$txt['tp-link'].'</option>
						<option value="head" ', $context['TPortal']['editmenuitem']['type']=='head' ? 'selected' : '', '>'.$txt['tp-header'].'</option>
						<option value="spac" ', $context['TPortal']['editmenuitem']['type']=='spac' ? 'selected' : '', '>'.$txt['tp-spacer'].'</option>';
				// check for menu button in createmenuitem
				if (isset($_GET['mid']))
					{
					if($_GET['mid']==0)
						{
						echo '
                            <option value="menu" ', $context['TPortal']['editmenuitem']['type']=='menu' ? 'selected' : '', '>'.$txt['tp-menu'].'</option>';
						}
					}
				// check for menu button in editmenuitem
				elseif ($context['TPortal']['editmenuitem']['menuID']==0)
					{
					echo '
                            <option value="menu" ', $context['TPortal']['editmenuitem']['type']=='menu' ? 'selected' : '', '>'.$txt['tp-menu'].'</option>';
					}
					echo '
					</select>
				</dd>
			</dl>					
			<hr>
			<dl class="settings">
				<dt>
					<label for="tp_item"><b>'.$txt['tp-item'].'</b></label>
				</dt>
				<dd>';
		// (category)
				echo '
					<select size="1" id="tp_menu_category" name="tp_menu_category" ' , $context['TPortal']['editmenuitem']['type']!='cats' ? '' : '' ,'>';

				if(count($context['TPortal']['editcats'])>0){
					foreach($context['TPortal']['editcats'] as $bmg){
						echo '
						<option value="',  $bmg['id']  ,'"' , $context['TPortal']['editmenuitem']['type'] =='cats' && isset($context['TPortal']['editmenuitem']['IDtype']) && $context['TPortal']['editmenuitem']['IDtype'] == $bmg['id'] ? ' selected' : ''  ,' > '. html_entity_decode($bmg['name']).'</option>';
					}
				}
				else
					echo '
 						<option value=""></option>';

		//  (article)
				echo '  
					</select>
					<select size="1" id="tp_menu_article" name="tp_menu_article" >';

				if(count($context['TPortal']['edit_articles']) > 0 ) {
					foreach($context['TPortal']['edit_articles'] as $bmg){
						echo '
						<option value="', $bmg['id']  ,'"' , $context['TPortal']['editmenuitem']['type'] == 'arti' && $context['TPortal']['editmenuitem']['IDtype'] == $bmg['id'] ? ' selected' : ''  ,'> '.html_entity_decode($bmg['subject']).'</option>';
					}
				}
				else
					echo '
						<option value=""></option>';

				echo '
					</select>
                    <input size="40" id="tp_menu_link" name="tp_menu_link" type="text" value="' , (in_array($context['TPortal']['editmenuitem']['type'], array ('link', 'menu' ))) ? $context['TPortal']['editmenuitem']['IDtype'] : ''  ,'" ' , !in_array($context['TPortal']['editmenuitem']['type'], array( 'link', 'menu' )) ? ' ' : '' ,'>
				</dd>
				<dt>
					<label for="tp_menu_newlink"><b>'.$txt['tp-windowmenu'].'?</b></label>
				</dt>
				<dd>
					<select size="1" name="tp_menu_newlink" id="tp_menu_newlink">
						<option value="0" ', $context['TPortal']['editmenuitem']['newlink'] == '0' ? 'selected' : '', '>'.$txt['tp-nowindowmenu'].'</option>
						<option value="1" ', $context['TPortal']['editmenuitem']['newlink'] == '1' ? 'selected' : '', '>'.$txt['tp-windowmenu'].'</option>
					</select>
				</dd>
				<dt>
					<label for="tp_menu_sub"><b>'.$txt['tp-sub_item'].'</b></label>
				</dt>
				<dd>
					<select size="1" name="tp_menu_sub" id="tp_menu_sub">
						<option value="0" ', $context['TPortal']['editmenuitem']['sub'] == '0' ? 'selected' : '', '>0</option>
						<option value="1" ', $context['TPortal']['editmenuitem']['sub'] == '1' ? 'selected' : '', '>1</option>
						<option value="2" ', $context['TPortal']['editmenuitem']['sub'] == '2' ? 'selected' : '', '>2</option>
						<option value="3" ', $context['TPortal']['editmenuitem']['sub'] == '3' ? 'selected' : '', '>3</option>
					</select>
				</dd>
				<dt>
					<label for="tp_menu_position"><b>'.$txt['tp-menu-after'].'</b></label>
				</dt>
				<dd>
					<select size="1" name="tp_menu_position" id="tp_menu_position">';
					foreach($context['menu_buttons'] as $k => $v ) {
						echo '
						<option value="', $k ,'" ', $context['TPortal']['editmenuitem']['position'] == $k ? 'selected' : '', '>', $v['title'], '</option>';
					}
					echo '
					</select>
				</dd>';
		if(TP_SMF21) { 
			echo '	
				<dt>
					<label for="tp_menu_icon"><b>'.$txt['tp-menu-icon'].'</b><br>
						'.$txt['tp-menu-icon2'].'</label>
				</dt>
				<dd>
					<input name="tp_menu_icon" id="tp_menu_icon" type="text" size="40" value="', isset($context['TPortal']['editmenuitem']['menuicon']) ? $context['TPortal']['editmenuitem']['menuicon'] : ''  ,'">
				</dd>';
		} 
			echo '	
			</dl>
			<div>
				</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';

    $context['insert_after_template'] =
        '<script>
            $(\'#tp_menu_type\').on(\'change\',function(){
                switch($(this).val()){
                    case "link":
                        $("#tp_menu_link").show()
                        $("#tp_menu_newlink").show()
                        $("#tp_menu_category").hide()
                        $("#tp_menu_article").hide()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
                        $(\'label[for="tp_menu_position"]\').hide();
						$(\'label[for="tp_menu_icon"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').show();
                        $(\'label[for="tp_item"]\').show();
                        break;
                    case "menu":
                        $("#tp_menu_link").show()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_category").hide()
                        $("#tp_menu_article").hide()
                        $("#tp_menu_sub").hide()
                        $("#tp_menu_position").show()
						$("#tp_menu_icon").show()
						$(\'label[for="tp_menu_icon"]\').show();
                        $(\'label[for="tp_menu_position"]\').show();
                        $(\'label[for="tp_menu_sub"]\').hide();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').show();
                        break;
                    case "spac":
                        $("#tp_menu_link").hide()
                        $("#tp_menu_category").hide()
                        $("#tp_menu_article").hide()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
						$(\'label[for="tp_menu_icon"]\').hide();
                        $(\'label[for="tp_menu_position"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').hide();
                        break;
                    case "head":
                        $("#tp_menu_link").hide()
                        $("#tp_menu_category").hide()
                        $("#tp_menu_article").hide()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
						$(\'label[for="tp_menu_icon"]\').hide();
                        $(\'label[for="tp_menu_position"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').hide();
                        break;
                    case "cats":
                        $("#tp_menu_link").hide()
                        $("#tp_menu_category").show()
                        $("#tp_menu_article").hide()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
                        $(\'label[for="tp_menu_icon"]\').hide();
						$(\'label[for="tp_menu_position"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').show();
                        break;
                    case "arti":
                        $("#tp_menu_link").hide()
                        $("#tp_menu_category").hide()
                        $("#tp_menu_article").show()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
                        $(\'label[for="tp_menu_icon"]\').hide();
                        $(\'label[for="tp_menu_position"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').show();
                        break;
                    default:
                        $("#tp_menu_link").hide()
                        $("#tp_menu_newlink").hide()
                        $("#tp_menu_category").show()
                        $("#tp_menu_article").show()
                        $("#tp_menu_sub").show()
                        $("#tp_menu_position").hide()
						$("#tp_menu_icon").hide()
                        $(\'label[for="tp_menu_icon"]\').hide();
                        $(\'label[for="tp_menu_position"]\').hide();
                        $(\'label[for="tp_menu_sub"]\').show();
                        $(\'label[for="tp_menu_newlink"]\').hide();
                        $(\'label[for="tp_item"]\').show();
                }
            });
        $(function () {
            $("#tp_menu_type").change();
        });
        </script>';

}

?>
