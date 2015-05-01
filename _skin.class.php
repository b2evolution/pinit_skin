<?php
/**
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2014 by Emin Ozlen - {@link http://eodepo.com/}
 */

if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * Specific code for this skin.
 *
 * ATTENTION: if you make a new skin you have to change the class name below accordingly
 */
class pinit_Skin extends Skin
{
	var $version = '1.0.1';

	/**
	 * Get default name for the skin.
	 * Note: the admin can customize it.
	 */
	function get_default_name()
	{
		return 'Pinit';
	}


  /**
	 * Get default type for the skin.
	 */
	function get_default_type()
	{
		return 'normal';
	}
/*	function eo_more_link() {
		global $Item;
		echo "moore";
		var_dump($Item);	
	}*/
	function eo_remove_headline($type,$file)
	{
		global $headlines,$rsc_url,$app_version;
		switch ($type) {
			case 'js' :
				$file_uri = url_add_param($rsc_url.'js/'.$file.'.js', 'v='.$app_version );
				$line = '<script type="text/javascript" src="'.$file_uri.'"></script>';
				if(($k = array_search($line, $headlines)) !== false) {
					unset($headlines[$k]);
				}
				break;
			case 'css' :
				$file_uri = url_add_param($rsc_url.'css/'.$file.'.css', 'v='.$app_version );
				$line = '<link rel="stylesheet" type="text/css" href="'.$file_uri.'" />';
				if(($k = array_search($line, $headlines)) !== false) {
					unset($headlines[$k]);
				}
				break;
		}
	}


	/**
	 * Get definitions for editable params
	 *
	 * @see Plugin::GetDefaultSettings()
	 * @param local params like 'for_editing' => true
	 */
	function get_param_definitions( $params )
	{
		$r = array_merge( array(
				'col_type' => array(
					'label' => T_('Columns'),
					'note' => 'Choose # of columns for Large - Medium - Small Devices',
					'defaultvalue' => 'colt1',
					'options' => array( 'colt1' => $this->T_('4-3-2'), 'colt2' => $this->T_('3-2-2'), 'colt3' => $this->T_('2-2-1') ),
					'type' => 'select'
				),
				/*
				'hide_sb_arc' => array(
					'label' => T_('Hide Sidebar on posts display'),
					'note' => T_('Load more posts automatically via ajax'),
					'defaultvalue' => 1,
					'type'	=>	'checkbox',
				),*/
				/*'link_color' => array(
					'label' => T_('Link Color'),
					'note' => T_('Ex: #ff0000 for red'),
					'defaultvalue' => '#ff0000',
					'valid_pattern' => array( 'pattern'=>'¤^(#([a-f0-9]{3}){1,2})?$¤i'),
					'error'=>T_('Invalid color code.'),
				),*/
				'inf_scroll' => array(
					'label' => T_('Infinite Scroll'),
					'note' => T_('Load more posts automatically via ajax'),
					'defaultvalue' => 1,
					'type'	=>	'checkbox',
				),
				'inf_scr_mode' => array(
					'label' => T_('Infinite Scroll Mode'),
					'note' => 'Choose to load more posts automatically or on demand',
					'defaultvalue' => 'manual',
					'options' => array( 'auto' => $this->T_('Auto'), 'manual' => $this->T_('Manual') ),
					'type' => 'select'
				),
				/*'no_foot' => array(
					'label' => T_('Disable Footer'),
					'note' => T_('Check to disable footer (you may particularly want to disable footer if you use infinite scroll)'),
					'defaultvalue' => 0,
					'type'	=>	'checkbox',
				),*/
				/*'colorbox' => array(
					'label' => T_('Colorbox Image Zoom'),
					'note' => T_('Check to enable javascript zooming on images (using the colorbox script)'),
					'defaultvalue' => 1,
					'type'	=>	'checkbox',
				),*/
				'disp_b2_credits' => array(
					'label' => T_('b2evo credits'),
					'note' => T_('Display b2evolution credit links in footer'),
					'defaultvalue' =>1,
					'type'	=>	'checkbox',
				),
			), parent::get_param_definitions( $params )	);

		return $r;
	}
	
