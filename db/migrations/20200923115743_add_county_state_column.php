<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddCountyStateColumn extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('counties');
        $table->addColumn('state_id', 'integer')
            ->update();

        $table->addForeignKey('state_id', 'states', 'id', ['delete' => 'CASCADE', 'update'=> 'NO_ACTION'])
            ->update();
    }

    public function down()
    {
        $table = $this->table('counties');
        $table->dropForeignKey('state_id')
            ->save();

        $table->removeColumn('state_id')
            ->save();
    }
}
