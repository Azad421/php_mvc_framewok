<?php

namespace azadkh\mvcframework;

use azadkh\mvcframework\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}