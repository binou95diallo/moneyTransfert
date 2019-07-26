<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190726224354 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_partenaire ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE admin_partenaire ADD CONSTRAINT FK_FAC105F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FAC105F6A76ED395 ON admin_partenaire (user_id)');
        $this->addSql('ALTER TABLE user_partenaire ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_partenaire ADD CONSTRAINT FK_9598659FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9598659FA76ED395 ON user_partenaire (user_id)');
        $this->addSql('ALTER TABLE partenaire ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA373A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_32FFA373A76ED395 ON partenaire (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_partenaire DROP FOREIGN KEY FK_FAC105F6A76ED395');
        $this->addSql('DROP INDEX IDX_FAC105F6A76ED395 ON admin_partenaire');
        $this->addSql('ALTER TABLE admin_partenaire DROP user_id');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA373A76ED395');
        $this->addSql('DROP INDEX IDX_32FFA373A76ED395 ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP user_id');
        $this->addSql('ALTER TABLE user_partenaire DROP FOREIGN KEY FK_9598659FA76ED395');
        $this->addSql('DROP INDEX IDX_9598659FA76ED395 ON user_partenaire');
        $this->addSql('ALTER TABLE user_partenaire DROP user_id');
    }
}
