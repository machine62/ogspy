<?php
/**
 * MySql database Managment Class
 * @package OGSpy
 * @subpackage MySql
 * @author Kyser
 * @created 15/11/2005
 * @copyright Copyright &copy; 2007, http://ogsteam.fr/
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 3.04b ($Rev: 7692 $)
 */

if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
}
/**
 * OGSpy MySQL Database Class
 * @package OGSpy
 * @subpackage MySql
 */
class sql_db extends SQLite3
{
    /**
     * Instance variable
     * @access private
     * @var int
     */
    private static $_instance = false; //(singleton)
    /**
     * Connection ID
     * @var int
     */
    var $db_connect_id;
    /**
     * DB Result
     * @var mixed
     */
    var $result;
    /**
     * Nb of Queries done
     * @var int
     */
    var $nb_requete = 0;
    /**
     * last query
     * @var int
     */
    var $last_query;


    /**
     * Class Constructor
     * @param string $sqlserver MySQL Server Name
     * @param string $sqluser MySQL User Name
     * @param string $sqlpassword MySQL User Password
     * @param string $database MySQL Database Name
     */

    function __construct()
    {
        global $sql_timing;
        $sql_start = benchmark();

        $this->open('./parameters/data.db');

		/* Vérification de la connexion */
		if ($this->lastErrorCode() ) {
			echo("Échec de la connexion : ".$this->lastErrorMsg() );
			exit();
		}
		
        $sql_timing += benchmark() - $sql_start;
    }

    /**
     * Closing the Connection with the MySQL Server
     */
    function sql_close()
    {
        unset($this->result);
        $this->close(); //deconnection
    }


    /**
     * MySQL Request Function
     * @param string $query The MySQL Query
     * @param boolean $Auth_dieSQLError True if a SQL error sneed to stop the application
     * @param boolean $save True to save the Query in the MySQL Logfile (if enabled)
     * @return bool|mixed|mysqli_result
     */
    function sql_query($query = "", $Auth_dieSQLError = true, $save = true)
    {
        global $sql_timing, $server_config;

        if ($query === "SHOW TABLE STATUS") return 0;

        $sql_start = benchmark();

        if ($Auth_dieSQLError) {
            if(!($this->result = $this->query($query))){

                $this->DieSQLError($query);
            }

        } else {
            $this->last_query = $query;
            $this->result = $this->query($query);
        }

        if ($save && isset($server_config["debug_log"])) {

            if ($server_config["debug_log"] == "1") {
                    $fichier = "sql_" . date("ymd") . ".sql";
                    $date = date("d/m/Y H:i:s");
                    $ligne = "/* " . $date . " - " . $_SERVER["REMOTE_ADDR"] . " */ " . $query . ";";
                    write_file(PATH_LOG_TODAY . $fichier, "a", $ligne);

            }
        }

        $sql_timing += benchmark() - $sql_start;

        $this->nb_requete += 1;
        return $this->result;
    }

    /**
     * Gets the result of the Query and returns it in a simple array
     * @param int $query_id The Query id.
     * @return array|bool the array containing the Database result
     */
    function sql_fetch_row($query_id = 0)
    {
        if (!$query_id) {
            $query_id = $this->result;
        }
        if ($query_id) {
            return $query_id->fetchArray();
        } else {
            return false;
        }
    }

    /**
     * Gets the result of the Query and returns it in a associative array
     * @param int $query_id The Query id.
     * @return array|bool the associative array containing the Database result
     */
    function sql_fetch_assoc($query_id = 0)
    {
        if (!$query_id) {
            $query_id = $this->result;
        }
        if ($query_id) {
            return $query_id->fetchArray(SQLITE3_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Gets the number of results returned by the Query
     * @param int $query_id The Query id.
     * @return array|bool number of results
     */
    function sql_numrows($query_id = 0)
    {
        if (!$query_id) {
            $query_id = $this->result;
        }
        if ($query_id) {
            $nrows = 0;
            $query_id->reset();
            while ($query_id->fetchArray())
                $nrows++;
            $query_id->reset();

            return $nrows;
        } else {
            return false;
        }
    }

    /**
     * Gets the number of affected rows by the Query
     * @return array|bool number of affected rows
     */
    function sql_affectedrows()
    {
        if ($this->db_connect_id) {
            $result = $this->changes();
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Identifier of the last insertion Query
     * @return array|int Returns the id
     */
    function sql_insertid()
    {
        if ($this->db_connect_id) {
            $result = $this->lastInsertRowID();
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Returns the latest Query Error.
     * @param int $query_id The Query id.
     * @return an array with the error code and the error message
     */
    function sql_error($query_id = 0)
    {
        $result["message"] = $this->lastErrorMsg() ;
        $result["code"] = $this->lastErrorCode();
        echo("<h3 style='color: #FF0000;text-align: center'>Erreur lors de la requête MySQL</h3>");
        echo("<b>- " . $result["message"] . "</b>");
        echo($this->last_query);
        exit();
    }

    /**
     * Returns the number of queries done.
     * @return int The number of queries done.
     */
    function sql_nb_requete()
    {
        return $this->nb_requete;
    }

    /**
     * Escapes all characters to set up the Query
     * @param string $str The string to escape
     * @return string the escaped string
     */
    function sql_escape_string($str)
    {
        if (isset($str)) {
            return $this->escapeString($str);
        } else {
            return false;
        }
    }

    /**
     * Displays an Error message and exits OGSpy
     * @param string $query Faulty SQL Request
     */
    function DieSQLError($query)
    {
        echo "<table align=center border=1>\n";
        echo "<tr><td class='c' colspan='3'>Database MySQL Error</td></tr>\n";
        echo "<tr><th colspan='3'>ErrNo:" . $this->lastErrorCode() ."</th></tr>\n";
        echo "<tr><th colspan='3'><u>Query:</u><br>" . $query . "</th></tr>\n";
        echo "<tr><th colspan='3'><u>Error:</u><br>" . $this->lastErrorMsg() . "</th></tr>\n";

        debug_print_backtrace();
        log_("mysql_error", array($query, $this->lastErrorCode(),
            $this->lastErrorMsg(), debug_backtrace()));
        die();
    }


}

