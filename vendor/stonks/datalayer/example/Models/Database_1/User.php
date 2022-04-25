<?php

namespace Example\Models\Database_1;

use Stonks\DataLayer\DataLayer;

/**
 * Class User
 *
 * @property int|string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @package Example\Models\Database_1
 */
class User extends DataLayer
{

	/**
	 * User constructor.
	 */
	public function __construct()
	{
		parent::__construct('user', [
			'first_name',
			'last_name',
			'name',
			'email',
		], 'id', true, 'db_1');
	}
}