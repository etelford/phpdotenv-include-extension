<?php

namespace Etelford;

use Dotenv\Loader as LoaderBase;

/**
 * This is the loaded class.
 *
 * It's responsible for loading variables by reading a file from disk and:
 * - stripping comments beginning with a `#`,
 * - parsing lines that look like they are attempting to include a sub .env file
 * - parsing lines that look shell variable setters, e.g `export key = value`, `key="value"`.
 */
class Loader extends LoaderBase
{
	/**
	 * The keyword to decide if this is a path and not a regular variable
	 * 
	 * @var string
	 */
	protected $pathKey;

	/**
	 * Create a new loader instance.
	 *
	 * @override
	 * @param string $filePath
	 * @param bool   $immutable
	 * @param string $pathKey
	 * @return void
	 */
	public function __construct($filePath, $immutable = false, $pathKey)
	{
	    $this->filePath = $filePath;
	    $this->immutable = $immutable;
	    $this->pathKey = $pathKey;
	}

	/**
	 * Load `.env` file in given directory.
	 *
	 * @override
	 * @return array
	 */
	public function load($lines = null, $prefix = '')
	{
		if (is_null($lines)) {		
		    $this->ensureFileIsReadable();

		    $filePath = $this->filePath;
		    $lines = $this->readLinesFromFile($filePath);
		}

	    foreach ($lines as $line) {
	    	if ($this->isComment($line)) {
	    		continue;
	    	}

	        if ($this->looksLikePath($line)) {
	        	list($name, $file) = $this->parsePathedEnvironmentVariable($line);

	        	if ($this->canLoadInclude($file)) {
		        	$lines = $this->readLinesFromFile($this->parseIncludeFilePath($file));

		        	$this->load($lines, $prefix . $name . '_');
	        	}
	        }

	        if ($this->looksLikeSetter($line)) {
	            $this->setEnvironmentVariable($prefix . $line);
	        }
	    }

	    return $lines;
	}

	/**
	 * Determine if the given line looks like it's setting a variable.
	 *
	 * @param string $line
	 * @return bool
	 */
	protected function looksLikePath($line)
	{
	    return strpos($line, '_' . $this->pathKey) !== false;
	}

	/**
	 * Parse the root name of the variable and its value, which will be the
	 * path to the include
	 * 
	 * @param  string 		$name  
	 * @param  string|null  $value
	 * @return void
	 */
	protected function parsePathedEnvironmentVariable($name, $value = null)
	{
		list($name, $value) = $this->normaliseEnvironmentVariable($name, $value);

		return [str_replace('_'.$this->pathKey, '', $name), $value];
	}

	/**
	 * Get the contents of the include file
	 * 
	 * @param  string $path
	 * @return string
	 */
	protected function canLoadInclude($file)
	{
		$includeFilePath = $this->parseIncludeFilePath($file);

		return is_readable($includeFilePath) && is_file($includeFilePath);
	}

	/**
	 * Figure out the path of the include file relative to the loaded .env
	 * 
	 * @param  string $file
	 * @return string
	 */
	protected function parseIncludeFilePath($file)
	{
		$path = explode('/', $this->filePath);
		array_pop($path);
		
		return implode($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
	}
}