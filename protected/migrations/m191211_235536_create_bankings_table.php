<?php

class m191211_235536_create_bankings_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('bankings', array(
            'id' => 'pk',
            'customer_id' => 'integer NOT NULL REFERENCES customers(id)',
            'transfer' => 'integer NOT NULL',
		));
	}

	public function down()
	{
		$this->dropTable('bankings');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}