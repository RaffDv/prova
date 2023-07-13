<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateUser extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('Users');
        $table
              ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->update();
    }
}
