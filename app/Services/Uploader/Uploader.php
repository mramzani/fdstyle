<?php

namespace App\Services\Uploader;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Uploader
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @var StorageManager $storageManager
     */
    private StorageManager $storageManager;

    /**
     * @param Request $request
     * @param StorageManager $storageManager
     */

    private $file;

    public function __construct(Request $request, StorageManager $storageManager)
    {
        $this->request = $request;
        $this->storageManager = $storageManager;
        $this->file = $request->image;
    }

    /**
     * Upload Logic
     * @throws Exception
     */
    public function upload()
    {
        if ($this->request->hasFile('image') || $this->request->hasFile('file')) {
            return $this->putFileIntoStorage();
        }
        return $this->request->old_image;
    }

    /**
     * @return string|null
     */
    public function multiUpload()
    {
        $images = [];
        if ($this->request->hasFile('image') || $this->request->hasFile('file')) {
            foreach ($this->request->image as $img){
                $name = Str::random(10) . '_' . str_replace(' ','_',$img->getClientOriginalName());
                $this->storageManager->putFileAsPublic($name, $img, $this->getFolderPath());
                $images[] = $name;
            }
            return implode(',',$images);
        }
        return null;
    }

    private function remove(string $name)
    {
        return $this->storageManager->removeFile($name,$this->getFolderPath());
    }

    /**
     * @throws Exception
     */
    private function putFileIntoStorage(): string
    {
        $name = $this->generateFileName();
        if ($this->isFileExists($name)) throw new \Exception('File has exists');
        $this->storageManager->putFileAsPublic($name, $this->file, $this->getFolderPath());
        return $name;
    }

    private function generateFileName(): string
    {
        return Str::random(10) . '_' . $this->file->getClientOriginalName();
    }

    /**
     * @return string|string[]
     */
    private function getFolderPath()
    {
        $paths = [
            'user' => 'users',
            'brand' => 'brands',
            'category' => 'categories',
            'product' => 'products',
            'order' => 'orders',
            'banners' => 'banners',
            'company' => 'companies',
        ];

        return ($this->request->folder == null) ? $paths : $paths[$this->request->folder];
    }

    private function isFileExists(string $name = null): bool
    {
        return $this->storageManager->isFileExists(
             $name,
            $this->getFolderPath()
        );
    }

    public function delete(string $fileName)
    {
       if ($this->isFileExists($fileName)){
           $this->storageManager->removeFile($fileName,$this->getFolderPath());
       }
    }


}
