<style>

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<?php

$telnet = new Telnet($url['172.24.4.2'], $url['23'], 10, '>');
				$telnet->connect();
				$telnet->login($url['root'], $url['admin']);

				$telnet->write('enable');
				$telnet->write('admin');
				$telnet->write('?');
/**
* Telnet class
* 
* Used to execute remote commands via telnet connection 
* Usess sockets functions and fgetc() to process result
* 
* All methods throw Exceptions on error
* 
* Written by Dalibor Andzakovic <dali@swerve.co.nz>
* Based on the code originally written by Marc Ennaji and extended by 
* Matthias Blaser <mb@adfinis.ch>
*
* Modified by Dmitry Shin <dmitry.s@hsdn.org>, 2018
*/
class Telnet 
{
	private $host;
	private $port;
	private $timeout;
	
	private $socket  = NULL;
	private $buffer = NULL;
	private $prompt;
	private $errno;
	private $errstr;
	private $header1;
	private $header2;

	public $NULL;
	public $DC1;
	public $WILL;
	public $WONT;
	public $DO;
	public $DONT;
	public $IAC;
	public $LINEMODE;

	const TELNET_ERROR = FALSE;
	const TELNET_OK = TRUE;        
	
	/**
	* Constructor. Initialises host, port and timeout parameters
	* defaults to localhost port 23 (standard telnet port)
	* 
	* @access	public
	* @param	string	$host Host name or IP addres
	* @param	int		$port TCP port number
	* @param	int		$timeout Connection timeout in seconds
	* @return	void
	*/
	public function __construct($host = '127.0.0.1', $port = '23', $timeout = 10, $prompt = '/[^\s]{2,}[\$%>] {0,1}$/')
	{
		$this->default_prompt = $prompt;
		$this->setPrompt();

		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;

		$this->header1 =
			chr(0xFF).chr(0xFB).chr(0x1F).	// 0xFF 0xFB 0x1F - WILL command - NEGOTIATE-WINDOW-SIZE
			chr(0xFF).chr(0xFB).chr(0x20).	// 0xFF 0xFB 0x20 - WILL command - TERMINAL-SPEED
			chr(0xFF).chr(0xFB).chr(0x18).	// 0xFF 0xFB 0x18 - WILL command - TERMINAL-TYPE
			chr(0xFF).chr(0xFB).chr(0x27).	// 0xFF 0xFB 0x27 - WILL command - NEW-ENVIRON
			chr(0xFF).chr(0xFD).chr(0x01).	// 0xFF 0xFD 0x01 - DO command - ECHO
			chr(0xFF).chr(0xFB).chr(0x03).	// 0xFF 0xFB 0x03 - WILL command - SUPPRESS-GO-AHEAD
			chr(0xFF).chr(0xFD).chr(0x03).	// 0xFF 0xFD 0x03 - DO command - SUPPRESS-GO-AHEAD
			chr(0xFF).chr(0xFC).chr(0x23).	// 0xFF 0xFC 0x23 - WON'T command - X-DISPLAY-LOCATION
			chr(0xFF).chr(0xFC).chr(0x24).	// 0xFF 0xFC 0x24 - WON'T command - ENVIRONMENT
			chr(0xFF).chr(0xFA).			// 0xFF 0xFA ... - SB command
								chr(0x1F).chr(0x00).chr(0xA0).chr(0x00).chr(0x18).	// NEGOTIATE-WINDOW-SIZE 
																					// <Width1>=0 <Width0>=160 <Height1>=0 <Height0>=24
			chr(0xFF).chr(0xF0).			// 0xFF 0xF0 - SE command
			chr(0xFF).chr(0xFA).			// 0xFF 0xFA ... - SB command
								chr(0x20).chr(0x00).chr(0x33).chr(0x38).chr(0x34).
								chr(0x30).chr(0x30).chr(0x2C).chr(0x33).chr(0x38).
								chr(0x34).chr(0x30).chr(0x30).	// TERMINAL-SPEED - 38400,38400
			chr(0xFF).chr(0xF0).			// 0xFF 0xF0 - SE command
			chr(0xFF).chr(0xFA).			// 0xFF 0xFA ... - SB command
									chr(0x27).chr(0x00).	// NEW-ENVIRON <IS> <empty>
			chr(0xFF).chr(0xF0).			// 0xFF 0xF0 - SE command
			chr(0xFF).chr(0xFA).			// 0xFF 0xFA ... - SB command
									chr(0x18).chr(0x00).chr(0x58).chr(0x54).chr(0x45).  
									chr(0x52).chr(0x4D).	// TERMINAL-TYPE: <IS> XTERM
			chr(0xFF).chr(0xF0);			// 0xFF 0xF0 - SE command

		$this->header2 = 
			chr(0xFF).chr(0xFC).chr(0x01).	// 0xFF 0xFC 0x01 - WON'T command - ECHO
			chr(0xFF).chr(0xFC).chr(0x22).	// 0xFF 0xFC 0x22 - WON'T command - LINEMODE
			chr(0xFF).chr(0xFE).chr(0x05).	// 0xFF 0xFE 0x05 - DON'T command - STATUS
			chr(0xFF).chr(0xFC).chr(0x21);	// 0xFF 0xFC 0x21 - WON'T command - TOGGLE-FLOW-CONTROL  

		$this->NULL = chr(0);
		$this->DC1 = chr(17);
		$this->WILL = chr(251);
		$this->WONT = chr(252);
		$this->DO = chr(253);
		$this->DONT = chr(254);
		$this->IAC = chr(255);
		$this->LINEMODE = chr(34);

		$this->connect();
	}

