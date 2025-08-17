<?php

namespace App\Services\Uploader;

use Illuminate\Http\UploadedFile;

class StorageManager
{
    public function putFileAsPublic(string $name,UploadedFile $file,string $folder)
    {
        return $this->disk()->putFileAs($folder,$file,$name);
    }

    public function isFileExists(string $name,string $folder) :bool
    {
        return $this->disk()->exists($folder . DIRECTORY_SEPARATOR . $name);
    }

    public function removeFile(string $name,string $folder): bool
    {
        return $this->disk()->delete($folder . DIRECTORY_SEPARATOR . $name);
    }

    private function disk()
    {
        if (config('filesystems.default') == 'liara')
            return \Storage::disk(config('filesystems.default'));
                elseif (config('filesystems.default') == 'local')
            return  \Storage::disk('public');
    }

}
