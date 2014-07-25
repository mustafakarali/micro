<?php /** MicroFormBuilder */

namespace Micro\web;

use Micro\web\helpers\MHtml;
use Micro\widgets\MFormWidget;

/**
 * Class MFormBuilder.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MFormBuilder
{
	/** @property MFormWidget $widget widget for render  */
	protected $widget;
	/** @property MForm $form generator for elements */
	protected $form;
	/** @property array $config config array */
	private $config;
	/** @property Model $model model for get data */
	private $model;


	/**
	 * Constructor object
	 *
	 * @access public
	 * @param array $config
	 * @param Model $model
	 * @param string $method
	 * @param string $type
	 * @param string $action
	 * @result void
	 */
	public function __construct($config = [], $model=null, $method='GET', $type='text/plain', $action='') {
		$this->config = $config;
		$this->model = $model;
		$this->widget = new MFormWidget(array('action'=>$action,'method'=>$method,'type'=>$type));
	}
	/**
	 * Set model data
	 *
	 * Loading data in model from array
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function setModelData($data=[]) {
		foreach ($data AS $key=>$value) {
			$this->model->$key = $value;
		}
	}
	/**
	 * Validation model
	 *
	 * @access public
	 * @return bool
	 */
	public function validateModel() {
		return $this->model->validate();
	}
	/**
	 * Get errors from model
	 *
	 * @access public
	 * @return array
	 */
	public function getModelErrors() {
		return $this->model->getErrors();
	}
	/**
	 * Getting model
	 *
	 * @access public
	 * @return Model
	 */
	public function getModel() {
		return $this->model;
	}
	/**
	 * Convert object to string
	 *
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}
	/**
	 * Render form builder
	 *
	 * @access public
	 * @return string
	 */
	public function render() {
		ob_start();

		$this->beginRender();
		$this->contentRender();
		$this->endRender();

		return ob_get_clean();
	}
	/**
	 * Render form heading
	 *
	 * @access public
	 * @return void
	 */
	public function beginRender() {
		$this->form = $this->widget->init();
		if (isset($this->config['legend'])) {
			echo MHtml::openTag('fieldset');
			echo MHtml::legend( $this->config['legend'] );
		}
		if (isset($this->config['description'])) {
			echo MHtml::openTag('div',array('class'=>'description')), $this->config['description'], MHtml::closeTag('div');
		}
		if ($this->model) {
			if ($errors = $this->getModelErrors()) {
				echo MHtml::openTag('div',array('class'=>'errors'));
				foreach ($errors AS $error) {
					echo MHtml::openTag('div',array('class'=>'error')), $error, MHtml::closeTag('div');
				}
				echo MHtml::closeTag('div');
			}
		}
	}
	/**
	 * Finish form render
	 *
	 * @access public
	 * @return void
	 */
	public function endRender() {
		if (isset($this->config['legend'])) {
			echo MHtml::closeTag('fieldset');
		}
		$this->widget->run();
	}
	/**
	 * Render form elements
	 *
	 * @access public
	 * @param null|array $conf
	 * @return void
	 */
	public function contentRender($conf=null) {
		if (!$conf) {
			$conf = $this->config;
		}
		foreach ($conf['elements'] AS $key=>$value) {
			if (is_array($conf['elements'][$key])) {
				if ($value['type']=='form') {
					$subForm = new MFormBuilder($value, (isset($value['model'])) ? $value['model'] : null);
					echo $subForm;
				} elseif ($this->model) {
					echo $this->form->$value['type']($this->model,$key,(isset($value['options'])) ? $value['options'] : []);
				} else {
					echo MHtml::$value['type']($key, $value['value'], $value['options']);
				}
			} else {
				echo $conf['elements'][$key];
			}
		}
		foreach ($this->config['buttons'] AS $button) {
			$type = $button['type'].'Button';
			echo MHtml::$type($button['label'], (isset($button['options'])) ? $button['options'] : [] );
		}
	}
}