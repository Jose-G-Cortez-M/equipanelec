<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711181937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, imagen VARCHAR(512) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_movimiento (material_id INT NOT NULL, movimiento_id INT NOT NULL, INDEX IDX_68CD6C18E308AC6F (material_id), INDEX IDX_68CD6C1858E7D5A2 (movimiento_id), PRIMARY KEY(material_id, movimiento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movimiento (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material_movimiento ADD CONSTRAINT FK_68CD6C18E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE material_movimiento ADD CONSTRAINT FK_68CD6C1858E7D5A2 FOREIGN KEY (movimiento_id) REFERENCES movimiento (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_movimiento DROP FOREIGN KEY FK_68CD6C18E308AC6F');
        $this->addSql('ALTER TABLE material_movimiento DROP FOREIGN KEY FK_68CD6C1858E7D5A2');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE material_movimiento');
        $this->addSql('DROP TABLE movimiento');
    }
}
