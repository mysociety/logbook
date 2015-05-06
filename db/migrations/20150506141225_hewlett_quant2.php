<?php

use Phinx\Migration\AbstractMigration;

class HewlettQuant2 extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('quant2_log');
        $table->addColumn('timestamp', 'integer')
              ->addColumn('site', 'string')
              ->addColumn('page', 'string')
              ->addColumn('bucket', 'integer')
              ->addColumn('event', 'string')
              ->addColumn('data', 'string', array('null' => true))
              ->addColumn('timer', 'integer', array('null' => true))
              ->save();
    }

    /**
     * Migrate Up.
     */
    public function up()
    {

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
