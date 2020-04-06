<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use PDOException;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createdatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the database for the project only if it does not already exist.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        
        $database = env( 'DB_DATABASE', false );
        $dbconnection = env( 'DB_CONNECTION', false );

        if( $dbconnection != 'mysql' || !$database ) {
            $this->error( 'Invalid DB_CONNECTION (should be mysql) or DB_DATABASE (should have a none empty value).' );
            return;
        } else {
            try {
                $pdo = new PDO( 
                    sprintf( 
                        'mysql:host=%s;port=%d;',
                        env( 'DB_HOST' ),
                        env( 'DB_PORT' ) 
                    ),
                    env( 'DB_USERNAME' ),
                    env( 'DB_PASSWORD' ) 
                );

                //check if DB exists. Do not seed if it already does
                $DBExists = $pdo->prepare("SHOW DATABASES LIKE :db_name;");
                $DBExists->bindParam(':db_name', $database);
                $DBExists->execute();
                $DBExists = $DBExists->fetch( PDO::FETCH_ASSOC );

                $query = sprintf(
                    'CREATE DATABASE IF NOT EXISTS `%s` ',
                    $database
                );

                if( ( $charset = env( 'DB_CHARSET', false ) ) && ( $collation = env( 'DB_COLLATION', false ) ) )
                    $query .= sprintf(' CHARACTER SET %s COLLATE %s',
                        env('DB_CHARSET'),
                        env('DB_COLLATION')
                    );

                $returnCode = $pdo->exec( "$query;" );

                if( $returnCode !== 1 )
                    throw new PDOException( 'Error while executing the query. DB variables might not be configured properly. ' . $query);

            } catch ( PDOException $exception ) {
                $this->error( sprintf( 'Failed to create %s database, %s', $database, $exception->getMessage() ) );
                exit(1);
            }
        }

        $this->info( "Successfully created $database database");
        
        //call migrations
        $this->call( 'migrate' );
        $this->info( "Successfully called migrations for $database database");

        //call seeder
        if( empty( $DBExists ) ) {
            $this->call('db:seed');
            $this->info( "Successfully called seeder for $database database");
        }
    }
}
