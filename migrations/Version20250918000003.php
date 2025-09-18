<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to recreate tables with simplified structure
 */
final class Version20250918000003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Recreate books and reviews tables with simplified structure';
    }

    public function up(Schema $schema): void
    {
        // Drop existing tables if they exist
        $this->addSql('DROP TABLE IF EXISTS reviews');
        $this->addSql('DROP TABLE IF EXISTS books');

        // Create books table with simplified structure
        $this->addSql('CREATE TABLE books (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            published_year INT NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create reviews table with simplified structure
        $this->addSql('CREATE TABLE reviews (
            id INT AUTO_INCREMENT NOT NULL,
            book_id INT NOT NULL,
            rating INT NOT NULL,
            comment TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add foreign key constraint
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F16A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');

        // Add indexes for better performance
        $this->addSql('CREATE INDEX IDX_reviews_book_id ON reviews (book_id)');
        $this->addSql('CREATE INDEX IDX_reviews_rating ON reviews (rating)');
        $this->addSql('CREATE INDEX IDX_reviews_created_at ON reviews (created_at)');
    }

    public function down(Schema $schema): void
    {
        // Drop the tables
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE books');
    }
}
