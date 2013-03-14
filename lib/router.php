<?php


class router
{


    private $registry;

    private $path;

    private $args = array();


    function __construct($registry)
    {

        $this->registry = $registry;

    }

    function setPath($path)
    {

        $path = trim($path);

        $path .= DIRSEP;


        if (is_dir($path) == false) {

            throw new Exception ('Invalid controller path: `' . $path . '`');

        }


        $this->path = $path;

    }


    function delegate()
    {

        // ����������� ����

        $this->getController($file, $service, $action, $args);


        // ���� ��������?

        if (is_readable($file) == false) {

            die("� ������� $service ���!");

        }


        // ���������� ����

        include($file);


        // ������ ��������� �����������

        $class = 'service_' . $service;

        $service = new $class($this->registry);


        // �������� ��������?

        if (is_callable(array($service, $action)) == false) {

            die ("� ������� $action ���!");

        }


        // ��������� ��������

        $service->$action();

    }


    private function getController(&$file, &$controller, &$action, &$args)
    {

        $route = (empty($_REQUEST['route'])) ? '' : $_REQUEST['route'];


        if (empty($route)) {
            $route = 'index';
        }


        // �������� ���������� �����

        $route = trim($route);

        $parts = explode('/', $route);


        // ������� ���������� ����������

        $cmd_path = $this->path;

        foreach ($parts as $part) {

            $fullpath = $cmd_path . $part;


            // ���� �� ����� � ����� ����?

            if (is_dir($fullpath)) {

                $cmd_path .= $part . DIRSEP;

                array_shift($parts);

                continue;

            }


            // ������� ����

            if (is_file($fullpath . '.php')) {

                $controller = $part;

                array_shift($parts);

                break;

            }

        }


        if (empty($controller)) {
            $controller = 'index';
        }

        $action = array_shift($parts);

        if (empty($action)) {
            $action = 'index';
        }


        $file = $cmd_path . $controller . '.php';

        $args = $parts;

        $this->registry->set('service', $controller);

    }
}
