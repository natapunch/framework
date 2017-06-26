<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 12.06.17
 * Time: 15:44
 */

namespace Punchenko\Framework\DataBase;


interface DataBaseInterface
{
	/**
	 * Gets PDO connection
	 *
	 * @return \PDO
	 */
	public function getConnection();
}

	