<?php
/**
 * configuration-manager metadata for the battlehorse template
 * 
 * @author:     Riccardo "battlehorse" Govoni <battlehorse@gmail.com>
 * @author:	Louis Wolf (Chirripó) <louiswolf@chirripo.nl>
 */

$meta['btl_sidebar_position'] = array('multichoice', '_choices' => array('left','right'));
$meta['btl_sidebar_name'] = array('string', '_pattern' => '#^[a-z]*#' ) ; 
$meta['btl_hide_user_actions'] = array('onoff');
$meta['btl_hide_page_actions'] = array('onoff');
$meta['btl_hide_wiki_actions'] = array('onoff');
$meta['btl_hide_submit_actions'] = array('onoff');
$meta['btl_default_user_actions_status'] = array('multichoice','_choices' => array('open','closed'));
$meta['btl_default_page_actions_status'] = array('multichoice','_choices' => array('open','closed'));
$meta['btl_default_wiki_actions_status'] = array('multichoice','_choices' => array('open','closed'));
$meta['btl_default_submit_actions_status'] = array('multichoice','_choices' => array('open','closed'));
$meta['btl_language'] = array('multichoice', '_choices' => array('cs', 'de','en', 'es', 'fr', 'it','nl','ru')); 

$meta['btl_hide_menu_for_pages'] = array('string', '_pattern' => '#^[a-z]*(,[a-z]+)*#');
$meta['btl_hide_sidebar_for_pages'] = array('string', '_pattern' => '#^[a-z]*(,[a-z]+)*#');
?>
