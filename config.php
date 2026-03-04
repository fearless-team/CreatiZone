<?php
class Database
{
    private string $host    = 'localhost';
    private string $dbname  = 'creatizone';
    private string $user    = 'root';
    private string $pass    = '';
    private string $charset = 'utf8mb4';

    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                error_log("Erreur BDD : " . $e->getMessage());
                die("Impossible de se connecter à la base de données.");
            }
        }
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup(): void
    {
        throw new \Exception("Singleton interdit à désérialiser.");
    }
}