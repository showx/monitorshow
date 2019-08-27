<?php
error_reporting(0);
/**
 * c/s监控引用接口
 * Author:show
 * version 1.0
 * 检查文件夹是否可写
 * 服务连接是否正常
 */
//【配置项】
//mysql 主
define("MYSQL_MASTER_HOST","127.0.0.1");
define("MYSQL_MASTER_USER","root");
define("MYSQL_MASTER_PASS","root");
//mysql 从,没从数据库为空即可
define("MYSQL_SLAVE_HOST","");
define("MYSQL_SLAVE_USER","");
define("MYSQL_SLAVE_PASS","");
//memcache
define("MEMCACHE_HOST","127.0.0.1");
//redis
define("REDIS_HOST","127.0.0.1");
//mongodb
define("MONGODB_HOST","127.0.0.1");
define("MONGODB_USER","");
define("MONGODB_PASS","");

//【wraning】要检查的项,开启请设置为1 不检测请设置为0
$_env_radio = array(
    //filecache一般有权限就无问题
    
    "folder"=>1,  
    "mysql"=>1,
    "memcache"=>0,
    "mongodb"=>0,
    "redis"=>0,
);
//要检查的文件夹,相当项目的文件夹
$write_folder_config = array(
    "/bone/data/",
    "/bone/data/cache/",
);
//============end 配置==========
class detection{
    private $path = '';
    private $msg = "ok";
    //要检查的文件夹
    public $write_folder_config = array();
    //错误变量
    public $err = array();
    protected $ext = array();
    //各服务端口
    public $mysql_port = 3306;
    public $memcache_port = 11211;
    public $redis_port = 6379;
    public $mongodb_port = 27017;

    public function __construct($config='',$write_folder='')
    {
        //没配置就是不用检查 
        if(empty($config))
        {
            die($this->msg);
        }
        $this->path = dirname(__FILE__);
        $this->_env_radio = $config;
        $this->write_folder_config = $write_folder;
        $this->ip = $_SERVER['SERVER_ADDR'];
        $this->ext = get_loaded_extensions();
    }
    /**
     * 查看info
     */
    public function info()
    {
        phpinfo();
        exit();
    }
    /**
     * 检查指定文件夹
     */
    public function check_folder()
    {
        if($this->write_folder_config)
        {
            foreach($this->write_folder_config as $folder)
            {
                $path = $this->path.$folder; 
                if(!is_writeable($path))
                {
                    return "folder :".$folder." is not writeable";
                } 
            }
        }    
        return '';
    }
    /**
     * 检查mysql
     * php新版本统一使用mysqli
     */
    public function check_mysql()
    {
        if(function_exists("mysqli_connect"))
        {
            //这里配置可以读取 /bone/inc_config
            $conn = mysqli_connect(MYSQL_MASTER_HOST, MYSQL_MASTER_USER, MYSQL_MASTER_PASS,"",$this->mysql_port);
            if($conn == false)
            {
                return "mysql connect err!(".MYSQL_MASTER_HOST."|".MYSQL_MASTER_USER."|".$this->mysql_port.")";
            }
            if(MYSQL_SLAVE_HOST)
            {
                $conn2 = mysqli_connect(MYSQL_SLAVE_HOST, MYSQL_SLAVE_USER, MYSQL_SLAVE_PASS,"",$this->mysql_port);
                if($conn2 == false)
                {
                    return "mysql connect err!(".MYSQL_SLAVE_HOST."|".MYSQL_SLAVE_USER."|".$this->mysql_port.")";
                }
            }
        }
    }
    /**
     * 检查memcache
     * 只检查本机
     */
    public function check_memcache()
    {
        if(function_exists("memcache_connect"))
        {
            $memcache_obj = memcache_connect(MEMCACHE_HOST, $this->memcache_port);
            if($memcache_obj == false)
            {
                return "memcache connect err! (".MEMCACHE_HOST.":{$this->memcache_port})"; 
            }
            
        }
    }
    /**
     * 检查mongodb
     * 扩展有两种
     */
    public function check_mongodb()
    {
        $class = ''; 
        if(class_exists("MongoClient"))
        { 
            $class = "MongoClient"; 
        }elseif(class_exists("Mongo"))
        {
            $class = "Mongo";
        }elseif(class_exists("MongoDB\Driver\Manager"))
        {
            $class = "MongoDB\Driver\Manager";
        }
        if($class)
        {
            if(MONGODB_USER)
            {
                $user = MONGODB_USER.":".MONGODB_PASS."@";
            }else{
                $user = "";
            }
            $str = "mongodb://".$user.MONGODB_HOST.":{$this->port}";
            $conn = new $class(MONGODB_HOST); 
            if(!$result)
            {
                return "mongodb connect err! (".MONGODB_HOST.":{$this->redis_port})"; 
            }
        }
    }
    /**
     * 检查redis
     * 使用扩展或predis
     * 这个检测，不支持predis
     */
    public function check_redis()
    {
        $redis = new redis();  
        $result = $redis->connect(REDIS_HOST, $this->redis_port);  
        if(!$result)
        {
            return "redis connect err! (".REDIS_HOST.":{$this->redis_port})"; 
        }
    }
    /*
     * 运行
     */
    public function run()
    {
        foreach($this->_env_radio as $serve=>$examine)
        {
            if($examine == '0')
            {
                continue;
            }
            $exe_fun = "check_{$serve}";
            $tmp = $this->{$exe_fun}();
            if(!empty($tmp))
            {
                $this->err[$serve] = $tmp.PHP_EOL;
            }
        }
        if($this->err)
        {
            $err = implode("|",$this->err);
            echo "本机ip:{$this->ip} ".$err;
        }else{
            echo $this->msg;
        }
    }
}
$app = new detection($_env_radio,$write_folder_config);
$app->run();
// $app->info();
