<?php

namespace taskforce\business;

use app\models\Offers;
use taskforce\business\actions\Actions;
use taskforce\business\actions\Cancel;
use taskforce\business\actions\Done;
use taskforce\business\actions\Reject;
use taskforce\business\actions\Take;
use taskforce\exception\TaskActionException;
use taskforce\exception\TaskStatusException;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_IN_PROGRESS = 'inprogress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAIL = 'fail';
    public const STATUSES = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_FAIL, self::STATUS_DONE, self::STATUS_CANCELED];

    public const ACTION_CUSTOMER_CANCEL = 'cancel';
    public const ACTION_CUSTOMER_DONE = 'done';
    public const ACTION_EXECUTOR_TAKE = 'take';
    public const ACTION_EXECUTOR_REJECT = 'reject';

    private $customerId;
    private $executorId;
    private $status;

    public function __construct(int $customerId, ?int $executorId, string $status)
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new TaskStatusException('Не верный статус');
        }
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = $status;
    }

    /**
     * Возвращает все статусы
     * @return array статусы
     */
    public static function getAllStatuses(): array
    {
        return [
          self::STATUS_NEW => 'Новый',
          self::STATUS_CANCELED => 'Отменено',
          self::STATUS_IN_PROGRESS => 'В работе',
          self::STATUS_DONE => 'Выполнено',
          self::STATUS_FAIL => 'Провалено',
        ];
    }

    /**
     * Возвращает все действия
     * @return array действия
     */
    public function getAllActions(): array
    {
        return [
          self::ACTION_CUSTOMER_CANCEL => 'Отменить',
          self::ACTION_CUSTOMER_DONE => 'Выполнено',
          self::ACTION_EXECUTOR_TAKE => 'Откликнуться',
          self::ACTION_EXECUTOR_REJECT => 'Отказаться',
        ];
    }

    /**
     * Возвращает доступные действия для пользователя в задании
     * @param int $userId id пользователя
     * @param int $task_id id заданий
     * @return ?Actions доступное действие
     */
    public function getAvailableActions(int $userId, int $task_id): ?Actions
    {
        $offer = new Offers();
        $offer = $offer->getUserOffer($userId, $task_id);

        if ($this->status === self::STATUS_NEW && Cancel::checkAccess($userId, $this->customerId, $this->executorId)) {
            return new Cancel();
        }
        if ($this->status === self::STATUS_NEW && Take::checkAccess($userId, $this->customerId, $this->executorId) && !$offer) {
            return new Take();
        }
        if ($this->status === self::STATUS_IN_PROGRESS && Done::checkAccess($userId, $this->customerId, $this->executorId)) {
            return new Done();
        }
        if ($this->status === self::STATUS_IN_PROGRESS && Reject::checkAccess($userId, $this->customerId, $this->executorId)) {
            return new Reject();
        }
        return null;
    }

    /**
     * Возвращает следующий статус при совершении действия
     * @param string $action Действие
     * @return string Следующий статус
     * @throws TaskActionException Действия не существует
     */
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

    /**
     * Возвращает статусы заданий которые должны отображаться на странице my   *
     * @param string $page Фильтр страницы tasks/my из get параметра
     * @return array Статусы заданий для страницы
     */
    public static function getTaskStatusesForMytask(string $page): array
    {
        $types = [
          Task::STATUS_NEW => [Task::STATUS_NEW],
          Task::STATUS_IN_PROGRESS => [Task::STATUS_IN_PROGRESS],
          Task::STATUS_DONE => [Task::STATUS_DONE, Task::STATUS_FAIL, Task::STATUS_CANCELED]
        ];
        return $types[$page] ? $types[$page] : [];
    }
}
