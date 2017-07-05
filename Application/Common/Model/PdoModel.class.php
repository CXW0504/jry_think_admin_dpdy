<?php
namespace Common\Model;

/**
 * 该模型为公共继承模型
 * 封装有query查询方法进行SQL查询
 * 备注：该类为实例化PDO操作类，故目前只支持书写SQL进行查询
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-06-30 14:43:34
 */
class PdoModel{
    private $dns = '';
    private $db_user = '';
    private $db_pass = '';
    private static $db;
    
    /**
     * 构造函数，作用为创建PDO类
     * @param string $dns 连接PDO的DNS参数
     *      如果不传入使用默认的MySQL类型
     *      其中需要读取DB_HOST、DB_NAME、DB_CHARSET、DB_PORT几个配置项
     * @param string $user 数据库用户
     *      如果不传入使用配置项中的DB_USER
     * @param string $pass 数据库用户密码
     *      如果不传入使用配置项中的DB_PWD
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-06-30 13:45:23
     */
    public function __construct($dns = '',$user = '',$pass = '') {
        // 设置DNS[暂时只支持MySQL]
        if(empty($dns)){
            $dns = 'mysql:host=localhost;dbname=test;charset=utf8';
        } else {
            $this->dns = $dns;
        }
        $this->db_user = empty($user) ? 'root' : $user ;// 设置用户
        $this->db_pass = empty($pass) ? 'root' : $pass ;// 设置密码
        self::$db = new \PDO($this->dns,$this->db_user,$this->db_pass);
    }

    /**
     * 执行查询操作
     * @param string $sql SQL语句，必须
     * @param array $data ?绑定的参数
     * @return array 查询到的数据
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-06-30 14:45:08
     */
    public function query($sql,$data = array()){
        $res = self::$db->prepare($sql);
        $res->execute($data);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }
}