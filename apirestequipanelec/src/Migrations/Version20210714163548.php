<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Habbim\IdToUuid\IdToUuidMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210714163548 extends IdToUuidMigration
{
    public function postUp(Schema $schema): void
    {
        $this->migrate('material');
        $this->migrate('movimiento');
    }
}