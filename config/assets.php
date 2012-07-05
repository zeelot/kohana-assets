<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * This is where assets and asset dependencies are defined.
 *
 * @package    YurikoCMS
 * @author     Lorenzo Pisani - Zeelot
 * @copyright  (c) 2008-2010 Lorenzo Pisani
 * @license    http://yurikocms.com/license
 */

return array
(
	/**
	 * Each group has an array of assets that gets included when the group is requested.
	 * Weight defaults to 0, lower weighted assets output first.
	 * Section is just a namespace and can be anything.
	 *     - head/body are good examples for dividing js you want in <head> or at the end of <body>
	 *     - it is up to you to output the various sections in the right place
	 * Attributes is an array of attributes added to the script or style tag
	 * Wrapper is an array where the first and second strings are wrapped around the tag
	 *
	 * 'group-name' => array
	 * (
	 *     array('[style/script]', '[path]', '[section]', [weight], [attributes], [wrapper]),
	 *     array('style', 'css/styles.css', 'head'),
	 *     array('script', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', 'body'),
	 *     array('script', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', 'body', 10),
	 *     array('script', 'http://example.com/test.js', 'body', 20, array('id' => 'test-script'), array('<!--[if IE 6]>', '<![endif]-->')),
	 * ),
	 *
	 * This group is added like this:
	 * $assets->group('group-name');
	 *
	 * This returns the css file
	 * $assets->get('head');
	 *
	 * This returns the two js files
	 * $assets->get('body');
	 *
	 * If other groups were added and they contained files in 'head' or 'body'
	 * they would be merged into the array returned by `get()`
	 */
);
