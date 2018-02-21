<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180216142929 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(' CREATE TABLE expired_passport (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            series INT(10) UNSIGNED NOT NULL,
            number INT(10) UNSIGNED NOT NULL
        )');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `expired_passport`');
    }
}
