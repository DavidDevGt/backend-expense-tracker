<?php

namespace App\Route;

use App\Model\TransactionModel;

class TransactionRoute
{
    private $transactionModel;

    public function __construct(TransactionModel $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function getTransactions($userId)
    {
        $transactions = $this->transactionModel->getTransactionsByUserId($userId);
        return $transactions;
    }

    public function createTransaction($userId, $text, $amount)
    {
        $transactionId = $this->transactionModel->createTransaction($userId, $text, $amount);
        return ['success' => true, 'transactionId' => $transactionId];
    }

    public function getTransaction($userId, $transactionId) {
        $transaction = $this->transactionModel->getTransactionById($userId, $transactionId);
        return $transaction;
    }

    public function updateTransaction($userId, $transactionId, $text, $amount) {
        $affectedRows = $this->transactionModel->updateTransaction($userId, $transactionId, $text, $amount);
        return ['success' => $affectedRows > 0];
    }

    public function deleteTransaction($userId, $transactionId) {
        $affectedRows = $this->transactionModel->deactivateTransaction($userId, $transactionId);
        return ['success' => $affectedRows > 0];
    }
}
