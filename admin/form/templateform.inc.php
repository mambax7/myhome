<?php
/**
* XOOPS - PHP Content Management System
* Copyright (c) 2001 - 2006 <http://www.xoops.org/>
*
* Module: myhome 2.x
* Licence : GPL
* Authors :
*           - solo (http://www.wolfpackclan.com/wolfactory)
*			- herve (http://www.herve-thouzard.com)
*			- blueteen (http://myxoops.romanais.info)
*			- DuGris (http://www.dugris.info)
*/


        $file_name     = 'myhome_'.$tpl;

        // CSS
        $css_file_name = 'myhome_'.$tpl.'_0.css';
        $css_file = '../../../'.$xoopsModuleConfig['edit_dir'].'cache/'.$css_file_name;
        $css_orig = '../templates/'.$file_name.'/css_0.css';
        if( !file_exists($css_file) && file_exists($css_orig) && $tpl ) { copy( $css_orig, $css_file ); }
        $css = file_exists( $css_file )?myhome_read_file( $css_file ):'';
        $css_is_open = $css?1:0;

        // Script
        $script_file_name = 'myhome_'.$tpl.'_0.js';
        $script_file = '../../../'.$xoopsModuleConfig['edit_dir'].'cache/'.$script_file_name;
        $script_orig = '../templates/'.$file_name.'/js_0.js';
        if(!file_exists($script_file) && file_exists($script_orig) && $tpl) { copy( $script_orig, $script_file ); }
        $script = file_exists( $script_file )?myhome_read_file( $script_file ):'';
        $script_is_open = $script?1:0;

        // Templates
        $sql = " SELECT a.tpl_id, b.tpl_source
                 FROM      " . $xoopsDB->prefix( 'tplfile' ) . " a
                 LEFT JOIN " . $xoopsDB->prefix('tplsource') . " b
                 ON    a.tpl_id = b.tpl_id
                 WHERE a.tpl_tplset='".$xoopsConfig['template_set']."'
                   AND a.tpl_module='".$xoopsModule->dirname()."'
                   AND a.tpl_file= '".$file_name.".html'";

        $result      = $xoopsDB->queryF( $sql );
        $myrow 	     = $xoopsDB->fetchArray( $result );

        if( !$myrow['tpl_id'] ) {
        $tpl_file = '../templates/'.$file_name.'.html';
        $myrow['tpl_source'] = file_exists( $tpl_file )?myhome_read_file( $tpl_file ):'';
        $myrow['tpl_id']='';
        }
        $tpl_is_open = $xoopsConfig['template_set']=='default'||!$myrow['tpl_id']?0:1;

        $formcss    = new XoopsFormTextArea(_MD_MYHOME_CSS . ' :<br />' .$css_file_name, "css", $css, 25);
        $formscript = new XoopsFormTextArea(_MD_MYHOME_SCRIPT . ' :<br />' .$script_file_name, "script", $script, 25);
        $formtpl    = new XoopsFormTextArea(_MD_MYHOME_TPL . ' : ' . $file_name.'.html', "tpl_source", $myrow['tpl_source'], 25);
        if( $xoopsConfig['template_set']=='default'||!$myrow['tpl_id']) { $formtpl->setExtra( 'disabled' ); }

	$submit_button = new XoopsFormButton('', 'submit', _MD_MYHOME_SUBMIT, 'submit');

// Display form
if( $tpl ) {
include('java.help.php');
    $css_drop      = myhome_drop_form( _MD_MYHOME_CSS, _HLP_MYHOME_CSS, _MD_MYHOME_HELP, $css_is_open );
    $script_drop   = myhome_drop_form( _MD_MYHOME_SCRIPT, _HLP_MYHOME_SCRIPT, _MD_MYHOME_HELP, $script_is_open);
    $template_drop = myhome_drop_form( _MD_MYHOME_TPL, _HLP_MYHOME_TPL, _MD_MYHOME_HELP, $tpl_is_open );

    $form->addElement($drop['in']);

	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new XoopsFormHidden("tpl",         $tpl));
	$form->addElement(new XoopsFormHidden("tpl_id",      $myrow['tpl_id']));
	$form->addElement(new XoopsFormHidden("template_set",$xoopsConfig['template_set']));
	$form->addElement(new XoopsFormHidden("css_file",    $css_file));
	$form->addElement(new XoopsFormHidden("script_file", $script_file));
	
     $form -> addElement( $css_drop['in'] );
	$form->addElement($formcss, false);
     $form -> addElement( $css_drop['out'] );
	
     $form -> addElement( $script_drop['in'] );
	$form->addElement($formscript,  false);
     $form -> addElement( $script_drop['out'] );

     $form -> addElement( $template_drop['in'] );
	$form->addElement($formtpl,  false);
     $form -> addElement( $template_drop['out'] );

	$form->addElement($submit_button);
}

?>