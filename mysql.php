<?php

	namespace phpish\mysql;


	function _link($link=NULL)
	{
		static $_link;
		if (!is_null($link)) $_link = $link;
		return $_link;
	}


	function connect($host, $username, $password, $database)
	{
		$link = mysqli_init();
		if (!$link)
		{
			error_log('mysqli_init failed');
			return false;
		}

//TODO: Allow setting options like connection timeout: http://www.php.net/manual/en/mysqli.options.php

		if (!mysqli_real_connect($link, $host, $username, $password, $database))
		{
			error_log('mysqli_real_connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			return false;
		}

		_link($link);
		return $link;
	}


	function query($query, $params, $link=NULL)
	{
		$link = $link ?: _link();
		array_map('mysqli_real_escape_string', $params);
		$query = vsprintf($query, $params);
		return mysqli_query($link, $query);
	}


	funcion rows($query, $params, $link=NULL)
	{
		$rows = array();
		if ($result = query($query, $params, $link))
		{
			while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
			mysqli_free_result($result);
		}

		return $rows;

	}


	funcion row($query, $params, $link=NULL)
	{
		$row = array();
		if ($result = query($query, $params, $link))
		{
			$row = mysqli_fetch_assoc($result)
			mysqli_free_result($result);
		}

		return $row;

	}


	function insert()
	{

	}


	function update()
	{

	}


	function close($link=NULL)
	{
		$link = $link ?: _link();
		return mysqli_close($link);
	}

?>