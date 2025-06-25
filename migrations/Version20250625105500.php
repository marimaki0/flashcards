<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625105500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE flashcard (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category_id INT NOT NULL, question LONGTEXT NOT NULL, answer LONGTEXT NOT NULL, difficulty INT NOT NULL, INDEX IDX_70511A09A76ED395 (user_id), INDEX IDX_70511A0912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE flashcard_tag (id INT AUTO_INCREMENT NOT NULL, flashcard_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_D1FEDA7CC5D16576 (flashcard_id), INDEX IDX_D1FEDA7CBAD26311 (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE study_session (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, flashcard_id INT NOT NULL, is_correct TINYINT(1) NOT NULL, studied_at DATETIME NOT NULL, time_spent INT NOT NULL, INDEX IDX_E55128B6A76ED395 (user_id), INDEX IDX_E55128B6C5D16576 (flashcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard ADD CONSTRAINT FK_70511A09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard ADD CONSTRAINT FK_70511A0912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard_tag ADD CONSTRAINT FK_D1FEDA7CC5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard_tag ADD CONSTRAINT FK_D1FEDA7CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE study_session ADD CONSTRAINT FK_E55128B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE study_session ADD CONSTRAINT FK_E55128B6C5D16576 FOREIGN KEY (flashcard_id) REFERENCES flashcard (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A09A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard DROP FOREIGN KEY FK_70511A0912469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard_tag DROP FOREIGN KEY FK_D1FEDA7CC5D16576
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flashcard_tag DROP FOREIGN KEY FK_D1FEDA7CBAD26311
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE study_session DROP FOREIGN KEY FK_E55128B6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE study_session DROP FOREIGN KEY FK_E55128B6C5D16576
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE flashcard
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE flashcard_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE study_session
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
