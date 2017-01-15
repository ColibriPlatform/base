<?php

namespace colibri\base\components;

use Yii;
use yii\db\Connection;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Manages application migrations.
 *
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Sylvain Philip <contact@sphilip.com>
 */
class Migration extends \yii\base\Component
{
    /**
     * The name of the dummy migration that marks the beginning of the whole migration history.
     */
    const BASE_MIGRATION = 'm000000_000000_base';

    /**
     * @var string the directory storing the migration classes. This can be either
     * a path alias or a directory.
     */
    public $migrationPath = '@app/migrations';

    /**
     * @var string the name of the table for keeping applied migration information.
     */
    public $migrationTable = '{{%migration}}';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection to use
     * when applying migrations. Starting from version 2.0.3, this can also be a configuration array
     * for creating the object.
     */
    public $db = 'db';

    public $messages = [];

    public function __construct($migrationPath='')
    {
        if (!empty($migrationPath)) {
            $this->migrationPath = $migrationPath;
        }
        $this->db = Yii::$app->getDb();
    }


    /**
     * Upgrades the application by applying new migrations.
     *
     *
     * @return integer the status of the action execution. 0 means normal, other values mean abnormal.
     */
    public function up($limit = 0)
    {
        $migrations = $this->getNewMigrations();

        if (empty($migrations)) {
            $this->messages[] = 'No new migration found. Your system is up-to-date.';
            return true;
        }

        foreach ($migrations as $migration) {
            if (!$this->migrateUp($migration)) {
                $this->messages[] = "Migration {$migration} failed. The rest of the migrations are canceled.";
                return false;
            }
        }
        return true;
    }

    /**
     * Upgrades with the specified migration class.
     * @param string $class the migration class name
     * @return boolean whether the migration is successful
     */
    protected function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }
        $this->messages[] = "*** applying $class";
    
        $start = microtime(true);
        $migration = $this->createMigration($class);
        ob_start();
        $result = $migration->up();
        $messages = ob_get_contents();
        ob_end_clean();
        $this->messages[] = $messages;
        // $this->messages = ArrayHelper::merge($this->messages, explode("\n", $messages));

        if ($result !== false) {
            $this->addMigrationHistory($class);
            $time = microtime(true) - $start;
            $this->messages[] = "*** applied $class (time: " . sprintf("%.3f", $time) . "s)";
    
            return true;
        } else {
            $time = microtime(true) - $start;
            $this->messages[] = "*** failed to apply $class (time: " . sprintf("%.3f", $time) . "s)";
            return false;
        }
    }


    /**
     * Returns the migrations that are not applied.
     * @return array list of new migrations
     */
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $version => $time) {
            $applied[substr($version, 1, 13)] = true;
        }
    
        $migrations = [];
        $handle = opendir($this->migrationPath);
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $this->migrationPath . DIRECTORY_SEPARATOR . $file;
            if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches) && !isset($applied[$matches[2]]) && is_file($path)) {
                $migrations[] = $matches[1];
            }
        }
        closedir($handle);
        sort($migrations);
    
        return $migrations;
    }


    /**
     * Creates a new migration instance.
     * @param string $class the migration class name
     * @return \yii\db\Migration the migration instance
     */
    protected function createMigration($class)
    {
        require_once($this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php');
        return new $class(['db' => $this->db]);
    }

    /**
     * Returns the migration history.
     * @param integer $limit the maximum number of records in the history to be returned. `null` for "no limit".
     * @return array the migration history
     */
    protected function getMigrationHistory($limit)
    {
        if ($this->db->schema->getTableSchema($this->migrationTable, true) === null) {
            $this->createMigrationHistoryTable();
        }
        $query = new Query;
        $rows = $query->select(['version', 'apply_time'])
        ->from($this->migrationTable)
        ->orderBy('apply_time DESC, version DESC')
        ->limit($limit)
        ->createCommand($this->db)
        ->queryAll();
        $history = ArrayHelper::map($rows, 'version', 'apply_time');
        unset($history[self::BASE_MIGRATION]);

        return $history;
    }

    /**
     * Creates the migration history table.
     */
    protected function createMigrationHistoryTable()
    {
        $tableName = $this->db->schema->getRawTableName($this->migrationTable);

        $this->db->createCommand()->createTable($this->migrationTable, [
            'version' => 'varchar(180) NOT NULL PRIMARY KEY',
            'apply_time' => 'integer',
        ])->execute();
        $this->db->createCommand()->insert($this->migrationTable, [
            'version' => self::BASE_MIGRATION,
            'apply_time' => time(),
        ])->execute();
    }

    /**
     * Adds new migration entry to the history.
     * @param string $version migration version name.
     */
    protected function addMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->insert($this->migrationTable, [
            'version' => $version,
            'apply_time' => time(),
        ])->execute();
    }

    /**
     * Removes existing migration from the history.
     * @param string $version migration version name.
     */
    protected function removeMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->delete($this->migrationTable, [
            'version' => $version,
        ])->execute();
    }
}
