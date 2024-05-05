<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428223021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reprandre (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, id_client INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE reprendre');
        $this->addSql('ALTER TABLE article CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE comment_id comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE defis CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fk_idproduit');
        $this->addSql('ALTER TABLE panier CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY fk_id_d');
        $this->addSql('ALTER TABLE participant CHANGE id_d id_d INT DEFAULT NULL, CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B115F48E0B3 FOREIGN KEY (id_d) REFERENCES defis (id_d)');
        $this->addSql('ALTER TABLE user CHANGE Image Image VARCHAR(100) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B80DF618 ON user (idA)');
        $this->addSql('ALTER TABLE user_nutrition DROP FOREIGN KEY pk_id');
        $this->addSql('ALTER TABLE user_nutrition ADD CONSTRAINT FK_B723ED15BF396750 FOREIGN KEY (id) REFERENCES user (idU)');
        $this->addSql('ALTER TABLE vote CHANGE idpart idpart INT DEFAULT NULL, CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages DROP redeliver_at, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_queue_name ON messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('DROP INDEX idx_available_at ON messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('DROP INDEX idx_delivered_at ON messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reprendre (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(555) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idclient INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE reprandre');
        $this->addSql('ALTER TABLE article CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE comment_id comment_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE defis CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages ADD redeliver_at DATETIME DEFAULT NULL, CHANGE created_at created_at VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX idx_75ea56e0e3bd61ce ON messenger_messages');
        $this->addSql('CREATE INDEX idx_available_at ON messenger_messages (available_at)');
        $this->addSql('DROP INDEX idx_75ea56e016ba31db ON messenger_messages');
        $this->addSql('CREATE INDEX idx_delivered_at ON messenger_messages (delivered_at)');
        $this->addSql('DROP INDEX idx_75ea56e0fb7336f0 ON messenger_messages');
        $this->addSql('CREATE INDEX idx_queue_name ON messenger_messages (queue_name)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7384557');
        $this->addSql('ALTER TABLE panier CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fk_idproduit FOREIGN KEY (id_produit) REFERENCES produit (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B115F48E0B3');
        $this->addSql('ALTER TABLE participant CHANGE id_d id_d INT NOT NULL, CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_id_d FOREIGN KEY (id_d) REFERENCES defis (id_d) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_8D93D649B80DF618 ON user');
        $this->addSql('ALTER TABLE user CHANGE Image Image VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user_nutrition DROP FOREIGN KEY FK_B723ED15BF396750');
        $this->addSql('ALTER TABLE user_nutrition ADD CONSTRAINT pk_id FOREIGN KEY (id) REFERENCES user (idU) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote CHANGE idpart idpart INT NOT NULL, CHANGE idU idU INT NOT NULL');
    }
}
