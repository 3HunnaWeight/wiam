<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Request extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'requests';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
            [['user_id', 'amount', 'term'], 'integer', 'min' => 1],
            [['user_id', 'amount', 'term'], 'filter', 'filter' => static fn($v) => $v === '' ? null : $v],
        ];
    }
}
