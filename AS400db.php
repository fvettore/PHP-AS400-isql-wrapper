<?php
/**************************************************************************
*	AS400DB isql wrapper
*	© 2015 by Fabrizio Vettore - fabrizio(at)vettore.org
*	V 0.55
*
*	Replacec PHP-ODBC connection not working on 64bit systems
*	due to BAD IBM driver implementaion that causes
*	segmentation fault on NULL values 
*	
*	Requires isql command (iusql for UNICODE)
*	and an installed and correctly configured ODBC DSN
*
*	It uses shell_exec command. Be careful.
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
**************************************************************************/


//DEFINE RESULT OBJECT RETURNED BY QUERY
class AS400QueryResult{
	public $num_rows=0;
	private $rowposition=0;	
	private $resarray;

	function __construct($result){
		$this->resarray=$result;
		$this->num_rows=count($result);
	}
	function fetch_array(){
		if($this->rowposition<$this->num_rows){
			$this->rowposition++;
			return $this->resarray[$this->rowposition-1];
		}
		else return NULL;
	}
}

//BASIC CLASS performing query
class AS400DB{
	
	private $dbuser;	
	private $dbpwd;
	public $ODBCerror;

	function __construct($DSN,$dbuser,$dbpwd){
		$this->DSN=$DSN;
		$this->dbuser=$dbuser;
		$this->dbpwd=$dbpwd;
	}

	function query($query){
		$query=str_replace("\n"," ",$query);

		//field separator is |
		$command="echo \"$query\" | iusql $this->DSN $this->dbuser $this->dbpwd -c -d\"|\"  -b -v";
		$result=shell_exec($command);	
		
		if($result[0]!="["){
			//everything fine: going to parse result
			$q=explode("\n",$result);
			$head=explode("|",$q[0]);
			$numfield=count($head);	
			//everything OK data returned
			for($i=1;$i<(count($q)-1);$i++){
				$rowval=$q[$i];
				$rowexpl=explode("|",$rowval);
				for($k=0;$k<$numfield;$k++){
					$row[$head[$k]]=trim($rowexpl[$k]);
				}
				$resarray[$i-1]=$row;
			}
			$result=new AS400QueryResult($resarray);
		return($result);
		}
		else {
			//not a parsable result PROBABLY ODBC error...
			$this->ODBCerror=$result;
			return(NULL);
		}
	}
}

?>
