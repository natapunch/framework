<?php
	/**
	 * Created by PhpStorm.
	 * User: n.punchenko
	 * Date: 19.06.2017
	 * Time: 12:28
	 */

	namespace Punchenko\Framework\Exception;


	class DInjectorNotFoundClassException extends \Exception
	{

		/**
		 * DInjectorNotFoundClassException constructor.
		 * @param string $param
		 */
		public function __construct($param)
		{
		}
	}