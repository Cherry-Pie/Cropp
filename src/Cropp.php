<?php

namespace Yaro\Cropp;

use Intervention\Image\Facades\Image;
use Log;


class Cropp
{
    
    private $source    = '';
    private $fileHash  = '';
    private $extension = '';
    private $methods   = array();
    private $isAssetWrap;
    
    private $isSkip = false;
    
    public function __construct($source, $isAssetWrap = true)
    {
        $this->isAssetWrap = $isAssetWrap;
        
        $source = public_path(ltrim($source, '/'));
        if (!is_readable($source)) {
            $noImageSource = config('cropp.no_image_source', false);
            if (!$noImageSource) {
                $this->isSkip = true;
                return;
            }
            $source = public_path(ltrim($noImageSource, '/'));
        }
        
        $this->fileHash = md5($source);
        
        preg_match('/\.[^\.]+$/i', $source, $matches);
        if (isset($matches[0])) {
            $this->extension = $matches[0];
        }
        $this->source = $source;
    } // end __construct
    
    public static function make($source, $isAssetWrap = true)
    {
        $cropp = get_called_class();
        
        return new $cropp($source, $isAssetWrap);
    } // end make
    
    public function __call($name, $arguments)
    {
        $this->methods[] = compact('name', 'arguments');
        
        return $this;
    } // end __call 
    
    public function __toString()
    {
        return $this->src();
    } // end __toString
    
    public function src()
    {
        if ($this->isSkip) {
            return '';
        }
        
        $quality = config('cropp.cache_quality', 90);
        $cacheStorage = trim(config('cropp.cache_dir', 'storage/cropp'), '/');
        
        $methodsHash = md5(serialize($this->methods));
        $hash = md5($this->fileHash . $methodsHash . $quality);
        
        $source = '/'. $cacheStorage .'/'. $hash . $this->extension;
        if (is_readable(public_path($source))) {
            return $this->isAssetWrap ? asset($source) : $source;
        }
        
        $image = Image::make($this->source);
        
        foreach ($this->methods as $method) {
            call_user_func_array(array($image, $method['name']), $method['arguments']);
        }
        
        try {
            $res = $image->save(public_path($source), $quality);
            if (!$res) {
                throw new \RuntimeException(
                    'Unable to save image cache to ['. public_path($source) .']'
                );
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return '';
        }
        
        return $this->isAssetWrap ? asset($source) : $source;
    } // end src

}
