<?php


abstract class service_base
{


    protected $registry;

    protected $request;


    protected $chat, $chat_id, $nick, $nick_id, $user, $user_id, $sex;

    protected $DB, $result;


    function __construct($registry)
    {

        $this->registry = $registry;

        if (empty($_REQUEST['data']))
            die("Ошибка ввода вывода.");

        $this->request = $_REQUEST['data'];


        $this->checkmd5();


        $this->chat_id = $this->request['chat_id'];
        $this->chat = $this->request['chat'];

        $this->nick_id = $this->request['nick_id'];
        $this->nick = $this->request['nick'];

        $this->user_id = $this->request['user_id'];
        //$this->user = $this->request['user'];

        $this->sex = $this->request['sex'];

        $this->DB = $this->registry['DB'];
        $this->result = $this->registry['result'];


    }

    private function checkmd5()
    {

        $service = $this->registry['service'];
        $chat = $this->request['chat'];
        $md5 = $this->request['md5'];

        $igust_2_0 = $this->registry['igust_2_0'];

        if (isset($igust_2_0[$service][$chat])) {
            $key = $igust_2_0[$service][$chat];

            foreach ($this->request as $k => $v) {
                if ($k != "md5") {
                    $key .= $v;
                }
            }

            $md_control = md5($key);

            if ($md_control != $md5) {
                die("Нарушена целостность данных");
            }

        }
        else {
            die("Чат не подключен к сервису");
        }


    }


    abstract function index();

}


?>
