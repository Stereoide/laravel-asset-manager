<?php

namespace Stereoide\AssetManager;

use Illuminate\Support\Facades\Facade;

class AssetManagerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'assetmanager';
    }
}