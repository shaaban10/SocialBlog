<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileService
{
    private string $targetDirectory;
    const PostType = 'post';
    const AvatarType = 'avatar';
    const Directories= [
        self::PostType => 'posts',
        self::AvatarType => 'avatars'
    ];

    public function __construct(string $target_dir)
    {
        $this->targetDirectory = $target_dir;
    }

    public function upload(File $file, string  $fileType): string
    {
        if ($file instanceof  UploadedFile )
        {
            $fileName = $file->getClientOriginalName();
        }
        else {
            $fileName = $file->getFilename();
        }
        $destination = $this->targetDirectory . '/'. self::Directories[$fileType] ;
        $originalName = pathinfo($fileName, PATHINFO_FILENAME);
        $newName = $originalName . '_' . uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $newName);
        
        return $newName;
       
    }

}