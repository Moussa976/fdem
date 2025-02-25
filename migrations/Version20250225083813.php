<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225083813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feuille_match ADD signatureavant_equipe1 TINYINT(1) DEFAULT NULL, ADD signatureavant_equipe2 TINYINT(1) DEFAULT NULL, ADD signatureapres_equipe1 TINYINT(1) DEFAULT NULL, ADD signatureapres_equipe2 TINYINT(1) DEFAULT NULL, ADD reserve_equipe1 VARCHAR(255) DEFAULT NULL, ADD reserve_equipe2 VARCHAR(255) DEFAULT NULL, ADD observation_arbitre VARCHAR(255) DEFAULT NULL, DROP signature_equipe1, DROP signature_equipe2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feuille_match ADD signature_equipe1 TINYINT(1) DEFAULT NULL, ADD signature_equipe2 TINYINT(1) DEFAULT NULL, DROP signatureavant_equipe1, DROP signatureavant_equipe2, DROP signatureapres_equipe1, DROP signatureapres_equipe2, DROP reserve_equipe1, DROP reserve_equipe2, DROP observation_arbitre');
    }
}
