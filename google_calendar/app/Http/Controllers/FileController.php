<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends AccountController
{
    public static function getStorageGoogleTokenPath($userId, $companyId)
    {
        return 'public/companies/' . $companyId . '/google_calendar/user/' . $userId . '/token.json';
    }

    public static function deleteFileFromStorage($filePath)
    {
        return Storage::delete(str_replace('storage/', 'public/', $filePath));
    }

    public static function deleteDirectoryFromStorage($directoryPath)
    {
        return Storage::deleteDirectory($directoryPath);
    }

}
