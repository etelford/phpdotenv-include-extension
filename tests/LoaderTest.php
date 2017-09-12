<?php

class LoaderTest extends \PHPUnit\Framework\TestCase
{
	public function setUp()
	{
		$this->fixtures =  __DIR__ . '/fixtures';

		parent::setUp();
	}

	/** @test */
	public function it_loads_standard_environment_variables()
	{
		$this->loadEnvironment();

		$this->assertSame('bar', getenv('FOO'));
		$this->assertSame('baz', getenv('BAR'));
		$this->assertSame('with spaces', getenv('SPACED'));
        $this->assertEmpty(getenv('NULL'));
	}

	/** @test */
	public function it_parses_out_comments_in_standard_environment_variables()
	{
		$this->loadEnvironment();

		$this->assertSame('bar', getenv('CFOO'));
	}

	/** @test */
	public function it_parses_out_variables_in_standard_environment_variables()
	{
		$this->loadEnvironment();

		$this->assertSame('bar baz', getenv('VBAR'));
	}

	/** @test */
	public function it_loads_an_env_file_as_an_include()
	{
		$this->loadEnvironment('.include-assertion.env');

		$this->assertSame('bar', getenv('FOO'));

		$this->assertSame('baz', getenv('BAR_BAR'));
		$this->assertSame('with spaces', getenv('BAR_SPACED'));
        $this->assertEmpty(getenv('BAR_NULL'));
        $this->assertSame('fuzz', getenv('BAR_FOO'));
        $this->assertSame('bar', getenv('BAR_CFOO'));
        $this->assertSame('bar buzz fuzz', getenv('BAR_NESTED'));
	}

		/** @test */
	public function it_recursively_loads_env_files_as_includes()
	{
		$this->loadEnvironment('.include-recursive-assertion.env');
		
		$this->assertSame('bar', getenv('FOO'));

		$this->assertSame('baz', getenv('BAZ_BAR'));
		$this->assertSame('with spaces', getenv('BAZ_SPACED'));
        $this->assertEmpty(getenv('BAZ_NULL'));
        $this->assertSame('fuzz', getenv('BAZ_FOO'));
        $this->assertSame('bar', getenv('BAZ_CFOO'));
        $this->assertSame('bar buzz fuzz', getenv('BAZ_NESTED'));

		$this->assertSame('baz', getenv('BAZ_BUZZ_BAR'));
		$this->assertSame('with spaces', getenv('BAZ_BUZZ_SPACED'));
        $this->assertEmpty(getenv('BAZ_BUZZ_NULL'));
        $this->assertSame('fuzz', getenv('BAZ_BUZZ_FOO'));
        $this->assertSame('bar', getenv('BAZ_BUZZ_CFOO'));
        $this->assertSame('bar buzz fuzz', getenv('BAZ_BUZZ_NESTED'));
        $this->assertSame('buzz fuzz baz', getenv('BAZ_BUZZ_DOUBLE_NESTED'));
	}

	/**
	 * Load the env file
	 * 
	 * @param  string $file
	 * @return void
	 */
	protected function loadEnvironment($file = '.env', $pathKey = 'INCLUDE')
	{
		$dotenv = new Etelford\Dotenv($this->fixtures, $file, $pathKey);
		$dotenv->load();
	}
}