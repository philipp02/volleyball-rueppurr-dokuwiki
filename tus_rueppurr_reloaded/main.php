<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
/**
 * DokuWiki New Day Template
 *
 * You should leave the doctype at the very top - It should
 * always be the very first line of a document.
 *
 * @link   http://www.dokuwiki.org/template:a_new_day
 * @link   http://blog.chirripo.nl
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Riccardo Govoni <battlehorse@gmail.com>
 * @author Louis Wolf <louiswolf@chirripo.nl>
 *
 */

// Must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

// Include functions that provide sidebar functionality
@require_once(dirname(__FILE__).'/tplfn_sidebar.php');

include(DOKU_TPLINC.'tpl_functions.php');
include(DOKU_TPLINC.'tpl_actions.php');

// Include translations of the template strings
@require_once(dirname(__FILE__).'/lang/'.tpl_translation($conf['lang']).'/settings.php');

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang']?>"
 lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction']?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>

  <link rel="shortcut icon" href="<?php echo DOKU_TPL?>images/favicon.ico" />

  <?php /*old includehook*/ @include(dirname(__FILE__).'/meta.html')?>

	<script src="<?php echo DOKU_TPL ?>js/prototype.js" type="text/javascript"></script>
	<script src="<?php echo DOKU_TPL ?>js/scriptaculous.js" type="text/javascript"></script>
</head>

<body>
<?php /*old includehook*/ @include(dirname(__FILE__).'/topheader.html')?>
<div class="dokuwiki">
  <?php html_msgarea()?>

  <?php if( check_namespace("halle") == true ) { ?>
    <div class="imagerow_halle"></div>
  <?php } else if( check_namespace("beach") == true ) { ?>
    <div class="imagerow_beach"></div>
  <?php } else { ?>
    <div class="imagerow"></div>
  <?php } ?>

  <div class="stylehead">

    <div class="header">
<!--      <div class="pagename">
        [[<?php tpl_link(wl($ID,'do=backlink'),tpl_pagetitle($ID,true))?>]]
      </div> -->
      <div class="title">
        <?php tpl_link(wl(),"TuS R&uuml;ppurr - Volleyball",'name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[ALT+H]"')?>
      </div>
      <a href="http://www.tus-rueppurr.de">
        <div class="logo">
        </div>
      </a>
      <div class="clearer"></div>
    </div>
    
    <?php if( tpl_hide_menu() == false ) { ?>
    <div class="menu_top">
    	<div id="tpl_simple_navi">
        <?php tpl_topbar() ?>
    	</div>
    </div>
    <?php } ?>

    <?php /*old includehook*/ @include(dirname(__FILE__).'/header.html')?>

    <?php if($conf['breadcrumbs']){?>
    <div class="breadcrumbs">
      <div style="float:left">
      <?php tpl_breadcrumbs()?>
      </div>
      <div style="float:right;text-align:right">
      <?php tpl_searchform() ?>
      </div>
      <?php //tpl_youarehere() //(some people prefer this)?>
    </div>
    <?php }?>

    <?php if($conf['youarehere']){?>
    <div class="bread_upper_dark"></div>
    <div class="breadcrumbs">
      <?php tpl_youarehere() ?>
    </div>
    <div class="bread_lower_dark"></div>
    <div class="bread_lower_medium"></div>
    <div class="bread_lower_light"></div>
    <?php }?>

  </div>
  <?php flush()?>

  <?php /*old includehook*/ @include(dirname(__FILE__).'/pageheader.html')?>

