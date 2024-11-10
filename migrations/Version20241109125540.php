<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241109125540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE5B05007F');
        $this->addSql('DROP INDEX IDX_E00CEDDE5B05007F ON booking');
        $this->addSql('ALTER TABLE booking CHANGE spot_id_id spot_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE2DF1D37C FOREIGN KEY (spot_id) REFERENCES parking_spot (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE2DF1D37C ON booking (spot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE2DF1D37C');
        $this->addSql('DROP INDEX IDX_E00CEDDE2DF1D37C ON booking');
        $this->addSql('ALTER TABLE booking CHANGE spot_id spot_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE5B05007F FOREIGN KEY (spot_id_id) REFERENCES parking_spot (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE5B05007F ON booking (spot_id_id)');
    }
}