	/**
	* Destructor. Cleans up socket connection and command buffer
	* 
	* @access	public
	* @return	void 
	*/
	public function __destruct() 
	{
		$this->disconnect();
		$this->buffer = NULL;
	}

	/**
	* Attempts connection to remote host. Returns TRUE if sucessful.      
	* 
	* @access	public
	* @return	bool
	*/
	public function connect()
	{
		if (!preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host)) 
		{
			$ip = gethostbyname($this->host);
			
			if ($this->host == $ip)
			{
				throw new Exception("Cannot resolve ".$this->host.".");
			}
			else
			{
				$this->host = $ip; 
			}
		}

		$this->socket = @fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);

		$this->write($this->header1, FALSE);

		usleep(100800);

		$this->write($this->header2, FALSE);

		usleep(100800);

		if (!$this->socket)
		{        	
			throw new Exception("Cannot connect to ".$this->host." on port ".$this->port.".");
		}
		
		return self::TELNET_OK;
	}

	/**
	* Closes IP socket
	* 
	* @access	public
	* @return	bool
	*/
	public function disconnect()
	{
		if ($this->socket)
		{
			$this->write('quit');

			if (!fclose($this->socket))
			{
				throw new Exception("Error while closing telnet socket.");                
			}

			$this->socket = NULL;
		}    

		return self::TELNET_OK;
	}
	
	/**
	* Executes command and returns a string with result.
	* This method is a wrapper for lower level private methods
	* 
	* @access	public
	* @param	string		$command Command to execute      
	* @return	string		Command result
	*/
	public function exec($command)
	{
		$this->write($command);
		$this->waitPrompt();

		return $this->getBuffer();
	}

	/**
	* Attempts login to remote host.
	* This method is a wrapper for lower level private methods and should be 
	* modified to reflect telnet implementation details like login/password
	* and line prompts. Defaults to standard unix non-root prompts
	* 
	* @access	public
	* @param	string		$username Username
	* @param	string		$password Password
	* @return	bool 
	*/
	public function login($username = FALSE, $password = FALSE) 
	{
		try
		{
			if ($username)
			{
				$this->setPrompt('/(ogin|name|word):.*$/');
				$this->waitPrompt();
				$this->write($username);
			}

			if ($password)
			{
				$this->setPrompt('/word:.*$/');
				$this->waitPrompt();
				$this->write($password);
			}

			$this->setPrompt();
			$this->waitPrompt();
		}
		catch (Exception $e)
		{
			throw new Exception("Login failed.");
		}

		return self::TELNET_OK;
	}

	/**
	* Sets the string of characters to respond to.
	* This should be set to the last character of the command line prompt
	* 
	* @access	public
	* @param	string		$s String to respond to
	* @return	bool
	*/
	public function setPrompt($s = '')
	{
		$this->prompt = $this->default_prompt;

		if ($s != '')
		{
			$this->prompt = $s;
		}

		return self::TELNET_OK;
	}

	/**
	* Gets character from the socket
	*     
	* @access	public
	* @return	void
	*/
	public function getc() 
	{
		@socket_set_timeout($this->socket, $this->timeout);

		return fgetc($this->socket); 
	}

	/**
	* Clears internal command buffer
	* 
	* @access	public
	* @return	void
	*/
	public function clearBuffer() 
	{
		$this->buffer = '';
	}

	/**
	* Reads characters from the socket and adds them to command buffer.
	* Handles telnet control characters. Stops when prompt is ecountered.
	* 
	* @access	public
	* @param	string		$prompt
	* @return	bool
	*/
	public function readTo($prompt)
	{
		if (!$this->socket)
		{
			throw new Exception("Telnet connection closed.");            
		}

		$this->clearBuffer();

		do
		{
			$c = $this->getc();

			if ($c === false)
			{
				throw new Exception("Couldn't find the requested : '".$prompt."', it was not in the data returned from server.");  
			}

			if ($c == $this->IAC)
			{
				if ($this->negotiateTelnetOptions())
				{
					continue;
				}
			}

			$this->buffer .= $c;

			if (@preg_match($prompt, $this->buffer)) 
			{			
				return self::TELNET_OK;
			}
		}
		while ($c != $this->NULL OR $c != $this->DC1);
	}

	/**
	* Write command to a socket
	* 
	* @access	public
	* @param	string		$buffer Stuff to write to socket
	* @param	bool		$addNewLine Default true, adds newline to the command 
	* @return	bool
	*/
	public function write($buffer = '', $addNewLine = TRUE)
	{
		if (!$this->socket)
		{
			throw new Exception("Telnet connection closed.");
		}

		// clear buffer from last command
		$this->clearBuffer();

		if ($addNewLine == true)
		{
			$buffer .= "\r\n";
		}

		if (!fwrite($this->socket, $buffer) < 0)
		{
			throw new Exception("Error writing to socket.");
		}

		return self::TELNET_OK;
	}
	
	/**
	* Returns the content of the command buffer
	* 
	* @access	public
	* @return	string		Content of the command buffer 
	*/
	public function getBuffer()
	{
		return $this->buffer;
	}
	
	/**
	* Telnet control character magic
	* 
	* @access	public
	* @param	string		$command Character to check
	* @return	bool
	*/
	public function negotiateTelnetOptions()
	{
		$c = $this->getc();
	
		if ($c != $this->IAC)
		{
			if (($c == $this->DO) OR ($c == $this->DONT))
			{
				$opt = $this->getc();
				fwrite($this->socket, $this->IAC.$this->WONT.$opt);
			}
			else if (($c == $this->WILL) OR ($c == $this->WONT)) 
			{
				$opt = $this->getc();            
				fwrite($this->socket, $this->IAC.$this->DONT.$opt);
			}
		}
		else
		{
			throw new Exception('Error: Something Wicked Happened.');
		}

		return self::TELNET_OK;
	}

	/**
	* Reads socket until prompt is encountered
	*
	* @access	public
	*/
	public function waitPrompt()
	{
		return $this->readTo($this->prompt);
	}

} // class Telnet
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>