<?php

namespace taskforce\business;

class User
{

  /**
   * Возвращает название столбца роли пользователя для sql запроса
   * @param bool $isExecutor Роль пользователя
   * @return string Название столбца в таблице Tasks 
   */
  public static function getSqlRole(bool $isExecutor): string
  {
    return $isExecutor ? 'executor_id' : 'customer_id';
  }
}
