<?php

namespace app\services;

use app\enums\RequestStatus;
use app\models\Request;
use Throwable;
use Yii;

class RequestService
{
    public function create(array $payload): int
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $request = $this->buildRequest($payload);

            if (!$request->validate()) {
                $transaction->rollBack();
                return 0;
            }

            $hasApproved = Request::find()
                ->where(['user_id' => $request->user_id, 'status' => RequestStatus::Approved->value])
                ->exists();

            if ($hasApproved) {
                $transaction->rollBack();
                return 0;
            }

            if (!$request->save()) {
                $transaction->rollBack();
                return 0;
            }

            $transaction->commit();

            return $request->id;
        } catch (Throwable $e) {
            $transaction->rollBack();
            return 0;
        }
    }

    private function buildRequest(array $payload): Request
    {
        $request = new Request();
        $request->user_id = $payload['user_id'] ?? null;
        $request->amount = $payload['amount'] ?? null;
        $request->term = $payload['term'] ?? null;
        $request->status = RequestStatus::Pending->value;

        return $request;
    }
}
