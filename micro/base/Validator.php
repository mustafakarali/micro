<?php /** MicroValidator */

namespace Micro\base;

use Micro\Micro;

/**
 * Validator is a runner validation process
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Validator
{
    /** @property array $rule */
    private $rule = [];
    /** @property array $errors */
    public $errors = [];
    /** @property array $elements */
    public $elements = [];
    /** @property array $params */
    public $params = [];
    /** @property array $validators */
    protected $validators = [
        'string' => 'StringValidator',
        'required' => 'RequiredValidator',
        //		'filter'=>'MFilterValidator',
        //		'match'=>'MRegularExpressionValidator',
        //		'email'=>'MEmailValidator',
        //		'url'=>'MUrlValidator',
        //		'unique'=>'MUniqueValidator',
        //		'compare'=>'MCompareValidator',
        //		'in'=>'MRangeValidator',
        //		'numerical'=>'MNumberValidator',
        //		'captcha'=>'MCaptchaValidator',
        //		'type'=>'MTypeValidator',
        //		'file'=>'MFileValidator',
        //		'default'=>'MDefaultValueValidator',
        //		'exist'=>'MExistValidator',
        //		'boolean'=>'MBooleanValidator',
        //		'safe'=>'MSafeValidator',
        //		'unsafe'=>'MUnsafeValidator',
        //		'date'=>'MDateValidator',
    ];


    /**
     * Constructor validator object
     *
     * @access public
     * @param array $rule
     * @result void
     */
    public function __construct($rule = [])
    {
        $this->rule = $rule;
    }

    /**
     * Check is empty property
     *
     * @access protected
     * @param $value
     * @param bool $trim
     * @return bool
     */
    protected function isEmpty($value, $trim = false)
    {
        return $value === null || $value === [] || $value === '' || $trim && is_scalar($value) && trim($value) === '';
    }

    /**
     * Get errors after run validation
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Running validation process
     *
     * @access public
     * @param $model
     * @param bool $client
     * @return bool|string
     */
    public function run($model, $client = false)
    {
        $elements = explode(',', str_replace(' ', '', array_shift($this->rule)));
        $name = array_shift($this->rule);

        $filename = Micro::getInstance()->config['MicroDir'] . '/validators/' . $this->validators[$name] . '.php';
        $extFilename = Micro::getInstance()->config['AppDir'] . '/validators/' . $name . '.php';

        if (file_exists($filename)) {
            include_once $filename;
        } elseif (file_exists($extFilename)) {
            include_once $extFilename;
        }

        $valid = new $this->validators[$name];
        $valid->elements = $elements;
        $valid->params = $this->rule;
        if ($client) {
            $result = $valid->client($model);
        } else {
            $result = $valid->validate($model);
        }

        if ($valid->errors) {
            $this->errors[] = $valid->errors;
        }
        return $result;
    }
}