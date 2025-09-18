<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to create the books table
 */
final class Version20250918000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create books table with all necessary columns';
    }

    public function up(Schema $schema): void
    {
        // Create books table
        $this->addSql('CREATE TABLE books (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            published_year INT NOT NULL,
            isbn VARCHAR(20) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            genre VARCHAR(100) DEFAULT NULL,
            price NUMERIC(10, 2) DEFAULT NULL,
            stock INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add indexes for better performance
        $this->addSql('CREATE INDEX IDX_books_title ON books (title)');
        $this->addSql('CREATE INDEX IDX_books_author ON books (author)');
        $this->addSql('CREATE INDEX IDX_books_genre ON books (genre)');
        $this->addSql('CREATE INDEX IDX_books_published_year ON books (published_year)');
        $this->addSql('CREATE INDEX IDX_books_created_at ON books (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_books_isbn ON books (isbn)');
    }

    public function down(Schema $schema): void
    {
        // Drop the books table
        $this->addSql('DROP TABLE books');
    }
}
