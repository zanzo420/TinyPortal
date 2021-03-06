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

function template_tp_above()
{
	global $context, $settings;

    // Body responsive classes
    $respClass = '';
	if (isset($context['TPortal'])) {
		$tm2 = '';
		$tm2=explode(",",$context['TPortal']['resp']);
		if (in_array($settings['theme_id'],$tm2)) {
			$respClass = "tp_nonresponsive";
			echo '
			<style>
				/** NON RESPONSIVE THEMES **/
				/** screen smaller then 900px **/
				@media all and (min-width: 0px) and (max-width: 900px) {
					body {
						min-width:900px!important;
					}
				}
			</style>';
		} else {$respClass = "tp_responsive";}
	}

    // Sidebars classes
    $sideclass = '';
	if (isset($context['TPortal']) && ($context['TPortal']['leftpanel']==0 && $context['TPortal']['rightpanel']==1)) {
		$sideclass =  "lrs rightpanelOn";
	} 
	elseif (isset($context['TPortal']) && ($context['TPortal']['leftpanel']==1 && $context['TPortal']['rightpanel']==0)) {
		$sideclass =  "lrs leftpanelOn";
	} 
	elseif (isset($context['TPortal']) && ($context['TPortal']['leftpanel']==1 && $context['TPortal']['rightpanel']==1)) {
		$sideclass =  "lrs lrON";
	} 
	elseif (isset($context['TPortal']) && ($context['TPortal']['leftpanel']==0 && $context['TPortal']['rightpanel']==0)) {
		$sideclass =  "nosides";
	} 
	else {
		$bclass =  "nosides";
	}

	echo '
	<div class="'. $sideclass .' '. $respClass .'">';
	if(!empty($context['TPortal']['upshrinkpanel']) && (!TP_SMF21))
		echo '
		<div class="tp_upshrink20">', $context['TPortal']['upshrinkpanel'] , '</div>';

	if($context['TPortal']['toppanel']==1)
		echo '
		<div id="tptopbarHeader" style="' , in_array('tptopbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , 'clear: both;">
			'	, TPortal_panel('top') , '
			<p class="clearthefloat"></p>
		</div>';

	echo '
		<div id="mainContainer" style="clear: both;">';

	// TinyPortal integrated bars
	if($context['TPortal']['leftpanel']==1) {
		echo '
			<div id="tpleftbarContainer" style="width:' , ($context['TPortal']['leftbar_width']) , 'px; ' , in_array('tpleftbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '" >
				<div id="tpleftbarHeader" style="' , in_array('tpleftbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '">
					' , $context['TPortal']['useroundframepanels']==1 ?
					'<span class="upperframe"><span></span></span>
					<div class="roundframe" style="overflow: auto;">' : ''
					, TPortal_panel('left') ,
					$context['TPortal']['useroundframepanels']==1 ?
					'</div>
					<span class="lowerframe"><span></span></span>' : '' , '
					<p class="clearthefloat"></p>
				</div>
			</div>';
	}
	// TinyPortal integrated bars
	if($context['TPortal']['rightpanel']==1)
	{
		echo '
			<div id="tprightbarContainer" style="width:' ,$context['TPortal']['rightbar_width'], 'px;' , in_array('tprightbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '" >
				<div id="tprightbarHeader" style="' , in_array('tprightbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '">
					' , $context['TPortal']['useroundframepanels']==1 ?
					'<span class="upperframe"><span></span></span>
					<div class="roundframe">' : ''
						, TPortal_panel('right') ,
						$context['TPortal']['useroundframepanels']==1 ?
					'</div>
					<span class="lowerframe"><span></span></span>' : '' , '
					<p class="clearthefloat"></p>
				</div>
			</div>';
	}
	echo '
			<div id="centerContainer">
				<div id="tpcontentHeader">';

	if($context['TPortal']['centerpanel']==1) {
		echo '
					<div id="tpcenterbarHeader" style="' , in_array('tpcenterbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '">
						' , TPortal_panel('center') , '
						<p class="clearthefloat"></p>
					</div>';
    }
	echo '
                </div><!--tpcontentHeader-->';
}

function template_tp_below()
{
	global $context;

	if($context['TPortal']['lowerpanel']==1)
		echo '
				<div id="tplowerbarHeader" style="' , in_array('tplowerbarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '">
					' , TPortal_panel('lower') , '
					<p class="clearthefloat"></p>
				</div>';
// end centerContainer
	echo '
			</div>';
// end mainContainer
	echo '
			<p class="clearthefloat" style="padding:0px;margin:0px;"></p>
		</div>';

	if($context['TPortal']['bottompanel']==1)
		echo '
		<div id="tpbottombarHeader" style="clear: both;' , in_array('tpbottombarHeader',$context['tp_panels']) && $context['TPortal']['showcollapse']==1 ? 'display: none;' : '' , '">
			' , TPortal_panel('bottom') , '
			<p class="clearthefloat"></p>
		</div>';
	echo '
	</div>';
}

