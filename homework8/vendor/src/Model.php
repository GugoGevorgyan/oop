<?php
namespace vendor\src;
use vendor\src\DBManager;
use vendor\src\Validate;
use vendor\src\DBMethods;
use PDO;
class Model extends DBManager implements DBMethods
{
    private $query_str = '';
    private $stmt;
    private $error_msg;
    private $data;
    use Validate;
    public function __construct()
    {
        //read database configs from config file
        require_once __DIR__.'/../../config/config.php';
        parent::__construct(HOST, NAME, PASS, USER);
    }

    /**
     * @return bool
     */
    public function connect()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           return true;
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    /**
     * @param $args
     * @return mixed|void
     */
    public function select($args){
        $this->query_str = 'SELECT ';
        if (!empty($args)) {
            if (gettype($args) === 'string') {
                $this->query_str .= $args;
            } elseif (is_array($args)) {
                for($i = 0; $i < count($args); $i++) {
                    $this->query_str .= $args[$i];
                    if($i < count($args)-1){
                        $this->query_str .= ', ';
                    }
                }
            }
        }else{
            $this->query_str .= ' * ';
        }

    }

    /**
     * @param the $table_name
     * @return mixed|void
     */
    public function from($table_name)
    {
        $this->query_str.= " FROM  {$table_name} ";
    }

    /**
     * @param the $table_name
     * @param array $args
     * @return bool|mixed
     */
    public function insert($table_name, array $args){
        if(!empty($args)){
            $arrNames = [];
            $values = [];
            foreach ($args as $cay => $val){
                $arrNames[] = $cay;
                $values[] = "'".$val."'";
            }
            $names = implode(", ",$arrNames);
            $value = implode(', ',$values);
            $this->query_str = "INSERT INTO ". $table_name." (".$names.") ". "VALUES" ." (". $value.")" ;
            try {
                $this->beginTransaction();
                $this->conn->prepare($this->query_str)->execute($values);
                $this->query_str = '';
                $this->commit();
                return true;
            } catch(\PDOException $e) {
                $this->rollBack();
                echo $this->query_str . "<br>" . $e->getMessage();
                return false;
            }
        }
        $this->error_msg = 'Arguments can not be empty';
    }

    /**
     * @param $table_name
     * @param $data
     * @return mixed|void
     */
    public function update($table_name, $data){
        $this->data = $data;
        $this->query_str .= "UPDATE {$table_name} SET ";
        if(!empty($data) && is_array($data)){
            $i = 0;
            foreach ($data as $k => $v){
                $this->query_str .= "$k =:$k";
                if ($i !== (count($data) - 1)){
                    $this->query_str .= ', ';
                }
                $i++;
            }
        }
    }

    /**
     *
     */
    public function delete() : void {
        $this->query_str .= " Delete ";
    }

    /**
     * @param $create_query
     * @return bool|mixed
     */
    public function createTable($create_query){
        try {
            $this->conn->exec($this->query_str);
            return true;
        } catch(\PDOException $e) {
           echo $this->query_str . "<br>" . $e->getMessage();
           return false;
        }
    }

    /**
     * @param array $condition
     */
    public function where(array $condition): void {
        $this->query_str .= ' WHERE ';
        $this->whereCondition($condition);
    }
    public function andWhere(array $condition)
    {
        $this->query_str .= ' AND ';
        $this->whereCondition($condition);
    }

    /**
     * @description for checking condition type
     * @param $condition
     */
    private function whereCondition($condition){
        $name = $condition['col_name'];
        $val = $condition['col_value'];
        if(!empty($condition['col_value1'])){
            $val1 = $condition['col_value1'];
        }
        switch ($condition['con_type']) {
            case '=':
                $this->query_str .= "$name = '$val'";
                break;
            case 'like':
                $this->query_str .= "$name like $val";
                break;
            case 'in':
                $tmp = '';
                if(is_array($val)){
                    for ($i = 0; $i < count($val); $i++ ){
                        $tmp .= " {$val[$i]}";
                        if($i !== (count($val) -1)){
                            $tmp .= ", ";
                        }
                    }
                    $this->query_str .= "$name IN ($tmp)";
                }elseif (is_string($val)){
                    $this->query_str .= "$name IN ($val)";
                }
                break;
            case 'not in':
                $tmp = '';
                if(is_array($val)){
                    for ($i = 0; $i < count($val); $i++ ){
                        $tmp .= " {$val[$i]}";
                        if($i !== (count($val) -1)){
                            $tmp .= ", ";
                        }
                    }
                    $this->query_str .= "$name Not IN ($tmp)";
                }elseif (is_string($val)){
                    $this->query_str .= "$name Not IN ($val)";
                }
                break;
            case 'between':
                $this->query_str .= "$name BETWEEN $val AND $val1";
                break;
        }

    }

    /**
     * @param array $args
     */
    public function orderBy(array $args):void {
        $default = "ASC";
        $this->query_str .= " ORDER BY ";
        for ($j = 0; $j < count($args); $j++){
            if(!empty($args['name'])){
                $this->query_str .= " {$args['name']} ";
            }
            if (!empty($args['type'])){
                $this->query_str .= " {$args['type']} ";
            }else{
                $this->query_str .= " {$default} ";
            }
            if ($j !== (count($args) - 1)){
                $this->query_str .= ' , ';
            }
        }

    }

    /**
     * @brief for getting a data from database
     */
    protected function get(){
        $this->stmt = $this->conn->prepare($this->query_str);
        $this->stmt->execute();
        $this->query_str = '';
    }

    /**
     * @brief for setting a data to database
     */
    protected function set(){
        try {
            $this->beginTransaction();
            $this->conn->prepare($this->query_str)->execute($this->data);
            $this->query_str = '';
            $this->commit();
            return true;
        }catch (\PDOException $e){
            $this->rollBack();
            echo $e->getMessage();
        }

    }

    /**
     * @brief for making an associative array
     * @return mixed
     */
    public function resultToArray(){
        $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $this->stmt->fetchAll();
    }

    /**
     * @brief for closing the connection
     */
    public function __destruct()
    {
        $this->conn = null;
    }

    /**
     * @brief to begin the transaction
     * @return mixed
     */
    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    /**
     * @brief to roll back the changes
     * @return mixed
     */
    public function rollBack()
    {
       return $this->conn->rollBack();
    }

    /**
     * @brief to commit the changes
     * @return mixed
     */
    public function commit()
    {
        return $this->conn->commit();
    }

    /**
     * @brief for getting the error message
     * @return mixed
     */
    public function getLastError(){
        return $this->error_msg;
    }

}