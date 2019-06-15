<?php
namespace Ogsteam\Ogspy\Core;

if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
}

class Template
{
    private $vars  = array();
    private $tpl_file;

    function __construct($file) {
        if (!is_file($file))
        {
            throw new Exception("Where is template file");
        }
        $this->tpl_file = $file;
    }

    public function __get($name) {
        return $this->vars[$name];
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function __isset($name){
        return isset($this->vars[$name]);
    }

    public function __unset($name){
        unset($this->vars[$name]);
    }


    public function add($name, $value) {
        if (!isset($this->vars[$name]))
        {
            $this->vars[$name] = array();
            $this->vars[$name][]= $value ;
        }
        else
        {
            if (is_array($this->vars[$name]))
            {
                $this->vars[$name][]= $value ;
            }
            else
            {
                $tmp =  $this->vars[$name];
                $this->vars[$name] = array();
                $this->vars[$name][]= $tmp ;
                $this->vars[$name][]= $value ;
            }
        }
    }


    public function render() {
        extract($this->vars);
        ob_start();
        include($this->tpl_file);
        return ob_get_clean();
       }


}