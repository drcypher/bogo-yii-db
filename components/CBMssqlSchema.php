<?php
/**
 * Extend CSMssqlSchema functionality.
 *
 * @since 1.0
 * @package Components
 * @author Konstantinos Filios <konfilios@gmail.com>
 */
class CBMssqlSchema extends CMssqlSchema
{
	/**
	 * @var array the abstract column types mapped to physical column types.
	 * @since 1.1.6
	 */
    public $columnTypes=array(
        'pk' => 'int IDENTITY PRIMARY KEY',
        'string' => 'nvarchar(255)',
        'text' => 'ntext',
        'integer' => 'int',
        'float' => 'float',
        'decimal' => 'decimal',
        'datetime' => 'datetime',
        'timestamp' => 'timestamp',
        'time' => 'time',
        'date' => 'date',
        'binary' => 'binary',
        'boolean' => 'bit',
		// Extra datatypes
		'uuid' => 'uniqueidentifier NOT NULL DEFAULT (newid())',
		'uuidfk' => 'uniqueidentifier',
		'datestamp' => 'datetime NOT NULL DEFAULT (getutcdate())',
		'mediumstring' => 'nvarchar(1022)',

		// Extra primary key datatypes
		'stringpk' => 'nvarchar(255) PRIMARY KEY',
		'tinypk' => 'tinyint IDENTITY PRIMARY KEY',
		'tinypk*' => 'tinyint PRIMARY KEY',
		'smallpk' => 'smallint IDENTITY PRIMARY KEY',
		'smallpk*' => 'smallint PRIMARY KEY',
		'bigpk' => 'bigint IDENTITY PRIMARY KEY',
		'bigpk*' => 'bigint PRIMARY KEY',

		'uuidpk' => 'uniqueidentifier PRIMARY KEY NOT NULL DEFAULT (newid())',
		'uuidpk*' => 'uniqueidentifier PRIMARY KEY NOT NULL',
    );

	/**
	 * SQL snippet that converts expression to given type.
	 *
	 * @see http://msdn.microsoft.com/en-us/library/ms187928.aspx
	 *
	 * @param string $expr
	 * @param string $toType
	 * @return string
	 */
	public function convertExpressionType($expr, $toType)
	{
		return 'CONVERT('.$toType.', '.$expr.')';
	}
}