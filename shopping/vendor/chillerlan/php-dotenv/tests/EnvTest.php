<?php
/**
 * Class EnvTest
 *
 * @created      25.11.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\DotEnvTest;

use chillerlan\DotEnv\{DotEnv, DotEnvException};
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase{

	protected DotEnv $dotenv;

	protected function setUp():void{
		$this->dotenv = new DotEnv(__DIR__, '.env_test');
	}

	public function testLoadInvalidFile():void{
		$this->expectException(DotEnvException::class);
		$this->expectExceptionMessage('invalid file:');

		(new DotEnv('foo'))->load();
	}

	public function testLoadInvalidReadError():void{
		$this->expectException(DotEnvException::class);
		$this->expectExceptionMessage('error while reading file:');

		(new DotEnv(__DIR__, '.env_error'))->load(); // empty file
	}

	public function testLoadRequiredVarMissing():void{
		$this->expectException(DotEnvException::class);
		$this->expectExceptionMessage('required variable not set: FOO');

		$this->dotenv->load(['foo']);
	}

	public function testAddEnv():void{
		$this->dotenv->addEnv(__DIR__, '.env_test');
		$this::assertNull($this->dotenv->get('foo'));

		$this->dotenv->addEnv(__DIR__, '.another_env', true, ['FOO']); // case sensitive here!
		$this::assertSame('BAR', $this->dotenv->get('foo'));
	}

	public function testLoadGet():void{
		$this->dotenv->load(['VAR']);

		$this::assertFalse(isset($_ENV[42])); // numerical keys shouldn't exist in globals

		$this::assertNotEmpty($_ENV); // we're in global mode
		$this::asserttrue(isset($_ENV['VAR']));

		$this::assertSame('test', $_ENV['VAR']);
		$this::assertSame('test', $this->dotenv->get('var'));
		$this::assertSame('test', $this->dotenv->get('VAR'));
		$this::assertSame('test', $this->dotenv->var);
		$this::assertSame('test', $this->dotenv->VAR);
		$this::assertSame($_ENV['VAR'], $this->dotenv->get('VAR'));
		$this::assertSame($_ENV['VAR'], $this->dotenv->VAR);

		$this::assertSame('Oh here\'s some silly &%=ä$&/"§% value', $_ENV['TEST']); // stripped comment line
		$this::assertSame('foo'.PHP_EOL.'bar'.PHP_EOL.'nope', $_ENV['MULTILINE']);

		$this::assertSame('Hello World!', $_ENV['VAR3']);
		$this::assertSame('{$VAR1} $VAR2 {VAR1}', $_ENV['VAR4']); // not resolved
	}

	public function testSetUnsetClear():void{
		$this->dotenv->load();

		$this::assertTrue(isset($_ENV['TEST']));
		$this::assertTrue(isset($this->dotenv->TEST));
		unset($this->dotenv->TEST);
		$this::assertFalse(isset($_ENV['TEST']));
		$this::assertFalse($this->dotenv->get('test'));
		$this::assertFalse($this->dotenv->test);

		// generic
		$this->dotenv->set('TESTVAR', 'some value: ${var3}');
		$this::assertSame('some value: Hello World!', $_ENV['TESTVAR']);
		$this::assertSame('some value: Hello World!', $this->dotenv->get('TESTVAR'));

		// magic
		$this->dotenv->TESTVAR = 'some other value: ${var3}';
		$this::assertSame('some other value: Hello World!', $_ENV['TESTVAR']);
		$this::assertSame('some other value: Hello World!', $this->dotenv->TESTVAR);

		$this->dotenv->clear();

		$this::assertSame([], $_ENV);
	}

}