<div class="sideandpage" >
  <?php if( tpl_hide_sidebar() == false ) { ?>
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr valign="top">
    
    <td width="1%"><div class="sideleft">

      <div class="userbar" >
        <div id="sidebarActionTableId">
          <div class="smallpadding">
            <div id="sidebar">
              <?php tpl_sidebar() ?>
            </div>
          </div>
        </div>
      </div>

      <br/>
		 
      <?php if($INFO['perm'] > AUTH_READ){ ?>		
      <div class="userbar">
        <div id="sidebarActionTableId">
          <div class="smallpadding">
            <div class="userbarheading">
              Bearbeiten
            </div> 
          </div>
        </div>
        
        <?php if (tpl_getConf('btl_hide_page_actions') == 0) { ?>
        <div id="pageActionTableId" <?php action_group_status('page'); ?> >
          <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr><td>
              <?php if (is_action_enabled('edit')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('edit')?></div>
                </td></tr><tr><td>
              <?php } ?>
              <?php if (is_action_enabled('history')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('history')?></div>
                </td></tr><tr><td>
              <?php } ?>
              <?php if (is_action_enabled('backlink')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('backlink')?></div>
                </td></tr><tr><td>
              <?php } ?>
            </td></tr>
          </table>
        </div>
        <?php } ?>
                
        <?php if (tpl_getConf('btl_hide_wiki_actions') == 0) { ?>
        <div id="wikiActionTableId" <?php action_group_status('wiki'); ?> >
          <table cellspacing="0" cellpadding="2" border="0" width="100%" >
            <tr><td>
              <?php if (is_action_enabled('index')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('index')?></div></td>
                </tr><tr><td>
              <?php } ?>
              <?php if (is_action_enabled('recent')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('recent')?></div>
                </td></tr><tr><td>
              <?php } ?>
              <?php if (is_action_enabled('admin') && $INFO['perm'] == 255) { ?>
                <div class="smallpadding"><?php tpl_actionlink('admin') ?></div>
                </td></tr><tr><td>
              <?php } ?>
            </td></tr>
          </table>
        </div>
        <?php } ?>
		
		    <div id="sidebarActionTableId">
          <div class="smallpadding">
            <div class="userbarheading">
              Hilfe
            </div> 
          </div>
        </div>
        <div id="wikiActionTableId">
          <table cellspacing="0" cellpadding="2" border="0" width="100%" >
            <tr><td>
              <div class="smallpadding">
                <?php tpl_link(wl('intern:anleitung',''),'Anleitung zum Editieren')?>
              </div>
            </td></tr>
          </table>
        </div>
        
      </div>
      <?php } ?>		

      <br/>
		 
      <div class="userbar">
        <div id="sidebarActionTableId">
          <div class="smallpadding">
            <div class="userbarheading">
              Benutzermen&uuml;
            </div> 
          </div>
        </div>

        <?php if (tpl_getConf('btl_hide_user_actions') == 0) { ?>
        <div id="userActionTableId" <?php action_group_status('user'); ?> >
          <table cellspacing="0" cellpadding="2" border="0" width="100%" >
            <tr><td>
              <?php if ($_SERVER['REMOTE_USER']) { ?>
							  Angemeldet als <?php echo $INFO['userinfo']['name']; ?>.
                </tr></td><tr><td> 
							<?php } ?>
              <?php if (is_action_enabled('profile') && $_SERVER['REMOTE_USER']) { ?>
                <div class="smallpadding"><?php tpl_actionlink('profile') ?></div>
                </td></tr><tr><td>
              <?php } ?>
              <?php if (is_action_enabled('login')) { ?>
                <div class="smallpadding"><?php tpl_actionlink('login')?></div>
                </td></tr><tr><td>
              <?php } ?>
            </td></tr>
          </table>
        </div>
				<?php } ?>
								
      </div>

    </div></td>
  
    <td class="mainright">
      <div class="page">
        <!-- wikipage start -->
        <?php tpl_content()?>
        <!-- wikipage stop -->
      </div>
    </td>
  </tr></table>
  <?php } else { ?>

  <div class="mainfull" >
    <div class="page">
      <!-- wikipage start -->
      <?php tpl_content()?>
      <!-- wikipage stop -->
    </div>
  </div>
  <?php } ?>

  <div class="clearer">&nbsp;</div>
</div>

  <?php flush()?>

  <div class="stylefoot">

    <div class="meta">
      <div class="user">
        <?php tpl_userinfo()?>
      </div>
      <div class="doc">
        <?php tpl_pageinfo()?> &nbsp;
		<span class="doclink">
			&nbsp;
	        <?php tpl_actionlink('top') ?>
		</span>
      </div>
    </div>

   <?php /*old includehook*/ @include(dirname(__FILE__).'/pagefooter.html')?>

    <div class="bar" id="bar__bottom">
       &copy; 2010 TuS R&uuml;ppurr, Abteilung Volleyball -
       <?php tpl_link(wl(':impressum',''),'Impressum')?>
       <?php /*old includehook*/ @include(dirname(__FILE__).'/footer.html')?>
       <div class="clearer"></div>
    </div>
    
  </div>

</div>

<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>
