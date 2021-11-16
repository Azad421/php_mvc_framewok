<?php

namespace azadkh\mvcframework\db;

use azadkh\mvcframework\Application;
use azadkh\mvcframework\Model;

abstract class DbModel extends Model
{
    abstract public static function tablename(): string;

    abstract public function attributes(): array;

    abstract public static function primaryKey(): string;

    public function save()
    {
        $tablename = $this->tablename();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statment = self::prepare("INSERT INTO $tablename (" . implode(',', $attributes) . ") VALUES(" . implode(',', $params) . ")");
        foreach ($attributes as $attribute) {
            $statment->bindParam(":$attribute", $this->{$attribute});
        }
        $statment->execute();
        return true;
    }

    public static function findOne($where)
    {
        $tablename = static::tablename();
        $attributes = array_keys($where);
        $sql = implode(" ADN ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statment = self::prepare("SELECT * FROM $tablename WHERE $sql");

        foreach ($where as $key => $value) {
            $statment->bindValue(":$key", $value);
        }

        $statment->execute();
        return $statment->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}
