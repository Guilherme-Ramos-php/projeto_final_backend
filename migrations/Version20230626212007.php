<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626212007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conta_id_conta_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE movimentacao_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conta (id_conta INT NOT NULL, pessoa_id INT NOT NULL, conta VARCHAR(11) NOT NULL, data_criacao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id_conta))');
        $this->addSql('CREATE INDEX IDX_485A16C3DF6FA0A5 ON conta (pessoa_id)');
        $this->addSql('CREATE TABLE movimentacao (id INT NOT NULL, conta_id INT NOT NULL, data_movimentacao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, acao VARCHAR(9) NOT NULL, valor DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1BF366A628EE05C ON movimentacao (conta_id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id_pessoa) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movimentacao ADD CONSTRAINT FK_C1BF366A628EE05C FOREIGN KEY (conta_id) REFERENCES conta (id_conta) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pessoa ALTER data_criacao DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE conta_id_conta_seq CASCADE');
        $this->addSql('DROP SEQUENCE movimentacao_id_seq CASCADE');
        $this->addSql('ALTER TABLE conta DROP CONSTRAINT FK_485A16C3DF6FA0A5');
        $this->addSql('ALTER TABLE movimentacao DROP CONSTRAINT FK_C1BF366A628EE05C');
        $this->addSql('DROP TABLE conta');
        $this->addSql('DROP TABLE movimentacao');
        $this->addSql('ALTER TABLE pessoa ALTER data_criacao SET DEFAULT CURRENT_TIMESTAMP');
    }
}
