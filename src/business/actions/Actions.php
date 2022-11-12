<?php

namespace taskforce\business\actions;

abstract class Actions {
    
    /**
     * Возвращает имя действия для кнопки
     * @return string Имя действия
     */
    abstract public function getActionName(): string;

    /**
     * Возвращает внутренее имя действия
     * @return string Имя действия
     */
    abstract public function getInternalName(): string;
    
    /**
     * Возвращает внутренее имя действия
     * @return string Имя действия
     */
    abstract public function getActionData(): string;
    
    /**
     * Возвращает css класс для кнопки действия
     * @return string css класс
     */
    abstract public function getClass(): string;

    /**
     * Проверяет доступно ли действие над задание для пользователя
     * @param int $userId id Пользователя
     * @param int $customerId id Заказчика
     * @param int $executorId id Испонителя назначеного на задание
     * @return bool Доступно ли пользователю это действие
     */
    abstract public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool;
} 
