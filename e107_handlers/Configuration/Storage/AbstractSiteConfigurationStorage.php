<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration\Storage;


/**
 * File-based site configuration storage for e107
 * @package e107\Configuration\Storage
 */
abstract class AbstractSiteConfigurationStorage implements ConfigurationStorageInterface
{
	protected string $filePath;
	protected const PATH_DEFAULTS = [
		// Current paths
		'admin'     => 'e107_admin/',
		'core'      => 'e107_core/',
		'docs'      => 'e107_docs/',
		'handlers'  => 'e107_handlers/',
		'images'    => 'e107_images/',
		'languages' => 'e107_languages/',
		'media'     => 'e107_media/',
		'plugins'   => 'e107_plugins/',
		'system'    => 'e107_system/',
		'themes'    => 'e107_themes/',
		'web'       => 'e107_web/',

		// Legacy paths
		'files'     => 'e107_files/',
	];

	/**
	 * AbstractSiteConfigurationStorage constructor
	 * @param string $filePath Path to the e107 configuration file.
	 *  Should (but doesn't have to) be an absolute path to avoid $PWD mistakes.
	 */
	public function __construct($filePath)
	{
		$this->filePath = $filePath;
	}

	/**
	 * Get the site configuration file template
	 * @return string The entire template, which should be filled in before being saved to persistent storage
	 */
	abstract protected function getSiteConfigFileTemplate();
}