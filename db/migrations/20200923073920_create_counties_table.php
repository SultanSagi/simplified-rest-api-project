<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCountiesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('counties');
        $table->addColumn('name', 'string')
            ->addColumn('tax_amount', 'integer')
            ->addColumn('tax_rate', 'integer')
            ->create();
    }
}
