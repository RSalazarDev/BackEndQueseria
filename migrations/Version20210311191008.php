<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311191008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrito (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_77E6BED5DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carrito_producto (carrito_id INT NOT NULL, producto_id INT NOT NULL, INDEX IDX_62C02DC2DE2CF6E7 (carrito_id), INDEX IDX_62C02DC27645698E (producto_id), PRIMARY KEY(carrito_id, producto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_77E6BED5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE carrito_producto ADD CONSTRAINT FK_62C02DC2DE2CF6E7 FOREIGN KEY (carrito_id) REFERENCES carrito (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carrito_producto ADD CONSTRAINT FK_62C02DC27645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrito_producto DROP FOREIGN KEY FK_62C02DC2DE2CF6E7');
        $this->addSql('DROP TABLE carrito');
        $this->addSql('DROP TABLE carrito_producto');
    }
}
