<?php
	$ttip_WithAnim = false;
	
	function makeNewTemplateLinks($array, $showLinkImage = true, $charsToShow = '', $menuSide = '')
	{
		if (empty($array))
			return '';
		
		global $auth, $ttip_WithAnim;
		
		$links = '';
		
		$nLoopTill = count($array);
		
		for ( $count = 0; $count < $nLoopTill; $count++ )
		{
			$url = htmlentities( $array[ $count ][ 0 ] , ENT_QUOTES );
			
			$friendly_name = $array[ $count ][ 1 ];
			
			$clientside_access = $array[ $count ][ 3 ];
			
			$open_as_newwin = $array[ $count ][ 4 ];
			
			$allow_multi_pages = $array[ $count ][ 5 ];
			
			if ( !strstr( $url , 'index.php' ) || $open_as_newwin )
			{
				$target = ' target="_blank"';
				$openAsTab_class = '';
			}
			else if ($clientside_access)
			{
				$target = ' target="_blank"';
				
				$openAsTab_class = '';
				
				$url = html_entity_decode( $url );
				
				$url_array = parse_url( $url );
				
				require_once strPath . 'functions/myfuncs.php';
				
				$backurl = encrypt( strPath . 'clients/index.php?' . $url_array[ 'query' ] );
				
				$url = 'index.php?module=access&amp;action=giveclientsideaccess&backurl=' . $backurl;
				
			}
			else if ( $allow_multi_pages )
			{
				$target = '';
				$openAsTab_class = 'open_as_tab multi';
			}
			else
			{
				$target = '';
				$openAsTab_class = 'open_as_tab';
			}
			
			if ( $showLinkImage )
			{
				$link_image = $array[ $count ][ 2 ];
				
				if ( empty( $link_image ) )
					$link_image = strPath . 'images/default_icon.png';
				else
				{
					$link_image = getFileUploadPath('NewTemplateLinkImages', 'n') . '/' . $link_image;
					
					if ( !is_file( $link_image ) )
						$link_image = strPath . 'images/default_icon.png';
				}
	
				$linkInner = '<img src="' . $link_image . '" border="none" alt="" class="link_images" />';	
			}
			else
			{
				$adjusted_friendly_name = $friendly_name;
				
				if ( $charsToShow != '' )
					$adjusted_friendly_name = valToShow( $adjusted_friendly_name , $charsToShow );
	
				$linkInner = $adjusted_friendly_name;
			}
			
			$gmail = $auth->get('gmail');

			if (($nLoopTill > 9 && $count >= 8 && $menuSide == 'right')  || ($nLoopTill > 9 && $count >= 8 && $menuSide == 'left'))
			{
				if ($count == 8){
					$links .= '<ul class="flyout flyout_'.$menuSide.'">
								<li><a href="javascript:;"><img src="' . strPath . 'pagelinkimages/Misc.png" border="none" alt="" class="link_images" /></a><ul><li>';
								
					if($gmail != '' && $friendly_name == 'Email'){
						$links .= '<a href="javascript:;" onclick="collapseDock(\''.$menuSide.'\', true); openSSO();">' . $friendly_name . '</a>';
					}else{
						$links .= '<a onclick="collapseDock(\''.$menuSide.'\', true);" href="' . $url . '"' . $target . ' class="' . $openAsTab_class . '">' . $friendly_name . '</a>';
					}		
					
					$links .= '</li>';
				}
				else
				{
				
					if($gmail != '' && $friendly_name == 'Email'){
						$links .= '<li><a href="javascript:;" onclick="collapseDock(\''.$menuSide.'\', true); openSSO();">' . $friendly_name . '</a></li>';
					}else{
						$links .= '<li><a onclick="collapseDock(\''.$menuSide.'\', true);" href="' . $url . '"' . $target . ' class="' . $openAsTab_class . '">' . $friendly_name . '</a></li>';	
					}	
										
				}
				
				if (($count + 1) == $nLoopTill)
					$links .= '</ul></li></ul>';
			}
			else 
			{
				if ($ttip_WithAnim)
				{
					$onmouseover = ' onmouseover="dockLinksTTip(this, \''.$friendly_name.'\');"';
					$dockInnTTip = '';
				}
				else
				{
					$onmouseover = '';
					$dockInnTTip = '<div class="dock_link_ttip"><div class="dock_link_ttip_tline"></div>'.$friendly_name.'</div>';
				}
				
				if ($gmail != '' && $friendly_name == 'Email')
				{
					$links .= '<a href="javascript:;" onclick="collapseDock(\''.$menuSide.'\'); openSSO();"'.$onmouseover.'><img src="'.strPath.'pagelinkimages/e-mail.png" border="0" alt="" class="link_images" />'.$dockInnTTip.'</a>';
				}
				else
				{
					$links .= '<a onclick="collapseDock(\''.$menuSide.'\');" href="'.$url.'"'.$target.' class="'.$openAsTab_class.'"'.$onmouseover.'>'.$linkInner.$dockInnTTip.'</a>';
				}		
			}
			
		}
		
		return $links;
	}

	function makeBottomCatsLinks($arrCatg)
	{
		global $ttip_WithAnim;
		
		$str1 = $str2 = '';

		foreach ( $arrCatg as $key => $val )
		{
			$array = $val;
			
			$str1 .= '<a href="javascript:;" class="abs" id="ctg_' . $key . '">' . $array[ 0 ][ 'catg_title' ] . '</a>';
			
			$str2 .= '<div class="class_bottomlinks wraps ctgLinks" id="ctgLinks_' . $key . '">';
			
			for ( $count = 0; $count < count( $array ); $count++ )
			{
				$friendly_name_adjusted = valToShow( $array[ $count ][ 'friendly_name' ] , 17 );
				
				$moreClass = '';
				
				if ($array[$count]['open_multi_newwin'])
					$moreClass .= ' multi';
				
				if ($ttip_WithAnim)
				{
					$onmouseover = ' onmouseover="dockLinksTTip(this, \''.$array[$count]['friendly_name'].'\');"';
					$dockInnTTip = '';
				}
				else
				{
					$onmouseover = '';
					$dockInnTTip = '<div class="dock_link_ttip"><div class="dock_link_ttip_tline"></div>'.$array[$count]['friendly_name'].'</div>';
				}
				
				$str2 .= '<a onclick="collapseDock(\'bottom\');" href="'.htmlentities($array[$count]['url'], ENT_QUOTES).'" class="dock_links block abs open_as_tab'.$moreClass.'"'.$onmouseover.'>'.$friendly_name_adjusted.$dockInnTTip.'</a>';
			}
			
			$str2 .= '<br class="clear" /></div>';
		}
		
		if ( empty( $str1 ) )
			$str1 = '<a href="javascript:;" class="abs bottomcats_dummy">Dummy</a>';
		
		return '<div class="class_bottomlinks wraps" id="bottom_categories">' . $str1 . '</div>' . $str2;
	}


    include strPath . 'classes/blocked.php';

    $objBlock = new userBlocks();

    $jsExec = '';

    $objBlock->updateBlocks($auth->get('userid'));
