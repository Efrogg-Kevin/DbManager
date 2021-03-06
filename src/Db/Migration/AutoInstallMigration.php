<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 21/06/16
 * Time: 09:58
 */

namespace Efrogg\Db\Migration;


class AutoInstallMigration extends Migration
{

    /**
     * @var
     */
    private $tableName = "migrations";


    /**
     * InstallMigration constructor.
     * @param null $tableName
     * @internal param string $name
     */
    public function __construct($tableName=null)
    {
        if(!is_null($tableName)) {
            $this->tableName = $tableName;
        }
    }


    public function up()
    {
        $this
            -> table($this->tableName)
            -> create("CREATE TABLE ".$this->tableName." (
                `id_migration` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `migration_name` VARCHAR(255) NOT NULL ,
                `batch` SMALLINT UNSIGNED NOT NULL ,
                INDEX `batch` (`batch`),
                PRIMARY KEY `id_migration` (`id_migration`),
                UNIQUE `migration_name` (`migration_name`)
              )
              ENGINE = InnoDB;");
//        if(!$executed->isValid()) {
//            var_dump($executed->getErrorMessage());
//        }
    }

    public function down()
    {
        // pas de drop sur celui-la :)
//        $this->db->execute("DROP TABLE IF EXISTS ".$this->tableName);
    }

    public function isFixed()
    {
        return true;
    }
}