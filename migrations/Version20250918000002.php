<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to create the reviews table with foreign key to books
 */
final class Version20250918000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create reviews table with relationship to books table';
    }

    public function up(Schema $schema): void
    {
        // Create reviews table
        $this->addSql('CREATE TABLE reviews (
            id INT AUTO_INCREMENT NOT NULL,
            book_id INT NOT NULL,
            rating INT NOT NULL,
            comment TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add foreign key constraint to books table
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F16A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');

        // Add indexes for better performance
        $this->addSql('CREATE INDEX IDX_reviews_book_id ON reviews (book_id)');
        $this->addSql('CREATE INDEX IDX_reviews_rating ON reviews (rating)');
        $this->addSql('CREATE INDEX IDX_reviews_created_at ON reviews (created_at)');
    }

    public function down(Schema $schema): void
    {
        // Drop the reviews table (foreign key will be dropped automatically)
        $this->addSql('DROP TABLE reviews');
    }
}
