<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626204831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pessoa_id_pessoa_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pessoa (id_pessoa INT NOT NULL, nome VARCHAR(200) NOT NULL, cpf VARCHAR(11) NOT NULL, data_nascimento DATE NOT NULL, email VARCHAR(200) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, data_criacao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL default CURRENT_TIMESTAMP, PRIMARY KEY(id_pessoa))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pessoa_id_pessoa_seq CASCADE');
        $this->addSql('DROP TABLE pessoa');
    }
}
