<?php
namespace Progsmile\Validator\Rules;

abstract class BaseRule
{
    const CONFIG_ALL         = 'all';
    const CONFIG_DATA        = 'data';
    const CONFIG_ORM         = 'orm';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;

    protected $params;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL){
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    protected function hasRule($rule)
    {
        if(!$rule){
            return false;
        }

        return strpos($this->getConfig(self::CONFIG_FIELD_RULES), $rule) !== false;
    }

    /**
     * Check if variable is not required - to prevent error messages from another validators
     *
     * @param string $type | 'var' or 'file'
     * @return bool
     */
    protected function isNotRequired($type = 'var')
    {
        $condition = false;

        if ($type == 'var'){
            $condition = !$this->params[1];

        } elseif ($type == 'file') {

            //when file field is not required, but we send it
            $condition = isset($_FILES[$this->params[0]]['size']) && $_FILES[$this->params[0]]['size'] == 0;
        }

        return !$this->hasRule('required') && $condition;
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the message if error occured
     *
     * @return string
     */
    public abstract function getMessage();


    /**
     * Will the process to check if it is valid or not
     *
     * @return boolean Return the result if valid or not
     */
    public abstract function isValid();

}