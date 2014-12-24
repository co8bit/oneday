<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
    protected function _initialize()
    {
        //parent::_initialize();
        header("Content-Type:text/html; charset=utf-8");
    }
    

    /**
     * 登录好后设置session
     * @param array $data 要设置session所需要的信息数组
     */
    protected function setSession($data)
    {
        session('userName',$data['name']);
        session("uid",$data["uid"]);
    }

    /**
     * 用户登录函数
     * @param string $userName 用户名
     * @param string  密码
     * @return bool"" 是否成功
     */
    public function login() 
    {
        //if (IS_POST)
       // {
            $userName           =       I('param.userName');
            $userPassword       =       I('param.userPassword');
            empty($userName) && $this->error("错误：用户名不能为空");
            empty($userPassword) && $this->error("错误：密码不能为空");
            
            if ( $result = D("User")->login($userName,$userPassword) )
            {
                //设置session
                $this->setSession($result);
                echo "true";
            }
            else
            {
                echo "false";
            }
       // }
    }
    
    /**
     * 用户退出函数
     * @return "true" 成功
     * @return "error" 各种理由的操作失败
     */
    public function logout()//安全退出
    {
        //判断session是否存在
        if (!session('?uid'))
        {
            echo "error";
        }
    
        //删除session
        session('userName',null);
        session('uid',null);
    
        //再次判断session是否存在
        if ( session('?uid') )
            echo "error";
        else
            echo "true";
    }

    /**
     * 注册函数
     * @param string $userName 用户名
     * @param string $userPassword 密码
     * @return bool "" 是否成功
     *         "error" 用户名已存在
     */
    public function sign()
    {
        $dbUser = D("User");
        $data["name"]      =       I('param.userName');
        $data["pwd"]       =       I('param.userPassword');
        empty($data["name"]) && $this->error("错误：用户名不能为空");
        empty($data["pwd"]) && $this->error("错误：密码不能为空");

        //判断用户名是否重复
        if ( !empty($dbUser->where(array("name"=>$data["name"]))->find()) )
            echo "error";

        $userId = $dbUser->add($data);
        if(empty($userId))//添加失败
        {
            echo "false";
        }
        else
        {
            $data["uid"]    =   $userId;
            $this->setSession($data);
            echo "true";
        }
    }
}