<?php

if (!function_exists('cropp'))
{
    function cropp($source, $isAssetWrap = true) 
    {
        return \Yaro\Cropp\Cropp::make($source, $isAssetWrap);
    } // end cropp
}
