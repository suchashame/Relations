<?php

class service_index extends service_base
{


    function index()
    {
        $this->registry['result']->set('name', 'υσινÿ');
        $this->registry['result']->show('index');
    }


}


?>
