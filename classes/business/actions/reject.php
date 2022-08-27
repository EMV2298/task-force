<?php

namespace taskforce\business\actions;

class reject extends Actions {
  
  public function getActionName(): string
  {
    return 'Отказаться';
  }

  public function getInternalName(): string
  {
    return 'reject';
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $executorId;
  }

}