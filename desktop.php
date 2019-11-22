<?php
	if ( template != 'default' )
		require_once( strPath . 'members/dashboard.php' );

	assignRights( $groupRights ); // by JAMSHED

	$extTemPa = new extTemPa( _tempBaseDir , "desktop.html" );

	$extTemPa->replace_variable( 'PAGE_WIDTH' , '780px' );

	$extTemPa->replace_variable( 'TITLE' , $langStr->giveString('TITLE_DESKTOP') );
	
	//$strQuery = "SELECT * FROM desktop_links WHERE display_on_desktop = '1' AND link_position = 'T' ORDER BY link_order";
	$strQuery = "SELECT url, friendly_name FROM desktop_links WHERE display_on_desktop = '1' AND link_position = 'T' ORDER BY link_order";		//Added by Mubashir Ali[27-05-2010]
	$result = $db->doQuery( $strQuery );

	$extTemPa->select_repeatblock('TOP_LINKS');

	$nLoopTill = sizeof( $result );

	for ( $count = 0; $count < $nLoopTill; $count++ )
	{
		if ( $count > 0 && $count%6 == 0 )
			$blockID = $extTemPa->AddRepeatBlockCopy();
		else
			$blockID = $extTemPa->AddRepeatBlockCopy('ROWBR');

		$extTemPa->replace_variable_repeatblock( $blockID , 'URL' , htmlentities( $result[ $count ]['url'] , ENT_QUOTES ) );
		
		$extTemPa->replace_variable_repeatblock( $blockID , 'LINKNAME' , $result[ $count ]['friendly_name'] );
	}

	$extTemPa->AppendRepatBlock();

	//$strQuery = "SELECT * FROM desktop_links WHERE display_on_desktop = '1' AND link_position = 'L' ORDER BY link_order";
	$strQuery = "SELECT url, friendly_name FROM desktop_links WHERE display_on_desktop = '1' AND link_position = 'L' ORDER BY link_order";		//Added by Mubashir Ali[27-05-2010]
	$result = $db->doQuery( $strQuery );

	$extTemPa->select_repeatblock('LEFT_LINKS');

	$nLoopTill = sizeof( $result );

	for ( $count = 0; $count < $nLoopTill; $count++ )
	{
		$blockID = $extTemPa->AddRepeatBlockCopy();

		$extTemPa->replace_variable_repeatblock( $blockID , 'URL' , htmlentities( $result[ $count ]['url'] , ENT_QUOTES ) );

		$extTemPa->replace_variable_repeatblock( $blockID , 'LINKNAME' , $result[ $count ]['friendly_name'] );
	}

	$extTemPa->AppendRepatBlock();

	$strQuery = "SELECT lgrp_id AS link_group FROM user_link_groups WHERE user_id = '".$auth->get( 'userid')."' ORDER BY sort";
	$resultLGrp = $db->doQuery( $strQuery );

	$cntgroup = sizeof( $resultLGrp );

	$extTemPa->select_repeatblock('BOTTOM_LINKS');

	for ( $grpindx = 0; $grpindx < $cntgroup; $grpindx++ )
	{
		$blockID = $extTemPa->AddRepeatBlockCopy();

		$extTemPa->replace_variable_repeatblock( $blockID , 'GROUP_ID' , $resultLGrp[ $grpindx ]['link_group'] );

		$strQuery = "SELECT lgrp_name FROM link_groups WHERE lgrp_id = '".$resultLGrp[ $grpindx ]['link_group']."'";
		$resultGrpName = $db->doQuery( $strQuery );
		
		$extTemPa->replace_variable_repeatblock( $blockID , 'LINKGROUP' , $resultGrpName[ 0 ]['lgrp_name'] );

		$strQuery = "SELECT url , friendly_name FROM desktop_links WHERE display_on_desktop = '1' AND link_position = 'B' AND link_group = '".$resultLGrp[ $grpindx ]['link_group']."' ORDER BY link_order";
		$result = $db->doQuery( $strQuery );
		
		$nLoopTill = sizeof( $result );

		$links = '<table style="width:880px;" cellpadding="0" border="0" cellspacing="0">
					<tr>';

		for ( $count = 0; $count < $nLoopTill; $count++ )
		{
			$lnBr = '';
			if (($count + 1) % 6 == 0 && $count != ($nLoopTill - 1))
				$lnBr = '</tr><tr>';
			$links .= '<td style="width:146px; height:22px;"><a class="text_grey_9" target="_blank" href="'.htmlentities( $result[ $count ]['url'] , ENT_QUOTES ).'" onClick="window.open(this.href); return false;">' . $result[ $count ]['friendly_name'] . '</a></td>' . $lnBr;
		}
		
		$colspanning = 6 - $nLoopTill;
		
		if ( $colspanning > 0 )
		{
			$colSpanWidth = $colspanning * 146;
			$links .= '<td style="width:'.$colSpanWidth.'px;" colspan="'.$colspanning.'">&nbsp;</td>';
		}
		
		$links .= '</tr></table>';
		
		$extTemPa->replace_variable_repeatblock( $blockID , 'LINKS' , $links );

		if ( !$nLoopTill )
			$extTemPa->replace_block_repeatblock( $blockID , 'LINKGROUP' , '' );
	}

	$extTemPa->AppendRepatBlock();
	
	$extTemPa->replace_variable( 'DATEFORMAT' , dateFormat );
	$extTemPa->replace_variable( 'TODAYSDATE' , $defs->getFormatedDate() );
	
	$extTemPa->replace_variable( 'TODAYSDATETIME' , date( 'F d, Y, g:i a' ) );

	$extTemPa->showTemplate();
?>