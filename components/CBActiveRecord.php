<?php
/**
 * Extend base CActiveRecord functionality with handy methods.
 *
 * @since 1.0
 * @package Components
 * @author Konstantinos Filios <konfilios@gmail.com>
 */
class CBActiveRecord extends CActiveRecord
{
	/**
	 * Utc datetime to datetime stamp.
	 *
	 * @param integer $stamp
	 * @return string
	 */
	public function stampToUdatetime($stamp = null)
	{
		if (!$stamp) {
			$stamp = time();
		}
		return gmdate('Y-m-d H:i:s', $stamp);
	}

	/**
	 * getErrors() as string with newlines.
	 *
	 * @param string $attribute
	 * @return string
	 */
	public function getErrorsAsString($attribute = null)
	{
		$errorString = '';
		foreach (parent::getErrors($attribute) as $errorArray) {
			$errorString .= implode("\n", $errorArray)."\n";
		}
		return $errorString;
	}

	/**
	 * Select specific data.
	 * @param string $select
	 * @return ZQActiveRecord
	 */
	public function scopeSelect($select)
	{
		$this->getDbCriteria()->select = $select;

		return $this;
	}

	/**
	 * Limit results.
	 *
	 * Singleton method.
	 *
	 * @param integer $limit Number of results.
	 * @return ZQActiveRecord
	 */
	public function scopeLimit($limit)
	{
		$this->getDbCriteria()->limit = intval($limit);

		return $this;
	}

	/**
	 * Zero-based offset of results.
	 *
	 * Singleton method.
	 *
	 * @param integer $offset Offset of results.
	 * @return ZQActiveRecord
	 */
	public function scopeOffset($offset)
	{
		$this->getDbCriteria()->offset = intval($offset);

		return $this;
	}

	/**
	 * Limit results.
	 *
	 * Singleton method.
	 *
	 * @param integer $order Order by clause.
	 * @return ZQActiveRecord
	 */
	public function scopeOrderBy($order)
	{
		$this->getDbCriteria()->order = $order;

		return $this;
	}
}
