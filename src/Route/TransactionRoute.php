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
        // Obtener todas las transacciones activas para el usuario
        $transactions = $this->transactionModel->getTransactionsByUserId($userId);
        return $transactions;
    }

    public function createTransaction($userId, $text, $amount)
    {
        // Crear una nueva transacción
        $transactionId = $this->transactionModel->createTransaction($userId, $text, $amount);
        return ['success' => true, 'transactionId' => $transactionId];
    }

    public function getTransaction($transactionId)
    {
        // Obtener una transacción específica
        $transaction = $this->transactionModel->getTransactionById($transactionId);
        return $transaction;
    }

    public function updateTransaction($transactionId, $text, $amount)
    {
        // Actualizar una transacción específica
        $affectedRows = $this->transactionModel->updateTransaction($transactionId, $text, $amount);
        return ['success' => $affectedRows > 0];
    }

    public function deleteTransaction($transactionId)
    {
        // Desactivar una transacción (cambiar active a false)
        $affectedRows = $this->transactionModel->deactivateTransaction($transactionId);
        return ['success' => $affectedRows > 0];
    }

    // ... otros métodos de rutas de transacciones.
}
