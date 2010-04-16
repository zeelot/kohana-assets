<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * The assets class handles loading view dependencies and simple assets.
 * 
 * @package    YurikoCMS
 * @author     Lorenzo Pisani - Zeelot
 * @copyright  (c) 2008-2009 Lorenzo Pisani
 * @license    http://yurikocms.com/license
 */
 
class Yuriko_Assets {
	
	/**
	 * Asset groups defined in config files
	 *
	 * @var array
	 */
	protected $_assets = array();

	/**
	 * Wether or not the dependencies have been calculated
	 *
	 * @var bool
	 */
	protected $_loaded_dependencies;

	/**
	 * Returns the instance of the class
	 *
	 * @staticvar Assets $instance
	 * @return Assets
	 */
	public static function instance()
	{
		static $instance;
		( ! $instance) AND $instance = new self();

		return $instance;
	}

	protected function __construct(){}

	public function render()
	{
		// calculate the dependencies just once
		! $this->_loaded_dependencies AND $this->load_dependencies();

		// sort the assets
		usort($this->_assets, array($this, 'sort_assets'));

		$output = "\n";
		
		foreach ($this->_assets as $group)
		{
			$styles = Arr::get($group, 'css', array());
			$scripts = Arr::get($group, 'js', array());

			// css files
			foreach ($styles as $file => $params)
			{
				$wrapper = Arr::get($params, 'wrapper', array('', ''));
				$attributes = Arr::get($params, 'attributes', NULL);

				$output .= $wrapper[0]."\n";
				$output .= HTML::style($file, $attributes)."\n";
				$output .= $wrapper[1]."\n";
			}

			// js files
			foreach ($scripts as $file => $params)
			{
				$wrapper = Arr::get($params, 'wrapper', array('', ''));
				$attributes = Arr::get($params, 'attributes', NULL);

				$output .= $wrapper[0]."\n";
				$output .= HTML::script($file, $attributes)."\n";
				$output .= $wrapper[1]."\n";
			}
		}

		return $output;
	}

	/**
	 * Adds an asset group to the list of files to include
	 *
	 * @param String $key - config key of the asset group
	 */
	public function add_group($key)
	{
		$group = Kohana::config('assets.'.$key);

		$group AND $this->_assets[] = $group;
	}

	/**
	 * Custom sorting method for assets based on 'weight' key
	 */
	public function sort_assets($a, $b)
	{
        if ($a['weight'] == $b['weight']) {
            return 0;
        }
        return ($a['weight'] > $b['weight']) ? +1 : -1;
	}
	
	/**
	 * Calculates all the dependencies based on which views were loaded
	 * for this request. Only runs once per request (should be done at the end)
	 *
	 * @param bool $force - will reload dependencies if TRUE
	 */
	protected function load_dependencies($force = FALSE)
	{
		// run this method the first time only
		if ($this->_loaded_dependencies AND ! $force)
			return;

		// the Views that where used
		$views = (array)View::$loaded;
		$view_string = ';'.implode(';', $views);

		// the assets that where defined
		$assets = (array)Kohana::config('assets');

		foreach ($assets as $key => $asset)
		{
			$pattern = Arr::get($asset, 'pattern');
			if ($pattern === FALSE)
				continue;

			// switch any leading ^ to a ; (to match path beginnings in our view_string)
			if (substr($pattern, 0, 1) === '^')
				$pattern = ';'.substr($pattern, 1);

			if (preg_match($pattern, $view_string))
				$this->add_group($key);
		}
		$this->_loaded_dependencies = TRUE;
	}
} // End Assets
