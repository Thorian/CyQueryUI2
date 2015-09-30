<?php
namespace CyQueryUI2;
require_once('interfaces/IIsClickable.php');
require_once('interfaces/IClickListener.php');
require_once('interfaces/IWindowResizeListener.php');
require_once('interfaces/IValidation.php');
require_once ('iCyPostprocessing.php');

require_once('Widget.php');
require_once('widgets/label.php');
require_once('widgets/button.php');
require_once('widgets/LinkButton.php');
require_once('widgets/container.php');
require_once('widgets/WindowSizeController.php');
require_once('widgets/MoveableContainer.php');
require_once('widgets/ResizeableMoveableContainer.php');
//require_once('widgets/TabContainer.php');
require_once('widgets/Dropbox.php');
require_once('widgets/ProgressBar.php');
require_once('widgets/Uploader.php');
require_once('widgets/DirectCallback.php');
//require_once('widgets/SortableContainer.php');
require_once('widgets/CheckBoxContainer.php');
require_once('widgets/input.php');
//require_once('widgets/SelectContainer.php');
require_once('widgets/Image.php');
require_once('widgets/CropableResizeableMoveableImage.php');
require_once('widgets/ColorPicker.php');


/**
 * CyQueryUI 2 is the next generation of the PHP JQueryUI Connector
 * 
 * Why does this exist? I think the main reason is i want intelligence server side and not 
 * want to worry about clientside JS and in addition i want my application state to be persistent
 * even over Browser restarts.
 * 
 * @author Martin Theobald <m.theobald@cyberdom.org>
 *
 */
class CyQueryUI2 implements \iCyPostprocessing{
	public static $changed = array();
	
	
	public static function getChanged() {
		return self::$changed;
	}
	
	
	public static function changed(Widget $wid)  {
		self::$changed[] = $wid;
	}
	
	public function POSTPROCESSING ($html) {
		//<script type="text/javascript">function transferDataX(reason,action,data) {var url = \'/CyQueryUI2.htm?reason=\'+reason+\'&action=\'+action+\'&data=\'+data;$.ajax({url: url,success: function(data,status,xhr) {var type = xhr.getResponseHeader("content-type") || "";if(type == "application/pdf"){return window.open(url);}var objData = $.parseJSON(data);$.each(objData, function(key,obj){$(\'#\'+obj[0]).replaceWith(unescape(obj[1]));});}});}</script>';
		
		$ht = '
	<link type="text/css" href="/modules/CyQueryUI2/ImageCrop/jquery.Jcrop.css" rel="stylesheet" />	
	<link type="text/css" href="/modules/CyQueryUI2/Farbtastic/farbtastic.css" rel="stylesheet" />
	<link type="text/css" href="/modules/CyQueryUI2/FileUpload/fileuploader.css" rel="stylesheet" />
	<link type="text/css" href="/modules/CyQueryUI2/Selectmenue/jquery.ui.selectmenu.css" rel="stylesheet" />	
	<script type="text/javascript" src="/modules/CyQueryUI2/ImageCrop/jquery.Jcrop.min.js"></script>
	<script type="text/javascript" src="/modules/CyQueryUI2/Farbtastic/farbtastic.js"></script>
	<script type="text/javascript" src="/modules/CyQueryUI2/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="/modules/CyQueryUI2/Selectmenue/jquery.ui.selectmenu.js"></script>
	<script type="text/javascript" src="/modules/CyQueryUI2/FileUpload/fileuploader.js"></script>
	<script type="text/javascript">function transferDataX(reason,action,data) {var url = "/CyQueryUI2.htm?reason=" + reason + "&amp;action=" + action + "&amp;data=" + data;$.ajax({url: url,success: function(data,status,xhr) {var type = xhr.getResponseHeader("content-type") || "";if(type == "application/pdf"){return window.open(url);}var objData = $.parseJSON(data);$.each(objData, function(key,obj){$(\'#\'+obj[0]).replaceWith(unescape(obj[1]));});}});}</script>';
	
	$ht .= '<script type="text/javascript">function processDataX(data){$(function() {
	var objData = data;$.each(objData, function(key,obj){$(\'#\'+obj[0]).replaceWith(obj[1]);});	
	});}</script>';
		
	$ht .= '<script type="text/javascript">(function(a){a.fn.toolTip=function(b){var c={background:"#1e2227",color:"#fff",opacity:"0.8"},b=a.extend(c,b);return this.each(function(){var c=a(this);var d=c.attr("title");if(d!=""){var e=a("<div id=\"tooltip\" />");c.attr("title","");c.hover(function(a){e.hide().appendTo("body").html(d).hide().css({"background-color":b.background,color:b.color,opacity:b.opacity}).fadeIn(500)},function(){e.remove()})}c.mousemove(function(a){e.css({top:a.pageY+10,left:a.pageX+20})})})}})(jQuery);$(document).ready(function(){$(".tooltip").toolTip()});</script>';
	$ht .= '<script type="text/javascript">function absorbClick(){var b=true;return (b)?b:false;}</script>';
	
		$html = str_replace('</head>',$ht."\n".'</head>',$html);
		return $html;
	}
	
	
	public function __construct(){
		if(class_exists('\CyShop')) {
			\CyShop::registerSpecialSite('CyQueryUI2','\\CyQueryUI2\\CyShopWidget');
			require_once('CyShopWidget.php');		
		} else {
			session_start();
		}
	}
}



class CyLanguage {
	protected static $data;


	public static function add($lang,$source,$dest) {
		$data[$source][$lang] = $dest;
	}


	public static function translate($text,$lang) {
		if (self::$data[$text][$lang]) return self::$data[$text][$lang];
		return $text;
	}


}

function __($text,$lang="") {
	return CyLanguage::translate($text, $lang);
}