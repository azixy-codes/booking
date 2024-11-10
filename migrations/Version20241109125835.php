<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241109125835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDECE1B1BDF');
        $this->addSql('DROP INDEX IDX_E00CEDDECE1B1BDF ON booking');
        $this->addSql('ALTER TABLE booking CHANGE timeslot_id_id timeslot_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEF920B9E9 FOREIGN KEY (timeslot_id) REFERENCES time_slot (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEF920B9E9 ON booking (timeslot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEF920B9E9');
        $this->addSql('DROP INDEX IDX_E00CEDDEF920B9E9 ON booking');
        $this->addSql('ALTER TABLE booking CHANGE timeslot_id timeslot_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDECE1B1BDF FOREIGN KEY (timeslot_id_id) REFERENCES time_slot (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDECE1B1BDF ON booking (timeslot_id_id)');
    }
}
