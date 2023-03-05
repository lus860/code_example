<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends AccountController
{
    /**
     * @param $userId
     * @param $companyId
     * @return string
     */
    public static function getStorageGoogleTokenPath($userId, $companyId)
    {
        return 'public/companies/' . $companyId . '/google_calendar/user/' . $userId . '/token.json';
    }

    /**
     * @param $filePath
     * @return mixed
     */
    public static function deleteFileFromStorage($filePath)
    {
        return Storage::delete(str_replace('storage/', 'public/', $filePath));
    }

    /**
     * @param $directoryPath
     * @return mixed
     */
    public static function deleteDirectoryFromStorage($directoryPath)
    {
        return Storage::deleteDirectory($directoryPath);
    }

}
