<?php

	class mysqlDatabase
	{
		// class variables
		var $CON;

		// CONF (could be changed from outside)
		var $execTime, $queryTime, $nbReq;

		// Construct
		function mysqlDatabase (){
			$this->execTime = 0;

			$this->nbReq = 0;
		}

		function connect (){
			if (!$this->CON = @mysql_connect (DB_HOST, DB_USER, DB_PASS)){
				echo '<html><head><title>Map Factory - service unavailable</title></head><body><h1><b>Map Factory</b> is temporarily unavailable.</h1><h2><i>come back in a few minutes ...</i></h2></body></html>';
				exit ();
			}

			$this->query ('SET NAMES UTF8', $this->CON);
		}

		function select_db (){
			mysql_select_db (DB_NAME, $this->CON);
		}

		function query ($query){
			if (!$this->CON){
				$this->connect ();
				$this->select_db ();
			}

			$this->nbReq ++;

			return mysql_query ($query, $this->CON);
		}

		function select ($query, $from = 0, $max = 0){
			if (preg_match ("/^\\s*(select)/i", $query)){
				$this->queryTime = (float) array_sum (explode (' ', microtime ()));

				$rs = $this->query ($query);

				$this->queryTime = (float) array_sum (explode (' ', microtime ())) - $this->queryTime;
				$this->execTime += $this->queryTime;

				if ($rs){
					$total = @mysql_num_rows ($rs);

					if ($total > 0 && $from < $total){
						@mysql_data_seek ($rs, $from);

						($max == 0)?
							$max = $total:
							NULL;

						for ($n = 0; $n < $max && $row = @mysql_fetch_assoc ($rs); $n ++){
							$results[$n] = $row;
						}

						return array ('result' => $results, 'total' => mysql_num_rows ($rs), 'sorted' => $n);
					}

					mysql_free_result ($rs);
				}

				return array ('result' => array (), 'total' => 0, 'sorted' => 0);
			}

			return false;
		}

		function insert ($query){
			if (preg_match ("/^\\s*(insert)/i", $query)){
				$this->query ($query);

				return mysql_insert_id ($this->CON);
			}

			return false;
		}

		function update ($query){
			if (preg_match ("/^\\s*(update)/i", $query)){
				$this->query ($query);

				return mysql_affected_rows ($this->CON);
			}

			return false;
		}

		function delete ($query){
			if (preg_match ("/^\\s*(delete)/i", $query)){
				$this->query ($query);

				return mysql_affected_rows ($this->CON);
			}

			return false;
		}

		function close (){
			mysql_close ($this->CON);
		}
	}

?>
