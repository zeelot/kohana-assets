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
	public static function factory()
	{
		return new Assets();
	}

	/**
	 * Group of requested assets
	 */
	protected $_requested = array();

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

	public function get($section = NULL)
	{
		$assets = array();

		foreach ($this->_requested as $name)
		{
			if (($group = Kohana::config('assets.'.$name)) !== NULL)
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
				Kohana::$log->add(Kohana::INFO, 'Could not find assets group `'.$name.'`');
			}
		}

		// Sort the assets
		usort($assets, array($this, '_sort_assets'));

		$array = array();
		foreach ($assets as $asset)
		{
			if ($asset[0] == 'script')
			{
				$array[] = HTML::script($asset[1]);
			}
			elseif ($asset[0] == 'style')
			{
				$array[] = HTML::style($asset[1]);
			}
		}

		return $array;
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
