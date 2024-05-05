<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402234331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recettes (id_r INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, chef VARCHAR(50) DEFAULT NULL, ingredients JSON NOT NULL COMMENT \'(DC2Type:json)\', Categorie VARCHAR(50) DEFAULT NULL, description VARCHAR(255) NOT NULL, imgSrc VARCHAR(255) NOT NULL, calorie INT NOT NULL, protein INT NOT NULL, idU INT DEFAULT NULL, INDEX fk_recette_userr (idu), PRIMARY KEY(id_r)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recettes ADD CONSTRAINT FK_EB48E72CA2D72265 FOREIGN KEY (idU) REFERENCES user (idU)');
        $this->addSql('ALTER TABLE nutrition_recommandation DROP FOREIGN KEY pk_id3');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY fk_recette_user');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY fk_recette_users');
        $this->addSql('DROP TABLE nutrition_recommandation');
        $this->addSql('DROP TABLE recette');
        $this->addSql('ALTER TABLE article CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE comment_id comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE defis CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fk_idproduit');
        $this->addSql('ALTER TABLE panier CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY fk_id_d');
        $this->addSql('ALTER TABLE participant CHANGE id_d id_d INT DEFAULT NULL, CHANGE idU idU INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B115F48E0B3 FOREIGN KEY (id_d) REFERENCES defis (id_d)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B80DF618 ON user (idA)');
        $this->addSql('ALTER TABLE user_nutrition MODIFY id_nut INT NOT NULL');
        $this->addSql('ALTER TABLE user_nutrition DROP FOREIGN KEY pk_id');
        $this->addSql('DROP INDEX IDX_B723ED15BF396750 ON user_nutrition');
        $this->addSql('DROP INDEX `primary` ON user_nutrition');
        $this->addSql('ALTER TABLE user_nutrition DROP id_nut');
        $this->addSql('ALTER TABLE user_nutrition ADD CONSTRAINT FK_B723ED15BF396750 FOREIGN KEY (id) REFERENCES user (idU)');
        $this->addSql('ALTER TABLE user_nutrition ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE vote CHANGE idpart idpart INT DEFAULT NULL, CHANGE idU idU INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nutrition_recommandation (id_meals INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX pk_id3 (user_id), PRIMARY KEY(id_meals)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recette (id_r INT AUTO_INCREMENT NOT NULL, idu INT DEFAULT NULL, iduser INT DEFAULT NULL, nom VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, imgSrc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, calorie INT NOT NULL, protein INT NOT NULL, INDEX fk_recette_users (iduser), INDEX fk_recette_user (idu), PRIMARY KEY(id_r)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE nutrition_recommandation ADD CONSTRAINT pk_id3 FOREIGN KEY (user_id) REFERENCES user (idU) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT fk_recette_user FOREIGN KEY (idu) REFERENCES user (idU)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT fk_recette_users FOREIGN KEY (iduser) REFERENCES user (idU)');
        $this->addSql('ALTER TABLE recettes DROP FOREIGN KEY FK_EB48E72CA2D72265');
        $this->addSql('DROP TABLE recettes');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE comment_id comment_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE defis CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7384557');
        $this->addSql('ALTER TABLE panier CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fk_idproduit FOREIGN KEY (id_produit) REFERENCES produit (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B115F48E0B3');
        $this->addSql('ALTER TABLE participant CHANGE id_d id_d INT NOT NULL, CHANGE idU idU INT NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_id_d FOREIGN KEY (id_d) REFERENCES defis (id_d) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_8D93D649B80DF618 ON user');
        $this->addSql('ALTER TABLE user_nutrition DROP FOREIGN KEY FK_B723ED15BF396750');
        $this->addSql('ALTER TABLE user_nutrition ADD id_nut INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_nut)');
        $this->addSql('ALTER TABLE user_nutrition ADD CONSTRAINT pk_id FOREIGN KEY (id) REFERENCES user (idU) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B723ED15BF396750 ON user_nutrition (id)');
        $this->addSql('ALTER TABLE vote CHANGE idpart idpart INT NOT NULL, CHANGE idU idU INT NOT NULL');
    }
}
