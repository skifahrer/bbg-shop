<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110095710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX idx_product_title_en ON product (title_en)');
        $this->addSql('CREATE INDEX idx_product_title_sk ON product (title_sk)');
        $this->addSql('CREATE INDEX idx_product_title_sl ON product (title_sl)');
        $this->addSql('CREATE INDEX idx_product_title_hu ON product (title_hu)');
        $this->addSql('CREATE INDEX idx_product_title_hr ON product (title_hr)');
        $this->addSql('CREATE INDEX idx_product_title_ro ON product (title_ro)');
        $this->addSql('CREATE INDEX idx_product_title_bg ON product (title_bg)');
        $this->addSql('ALTER TABLE user ADD invoice_address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_product_title_en ON product');
        $this->addSql('DROP INDEX idx_product_title_sk ON product');
        $this->addSql('DROP INDEX idx_product_title_sl ON product');
        $this->addSql('DROP INDEX idx_product_title_hu ON product');
        $this->addSql('DROP INDEX idx_product_title_hr ON product');
        $this->addSql('DROP INDEX idx_product_title_ro ON product');
        $this->addSql('DROP INDEX idx_product_title_bg ON product');
        $this->addSql('ALTER TABLE user DROP invoice_address');
    }
}
