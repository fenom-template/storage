<?php

namespace Fenom;


trait StorageTrait
{
    /**
     * @var array storage
     */
    protected $_vars = array();

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assign($name, $variable)
    {
        $this->_vars[$name] = $variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $key
     * @param mixed $variable
     * @return $this
     */
    public function assignKey($name, $key, $variable)
    {
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        $this->_vars[$name][$key] = $variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assignByRef($name, &$variable)
    {
        $this->_vars[$name] = & $variable;
        return $this;
    }

    /**
     * Set all variables
     * @param array $vars
     * @param bool $merge merge new variables array with current or rewrite
     * @return $this
     */
    public function assignAll(array $vars, $merge = true) {
        if($merge) {
            $this->_vars = $vars + $this->_vars;
        } else {
            $this->_vars = $vars;
        }
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function prepend($name, $variable)
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        array_unshift($this->_vars[$name], $variable);
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function append($name, $variable)
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        $this->_vars[$name][] = $variable;
        return $this;
    }

    /**
     * Get collected variables
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Reset collected variables
     * @return $this
     */
    public function resetVars()
    {
        $this->_vars = array();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function display($template, array $vars = array())
    {
        /* @var \Fenom|StorageTrait $this */
        return $this->_vars = $this->getTemplate($template)->display($vars ? $vars + $this->_vars : $this->_vars);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($template, array $vars = array())
    {

        /* @var \Fenom|StorageTrait $this */
        $tpl = $this->getTemplate($template);
        ob_start();
        try {

            $this->_vars = $tpl->display($vars ? $vars + $this->_vars : $this->_vars);
            return ob_get_clean();
        } catch(\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function pipe($template, $callback, array $vars = array(), $chunk = 1e6)
    {
        /* @var \Fenom|StorageTrait $this */
        ob_start($callback, $chunk, PHP_OUTPUT_HANDLER_STDFLAGS);
        $data = $this->getTemplate($template)->display($vars ? $vars + $this->_vars : $this->_vars);
        ob_end_flush();
        return $data;
    }
}
