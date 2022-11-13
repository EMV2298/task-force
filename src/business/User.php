<?php

namespace taskforce\business;

use app\models\Tasks;

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

    /**
     * Проверяет можно ли показывать контакты на странице профиля
     * @param int $userId Id пользователя
     * @param int $profileId Id просматримаевого профиля
     * @param bool $showContact Настройки профиля
     * @return bool Разрешает или запрещает показывать контакты
     */
    public static function showContacts(int $userId, int $profileId, ?bool $showContact): bool
    {
        if ($showContact || $userId === $profileId) {
            return true;
        } else {
            $tasks = Tasks::find()->andFilterWhere(['executor_id' => $profileId, 'customer_id' => $userId])->all();
            return $tasks ? true : false;
        }
    }
}
