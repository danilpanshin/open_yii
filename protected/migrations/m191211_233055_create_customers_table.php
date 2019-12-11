<?php

class m191211_233055_create_customers_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('customers', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'balance' => 'integer NOT NULL',
        ));
	}

	public function down()
	{
		$this->dropTable('customers');
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