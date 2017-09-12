<?php

namespace Etelford;

use Dotenv\Dotenv as DotenvBase;

/**
 * @inheritDoc
 */
class Dotenv extends DotenvBase
{
	/**
	 * The keyword to decide if this is a path and not a regular variable
	 * 
	 * @var string
	 */
	protected $pathKey;

	/**
	 * Create a new dotenv instance.
	 *
	 * @ovveride
	 * @param string $path
	 * @param string $file
	 * @param string $pathKey
	 *
	 * @return void
	 */
	public function __construct($path, $file = '.env', $pathKey = 'INCLUDE')
	{
	    $this->filePath = $this->getFilePath($path, $file);
	    $this->pathKey = $pathKey;
	    $this->loader = new Loader($this->filePath, true, $this->pathKey);
	}

	/**
	 * Actually load the data.
	 *
	 * @ovveride
	 * @param bool $overload
	 * @return array
	 */
	protected function loadData($overload = false)
	{
	    $this->loader = new Loader($this->filePath, ! $overload, $this->pathKey);

	    return $this->loader->load();
	}
}