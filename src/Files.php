<?php 

namespace taskforce;

class Files 
{
  public static function uploadTaskFiles(array $files): array
  {
    $savedFiles = [];    
    foreach ($files as $file) {
      $name = uniqid('task-file') . '.' . $file->getExtension();
      if ($file->saveAs('@webroot/uploads/tasks-files/' . $name)) {
        $savedFiles[] = $name;
      }
    }
    
    return $savedFiles;    
  }

  public static function uploadUserAvatar(object $file): string
  {
    $name = uniqid('user-avatar') . '.' . $file->getExtension();

    return $file->saveAs('@webroot/uploads/user-avatar/' . $name) ? $name : '';  
  }

  public static function uploadUrlAvatar(string $filesUrl): string
  {
    $name = uniqid('user') . '.png';  
    
    return file_put_contents("uploads/user-avatar/{$name}", file_get_contents($filesUrl)) ? $name : '';
  }
}