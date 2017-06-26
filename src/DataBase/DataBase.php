<?php
	namespace Punchenko\Framework\DataBase;

	/**
	 * Компонент для работы с базой данных
	 */


	use Exception;
	use PDOStatement;

// use Punchenko\Framework\Config\Config;


	class DataBase implements DataBaseInterface
	{
		protected $connection;
		protected $config = [];

		/**
		 * Устанавливает соединение с базой данных
		 * @internal param Config $config
		 */

		public function __construct()
		{
			// Получаем параметры подключения из файла
			if (empty ($this->config['db'])) {
				throw new \Exception('No DB connection params');

			}
			$dsn = sprintf('%s:dbname=%s;host=%s',
				$this->config['db']['driver'],
				$this->config['db']['dbname'],
				$this->config['db']['host']
			);
			$options = [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			];
			// Устанавливаем соединение
			try {
				$this->connection = new \PDO($dsn, $this->config['db']['user'], $this->config['db']['password'], $options);

			} catch (\PDOException $e) {
				throw $e;
			}

		}

		/**
		 * @inheritdoc
		 */
		public function getConnection(): \PDO
		{
			return $this->connection;
		}
//         public function __construct(Config $config)
//     {
//         if(!$config->has('db')){
//             throw new \Exception('No DB connection params predefined');
//         }
//         $dsn = sprintf('%s:dbname=%s;host=%s;',
//             $config->get('db.driver', 'pgsql'),
//             $config->get('db.dbname','phpshop'),
//             $config->get('db.host', '127.0.0.1')
//         );
//         $options = [
//             \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
//             \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
//         ];
//         try {
//             $this->connection = new Database($dsn,
//                 $config->get('db.user', 'postgres'),
//                 $config->get('db.password', 'postgress57'),
//                 $options);
//         } catch (\PDOException $e) {
//             throw $e;
//         }
//     }


		/**
		 * Magic call
		 *
		 * @param $method
		 * @param $args
		 * @return mixed
		 */
		public function __call($method, $args)
		{
			return call_user_func_array([$this->connection, $method], $args);
		}

		/**
		 * Query method
		 * @param $statement
		 * @return PDOStatement
		 */
		public function query($statement)
		{

			return $this->connection->query($statement, \PDO::FETCH_CLASS, '\\stdClass');
		}


	} 