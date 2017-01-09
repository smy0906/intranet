<?php
namespace Intra\Model;

use Intra\Core\BaseModel;
use Intra\Service\Payment\PaymentAcceptDto;

class PaymentAcceptModel extends BaseModel
{
    public static function getsByPaymentids(array $payment_ids)
    {
        return self::getDb()->sqlDicts('select * from payment_accept where ?', sqlWhere(['paymentid' => $payment_ids]));
    }

    public static function insert(PaymentAcceptDto $payment_accept_dto)
    {
        $rows = $payment_accept_dto->exportDatabaseInsert();
        return self::getDb()->sqlInsert('payment_accept', $rows);
    }
}