//    if($objBlock->isBlocked($auth->get('userid')) && $auth->get('isTestPage') == 1){
    if($objBlock->isBlocked($auth->get('userid'))){
        $userBlocked = true;
        $jsExec = "
        var t = null;
        var a = 'index.php?module=block&action=viewblocks&keepThis=true&TB_iframe=true&hideTBtitle=true&modal=true';
        var g = false;
        tb_show(t,a,g);";

    }else{
        $userBlocked = false;
    }

    $extTemPa = new extTemPa( _tempBaseDir , "desktop.html" );

	$extTemPa->replace_variable( 'TITLE' , 'Dashboard' );
	
	$extTemPa->remove_block( 'BLOCK_JS_HEADER' );
	
	$extTemPa->remove_block( 'BLOCK_WRAPPER_HEADER' );
	
	$extTemPa->remove_block( 'BLOCK_WRAPPER_FOOTER' );
	
	$arrLeftbar = $arrRightbar = $arrTopbar = $arrRemLinks = $arrCatg = array();
	
	$strQuery = "SELECT group_id FROM page_user_groups WHERE user_id = '" . $auth->get( 'userid' ) . "'";
	
	$result = $db->doQuery( $strQuery );
	
	if ($db->affectedrows())
	{
		$groupid = $result[ 0 ][ 'group_id' ];
		
		$strQuery = "
					SELECT
						A.pos ,
						B.friendly_name ,
						B.url ,
						B.image_renamed ,
						B.clientside_access ,
						B.open_as_newwin ,
						B.open_multi_newwin,
						C.catg_id ,
						C.catg_title
					FROM
						page_group_links A
					LEFT JOIN
						page_links B
					ON
						( A.link_id = B.link_id )
					LEFT JOIN
						page_catg C
					ON
						( A.link_id = C.catg_id )
					WHERE
						A.group_id = '$groupid'
					ORDER BY
						A.pos, A.id
					";
					
		$result = $db->doQuery( $strQuery );
		
		$nLoopTill = count( $result );
		
		for ( $count = 0; $count < $nLoopTill; $count++ )
		{
		
			$gmail = $auth->get('gmail');
			
			if($gmail == '' && $result[ $count ][ 'friendly_name' ] == 'Email')
				continue;
				
			$thisArray = array( $result[ $count ][ 'url' ] , $result[ $count ][ 'friendly_name' ] , $result[ $count ][ 'image_renamed' ] , $result[ $count ][ 'clientside_access' ] , $result[ $count ][ 'open_as_newwin' ],  $result[ $count ][ 'open_multi_newwin' ]);
			
			if ( $result[ $count ][ 'pos' ] == 'BC' && $result[ $count ][ 'catg_id' ] != '' )
			{
				$arrCatg[ $result[ $count ][ 'catg_id' ] ] = array();
				
				$arrCatg[ $result[ $count ][ 'catg_id' ] ][ 0 ][ 'catg_title' ] = $result[ $count ][ 'catg_title' ];
				
				$strQuery = "SELECT A.friendly_name , A.url , A.open_multi_newwin FROM page_links A , page_catg_links B WHERE A.link_id = B.link_id AND B.catg_id = '" . $result[ $count ][ 'catg_id' ] . "' ORDER BY B.catglink_id";
				
				$resultBC = $db->doQuery( $strQuery );
				
				for ( $cnt = 0; $cnt < count( $resultBC ); $cnt++ )
				{
					$arrCatg[ $result[ $count ][ 'catg_id' ] ][ $cnt ][ 'url' ] = $resultBC[ $cnt ][ 'url' ];

					$arrCatg[ $result[ $count ][ 'catg_id' ] ][ $cnt ][ 'friendly_name' ] = $resultBC[ $cnt ][ 'friendly_name' ];
					
					$arrCatg[ $result[ $count ][ 'catg_id' ] ][ $cnt ][ 'open_multi_newwin' ] = $resultBC[ $cnt ][ 'open_multi_newwin' ];
				}
			}
			
			switch ( $result[ $count ][ 'pos' ] )
			{
				case 'L':
					$arrLeftbar[] = $thisArray;
					break;
				case 'R':
					$arrRightbar[] = $thisArray;
					break;
				case 'T':
					$arrTopbar[] = $thisArray;
					break;
				case 'RM':
					$arrRemLinks[] = $thisArray;
					break;
				case 'BC':
					break;
				default:
					$homepageurl = $result[ $count ][ 'url' ];
					break;
			}
		}
	}
	else
		$homepageurl = 'index.php?module=reminder&action=showreminderspage';
	
	//$homepageurl = 'index.php?module=lapchiinventory&action=search';
	
	$tmphomepageurl = $store->getQueryString('tmphomepageurl');
	
	if (!empty($tmphomepageurl))
		$homepageurl = urldecode($tmphomepageurl);
	
	$result = $db->doQuery( "SELECT A.group_name FROM page_groups A , page_user_groups B WHERE A.group_id = B.group_id AND B.user_id = '" . $auth->get("userid") . "' LIMIT 0 , 1" );
	
	if ( $db->affectedrows() )
		$group_name = $result[ 0 ][ 'group_name' ];
	else
		$group_name = 'Miscellaneous';
	
	$extTemPa->replace_variable( 'LEFTBAR_LINKS' , makeNewTemplateLinks($arrLeftbar, true, '', 'left') );
	$extTemPa->replace_variable( 'RIGHTBAR_LINKS' , makeNewTemplateLinks($arrRightbar, true, '', 'right') );
	
	$homepageurl = htmlentities( $homepageurl , ENT_QUOTES );
	
	$extTemPa->replace_variable( 'DEFAULT_URL' , $homepageurl );

	$extTemPa->replace_variable( 'HOME_URL' , $homepageurl );
	
	$extTemPa->replace_variable( 'BOTTOM_CATS_LINKS' , makeBottomCatsLinks( $arrCatg ) );
	
	$arrPageLinkImageProperties = array( 'WIDTH' => 61 , 'HEIGHT' => 55 );
	
	$extTemPa->replace_variable( 'LINK_IMGS_WIDTH' , $arrPageLinkImageProperties[ 'WIDTH' ] );
	
	$extTemPa->replace_variable( 'LINK_IMGS_HEIGHT' , $arrPageLinkImageProperties[ 'HEIGHT' ] );
	
	/*$topBarBGimage = strPath . 'images/template1/topbar_bg.png';
	
	$arrImageInfo = getimagesize($topBarBGimage);
	
	$topBarImageWidth = $arrImageInfo[0];
	
	$topBarImageHeight = $arrImageInfo[1];
	
	$extTemPa->replace_variable( 'TOPBAR_BG_IMAGE' , $topBarBGimage );

	$extTemPa->replace_variable( 'TOPBAR_BG_IMAGE_W' , $topBarImageWidth );

	$extTemPa->replace_variable( 'TOPBAR_BG_IMAGE_H' , $topBarImageHeight );*/
	
	parseSSO_link();
	
	if (!$ttip_WithAnim)
	{
		$extTemPa->remove_block('BLK_WZTOOLTIP');
	}

	//$extTemPa->replace_variable( 'RAND_NUM' , getNiceRand() );
	
	/*$strQuery = "SHOW TABLES FROM lapchinew;";
	$result = $db->doQuery($strQuery);
	$nLoop = count($result);
	for ($i = 0; $i < $nLoop; $i++)
	{
		echo $result[$i]['Tables_in_lapchinew']."<br>";
		$strQuery = "SHOW COLUMNS FROM lapchinew.".$result[$i]['Tables_in_lapchinew']."";
		$result2 = $db->doQuery($strQuery);
		$nLoop2 = count($result2);
		for ($c = 0; $c < $nLoop2; $c++)
		{
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$result2[$c]['Field']."<br>";
		}
	}
	exit;*/

    $extTemPa->replace_variable('JSEXEC', $jsExec);
	$extTemPa->showTemplate();
	
	exit;
?>