<?php
class Data extends \Asgard\Migration\DBMigration {
	public function up() {
		$table = $this->container['config']['database.prefix'].'data';
		$this->container['schema']->create($table, function($table) {
			$table->addColumn('id', 'integer', [
				'length' => 11,
			]);
			$table->addColumn('created_at', 'datetime', [
			]);
			$table->addColumn('updated_at', 'datetime', [
			]);
			$table->addColumn('key', 'string', [
			]);
			$table->addColumn('value', 'text', [
			]);
		});
	}

	public function down() {
		$this->container['schema']->drop($this->container['config']['database.prefix'].'data');
	}
}