<?php

class service_index extends service_base
{


    function index()
    {
        $this->registry['result']->set('name', '�����');
        $this->registry['result']->show('index');
    }


}


?>
