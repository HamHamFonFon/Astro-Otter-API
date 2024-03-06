<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306132228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE update_data_id_seq CASCADE');
        $this->addSql('ALTER TABLE update_data ALTER COLUMN id TYPE UUID USING id::uuid');
        $this->addSql('COMMENT ON COLUMN update_data.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE update_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE update_data ALTER id TYPE INT');
        $this->addSql('COMMENT ON COLUMN update_data.id IS NULL');
    }
}
