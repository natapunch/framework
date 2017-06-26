<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 12.06.17
 * Time: 18:39
 */

namespace Punchenko\Framework\Model;



use Punchenko\Framework\DataBase\DataBaseInterface;
use Punchenko\Framework\DI\DInjector;


class Model extends QueryBuilder
{
    /**
     * @var string
     */
    protected $table = '';

    protected $dbo;

    protected $rowData;


    /**
     * Model constructor.
     * @param DataBaseInterface $dbo
     * @internal param $db
     */
    public function __construct(DataBaseInterface $dbo)
    {
        $this->dbo = DInjector::get('db');
    }
    /**
     * Create new entity
     */
    public function create(): self
    {
        return DInjector::make(get_class($this));
    }
    /**
     * Get queried records
     *
     * @return array
     */
    public function get(): array {
        $sql = $this->select()
            ->from($this->table)
            ->build();
        if($pdo_stat = $this->dbo->query( $sql ))
        {
            $pdo_stat->setFetchMode (\PDO::FETCH_CLASS , get_class($this) , [$this->dbo] );
        }
        // Warning! calls to fetch methods should be wrapped in Adapter!
        return $pdo_stat?$pdo_stat->fetchAll() : [];
    }
    /**
     * Get $varname
     *
     * @param $varname
     * @return null
     */
    public function __get($name)
    {
        return isset($this->rowData[$name]) ? $this->rowData[$name] : null;
    }

    /**
     * set $value
     *
     * @param $varname
     */
    public function __set($name,$value)
    {
        $this->rowData[$name] = $value;
    }


    /**
     * Find single record by ID
     *
     * @param $id
     *
     * @return Object
     */
    public function find($id){
        $sql =$this->select()
            ->from($this->table)
            ->where('id', (int)$id)
            ->limit(1)
            ->build();
        if($pdo_stat = $this->dbo->query( $sql ))
        {
            $pdo_stat->setFetchMode ( \PDO::FETCH_CLASS , get_class($this), [$this->dbo] );
        }
        // Warning! calls to fetch methods should be wrapped in Adapter!
        return $pdo_stat ? $pdo_stat->fetch() : null;
    }

    /**
     * Executes the prepared query
     *
     * @param string $sql
     * @param bool $isReturn
     * @param bool $isAll
     * @return array|mixed
     */
    public function executeQuery(string $sql = '', bool $isReturn = false, bool $isAll = false)
    {
        try {
            $PDOStatement = $this->dbo->getConnection()->prepare($sql);
            $PDOStatement->execute();
            if ($PDOStatement->errorInfo()[0] != '00000') {
                throw new ModelBadQueryException(implode(': ', $PDOStatement->errorInfo()));
            }
        } catch (ModelBadQueryException $e) {
            echo $e->getMessage();
        }
        if ($isReturn) {
            return ($isAll)
                ? $PDOStatement->fetchAll(\PDO::FETCH_ASSOC)
                : $PDOStatement->fetch(\PDO::FETCH_ASSOC);
        }
    }
}