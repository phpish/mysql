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

# TODO: Allow setting options like connection timeout: http://www.php.net/manual/en/mysqli.options.php

		if (!mysqli_real_connect($link, $host, $username, $password, $database))
		{
			error_log('mysqli_real_connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			return false;
		}

		_link($link);
		return $link;
	}


	function query($query, $params=array(), $link=NULL)
	{
		$link = $link ?: _link();
		$params = array_map(function($val) use($link) {
			return mysqli_real_escape_string($link, $val);
		}, $params);
		$query = vsprintf($query, $params);
		return mysqli_query($link, $query);
	}


	function rows($query, $params=array(), $link=NULL)
	{
		$rows = array();
		if ($result = query($query, $params, $link))
		{
			while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
			mysqli_free_result($result);
		}

		return $rows;

	}


	function row($query, $params=array(), $link=NULL)
	{
		$row = array();
		if ($result = query($query, $params, $link))
		{
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
		}

		return $row;

	}


	function num_rows($result)
	{
		return mysqli_num_rows($result);
	}


	function insert()
	{
		# TODO: insert('table', array('field1'=>array('%s'=>$value1)))
	}


	function insert_id($link=NULL)
	{
		$link = $link ?: _link();
		return mysqli_insert_id($link);
	}


	function update()
	{
		# TODO: update('table', array('field1'=>array('%s'=>$value1)), array('id'=>array('%d'=>1)))
	}


	function affected_rows($link=NULL)
	{
		$link = $link ?: _link();
		return  mysqli_affected_rows($link);
	}


	function close($link=NULL)
	{
		$link = $link ?: _link();
		return mysqli_close($link);
	}


	function error($link=NUll)
	{
		$link = $link ?: _link();
		return mysqli_error($link);
	}

?>