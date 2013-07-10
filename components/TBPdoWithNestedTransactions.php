<?php
/**
 * Nested transactions functionality.
 *
 * @since 1.0
 * @package Components
 * @author Konstantinos Filios <konfilios@gmail.com>
 */
trait TBPdoWithNestedTransactions
{
	/**
	 * Transaction is still referenced but has been rolled back.
	 *
	 * @var boolean
	 */
	private $isTransactionRolledBack = false;

	/**
	 * Nesting level of current transaction.
	 * @var integer
	 */
	private $transactionRefcount = 0;

	/**
	 * Begin new or resume existing transaction.
	 */
	public function beginTransaction()
	{
		if ($this->transactionRefcount === 0) {
			// No transaction active so far
			parent::beginTransaction();
		}

		// Increase refcount
		$this->transactionRefcount++;
	}

	/**
	 * Commit or decrease refcount of transaction.
	 */
	public function commit()
	{
		// One less is referencing this transaction
		$this->transactionRefcount--;

		if (($this->transactionRefcount === 0) && !$this->isTransactionRolledBack) {
			// Nobody is referencing the transaction and it has not failed.
			// It's time we commit
			parent::commit();
		}
	}

	/**
	 * Rollback or decrease refcount of transaction.
	 */
	public function rollback()
	{
		// One less is referencing this transaction
		$this->transactionRefcount--;

		if (!$this->isTransactionRolledBack) {
			// We have not rolled back yet. Rollback now instead of waiting for
			// refcount to reach 0 to avoid "leaks".
			// Try-and-catch is suggested in http://support.microsoft.com/kb/309335
			try {
				parent::rollBack();
			} catch (Exception $e) {
				// Gargara
			}
		}

		// Mark as rolled back for as long as somebody is referencing this transaction
		$this->isTransactionRolledBack = ($this->transactionRefcount > 0);
	}
}