<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106083533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE checkout (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', cart_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, shipping_address VARCHAR(255) NOT NULL, invoice_address VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, INDEX IDX_AF382D4EA76ED395 (user_id), UNIQUE INDEX UNIQ_AF382D4E1AD5CDBF (cart_id), UNIQUE INDEX UNIQ_AF382D4E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_quantity (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', cart_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', order_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, INDEX IDX_F3FEDEFD1AD5CDBF (cart_id), INDEX IDX_F3FEDEFD8D9F6D38 (order_id), INDEX IDX_F3FEDEFD4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, final_price NUMERIC(10, 2) NOT NULL, shipping_address VARCHAR(255) NOT NULL, invoice_address VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', item VARCHAR(255) NOT NULL, oxprice NUMERIC(10, 2) NOT NULL, weight NUMERIC(10, 2) NOT NULL, stock INT NOT NULL, length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, title_en VARCHAR(255) NOT NULL, title_sk VARCHAR(255) NOT NULL, title_sl VARCHAR(255) NOT NULL, title_hu VARCHAR(255) NOT NULL, title_hr VARCHAR(255) NOT NULL, title_ro VARCHAR(255) NOT NULL, title_bg VARCHAR(255) NOT NULL, features_json JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(180) NOT NULL, family VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, shipping_address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4EA76ED395');
        $this->addSql('ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E1AD5CDBF');
        $this->addSql('ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E8D9F6D38');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFD1AD5CDBF');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFD8D9F6D38');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFD4584665A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE checkout');
        $this->addSql('DROP TABLE item_quantity');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
