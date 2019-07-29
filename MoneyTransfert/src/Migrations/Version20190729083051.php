<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729083051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_partenaire');
        $this->addSql('DROP TABLE admin_systeme');
        $this->addSql('ALTER TABLE user ADD nom_complet VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_partenaire MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user_partenaire DROP FOREIGN KEY FK_9598659F98DE13AC');
        $this->addSql('ALTER TABLE user_partenaire DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_partenaire ADD user_id INT NOT NULL, DROP id, DROP matricule, DROP username, DROP password, DROP nom_complet, DROP adresse, DROP telephone, DROP email');
        $this->addSql('ALTER TABLE user_partenaire ADD CONSTRAINT FK_9598659FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_partenaire ADD CONSTRAINT FK_9598659F98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9598659FA76ED395 ON user_partenaire (user_id)');
        $this->addSql('ALTER TABLE user_partenaire ADD PRIMARY KEY (user_id, partenaire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_partenaire (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT NOT NULL, matricule VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_FAC105F698DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_systeme (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admin_partenaire ADD CONSTRAINT FK_FAC105F698DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE user DROP nom_complet, DROP adresse, DROP telephone, DROP email');
        $this->addSql('ALTER TABLE user_partenaire DROP FOREIGN KEY FK_9598659FA76ED395');
        $this->addSql('ALTER TABLE user_partenaire DROP FOREIGN KEY FK_9598659F98DE13AC');
        $this->addSql('DROP INDEX IDX_9598659FA76ED395 ON user_partenaire');
        $this->addSql('ALTER TABLE user_partenaire DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_partenaire ADD id INT AUTO_INCREMENT NOT NULL, ADD matricule VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP user_id');
        $this->addSql('ALTER TABLE user_partenaire ADD CONSTRAINT FK_9598659F98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE user_partenaire ADD PRIMARY KEY (id)');
    }
}
