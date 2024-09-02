<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240902081031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert data into products table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO product (id, description, category, price) VALUES ('A101', 'Screwdriver', 1, 9.75)");
        $this->addSql("INSERT INTO product (id, description, category, price) VALUES ('A102', 'Electric Screwdriver', 1, 49.50)");
        $this->addSql("INSERT INTO product (id, description, category, price) VALUES ('B101', 'Basic on-off switch', 2, 4.99)");
        $this->addSql("INSERT INTO product (id, description, category, price) VALUES ('B102', 'Press button', 2, 4.99)");
        $this->addSql("INSERT INTO product (id, description, category, price) VALUES ('B103', 'Switch with motion detector', 2, 12.95)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM product WHERE id IN ('A101', 'A102', 'B101', 'B102', 'B103')");
    }
}
