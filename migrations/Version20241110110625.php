<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110110625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_slot DROP FOREIGN KEY FK_1B3294AA31B2BA6');
        $this->addSql('DROP INDEX IDX_1B3294AA31B2BA6 ON time_slot');
        $this->addSql('ALTER TABLE time_slot DROP parking_spot_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_slot ADD parking_spot_id INT NOT NULL');
        $this->addSql('ALTER TABLE time_slot ADD CONSTRAINT FK_1B3294AA31B2BA6 FOREIGN KEY (parking_spot_id) REFERENCES parking_spot (id)');
        $this->addSql('CREATE INDEX IDX_1B3294AA31B2BA6 ON time_slot (parking_spot_id)');
    }
}