// Edit Block Page (including settings per block type)
function template_editblock()
{
	global $context, $settings, $txt, $scripturl, $boardurl;

	$newtitle = html_entity_decode(TPgetlangOption($context['TPortal']['blockedit']['lang'], $context['user']['language']));
	if(empty($newtitle)) {
		$newtitle = html_entity_decode($context['TPortal']['blockedit']['title']);
	}

	echo '
	<form accept-charset="', $context['character_set'], '" name="tpadmin_news" enctype="multipart/form-data" action="' . $scripturl . '?action=tpadmin" method="post" onsubmit="submitonce(this);">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="blockedit">
		<input name="tpadmin_form_id" type="hidden" value="' . $context['TPortal']['blockedit']['id'] . '">
		<div class="cat_bar"><h3 class="catbg">' . $txt['tp-editblock'] . '</h3></div>
		<div id="editblock" class="admintable admin-area">
			<div class="windowbg noup padding-div">
				<div class="formtable">
					<dl class="settings">
						<dt>
							<b><label for="field_name">', $txt['tp-status'], '<img style="margin:0 1ex;" src="' . $settings['tp_images_url'] . '/TP' , $context['TPortal']['blockedit']['off']==0 ? 'green' : 'red' , '.png" alt="" /></label></b>
						</dt>
						<dd>
							<input type="radio" value="0" name="tp_block_off"',$context['TPortal']['blockedit']['off']==0 ? ' checked="checked"' : '' ,' />'.$txt['tp-on'].'
							<input type="radio" value="1" name="tp_block_off"',$context['TPortal']['blockedit']['off']==1 ? ' checked="checked"' : '' ,' />'.$txt['tp-off'].'
						</dd>
					</dl>
					<dl class="settings">
						<dt><label for="tp_block_title"><b>'.$txt['tp-title'].'</b></label></dt>
						<dd>
							<input size=60 name="tp_block_title" id="tp_block_title" type="text" value="' .$newtitle. '"><br><br>
						</dd>
						<dt><label for="tp_block_type"><b>',$txt['tp-type'].'</b></label></dt>
						<dd>
							<select size="1" onchange="document.getElementById(\'blocknotice\').style.display=\'\';" name="tp_block_type" id="tp_block_type">
								<option value="0"' ,$context['TPortal']['blockedit']['type']=='0' ? ' selected' : '' , '>', $txt['tp-blocktype0'] , '</option>
								<option value="18"' ,$context['TPortal']['blockedit']['type']=='18' ? ' selected' : '' , '>', $txt['tp-blocktype18'] , '</option>
								<option value="19"' ,$context['TPortal']['blockedit']['type']=='19' ? ' selected' : '' , '>', $txt['tp-blocktype19'] , '</option>
								<option value="14"' ,$context['TPortal']['blockedit']['type']=='14' ? ' selected' : '' , '>', $txt['tp-blocktype14'] , '</option>
								<option value="5"' ,$context['TPortal']['blockedit']['type']=='5' ? ' selected' : '' , '>', $txt['tp-blocktype5'] , '</option>
								<option value="11"' ,$context['TPortal']['blockedit']['type']=='11' ? ' selected' : '' , '>', $txt['tp-blocktype11'] , '</option>
								<option value="10"' ,$context['TPortal']['blockedit']['type']=='10' ? ' selected' : '' , '>', $txt['tp-blocktype10'] , '</option>
								<option value="9"' ,$context['TPortal']['blockedit']['type']=='9' ? ' selected' : '' , '>', $txt['tp-blocktype9'] , '</option>
								<option value="2"' ,$context['TPortal']['blockedit']['type']=='2' ? ' selected' : '' , '>', $txt['tp-blocktype2'] , '</option>
								<option value="6"' ,$context['TPortal']['blockedit']['type']=='6' ? ' selected' : '' , '>', $txt['tp-blocktype6'] , '</option>
								<option value="12"' ,$context['TPortal']['blockedit']['type']=='12' ? ' selected' : '' , '>', $txt['tp-blocktype12'] , '</option>
								<option value="15"' ,$context['TPortal']['blockedit']['type']=='15' ? ' selected' : '' , '>', $txt['tp-blocktype15'] , '</option>
								<option value="4"' ,$context['TPortal']['blockedit']['type']=='4' ? ' selected' : '' , '>', $txt['tp-blocktype4'] , '</option>
								<option value="16"' ,$context['TPortal']['blockedit']['type']=='16' ? ' selected' : '' , '>', $txt['tp-blocktype16'] , '</option>
								<option value="13"' ,$context['TPortal']['blockedit']['type']=='13' ? ' selected' : '' , '>', $txt['tp-blocktype13'] , '</option>
								<option value="3"' ,$context['TPortal']['blockedit']['type']=='3' ? ' selected' : '' , '>', $txt['tp-blocktype3'] , '</option>
								<option value="7"' ,$context['TPortal']['blockedit']['type']=='7' ? ' selected' : '' , '>', $txt['tp-blocktype7'] , '</option>
								<option value="1"' ,$context['TPortal']['blockedit']['type']=='1' ? ' selected' : '' , '>', $txt['tp-blocktype1'] , '</option>
								<option value="20"' ,$context['TPortal']['blockedit']['type']=='20' ? ' selected' : '' , '>', $txt['tp-blocktype20'] , '</option>';
		// theme hooks
		if(function_exists('ctheme_tp_blocks'))
		{
			ctheme_tp_blocks('listblocktypes');
		}
		echo '
							</select>
						</dd>
						<dt>
							<br><div class="padding-div"><input type="submit" class="button button_submit" value="' . $txt['tp-send'] . '" /></div>
						</dt>
						<dd>
							<div>
								<div id="blocknotice" class="smallpadding error middletext" style="display: none;">' , $txt['tp-blocknotice'] , '</div>
							</div>
						</dd>
					</dl>
					<div class="windowbg2 padding-div">
					 <div>';
// Block types: 5 (BBC code), 10 (PHP Code) and 11 (HTML & Javascript code)
			if($context['TPortal']['blockedit']['type']=='5' || $context['TPortal']['blockedit']['type']=='10' || $context['TPortal']['blockedit']['type']=='11')
			{
				if($context['TPortal']['blockedit']['type']=='11')
				{
					echo '</div><hr><div><b>',$txt['tp-body'],'</b> <br><textarea style="width: 94%;" name="tp_block_body" id="tp_block_body" rows="15" cols="40" wrap="auto">' , $context['TPortal']['blockedit']['body'], '</textarea>';
				}
				elseif($context['TPortal']['blockedit']['type']=='5')
				{
						echo '
						</div><hr><div>';
					TP_bbcbox($context['TPortal']['editor_id']);
				}
				else
						echo '<hr><b>'.$txt['tp-body'].'</b>';
				if($context['TPortal']['blockedit']['type']=='10')
				{
					echo '
						</div><div>
						<textarea style="width: 94%; margin: 0px 0px 10px;" name="tp_block_body" id="tp_block_body" rows="15" cols="40" wrap="auto">' ,  $context['TPortal']['blockedit']['body'] , '</textarea>
						<p><div class="tborder" style=""><p style="padding: 0 0 5px 0; margin: 0;">' , $txt['tp-blockcodes'] , ':</p>
							<select name="tp_blockcode" id="tp_blockcode" size="8" style="margin-bottom: 5px; width: 94%" onchange="changeSnippet(this.selectedIndex);">
								<option value="0" selected="selected">' , $txt['tp-none-'] , '</option>';
					if(!empty($context['TPortal']['blockcodes']))
					{
						foreach($context['TPortal']['blockcodes'] as $bc)
							echo '
								<option value="' , $bc['file'] , '">' , $bc['name'] , '</option>';
					}
					echo '
							</select>
							<p style="padding: 10px 0 10px 0; margin: 0;"><input type="button" value="' , $txt['tp-insert'] , '" name="blockcode_save" onclick="submit();" />
							<input type="checkbox" value="' . $context['TPortal']['blockedit']['id'] . '" name="blockcode_overwrite" /> ' , $txt['tp-blockcodes_overwrite'] , '</p>
						</div>
					<div id="blockcodeinfo" class="description" >&nbsp;</div>
					<script type="text/javascript"><!-- // --><![CDATA[
						function changeSnippet(indx)
						{
							var snipp = new Array();
							var snippAuthor = new Array();
							var snippTitle = new Array();
							snipp[0] = "";
							snippAuthor[0] = "";
							snippTitle[0] = "";';
					$count=1;
					foreach($context['TPortal']['blockcodes'] as $bc)
					{
						$what = str_replace(array(",",".","/","\n"),array("&#44;","&#46;","&#47;",""), $bc['text']);
						echo '
							snipp[' . $count . '] = "<div>' . $what . '</div>";
							snippTitle[' . $count . '] = "<h3 style=\"margin: 0 0 5px 0; padding: 0;\">' . $bc['name'].' <span style=\"font-weight: normal;\">' . $txt['tp-by'] . '</span> ' . $bc['author'] . '</h3>";
							';
							$count++;
					}
					echo '
							setInnerHTML(document.getElementById("blockcodeinfo"), snippTitle[indx] + snipp[indx]);
						}
					// ]]></script>';
				}
			}
// Block types: Recent Topics
			elseif($context['TPortal']['blockedit']['type']=='12'){
				if(!is_numeric($context['TPortal']['blockedit']['body']))
					$context['TPortal']['blockedit']['body']=10;
				echo '
					<hr>
					<dl class="settings">
						<dt>'.$txt['tp-numberofrecenttopics'].'</dt>
						<dd>
							<input style="width: 50px;" name="tp_block_body" value="' .$context['TPortal']['blockedit']['body']. '">
						</dd>
						<dt>'.$txt['tp-recentboards'].'</dt>
						<dd>
							<input name="tp_block_var2" size="20" type="text" value="' , $context['TPortal']['blockedit']['var2'] ,'">
						</dd>';
				echo '
						<dt>'.$txt['tp-recentincexc'].'</dt>
						<dd>
							<input name="tp_block_var3" type="radio" value="1" ' , ($context['TPortal']['blockedit']['var3']=='1' || $context['TPortal']['blockedit']['var3']=='') ? ' checked' : '' ,'> Include boards<br>
							<input name="tp_block_var3" type="radio" value="0" ' , $context['TPortal']['blockedit']['var3']=='0' ? 'checked' : '' ,'> Exclude boards
						</dd>
						<dt>' . $txt['tp-rssblock-showavatar'].'</dt>
						<dd>
							<input name="tp_block_var1" type="radio" value="1" ' , ($context['TPortal']['blockedit']['var1']=='1' || $context['TPortal']['blockedit']['var1']=='') ? ' checked' : '' ,'>'.$txt['tp-yes'].'
							<input name="tp_block_var1" type="radio" value="0" ' , $context['TPortal']['blockedit']['var1']=='0' ? ' checked' : '' ,'>'.$txt['tp-no'].'
						</dd>
					</dl>';
			}
// Block type: SSI functions
			elseif($context['TPortal']['blockedit']['type']=='13'){
				if(!in_array($context['TPortal']['blockedit']['body'],array('recentpoll','toppoll','topposters','topboards','topreplies','topviews','calendar')))
					$context['TPortal']['blockedit']['body']='';
						echo '
						</div><div>';
						echo '
						<hr><dl class="settings">
						<dt>'.$txt['tp-showssibox'].'</dt>
						<dd>
							<input name="tp_block_body" type="radio" value="" ' , $context['TPortal']['blockedit']['body']=='' ? 'checked' : '' , '> ' .$txt['tp-none-']. '<br>
							<input name="tp_block_body" id="tp_block_body1" type="radio" value="topboards" ' , $context['TPortal']['blockedit']['body']=='topboards' ? 'checked' : '' , '><label for="tp_block_body1"> '.$txt['tp-ssi-topboards']. '</label><br>
							<input name="tp_block_body" id="tp_block_body2" type="radio" value="topposters" ' , $context['TPortal']['blockedit']['body']=='topposters' ? 'checked' : '' , '><label for="tp_block_body2"> '.$txt['tp-ssi-topposters']. '</label><br>
							<input name="tp_block_body" id="tp_block_body3" type="radio" value="topreplies" ' , $context['TPortal']['blockedit']['body']=='topreplies' ? 'checked' : '' , '><label for="tp_block_body3"> '.$txt['tp-ssi-topreplies']. '</label><br>
							<input name="tp_block_body" id="tp_block_body4" type="radio" value="topviews" ' , $context['TPortal']['blockedit']['body']=='topviews' ? 'checked' : '' , '><label for="tp_block_body4"> '.$txt['tp-ssi-topviews']. '</label><br>
							<input name="tp_block_body" id="tp_block_body5" type="radio" value="calendar" ' , $context['TPortal']['blockedit']['body']=='calendar' ? 'checked' : '' , '><label for="tp_block_body5"> '.$txt['tp-ssi-calendar']. '</label><br>
						</dd>
					</dl>';
			}
// Block type: TP module
			elseif($context['TPortal']['blockedit']['type']=='20'){
                if(isset($context['TPortal']['tpblocks']['blockrender'])) {
					echo '
						</div><div>
						<hr><dl class="settings">
						<dt>'.$txt['tp-showmodulebox'].'</dt>
						<dd>';

				    foreach($context['TPortal']['tpblocks']['blockrender'] as $tpm) {
					    echo '
						    <input name="tp_block_var1" type="radio" value="' . $tpm['id'] . '" ' , $context['TPortal']['blockedit']['var1']==$tpm['id'] ? 'checked' : '' , '>'.$tpm['name'].'<br>';
					}

					echo '
						</dd>
					</dl>'; 
                }
			}
// Block type: Article / Download functions
			elseif($context['TPortal']['blockedit']['type']=='14'){
				// Module block...choose module and module ID , check if module is active
						echo '
						</div><div>
						<hr><dl class="settings">
						<dt>'.$txt['tp-showstatsbox'].'</dt>
						<dd>
							<input name="tp_block_body" type="radio" value="" ' , $context['TPortal']['blockedit']['body']=='' ? 'checked' : '' , '> ' .$txt['tp-none-']. '<br>
							<input name="tp_block_body" id="tp_block_body1" type="radio" value="dl-stats" ' , $context['TPortal']['blockedit']['body']=='dl-stats' ? 'checked' : '' , '><label for="tp_block_body1"> '.$txt['tp-module1'].'</label><br>
							<input name="tp_block_body" id="tp_block_body2" type="radio" value="dl-stats2" ' , $context['TPortal']['blockedit']['body']=='dl-stats2' ? 'checked' : '' , '><label for="tp_block_body2"> '.$txt['tp-module2'].'</label><br>
							<input name="tp_block_body" id="tp_block_body3" type="radio" value="dl-stats3" ' , $context['TPortal']['blockedit']['body']=='dl-stats3' ? 'checked' : '' , '><label for="tp_block_body3"> '.$txt['tp-module3'].'</label><br>
							<input name="tp_block_body" id="tp_block_body4" type="radio" value="dl-stats4" ' , $context['TPortal']['blockedit']['body']=='dl-stats4' ? 'checked' : '' , '><label for="tp_block_body4"> '.$txt['tp-module4'].'</label><br>
							<input name="tp_block_body" id="tp_block_body5" type="radio" value="dl-stats5" ' , $context['TPortal']['blockedit']['body']=='dl-stats5' ? 'checked' : '' , '><label for="tp_block_body5"> '.$txt['tp-module5'].'</label><br>
							<input name="tp_block_body" id="tp_block_body6" type="radio" value="dl-stats6" ' , $context['TPortal']['blockedit']['body']=='dl-stats6' ? 'checked' : '' , '><label for="tp_block_body6"> '.$txt['tp-module6'].'</label><br>
							<input name="tp_block_body" id="tp_block_body7" type="radio" value="dl-stats7" ' , $context['TPortal']['blockedit']['body']=='dl-stats7' ? 'checked' : '' , '><label for="tp_block_body7"> '.$txt['tp-module7'].'</label><br>
							<input name="tp_block_body" id="tp_block_body8" type="radio" value="dl-stats8" ' , $context['TPortal']['blockedit']['body']=='dl-stats8' ? 'checked' : '' , '><label for="tp_block_body8"> '.$txt['tp-module8'].'</label><br>
							<input name="tp_block_body" id="tp_block_body9" type="radio" value="dl-stats9" ' , $context['TPortal']['blockedit']['body']=='dl-stats9' ? 'checked' : '' , '><label for="tp_block_body9"> '.$txt['tp-module9'].'</label><br>
						</dd>
					</dl>';
			}
// Block type: Stats
			elseif($context['TPortal']['blockedit']['type']=='3'){
				echo '
					</div><div>
					<hr><dl class="settings">
						<dt>'.$txt['tp-showuserbox'].'</dt>';
				if(isset($context['TPortal']['userbox']['avatar']) && $context['TPortal']['userbox']['avatar'])
					echo '<input name="tp_userbox_options0" type="hidden" value="avatar">';
				if(isset($context['TPortal']['userbox']['logged']) && $context['TPortal']['userbox']['logged'])
					echo '<input name="tp_userbox_options1" type="hidden" value="logged">';
				if(isset($context['TPortal']['userbox']['time']) && $context['TPortal']['userbox']['time'])
					echo '<input name="tp_userbox_options2" type="hidden" value="time">';
				if(isset($context['TPortal']['userbox']['unread']) && $context['TPortal']['userbox']['unread'])
					echo '<input name="tp_userbox_options3" type="hidden" value="unread">';
				echo '	<dd>
							<input name="tp_userbox_options4" id="tp_userbox_options4" type="checkbox" value="stats" ', (isset($context['TPortal']['userbox']['stats']) && $context['TPortal']['userbox']['stats']) ? 'checked' : '' , '><label for="tp_userbox_options4"> '.$txt['tp-userbox5'].'</label><br>
							<input name="tp_userbox_options5" id="tp_userbox_options5" type="checkbox" value="online" ', (isset($context['TPortal']['userbox']['online']) && $context['TPortal']['userbox']['online']) ? 'checked' : '' , '><label for="tp_userbox_options5"> '.$txt['tp-userbox6'].'</label><br>
							<input name="tp_userbox_options6" id="tp_userbox_options6" type="checkbox" value="stats_all" ', (isset($context['TPortal']['userbox']['stats_all']) && $context['TPortal']['userbox']['stats_all']) ? 'checked' : '' , '><label for="tp_userbox_options6"> '.$txt['tp-userbox7'].'</label>
						</dd>
					</dl>';
			}
// Block type: User
			elseif($context['TPortal']['blockedit']['type']=='1'){
				echo '
					</div><div>
					<hr><dl class="settings">
						<dt>'. $txt['tp-showuserbox2'].'</dt>
						<dd>
							<input name="tp_userbox_options0" id="tp_userbox_options0" type="checkbox" value="avatar" ', (isset($context['TPortal']['userbox']['avatar']) && $context['TPortal']['userbox']['avatar']) ? 'checked' : '' , '><label for="tp_userbox_options0"> '.$txt['tp-userbox1'].'</label><br>
							<input name="tp_userbox_options1" id="tp_userbox_options1" type="checkbox" value="logged" ', (isset($context['TPortal']['userbox']['logged']) && $context['TPortal']['userbox']['logged']) ? 'checked' : '' , '><label for="tp_userbox_options1"> '.$txt['tp-userbox2'].'</label><br>
							<input name="tp_userbox_options2" id="tp_userbox_options2" type="checkbox" value="time" ', (isset($context['TPortal']['userbox']['time']) && $context['TPortal']['userbox']['time']) ? 'checked' : '' , '><label for="tp_userbox_options2"> '.$txt['tp-userbox3'].'</label><br>
							<input name="tp_userbox_options3" id="tp_userbox_options3" type="checkbox" value="unread" ', (isset($context['TPortal']['userbox']['unread']) && $context['TPortal']['userbox']['unread']) ? 'checked' : '' , '><label for="tp_userbox_options3"> '.$txt['tp-userbox4'].'</label><br>
						</dd>
					</dl>';
				if(isset($context['TPortal']['userbox']['stats']) && $context['TPortal']['userbox']['stats'])
					echo '<input name="tp_userbox_options4" type="hidden" value="stats">';
				if(isset($context['TPortal']['userbox']['online']) && $context['TPortal']['userbox']['online'])
					echo '<input name="tp_userbox_options5" type="hidden" value="online">';
				if(isset($context['TPortal']['userbox']['stats_all']) && $context['TPortal']['userbox']['stats_all'])
					echo '<input name="tp_userbox_options6" type="hidden" value="stats_all">';
			}
// Block type: RSS
			elseif($context['TPortal']['blockedit']['type']=='15'){
				echo '
					<hr><dl class="settings">
						<dt>' .	$txt['tp-rssblock'] . '</dt>
						<dd>
							<input style="width: 95%" name="tp_block_body" value="' .$context['TPortal']['blockedit']['body']. '">
						</dd>
						<dt>' , $txt['tp-rssblock-useutf8'].'</dt>
						<dd>
							<input name="tp_block_var1" type="radio" value="1" ' , $context['TPortal']['blockedit']['var1']=='1' ? ' checked' : '' ,'>'.$txt['tp-utf8'].'<br>
							<input name="tp_block_var1" type="radio" value="0" ' , ($context['TPortal']['blockedit']['var1']=='0' || $context['TPortal']['blockedit']['var1']=='') ? ' checked' : '' ,'>'.$txt['tp-iso'].'
						</dd>
						<dt>' . $txt['tp-rssblock-showonlytitle'].'</dt>
						<dd>
							<input name="tp_block_var2" type="radio" value="1" ' , $context['TPortal']['blockedit']['var2']=='1' ? ' checked' : '' ,'>'.$txt['tp-yes'].'
							<input name="tp_block_var2" type="radio" value="0" ' , ($context['TPortal']['blockedit']['var2']=='0' || $context['TPortal']['blockedit']['var2']=='') ? ' checked' : '' ,'>'.$txt['tp-no'], '
						</dd>
						<dt>' . $txt['tp-rssblock-maxwidth'].'</dt>
						<dd>
							<input name="tp_block_var3" type="text" value="' , $context['TPortal']['blockedit']['var3'],'">
						</dd>
						<dt>' . $txt['tp-rssblock-maxshown'].'</dt>
						<dd>
							<input name="tp_block_var4" type="text" value="' , $context['TPortal']['blockedit']['var4'],'">
						</dd>
					</dl>
				</div>';
			}
// Block type: Sitemap
			elseif($context['TPortal']['blockedit']['type']=='16'){
				echo '
					</div><div>
					<hr><dl class="settings">
						<dt>'.$txt['tp-sitemapmodules'].'<ul class="disc"></dt>
						<dd>';
				if($context['TPortal']['show_download']=='1')
					echo '<li>&nbsp;'.$txt['tp-dldownloads'].'</li>';
				echo '</ul>
						</dd>
					</dl>';
			}
// Block type: Single Article
			elseif($context['TPortal']['blockedit']['type']=='18'){
				// check to see if it is numeric
				if(!is_numeric($context['TPortal']['blockedit']['body']))
					$lblock['body']='';
				echo '
					</div><div>
					<hr><dl class="settings">
						<dt>',$txt['tp-showarticle'],'</dt>
						<dd>
							<select name="tp_block_body">';
				foreach($context['TPortal']['edit_articles'] as $art => $article ){
					echo '<option value="'.$article['id'].'" ' , $context['TPortal']['blockedit']['body']==$article['id'] ? ' selected="selected"' : '' ,' >'.html_entity_decode($article['subject']).'</option>';
				}
				echo '</select>
						</dd>
					</dl>';
			}
// Block type: Themes
			elseif($context['TPortal']['blockedit']['type']=='7') {
				// get the ids
				$myt=array();
				$thems=explode(",",$context['TPortal']['blockedit']['body']);
				foreach($thems as $g => $gh)
				{
					$wh=explode("|",$gh);
					$myt[]=$wh[0];
				}
					echo '
						<hr><input type="hidden" name="blockbody' .$context['TPortal']['blockedit']['id']. '" value="' .$context['TPortal']['blockedit']['body'] . '" />
						<div style="padding: 5px;">
							<div style="max-height: 25em; overflow: auto;">
							<input name="tp_theme-1" type="hidden" value="-1">
							<input type="hidden" value="1" name="tp_tpath-1">';
				foreach($context['TPthemes'] as $tema)
				{
					if(TP_SMF21) {
						echo '
							<img class="theme_icon" alt="*" src="'.$tema['path'].'/thumbnail.png" /> <input name="tp_theme'.$tema['id'].'" type="checkbox" value="'.$tema['name'].'"';
						}
					else {
						echo '
							<img class="theme_icon" alt="*" src="'.$tema['path'].'/thumbnail.gif" /> <input name="tp_theme'.$tema['id'].'" type="checkbox" value="'.$tema['name'].'"';
						}
					if(in_array($tema['id'],$myt))
						echo ' checked';
					echo '>'.$tema['name'].'<input type="hidden" value="'.$tema['path'].'" name="tp_path'.$tema['id'].'"><br>';
				}
				echo '
					</div>
					<br>
					<input type="checkbox" onclick="invertAll(this, this.form, \'tp_theme\');" /> '.$txt['tp-checkall'],'
				';
			}
// Block type: Articles in a Category
			elseif($context['TPortal']['blockedit']['type']=='19') {
				if(!is_numeric($context['TPortal']['blockedit']['body']))
					$lblock['body']='';
				if(!is_numeric($context['TPortal']['blockedit']['var1']))
					$lblock['var1']='15';
				if($context['TPortal']['blockedit']['var1']=='0')
					$lblock['var1']='15';
				echo '
					<hr><dl class="settings">
						<dt>'.$txt['tp-showcategory'].'</dt>
						<dd>
							<select name="tp_block_body">';
				foreach($context['TPortal']['catnames'] as $cat => $catname){
					echo '
								<option value="'.$cat.'" ' , $context['TPortal']['blockedit']['body']==$cat ? ' selected' : '' ,' >'.html_entity_decode($catname).'</option>';
				}
				echo '
							</select>
						</dd>
						<dt>'.$txt['tp-catboxheight'].'</dt>
						<dd>
							<input name="tp_block_var1" size="4" type="text" value="' , $context['TPortal']['blockedit']['var1'] ,'"> em
						</dd>
						<dt>'.$txt['tp-catboxauthor'].'</dt>
						<dd>
							<input name="tp_block_var2" type="radio" value="1" ' , $context['TPortal']['blockedit']['var2']=='1' ? 'checked' : '' ,'> ', $txt['tp-yes'], '<br>
							<input name="tp_block_var2" type="radio" value="0" ' , $context['TPortal']['blockedit']['var2']=='0' ? 'checked' : '' ,'> ', $txt['tp-no'], '
						</dd>
					</dl>';
			}
// Block type: Menu
			elseif($context['TPortal']['blockedit']['type']=='9') {
				if(!is_numeric($context['TPortal']['blockedit']['body']))
					$lblock['body']='0';
				echo '
					<hr><dl class="settings">
						<dt>',$txt['tp-showmenus'],'</dt>
						<dd>
							<select name="tp_block_body">';
				foreach($context['TPortal']['menus'] as $men){
					echo '
						<option value="'.$men['id'].'" ' , $context['TPortal']['blockedit']['body']==$men['id'] ? ' selected' : '' ,' >'.$men['name'].'</option>';
				}
				echo '
					</select>
						</dd>
						<dt>',$txt['tp-showmenustyle'],' </dt>
						<dd>
							<input name="tp_block_var1" type="radio" value="0" ' , ($context['TPortal']['blockedit']['var1']=='' || $context['TPortal']['blockedit']['var1']=='0') ? ' checked' : '' ,' > <img src="'.$settings['tp_images_url'].'/TPdivider2.png" alt="" /><br>
							<input name="tp_block_var1" type="radio" value="1" ' , ($context['TPortal']['blockedit']['var1']=='1') ? ' checked' : '' ,' > <img src="'.$settings['tp_images_url'].'/bullet3.png" alt="" /><br>
							<input name="tp_block_var1" type="radio" value="2" ' , ($context['TPortal']['blockedit']['var1']=='2') ? ' checked' : '' ,' > '.$txt['tp-none-'].'
						</dd>
					</dl>';
			}
// Block type: Online
			elseif($context['TPortal']['blockedit']['type']=='6') {
				echo '
					<hr><dl class="settings">
						<dt>'.$txt['tp-rssblock-showavatar'].'</dt>
						<dd>
							<input name="tp_block_var1" type="radio" value="1" ' , ($context['TPortal']['blockedit']['var1']=='1' || $context['TPortal']['blockedit']['var1']=='') ? ' checked' : '' ,'>'.$txt['tp-yes'].' <input name="tp_block_var1" type="radio" value="0" ' , $context['TPortal']['blockedit']['var1']=='0' ? ' checked' : '' ,'>'.$txt['tp-no'].'
						</dd>
					</dl>';
			}
			// theme hooks
			elseif($context['TPortal']['blockedit']['type']>'50' && function_exists('ctheme_tp_blocks'))
			{
				ctheme_tp_blocks('blockoptions');
			}
			else
				echo '
			</div><div>';
			echo '
					</div>
				</div>
				<div><hr>
					<div><a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-blockstylehelpdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>'.$txt['tp-blockstylehelp'].'<br>
					</div>				
					<br><input name="tp_block_var5" id="tp_block_var5" type="radio" value="99" ' , $context['TPortal']['blockedit']['var5']=='99' ? 'checked' : '' , '><span' , $context['TPortal']['blockedit']['var5']=='99' ? ' style="color: red;">' : '><label for="tp_block_var5">' , $txt['tp-blocksusepaneltyle'] , '</label></span>
				<div>
				<div class="panels-optionsbg">';
			if(function_exists('ctheme_tp_getblockstyles'))
				$types = ctheme_tp_getblockstyles();
			if(TP_SMF21)
				$types = tp_getblockstyles21();
			else
				$types = tp_getblockstyles();
			foreach($types as $blo => $bl)
				echo '
					<div class="panels-options">
						<div>
							<input name="tp_block_var5" id="tp_block_var5'.$blo.'" type="radio" value="'.$blo.'" ' , $context['TPortal']['blockedit']['var5']==$blo ? 'checked' : '' , '><label for="tp_block_var5'.$blo.'"><span' , $context['TPortal']['blockedit']['var5']==$blo ? ' style="color: red;">' : '>' , $bl['class'] , '</span></label>
						</div>
						' . $bl['code_title_left'] . 'title'. $bl['code_title_right'].'
						' . $bl['code_top'] . 'body' . $bl['code_bottom'] . '
					</div>';
			echo '
						</div>
					</div>
				</div>
				<br>
					<dl class="settings">
						<dt>'.$txt['tp-blockframehelp'].'</dt>
						<dd>
							<input name="tp_block_frame" id="useframe" type="radio" value="theme" ' , $context['TPortal']['blockedit']['frame']=='theme' ? 'checked' : '' , '><label for="useframe"> '.$txt['tp-useframe'].'</label><br>
							<input name="tp_block_frame" id="useframe2" type="radio" value="frame" ' , $context['TPortal']['blockedit']['frame']=='frame' ? 'checked' : '' , '><label for="useframe2"> '.$txt['tp-useframe2'].' </label><br>
							<input name="tp_block_frame" id="usetitle" type="radio" value="title" ' , $context['TPortal']['blockedit']['frame']=='title' ? 'checked' : '' , '><label for="usetitle"> '.$txt['tp-usetitle'].' </label></br>
							<input name="tp_block_frame" id="noframe" type="radio" value="none" ' , $context['TPortal']['blockedit']['frame']=='none' ? 'checked' : '' , '><label for="noframe"> '.$txt['tp-noframe'].'</label>
						</dd>
					</dl>
					<br>
					<dl class="settings">
						<dt> '.$txt['tp-allowupshrink'].' </dt>
						<dd>
							<input name="tp_block_visible" id="allowupshrink" type="radio" value="1" ' , ($context['TPortal']['blockedit']['visible']=='' || $context['TPortal']['blockedit']['visible']=='1') ? 'checked' : '' , '><label for="allowupshrink"> '.$txt['tp-allowupshrink'].'</label><br>
							<input name="tp_block_visible" id="notallowupshrink" type="radio" value="0" ' , ($context['TPortal']['blockedit']['visible']=='0') ? 'checked' : '' , '><label for="notallowupshrink"> '.$txt['tp-notallowupshrink'].'</label>
						</dd>
					</dl>
					<br>
					<dl class="settings">
						<dt><a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-membergrouphelpdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a> '.$txt['tp-membergrouphelp'].'</dt>
						<dd><div>
							  <div class="tp_largelist">';
			// loop through and set membergroups
			$tg=explode(',',$context['TPortal']['blockedit']['access']);
			if( !empty($context['TPmembergroups']))
			{
				foreach($context['TPmembergroups'] as $mg)
				{
					if($mg['posts']=='-1' && $mg['id']!='1'){
						echo '<input name="tp_group'.$mg['id'].'" id="tp_group'.$mg['id'].'" type="checkbox" value="'.$context['TPortal']['blockedit']['id'].'"';
						if(in_array($mg['id'],$tg))
							echo ' checked';
						echo '><label for="tp_group'.$mg['id'].'"> '.$mg['name'].'</label><br>';
					}
				}
			}
			// if none is chosen, have a control value
			echo '
							</div>
								<input id="checkallmg" type="checkbox" onclick="invertAll(this, this.form, \'tp_group\');" /><label for="checkallmg">'.$txt['tp-checkall'].'</label><br>
							</div>
						</dd>
					</dl>';
			//edit membergroups
			echo '
					<dl class="settings">
						<dt><a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-editgrouphelpdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>'.$txt['tp-editgrouphelp'].'</dt>
						<dd>
							<div>
								<div class="tp_largelist">';
			$tg=explode(',',$context['TPortal']['blockedit']['editgroups']);
			foreach($context['TPmembergroups'] as $mg){
				if($mg['posts']=='-1' && $mg['id']!='1' && $mg['id']!='-1' && $mg['id']!='0'){
					echo '<input name="tp_editgroup'.$mg['id'].'" id="tp_editgroup'.$mg['id'].'" type="checkbox" value="'.$context['TPortal']['blockedit']['id'].'"';
					if(in_array($mg['id'],$tg))
						echo ' checked';
					echo '><label for="tp_editgroup'.$mg['id'].'"> '.$mg['name'].'</label><br>';
				}
			}
			// if none is chosen, have a control value
			echo '				</div><input id="checkalleditmg" type="checkbox" onclick="invertAll(this, this.form, \'tp_editgroup\');" /><label for="checkalleditmg">'.$txt['tp-checkall'];
			echo '				</label><br>
							</div>
						</dd>
					</dl>
					<dl class="settings">
						<dt><a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-langhelpdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a><label for="field_name">'.$txt['tp-langhelp'].'</label></dt>
						<dd>
							<div>';
			foreach($context['TPortal']['langfiles'] as $langlist => $lang){
				if($lang!=$context['user']['language'] && $lang!='')
					echo '<input size="50" name="tp_lang_'.$lang.'" type="text" value="' , !empty($context['TPortal']['blockedit']['langfiles'][$lang]) ? html_entity_decode($context['TPortal']['blockedit']['langfiles'][$lang], ENT_QUOTES) : html_entity_decode($context['TPortal']['blockedit']['title'],ENT_QUOTES) , '"> '. $lang.'<br>';
			}
			echo '			</div>
						<br></dd>
						<dt><a href="', $scripturl, '?action=helpadmin;help=',$txt['tp-langdesc'],'" onclick=' . ((!TP_SMF21) ? '"return reqWin(this.href);"' : '"return reqOverlayDiv(this.href);"') . '><span class="tptooltip" title="', $txt['help'], '"></span></a>' . $txt['tp-lang'] . '';
				// alert if the settings is off, supply link if allowed
				if(empty($context['TPortal']['uselangoption']))
				{
					echo '
					<br><span class="error">', $txt['tp-uselangoption2'] , ' ' , allowedTo('tp_settings') ? '<a href="'.$scripturl.'?action=tpadmin;sa=settings#uselangoption">&nbsp;['. $txt['tp-settings'] .']&nbsp;</a>' : '' , '</span>';
				}						
				echo '
					</dt>
					<dd>';
				$a=1;
				foreach($context['TPortal']['langfiles'] as $bb => $lang)
				{
					echo '
							<input type="checkbox" name="langtype' . $a . '" id="langtype' . $a . '" value="'.$lang.'" ' , in_array($lang, $context['TPortal']['blockedit']['access2']['lang']) ? 'checked="checked"' : '' , '><label for="langtype' . $a . '"> '.$lang.'</label><br>';
					$a++;
				}
				echo ' </dd>
					</dl>
				</div>';
		if($context['TPortal']['blockedit']['bar']!=4)
		{
			// extended visible options
				echo '
					<div class="admintable">
						<div>'.$txt['tp-access2help'].'</div>
						<div id="collapse-options">
						', tp_hidepanel('blockopts', true) , '
				' , empty($context['TPortal']['blockedit']['access22']) ? '<div class="tborder error" style="margin: 1em 0; padding: 4px 4px 4px 20px;">' . $txt['tp-noaccess'] . '</div>' : '' , '
						<fieldset class="tborder" id="blockopts" ' , in_array('blockopts',$context['tp_panels']) ? ' style="display: none;"' : '' , '>
						<input type="hidden" name="TPadmin_blocks_vo" value="'.$mg['id'].'" />';
				if(!empty($context['TPortal']['return_url']))
					echo '
							<input type="hidden" name="fromblockpost" value="'.$context['TPortal']['return_url'].'" />';
					echo '
					<dl class="settings">
						<dt><b>' . $txt['tp-actions'] . '</b></dt>
						<dd>
							<div>
								<input name="actiontype1" id="actiontype1" type="checkbox" value="allpages" ' ,in_array('allpages',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype1"> '.$txt['tp-allpages'].'</label><br><br>
								<input name="actiontype2" id="actiontype2" type="checkbox" value="frontpage" ' ,in_array('frontpage',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype2"> '.$txt['tp-frontpage'].'</label><br>
								<input name="actiontype3" id="actiontype3" type="checkbox" value="forumall" ' ,in_array('forumall',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype3"> '.$txt['tp-forumall'].'</label><br>
								<input name="actiontype4" id="actiontype4" type="checkbox" value="forum" ' ,in_array('forum',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype4"> '.$txt['tp-forumfront'].'</label><br>
								<input name="actiontype5" id="actiontype5" type="checkbox" value="recent" ' ,in_array('recent',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype5"> '.$txt['tp-recent'].'</label><br>
								<input name="actiontype6" id="actiontype6" type="checkbox" value="unread" ' ,in_array('unread',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype6"> '.$txt['tp-unread'].'</label><br>
								<input name="actiontype7" id="actiontype7" type="checkbox" value="unreadreplies" ' ,in_array('unreadreplies',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype7"> '.$txt['tp-unreadreplies'].'</label><br>
								<input name="actiontype8" id="actiontype8" type="checkbox" value="profile" ' ,in_array('profile',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype8"> '.$txt['profile'].'</label><br>
								<input name="actiontype9" id="actiontype9" type="checkbox" value="pm" ' ,in_array('pm',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype9"> '.$txt['pm_short'].'</label><br>
								<input name="actiontype10" id="actiontype10" type="checkbox" value="calendar" ' ,in_array('calendar',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype10"> '.$txt['calendar'].'</label><br>
								<input name="actiontype11" id="actiontype11" type="checkbox" value="admin" ' ,in_array('admin',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype11"> '.$txt['admin'].'</label><br>
								<input name="actiontype12" id="actiontype12" type="checkbox" value="login" ' ,in_array('login',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype12"> '.$txt['login'].'</label><br>
								<input name="actiontype13" id="actiontype13" type="checkbox" value="logout" ' ,in_array('logout',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype13"> '.$txt['logout'].'</label><br>
								<input name="actiontype14" id="actiontype14" type="checkbox" value="register" ' ,in_array('register',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype14"> '.$txt['register'].'</label><br>
								<input name="actiontype15" id="actiontype15" type="checkbox" value="post" ' ,in_array('post',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype15"> '.$txt['post'].'</label><br>
								<input name="actiontype16" id="actiontype16" type="checkbox" value="stats" ' ,in_array('stats',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype16"> '.$txt['tp-stats'].'</label><br>
								<input name="actiontype17" id="actiontype17" type="checkbox" value="search" ' ,in_array('search',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype17"> '.$txt['search'].'</label><br>
								<input name="actiontype18" id="actiontype18" type="checkbox" value="mlist" ' ,in_array('mlist',$context['TPortal']['blockedit']['access2']['action']) ? 'checked="checked"' : '' , '><label for="actiontype18"> '.$txt['tp-memberlist'].'</label><br><br>';
					// add the custom ones you added
					$count=19;
					foreach($context['TPortal']['blockedit']['access2']['action'] as $po => $p) {
						if(!in_array($p, array('allpages','frontpage','forumall','forum','recent','unread','unreadreplies','profile','pm','calendar','admin','login','logout','register','post','stats','search','mlist')))
						{
							echo '<input name="actiontype'.$count.'" id="actiontype'.$count.'" type="checkbox" value="'.$p.'" checked="checked"><label for="name="actiontype'.$count.'">'.$p.'</label><br>';
							$count++;
						}
					}
					echo '
							<p><label for="custotype0">'.$txt['tp-customactions'].'</label></p>
								<input style="width: 90%;" type="text" name="custotype0" id="custotype0" value="">
								</div>
							</dd>
					</dl>
					<dl class="settings">
						<dt><b>' . $txt['tp-boards'] . '</b></dt>
						<dd>
							<div class="tp_largelist">';
				$a=1;
				if(!empty($context['TPortal']['boards']))
				{
					echo '<input type="checkbox" name="boardtype' , $a, '" value="-1" id="allboards" ' , in_array('-1', $context['TPortal']['blockedit']['access2']['board']) ? 'checked="checked"' : '' , '><label for="allboards"> '.$txt['tp-allboards'].'</label><br><br>';
					$a++;
					foreach($context['TPortal']['boards'] as $bb)
					{
						echo '
								<input type="checkbox" name="boardtype' , $a, '" id="boardtype' , $a, '" value="'.$bb['id'].'" ' , in_array($bb['id'], $context['TPortal']['blockedit']['access2']['board']) ? 'checked="checked"' : '' , '><label for="boardtype' , $a, '"> '.$bb['name'].'</label><br>';
						$a++;
					}
				}
				echo '
							 </div>
						</dd>
					</dl>
					<dl class="settings">
						<dt><b>' . $txt['tp-articles'] . '</b></dt>
						<dd>
							 <div class="tp_largelist">';
				$a=1;
				foreach($context['TPortal']['edit_articles'] as $bb)
				{
					echo '
								<input type="checkbox" name="articletype' , $a , '" id="articletype' , $a , '" value="'.$bb['id'].'" ' ,in_array($bb['id'], $context['TPortal']['blockedit']['access2']['page']) ? 'checked="checked"' : '' , '><label for="articletype' , $a , '"> '.html_entity_decode($bb['subject'],ENT_QUOTES).'</label><br>';
					$a++;
				}
				// if none is chosen, have a control value
				echo '</div><input type="checkbox" id="togglearticle" onclick="invertAll(this, this.form, \'articletype\');" /><label for="togglearticle">'.$txt['tp-checkall'];
				echo '</label><br>
						</dd>
					</dl>
					<dl class="settings">
						<dt><b>' . $txt['tp-artcat'] . '</b></dt>
						<dd>
						    <div class="tp_largelist">';
				$a=1;
				if(isset($context['TPortal']['article_categories']))
				{
					foreach($context['TPortal']['article_categories'] as $bb)
					{
						echo '
								<input type="checkbox" name="categorytype' . $a . '" id="categorytype' . $a . '" value="'.$bb['id'].'" ' , in_array($bb['id'], $context['TPortal']['blockedit']['access2']['cat']) ? 'checked="checked"' : '' , '><label for="categorytype' . $a . '"> '.$bb['name'].'</label><br>';
						$a++;
					}
				}
				// if none is chosen, have a control value
				echo '</div><input type="checkbox" id="togglecat" onclick="invertAll(this, this.form, \'categorytype\');" /><label for="togglecat">'.$txt['tp-checkall'];
				echo '</label><br>
						</dd>
					</dl>
					<dl class="settings">
						<dt><b>' . $txt['tp-dlmanager'] . '</b></dt>
						<dd>
							<div class="tp_largelist">';
				$a=1;
				if(!empty($context['TPortal']['dlcats']))
				{
					$a++;
					foreach($context['TPortal']['dlcats'] as $bb)
					{
						echo '
								<input type="checkbox" name="dlcattype' , $a, '" id="dlcattype' , $a, '" value="'.$bb['id'].'" ' , in_array($bb['id'], $context['TPortal']['blockedit']['access2']['dlcat']) ? 'checked="checked"' : '' , '><label for="dlcattype' , $a, '"> '.$bb['name'].'</label><br>';
						$a++;
					}
				}
				// if none is chosen, have a control value
				echo '		</div><input id="toggledlcat" type="checkbox" onclick="invertAll(this, this.form, \'dlcattype\');" /><label for="toggledlcat">'.$txt['tp-checkall'];
				echo '</label<br>
						</dd>
					</dl>
				</fieldset>
				</div>
			</div>';
		}
			echo '
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			</div>
		</div>
	</form>';
}
?>
