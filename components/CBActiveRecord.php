<?php
/**
 * Extend base CActiveRecord functionality with handy functions.
 *
 * @since 1.0
 * @package Components
 * @author Konstantinos Filios <konfilios@gmail.com>
 */
class CBActiveRecord extends CActiveRecord
{
	/**
	 * Variation of CHtml::listData allowing array results.
	 *
	 * @param array $models Array of source models.
	 * @param mixed $keyAttr Attribute to be used as key. If null, an array is returned instead of an assoc.
	 * @param mixed $valueAttr Attribute to be used as value. If null, the model itself is used as is.
	 * @return array
	 */
	static public function listData(array $models, $keyAttr = null, $valueAttr = null)
	{
		$values = array();

		// Go through all models
		foreach ($models as $model) {
			// Retrieve value
			$value = ($valueAttr === null) ? $model : self::value($model, $valueAttr);

			if ($keyAttr === null) {
				// Array mode (no key)
				$values[] = $value;
			} else {
				// Assoc mode
				$values[self::value($model, $keyAttr)] = $value;
			}
		}

		return $values;
	}

	/**
	 * Variation of CHtml::value retrieving model values.
	 *
	 * This variation does not accept default values and optimizes the isset conditions.
	 *
	 * @param mixed $model Source model
	 * @param mixed $valueAttr
	 * @return mixed
	 */
	public static function value($model, $valueAttr)
	{
		if (is_scalar($valueAttr)) {
			foreach (explode('.', $valueAttr) as $valuAttrComponent) {
				if (is_object($model)) {
					if (!isset($model->$valuAttrComponent)) {
						return null;
					}
					$model=$model->$valuAttrComponent;

				} else if(is_array($model)) {
					if (!isset($model[$valuAttrComponent])) {
						return null;
					}
					$model=$model[$valuAttrComponent];
				} else {
					return null;
				}
			}
		} else {
			return call_user_func($valueAttr,$model);
		}

		return $model;
	}

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
	 * @return CBActiveRecord
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
	 * @return CBActiveRecord
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
	 * @return CBActiveRecord
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
	 * @return CBActiveRecord
	 */
	public function scopeOrderBy($order)
	{
		$this->getDbCriteria()->order = $order;

		return $this;
	}

	/**
	 * Finds a single active record that has the specified attribute values.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array $attributes list of attribute values (indexed by attribute names) that the active records should match.
	 * An attribute value can be an array which will be used to generate an IN condition.
	 * @param mixed $condition query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return CBActiveRecord
	 */
	public function scopeByAttributes($attributes,$condition='',$params=array())
	{
		$prefix=$this->getTableAlias(true).'.';
		$criteria=$this->getCommandBuilder()->createColumnCriteria($this->getTableSchema(),$attributes,$condition,$params,$prefix);
		$this->getDbCriteria()->mergeWith($criteria);
		return $this;
	}
}
