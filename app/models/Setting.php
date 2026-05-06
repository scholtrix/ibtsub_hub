<?php

class Setting extends Model
{
    public static function findByKey($key)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        return $stmt->fetch();
    }

    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT * FROM settings');
        return $stmt->fetchAll();
    }

    public static function updateValue($key, $value)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('UPDATE settings SET setting_value = :value, updated_at = NOW() WHERE setting_key = :key');
        return $stmt->execute([':value' => $value, ':key' => $key]);
    }

    public static function saveValues(array $values)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('UPDATE settings SET setting_value = :value, updated_at = NOW() WHERE setting_key = :key');
        foreach ($values as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
        return true;
    }
}
