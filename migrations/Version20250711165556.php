<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250711165556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates tables for book, user and messenger_messages';
    }

    public function up(Schema $schema): void
    {
        // Added indexes. I was using MySQL locally (functional indexes are not supported),
        // so have to workaround with creating extra columns title_lower, author_lower:
        $this->addSql('
            CREATE TABLE book (
                id INT AUTO_INCREMENT NOT NULL,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                isbn VARCHAR(13) NOT NULL,
                publication_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
                genre VARCHAR(100) NOT NULL,
                number_of_copies INT NOT NULL,
                title_lower VARCHAR(255) GENERATED ALWAYS AS (LOWER(title)) STORED,
                author_lower VARCHAR(255) GENERATED ALWAYS AS (LOWER(author)) STORED,
                UNIQUE INDEX UNIQ_CBE5A331CC1CF4E6 (isbn),
                INDEX IDX_BOOK_TITLE_LOWER (title_lower),
                INDEX IDX_BOOK_AUTHOR_LOWER (author_lower),
                INDEX IDX_BOOK_ISBN (isbn),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(100) NOT NULL, surname VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
