<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190725202701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_partenaire DROP FOREIGN KEY FK_FAC105F698DE13AC');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A98DE13AC');
        $this->addSql('ALTER TABLE users_partenaire DROP FOREIGN KEY FK_E5940CE798DE13AC');
        $this->addSql('DROP TABLE admin_partenaire');
        $this->addSql('DROP TABLE admin_systeme');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE users_partenaire');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_partenaire (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT NOT NULL, matricule_admin_p VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, login VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, pass_word VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_FAC105F698DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_systeme (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, login VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, pass_word VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT NOT NULL, numero_compte VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, solde INT NOT NULL, UNIQUE INDEX UNIQ_53A23E0A98DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, matricule_partenaire VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, login VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, pass_word VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ninea VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_partenaire (id INT AUTO_INCREMENT NOT NULL, partenaire_id INT NOT NULL, matricule_users_p VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, login VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, pass_word VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_complet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_E5940CE798DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admin_partenaire ADD CONSTRAINT FK_FAC105F698DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE users_partenaire ADD CONSTRAINT FK_E5940CE798DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
    }
}
