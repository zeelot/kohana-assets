<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * The assets class handles loading view dependencies and simple assets.
 *
 * @package    YurikoCMS
 * @author     Lorenzo Pisani - Zeelot
 * @copyright  (c) 2008-2011 Lorenzo Pisani
 * @license    http://yurikocms.com/license
 */

class Yuriko_Assets {

	/**
	 * Basic factory method, simply for chaining
	 */
	public static function factory(Object $config = NULL)
	{
		return new Assets($config);
	}

	/**
	 * Group of requested assets
	 */
	protected $_requested = array();

	/**
	 * @var  array  Collection of variables of pass to JavaScript
	 */
	protected $_pass = array();

	protected $_config;

	public function __construct(ArrayObject $config = NULL)
	{
		$this->_config = ($config) ? $config : Kohana::$config->load('assets');
	}

	public function group($name)
	{
		// Key to remove duplicates, value for simplicity
		$this->_requested[$name] = $name;

		return $this;
	}

	public function groups(array $names)
	{
		foreach ($names as $name)
		{
			$this->group($name);
		}

		return $this;
	}

	/**
	 * Pass variable to JavaScript through 'head' section
	 *
	 * @param  array  $pairs  An array that will be converted into JSON eventually
	 * @param  bool   $reset  Reset previous passes?
	 * @return $this
	 */
	public function pass(array $pairs, $reset = FALSE)
	{
		if ($reset)
		{
			$this->_pass = array();
		}

		foreach ($pairs as $key => $value)
		{
			$this->_pass[$key] = $value;
		}

		return $this;
	}

	public function get($section = NULL)
	{
		$assets = array();

		foreach ($this->_requested as $name)
		{
			if (($group = Arr::get($this->_config, $name)) !== NULL)
			{
				foreach ($group as $asset)
				{
					if ($asset[2] === $section)
					{
						$assets[] = $asset;
					}
				}
			}
			else
			{
				// Log a warning
				Kohana::$log->add(Log::INFO, 'Could not find assets group `'.$name.'`');
			}
		}

		// Sort the assets
		usort($assets, array($this, '_sort_assets'));

		$array = array();

		// Prepend pass-through variables to JavaScript
		if ($section == 'head')
		{
			$array[] = $this->_escape_pass();
		}

		foreach ($assets as $asset)
		{
			$attributes = Arr::get($asset, 4, array());
			// This wraps around the style or script tag
			$wrapper = Arr::get($asset, 5, array('', ''));

			( ! $attributes) AND $attributes = array();
			( ! $wrapper) AND $wrapper = array('', '');

			if ($asset[0] == 'script')
			{
				$array[] = $wrapper[0].HTML::script($asset[1], $attributes).$wrapper[1];
			}
			elseif ($asset[0] == 'style')
			{
				$array[] = $wrapper[0].HTML::style($asset[1], $attributes).$wrapper[1];
			}
		}

		return $array;
	}

	/**
	 * Escape pass-through variables as a JavaScript inline block
	 *
	 * @return string
	 */
	protected function _escape_pass()
	{
		$escaped = 'window.pass = '.json_encode($this->_pass).';';
		return '<script type="text/javascript">'.$escaped.'</script>';
	}

	/**
	 * Custom sorting method for assets based on 'weight' key
	 */
	protected function _sort_assets($a, $b)
	{
		( ! isset($a[3])) AND $a[3] = 0;
		( ! isset($b[3])) AND $b[3] = 0;

		if ($a[3] == $b[3]) {
			return 0;
		}

		return ($a[3] - $b[3]);
	}
} // End Assets
