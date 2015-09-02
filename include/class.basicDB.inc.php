<?php

require_once 'common.inc.php';

class c_basicDB
{
	protected $dbConnection;		// db connection

	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $dbConnection = '' )
	{
        if($dbConnection != '' )    { $this->dbConnection = $dbConnection;  }
        else                        { $this->connectDB();                   }
			
	} // __construct
	
	
	//---------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{

		$this->closeDB();
		
	} // __destruct

	//---------------------------------------------------------------------------------------------
	// connectDB
	//---------------------------------------------------------------------------------------------
	function connectDB()
	{
//      echo "In connectDB()";
		
		$this->dbConnection = new mysqli (DB_SERVER, USER_NAME, PASSWORD, DATABASE);
		
        if( $this->dbConnection->connect_errno )
        {
            echo "Connection to database failed";
            exit();
        }

		return $this->dbConnection;
				
	} // connectDB

	//---------------------------------------------------------------------------------------------
	// closeDB
	//---------------------------------------------------------------------------------------------
	function closeDB()
	{
		if ( isset($this->dbConnection)  )
		{ 
            $this->dbConnection->close();
		}
	
	} // closeDB


	//---------------------------------------------------------------------------------------------
	// getDBConnection
	//---------------------------------------------------------------------------------------------
	function getDBConnection()
	{
	    return $this->dbConnection;

	} // getDBConnection

	//---------------------------------------------------------------------------------------------
    // srubInput 
    //
    // Description: scrubs down input value elimaate possible sql injection
	//---------------------------------------------------------------------------------------------
    function scrubInput($value)
    {
        
        //if( get_magic_quotes_gpc() )    { $value = stripslashes($value); }                                           // Stripslashes


        $value = $this->dbConnection->real_escape_string( $value );

        //if (!is_numeric($value)) { $value = "'" . $value . "'";  } // Quote if not a number

        return $value;

    } // scrubInput


} // class c_BasicDB

?>