<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStateCountryColumn extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('states');
        $table->addColumn('country_id', 'integer')
            ->update();

        $table->addForeignKey('country_id', 'countries', 'id', ['delete' => 'CASCADE', 'update'=> 'NO_ACTION'])
            ->update();
    }

    public function down()
    {
        $table = $this->table('states');
        $table->dropForeignKey('country_id')
            ->save();

        $table->removeColumn('country_id')
            ->save();
    }
}
