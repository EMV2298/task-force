<?php

namespace taskforce\business\actions;

class done extends Actions {
  
  public function getActionName(): string
  {
    return 'Выполнено';
  }

  public function getInternalName(): string
  {
    return 'done';
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $customerId;
  }

}