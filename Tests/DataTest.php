<?php
namespace Asgard\Data\Tests;

class DataTest extends \PHPUnit_Framework_TestCase {
	protected static $db;

	public static function setUpBeforeClass() {
		static::$db = $db = new \Asgard\Db\DB([
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'asgard'
		]);
		$schema = new \Asgard\Db\Schema($db);

		$table = 'data';
		try {
			$schema->drop($table);
		} catch(\Exception $e) {}
		$schema->create($table, function($table) {	
			$table->add('id', 'int(11)')
				->autoincrement()
				->primary();	
			$table->add('created_at', 'datetime')
				->nullable();
			$table->add('updated_at', 'datetime')
				->nullable();
			$table->add('key', 'varchar(255)')
				->nullable();
			$table->add('value', 'text')
				->nullable();
		});
	}

	public function test() {
		$data = new \Asgard\Data\Data(static::$db);

		$this->assertEquals(null, $data->get('foo'));

		$data->set('foo', 123);
		$this->assertEquals(123, $data->get('foo'));

		$data->set('foo', array('a'=>'b', 2));
		$this->assertEquals(array('a'=>'b', 2), $data->get('foo'));

		$data->register(
			'bar',
			function($input) {
				return $input->name;
			},
			function($input) {
				$bar = new Bar;
				$bar->name = $input;
				return $bar;
			}
		);
		$bar = new Bar;
		$bar->name = 'bob';
		$data->set('test', $bar, 'bar');
		$this->assertEquals($bar, $data->get('test'));

		$this->assertEquals($bar, $data['test']);
		$this->assertTrue(isset($data['test']));
		$this->assertTrue($data->has('test'));
		$data->delete('test');
		$this->assertFalse($data->has('test'));

		$data['test'] = 'abc';
		$this->assertEquals('abc', $data['test']);
		unset($data['test']);
		$this->assertFalse(isset($data['test']));
	}
}

class Bar {
	public $name;
}