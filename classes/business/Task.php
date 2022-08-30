<?php

namespace taskforce\business;

use taskforce\business\actions\cancel;
use taskforce\business\actions\done;
use taskforce\business\actions\reject;
use taskforce\business\actions\take;
use taskforce\exception\TaskActionException;
use taskforce\exception\TaskStatusException;

class Task
{

  const STATUS_NEW = 'new';
  const STATUS_CANCELED = 'canceled';
  const STATUS_IN_PROGRESS = 'inprogress';
  const STATUS_DONE = 'done';
  const STATUS_FAIL = 'fail';
  const STATUSES = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_FAIL, self::STATUS_DONE, self::STATUS_CANCELED];

  const ACTION_CUSTOMER_CANCEL = 'cancel';
  const ACTION_CUSTOMER_DONE = 'done';
  const ACTION_EXECUTOR_TAKE = 'take';
  const ACTION_EXECUTOR_REJECT = 'reject';
  

  private $customerId;
  private $executorId;
  private $status;

  public function __construct(int $customerId, int $executorId, string $status)
  {
    if (in_array($status, self::STATUSES, true)) {
      $this->customerId = $customerId;
      $this->executorId = $executorId;
      $this->status = $status;
    } else {
      throw new TaskStatusException('Не верный статус');
    }
  }

  public function getAllStatuses()
  {
    return [
      self::STATUS_NEW => 'новый',
      self::STATUS_CANCELED => 'Отменено',
      self::STATUS_IN_PROGRESS => 'В работе',
      self::STATUS_DONE => 'Выполнено',
      self::STATUS_FAIL => 'Провалено',
    ];
  }

  public function getAllActions()
  {
    return [
      self::ACTION_CUSTOMER_CANCEL => 'Отменить',
      self::ACTION_CUSTOMER_DONE => 'Выполнено',
      self::ACTION_EXECUTOR_TAKE => 'Откликнуться',
      self::ACTION_EXECUTOR_REJECT => 'Отказаться',
    ];
  }

  public function getAvailableActions(int $userId) 
  {
    if ($this->status === self::STATUS_NEW && cancel::checkAccess($userId, $this->customerId, $this->executorId)) {
      return new cancel;
    }
    if ($this->status === self::STATUS_NEW && take::checkAccess($userId, $this->customerId, $this->executorId)) {
      return new take;
    }
    if ($this->status === self::STATUS_IN_PROGRESS && done::checkAccess($userId, $this->customerId, $this->executorId)) {
      return new done;
    }
    if ($this->status === self::STATUS_IN_PROGRESS && reject::checkAccess($userId, $this->customerId, $this->executorId)) {
      return new reject;
    }
  }

  public function getNextStatus(string $action): string
  {
    $nextStatus = [
      self::ACTION_CUSTOMER_CANCEL => self::STATUS_CANCELED,
      self::ACTION_CUSTOMER_DONE => self::STATUS_DONE,
      self::ACTION_EXECUTOR_REJECT => self::STATUS_FAIL,
      self::ACTION_EXECUTOR_TAKE => self::STATUS_IN_PROGRESS
    ];

    if (empty($nextStatus[$action])) {
      throw new TaskActionException('Действия не существует');
    }
    return $nextStatus[$action];
  }
};
