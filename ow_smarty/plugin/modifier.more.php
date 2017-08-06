<?php

/**
 * EXHIBIT A. Common Public Attribution License Version 1.0
 * The contents of this file are subject to the Common Public Attribution License Version 1.0 (the “License”);
 * you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * http://www.oxwall.org/license. The License is based on the Mozilla Public License Version 1.1
 * but Sections 14 and 15 have been added to cover use of software over a computer network and provide for
 * limited attribution for the Original Developer. In addition, Exhibit A has been modified to be consistent
 * with Exhibit B. Software distributed under the License is distributed on an “AS IS” basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language
 * governing rights and limitations under the License. The Original Code is Oxwall software.
 * The Initial Developer of the Original Code is Oxwall Foundation (http://www.oxwall.org/foundation).
 * All portions of the code written by Oxwall Foundation are Copyright (c) 2011. All Rights Reserved.

 * EXHIBIT B. Attribution Information
 * Attribution Copyright Notice: Copyright 2011 Oxwall Foundation. All rights reserved.
 * Attribution Phrase (not exceeding 10 words): Powered by Oxwall community software
 * Attribution URL: http://www.oxwall.org/
 * Graphic Image as provided in the Covered Code.
 * Display of Attribution Information is required in Larger Works which are defined in the CPAL as a work
 * which combines Covered Code or portions thereof with code not governed by the terms of the CPAL.
 */

/**
 * Smarty truncate modifier.
 *
 * @author Sergey Kambalin <greyexpert@gmail.com>
 * @package ow.ow_smarty.plugin
 * @since 1.0
 */
function smarty_modifier_more( $string, $length )
{
    if( strlen($string) < $length + 50) {
        return $string;
    }

    $new_length = $length;
    $contentPart1 = mb_substr($string, 0, $length);
    if(mb_strpos($contentPart1,'<')!==false){
        if(mb_strrpos($contentPart1,'<')!==mb_strrpos($contentPart1,'</')){
            $lastStarter = mb_strrpos($contentPart1,'<');
            $lastEnder = mb_strpos($string,'</',$lastStarter);
            $lastEnder = mb_strpos($string, '>',$lastEnder);
            $new_length = $lastEnder + 1;
        }
        else
        {
            $lastEnder = mb_strrpos($contentPart1,'</');
            $lastEnder = mb_strpos($string, '>',$lastEnder);
            if($lastEnder+1>$new_length)
                $new_length = $lastEnder+1;
        }
    }
    $length = $new_length;
    $truncated = UTIL_String::truncate($string, $length);
    
    if ( strlen($string) - strlen($truncated) < 50 )
    {
        return $string;
    }
    
    $uniqId = uniqid("more-");
    $seeMoreEmbed = '<a href="javascript://" class="ow_small" onclick="$(\'#' . $uniqId . '\').attr(\'data-collapsed\', 0);">'
            . OW::getLanguage()->text("base", "comments_see_more_label") 
            . '</a>';
    
    return '<span class="ow_more_text" data-collapsed="1" id="' . $uniqId . '">'
            . '<span data-text="full">' . $string . '</span>'
            . '<span data-text="truncated">' . $truncated
            . '... ' . $seeMoreEmbed
            . '</span>'
            . '</span>';
}