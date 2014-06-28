<?php
class Data extends \Asgard\Migration\DBMigration {
	public function up() {
		$table = $this->app['config']['database.prefix'].'data';
		$this->app['schema']->create($table, function($table) {	
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
	
	public function down() {
		$this->app['schema']->drop($this->app['config']['database.prefix'].'data');
	}
}