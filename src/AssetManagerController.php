<?php

namespace Stereoide\AssetManager;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use AssetManager;
use Stereoide\AssetManager\Models\Folder;
use Stereoide\AssetManager\Models\File;

/**
 * Class AssetManagerController
 * @package Stereoide\AssetManager
 */
class AssetManagerController extends \App\Http\Controllers\Controller
{
    function dummyCF()
    {
        /* Create folders */

        $folder = AssetManager::createFolder(0, 'First', 'no description');

        $folder = AssetManager::createFolder($folder->id, 'Second', 'no description');
        $file = AssetManager::createFile($folder->id, 'C:/Users/j.maske/Dropbox/Camera Uploads/2017-04-15 12.47.50.jpg');

        $folder = AssetManager::createFolder($folder->id, 'Third', 'no description');

        $folder = AssetManager::createFolder($folder->id, 'Fourth', 'no description');
        $folderId = $folder->id;

        $folder = AssetManager::createFolder($folder->id, 'Fifth', 'no description');

        /* Delete folders */

        $folder = AssetManager::getFolder($folderId)->delete();
    }

    function getBaseFolder()
    {
        return Folder::where('folder_id', 0)->first();
    }

    function getDisk()
    {
        /* Determine disk to use */

        $configuredDisk = config('assetmanager.disk');
        $disk = ('' == $configuredDisk ? Storage::disk('local') : Storage::disk($configuredDisk));

        /* Return */

        return $disk;
    }

    function assertPathExists($path)
    {
        AssetManager::getDisk()->makeDirectory($path);
    }

    function getFolder($id = null)
    {
        /* Fetch folder */

        if (is_null($id)) {
            $folder = AssetManager::getBaseFolder();
        } else {
            $folder = Folder::findOrFail($id);
        }

        /* Return */

        return $folder;
    }

    function getFolders($parentId = null)
    {
        /* Fetch folder */

        $folder = AssetManager::getFolder($parentId);

        /* Return folders */

        return $folder->folders;
    }

    function getFiles($parentId = null)
    {
        /* Fetch folder */

        $folder = AssetManager::getFolder($parentId);

        /* Return files */

        return $folder->files;
    }

    function createFolder($parentId, $name, $description = '')
    {
        /* Create folder */

        $folder = Folder::create(['folder_id' => $parentId, 'name' => $name, 'description' => $description]);

        /* Return */

        return $folder;
    }

    function createFile($parentId, $filePath, $name = '', $description = '')
    {
        /* Sanitize parameters */

        if (empty($name)) {
            $name = basename($filePath);
        }

        /* Create file */

        $file = File::create(['folder_id' => $parentId, 'name' => $name, 'filesize' => 0, 'description' => $description, 'mimetype' => 'application/unknown', 'width' => 0, 'height' => 0]);

        /* Attach source file */

        $file->attachSourceFile($filePath);

        /* Update filesize */

        $file->filesize = AssetManager::getDisk()->size($file->fetchPath());
        $file->save();
    }
}