	function eo_get_tinyurl($pid=NULL) {
		global $Item;
		$tinyurl = $Item->get_tinyurl();
		return $tinyurl;
		
	}
	function eo_get_featured_image_url($size=NULL) {
		global $Item;
		// Get list of attached files
		$feat_img_uri = false;
		$params = array(
			'before'              => '',
			'before_image'        => '',
			'before_image_legend' => '',
			'after_image_legend'  => '',
			'after_image'         => '',
			'after'               => '',
			'limit'               => 1,
			'image_link_to'       => 'original',
			'before_gallery'      => '',
			'after_gallery'       => '',
			// Optionally restrict to files/images linked to specific position: 'teaser'|'aftermore'
			'restrict_to_image_position' => 'teaser',
		);
		if(isset($size)) $params['image_size'] = $size;
		$post_images = $Item->get_images( $params,'raw' );
		if( ! empty ($post_images) ) {
	
			preg_match('/(src)=("[^"]*")/',$post_images, $img_parts);
			$feat_img_uri = str_replace(array('src="','"','&amp;'),array('','','&'),$img_parts[0]);
		}
		return $feat_img_uri;

		
		/*if( $app_version < 5 ) {
			if( ! $FileList = $Item->get_attachment_FileList(1, 'teaser') )
			{
				return '';
			}
	
			$r = '';
			$File = NULL;
			while( $File = & $FileList->get_next() )
			{
				if( $File->is_image() ) {
					$feat_img_uri = $File->_FileRoot->ads_url . $File->_rdfp_rel_path;
			//		var_dump($feat_img_uri);
				}
			}
		}*/

			
	}

	/**
	 * Get ready for displaying the skin.
	 *
	 * This may register some CSS or JS...
	 */
	function display_init()
	{
		global $Blog, $disp, $Item, $baseurl, $skins_url, $Chapter, $ReqURI,$app_version,$Skin,$skin;
	//	var_dump($headlines);	
	//	$thumbnail_sizes['fit-440x600'] = array('fit',440,600,90);
		// call parent:

		parent::display_init();
		$Skin->arc_disp_arr = array('posts','front','search');
		$Skin->feat_post_typs = array(
			'Intro-Main' => 1500,
			'Intro-Cat' => 1520,
			'Intro-Tag' => 1530,
			'Intro-Sub' => 1570,
			'Intro-All' => 1600,
		);
		
		$Skin->skinuri = $skins_url.$skin.'/';
		$this->curruri = $_SERVER['HTTP_HOST'].$ReqURI;
		//		var_dump($Skin);


	//	$is4x = version_compare( $app_version, '5', '<' ) && version_compare($app_version, '4', ">=");
		$is5x = version_compare( $app_version, '5', '>' );
		$isless5x = version_compare( $app_version, '5', '<' );
	//	var_dump($Blog);
	//	($is5x) ? $blogdesc = $Blog->longdesc : $blogdesc = $Blog->description;
	$blogdesc = $Blog->longdesc;
		if($isless5x) require_css( 'basic.css' );
			$og_uri = $_SERVER['HTTP_HOST'].$ReqURI;
			$this->og_img = false;
			if( isset($Item) && ! empty($Item) ) {
				$id = $Item->ID;
				$og_uri = $baseurl .'index.php?p='.$id.'&amp;blog='.$Blog->ID.'&amp;redir=no';
				$og_desc = $Item->excerpt;
				$og_title = $Item->title;	
				$this->og_img = $this->eo_get_featured_image_url();
			}
			else if ( isset($Chapter) && ! empty($Chapter) ) {
				//if we are on category 
				$og_desc = $Chapter->description;
				$og_title = $Chapter->name;
			}
			else {
				// Just in case, for everything else.	
				$og_desc = $blogdesc;
				$og_title = $Blog->name;
			}
	//	$id = $Item->ID;
		add_headline('<meta property="og:type" content="article">');
		add_headline('<meta property="og:description" content="'.$og_desc.'">');
		add_headline('<meta property="og:url" content="'. $og_uri . '">');
		add_headline('<meta property="og:title" content="'.$og_title.'">');
		if($this->og_img) add_headline('<meta property="og:image" content="'.$this->og_img.'">');

		// Add CSS:
		require_css( 'basic_styles.css', 'blog' ); // the REAL basic styles
		require_css( 'basic.css', 'blog' ); // Basic styles
		require_css( 'blog_base.css', 'blog' ); // Default styles for the blog navigation
		require_css( 'item_base.css', 'blog' ); // Default styles for the post CONTENT


		// Colorbox (a lightweight Lightbox alternative) allows to zoom on images and do slideshows with groups of images:

			require_js_helper( 'colorbox' );

			
	}

}

/*
	Line 1844: 				$disp_param = 'posts';
	Line 1848: 				$disp_param = 'comments';
	Line 1852: 				$disp_param = 'search';
	Line 1856: 				$disp_param = 'arcdir';
	Line 1860: 				$disp_param = 'catdir';
	Line 1864: 				$disp_param = 'postidx';
	Line 1868: 				$disp_param = 'mediaidx';
	Line 1872: 				$disp_param = 'sitemap';
	Line 1876: 				$disp_param = 'msgform';
	Line 1883: 				$disp_param = 'users';
	Line 1893: 				$disp_param = 'threads';
	Line 1897: 				$disp_param = 'contacts';
	Line 1907: 					$disp_param = 'help';
	Line 1985: 		if( !empty( $disp_param ) )
	Line 1987: 			if( $this->get_setting( 'front_disp' ) == $disp_param )
	Line 1993: 				return url_add_param( $this->gen_blogurl(), 'disp='.$disp_param );*/
?>