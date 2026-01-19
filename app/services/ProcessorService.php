<?php namespace app\services;

use app\models\Request;
use app\enums\RequestStatus;
use Throwable;
use Yii;
use yii\db\IntegrityException;

class ProcessorService
{
    public function process(int $delaySeconds): void
    {
        $delaySeconds = max(0, $delaySeconds);
        while (true) {
            $db = Yii::$app->db;
            try {
                $transaction = $db->beginTransaction();
                $id = $this->fetchNextPendingRequestId();
                if (!$id) {
                    $transaction->rollBack();
                    break;
                }

                $request = Request::findOne($id);
                if (!$request) {
                    $transaction->rollBack();
                    break;
                }

                $this->setStatus($request, RequestStatus::Processing);
                $transaction->commit();

                if ($delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                $transaction = $db->beginTransaction();
                if ($this->shouldApprove()) {
                    $this->setStatus($request, RequestStatus::Approved);
                } else {
                    $this->setStatus($request, RequestStatus::Declined);
                }
                $transaction->commit();

            } catch (IntegrityException $e) {
                if ($transaction->isActive) {
                    $transaction->rollBack();
                }
                $this->setStatus($request, RequestStatus::Declined);
            } catch (Throwable $e) {
                if ($transaction->isActive) {
                    $transaction->rollBack();
                }
                $this->setStatus($request, RequestStatus::Error);
            }
        }
    }

    private function fetchNextPendingRequestId(): int
    {
        return (int)Yii::$app->db->createCommand("SELECT id FROM requests WHERE status = :status ORDER BY id ASC LIMIT 1 FOR UPDATE SKIP LOCKED", [':status' => RequestStatus::Pending->value])->queryScalar();
    }

    private function shouldApprove(): bool
    {
        return random_int(1, 2) === 1;
    }

    private function setStatus(Request $request, RequestStatus $status): void
    {
        $request->status = $status->value;
        $request->save(false, ['status', 'updated_at']);
    }
}