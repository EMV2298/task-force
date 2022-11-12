<?php 

namespace taskforce;

class Files 
{
   /**
   * Загружает файлы на сервер
   * @param array $files Файлы для загрузки
   * @return array Возвращает имена загруженных файлов     
   */
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

   /**
   * Загружает файл на сервер
   * @param object $file Файл для загрузки
   * @return string Возвращает имя загруженного файла  
   */
  public static function uploadUserAvatar(object $file): string
  {
    $name = uniqid('user-avatar') . '.' . $file->getExtension();

    return $file->saveAs('@webroot/uploads/user-avatar/' . $name) ? $name : '';  
  }

   /**
   * Загружает файлы на сервер
   * @param string $filesUrl Url файла для загрузки
   * @return string Возвращает имя загруженного файла     
   */
  public static function uploadUrlAvatar(string $filesUrl): string
  {
    $name = uniqid('user') . '.png';  
    
    return file_put_contents("uploads/user-avatar/{$name}", file_get_contents($filesUrl)) ? $name : '';
  }
}