<?php
	session_start();
	
	if(!is_dir(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor'))
	{
		exit('Please run "<code>composer install</code>" from "<code>' . dirname(dirname(__DIR__)) . '</code>" first to fetch the required repository!');
	}
	
	elseif(file_exists(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config.php'))
	{
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			header('Content-Type: application/json');
			
			echo json_encode
			(
				array
				(
					'status'						=> 301,
					'url'							=> '../xhr/boot'
				)
			);
			exit;
		}
		else
		{
			header('Location: ../');
			exit;
		}
	}
	
	header('Content-Type: application/json');
	
	$error											= false;
	$unzip											= false;
	
	$source											= file_get_contents('assets' . DIRECTORY_SEPARATOR . 'config-sample.txt');
	$output											= dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config.php';
	
	$source											= str_replace
	(
		array
		(
			'%OPENTAG%',
			'%ENCRYPTION_KEY%',
			'%COOKIE_NAME%',
			'%DSN%',
			'%DB_DRIVER%',
			'%DB_HOSTNAME%',
			'%DB_PORT%',
			'%DB_USERNAME%',
			'%DB_PASSWORD%',
			'%DB_DATABASE%',
			'%TIMEZONE%',
			'%DOCUMENT_EXTENSION%',
			'%IMAGE_EXTENSION%',
			'%MAX_UPLOAD_SIZE%',
			'%IMAGE_DIMENSION%',
			'%THUMBNAIL_DIMENSION%',
			'%ICON_DIMENSION%'
		),
		array
		(
			'<?php',
			$_SESSION['security']['encryption'],
			$_SESSION['security']['cookie_name'],
			(isset($_SESSION['database']['dsn']) ? $_SESSION['database']['dsn'] : null),
			$_SESSION['database']['driver'],
			$_SESSION['database']['hostname'],
			$_SESSION['database']['port'],
			$_SESSION['database']['username'],
			$_SESSION['database']['password'],
			$_SESSION['database']['initial'],
			$_SESSION['system']['timezone'],
			$_SESSION['system']['file_extension'],
			$_SESSION['system']['image_extension'],
			$_SESSION['system']['max_upload_size'],
			$_SESSION['system']['image_dimension'],
			$_SESSION['system']['thumbnail_dimension'],
			$_SESSION['system']['icon_dimension']
		),
		$source
	);
	
	if(!isset($_GET['validate_config']) && !file_exists(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config.php'))
	{
		$ftp_form									= '
			<label class="d-block">
				<input type="checkbox" name="request_config" value="1"  /> <b>CLICK HERE</b> to upload config file manually after installation
			</label>
			<div class="using_ftp">
				<hr />
				<p>
					<b>OR</b> fill the field below with your FTP account to try writing configuration file over FTP.
				</p>
				<div class="row">
					<div class="col-sm-9">
						<div class="form-group mb-2">
							<label class="d-block text-muted mb-1">
								Hostname
							</label>
							<input type="text" name="ftp_host" class="form-control form-control-sm" placeholder="e.g: ftp.example.com" value="' . (isset($_POST['ftp_host']) ? $_POST['ftp_host'] : null) . '" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group mb-2">
							<label class="d-block text-muted mb-1">
								Port
							</label>
							<input type="text" name="ftp_port" class="form-control form-control-sm" placeholder="e.g: 21" value="' . (isset($_POST['ftp_port']) ? $_POST['ftp_port'] : null) . '" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group mb-2">
							<label class="d-block text-muted mb-1">
								Username
							</label>
							<input type="text" name="ftp_user" class="form-control form-control-sm" placeholder="e.g: root" value="' . (isset($_POST['ftp_user']) ? $_POST['ftp_user'] : null) . '" />
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group mb-2">
							<label class="d-block text-muted mb-1">
								Password
							</label>
							<input type="password" name="ftp_password" class="form-control form-control-sm" placeholder="Your FTP password" value="' . (isset($_POST['ftp_password']) ? $_POST['ftp_password'] : null) . '" />
						</div>
					</div>
				</div>
				<div class="form-group mb-2">
					<label class="d-block text-muted mb-1">
						Install Path
					</label>
					<input type="text" name="ftp_directory" class="form-control form-control-sm" placeholder="Path to install Aksara" value="' . (isset($_POST['ftp_directory']) ? $_POST['ftp_directory'] : dirname(dirname(dirname(__FILE__)))) . '" />
				</div>
			</div>
		';
		
		if((!isset($_POST['request_config']) || !$_POST['request_config']) && isset($_POST['ftp_host']) && $_POST['ftp_host'] && isset($_POST['ftp_port']) && $_POST['ftp_port'] && isset($_POST['ftp_user']) && $_POST['ftp_user'] && isset($_POST['ftp_password']) && $_POST['ftp_password'] && isset($_POST['ftp_directory']) && $_POST['ftp_directory'])
		{
			$connection								= ftp_connect($_POST['ftp_host'], $_POST['ftp_port'], 10);
			
			if($connection && ftp_login($connection, $_POST['ftp_user'], $_POST['ftp_password']))
			{
				ftp_pasv($connection, true);
				
				$tmpfile							= fopen('php://memory', 'r+');
				
				fwrite($tmpfile, $source);
				rewind($tmpfile);
				
				if(!ftp_fput($connection, $_POST['ftp_directory'] . DIRECTORY_SEPARATOR . 'config.php', $tmpfile, FTP_BINARY))
				{
					$error							= true;
				}
				
				fclose($tmpfile);
				
				ftp_close($connection);
				
				if($error)
				{
					echo json_encode
					(
						array
						(
							'status'				=> 403,
							'message'				=> 'Cannot write file using FTP. Please check if the Aksara install path is correct.<hr />' . $ftp_form
						)
					);
					
					exit;
				}
			}
			else
			{
				echo json_encode
				(
					array
					(
						'status'					=> 403,
						'message'					=> 'Couldn\'t connect to FTP server using provided settings!<hr />' . $ftp_form
					)
				);
				
				exit;
			}
		}
		elseif(!isset($_POST['request_config']) || !$_POST['request_config'])
		{
			try
			{
				$handle								= fopen($output, 'w+');
				
				if(!$handle)
				{
					throw new \RuntimeException('Failed to open or create config file!');
				}
				
				if(!fwrite($handle, $source))
				{
					throw new \RuntimeException('Failed to write configuration into config file!');
				}
				
				fclose($handle);
			}
			catch(\Exception $e)
			{
				echo json_encode
				(
					array
					(
						'status'					=> 403,
						'message'					=> $e->getMessage() . '<hr />' . $ftp_form
					)
				);
				
				exit;
			}
		}
		
		//$available_driver							= PDO::getAvailableDrivers();
		$available_driver							= array('MySQLi', 'Postgre', 'SQLSRV', 'SQLite3');
		
		if(in_array($_SESSION['database']['driver'], $available_driver))
		{
			$query									= null;
			
			$schema									= file_get_contents('assets' . DIRECTORY_SEPARATOR . 'schema.sql');
			
			$schema									.= '
				INSERT INTO app__settings
				(
					app_name,
					app_description,
					frontend_theme,
					backend_theme,
					app_language,
					office_name,
					office_phone,
					office_email,
					office_address,
					office_map,
					frontend_registration,
					default_membership_group
				)
				VALUES
				(
					"' . addslashes($_SESSION['system']['site_title']) . '",
					"' . addslashes($_SESSION['system']['site_description']) . '",
					"default",
					"backend",
					1,
					"Some Company Name",
					"+6281381614558",
					"info@example.com",
					"2nd Floor Example Tower Building, Some Road Name, Any Region",
					"[]",
					1,
					3
				);
			';
			
			$schema									.= '
				INSERT INTO app__users
				(
					first_name,
					last_name,
					email,
					username,
					password,
					language_id,
					group_id,
					registered_date,
					status
				)
				VALUES
				(
					"' . addslashes($_SESSION['security']['first_name']) . '",
					"' . addslashes($_SESSION['security']['last_name']) . '",
					"' . $_SESSION['security']['email'] . '",
					"' . $_SESSION['security']['username'] . '",
					"' . password_hash($_SESSION['security']['password'] . $_SESSION['security']['encryption'], PASSWORD_DEFAULT) . '",
					1,
					1,
					"' . date('Y-m-d') . '",
					1
				);
			';
			
			if(!$_SESSION['system']['mode'])
			{
				$schema								.= file_get_contents('assets' . DIRECTORY_SEPARATOR . 'sample-data.sql');
			}
			
			if('MySQLi' == $_SESSION['database']['driver'])
			{
				$connection							= new mysqli($_SESSION['database']['hostname'], $_SESSION['database']['username'], $_SESSION['database']['password'], $_SESSION['database']['initial'], (is_int($_SESSION['database']['port']) ? $_SESSION['database']['port'] : 3306));
				
				if(!$connection->connect_errno)
				{
					$table_exists					= $connection->query('SHOW TABLES');
					
					if($table_exists->num_rows)
					{
						$connection->query('SET foreign_key_checks = 0');
						
						foreach($table_exists->fetch_all() as $key => $val)
						{
							$connection->query('DROP TABLE ' . $val[0]);
						}
						
						$connection->query('SET foreign_key_checks = 1');
					}
					
					$connection->multi_query($schema);
					
					if($connection->connect_errno)
					{
						$error						= $connection->connect_error;
					}
				}
				else
				{
					$error							= $connection->connect_error;
				}
			}
			
			elseif('Postgre' == $_SESSION['database']['driver'])
			{
				$connection							= pg_connect('host=' . $_SESSION['database']['hostname'] . ' port=' . (is_int($_SESSION['database']['port']) ? $_SESSION['database']['port'] : 5432) . ' user=' . $_SESSION['database']['username'] . ' password=' . $_SESSION['database']['password'] . ' dbname=' . $_SESSION['database']['initial']);
				
				if($connection)
				{
					$table_exists					= pg_query($connection, 'SHOW TABLES');
					
					if(pg_num_rows($table_exists))
					{
						pg_query($connection, 'SET foreign_key_checks = 0');
						
						foreach(pg_fetch_all($table_exists) as $key => $val)
						{
							pg_query($connection, 'DROP TABLE ' . $val);
						}
						
						pg_query($connection, 'SET foreign_key_checks = 1');
					}
					
					pg_send_query($connection, $schema);
					
					if(error_get_last())
					{
						$error						= error_get_last();
						$error						= $error['message'];
					}
				}
				else
				{
					$error							= error_get_last();
					$error							= $error['message'];
				}
			}
			
			elseif('SQLSRV' == $_SESSION['database']['driver'])
			{
				$connection							= sqlsrv_connect($_SESSION['database']['hostname'] . ($$_SESSION['database']['port'] ? ',' . $_SESSION['database']['port'] : null), array('UID' => $_SESSION['database']['username'], 'Password' => $_SESSION['database']['password'], 'Database' => $_SESSION['database']['initial']));
				
				if($connection)
				{
					$table_exists					= sqlsrv_query($connection, 'SHOW TABLES');
					
					if(sqlsrv_num_rows($table_exists))
					{
						sqlsrv_query($connection, 'SET foreign_key_checks = 0');
						
						foreach(sqlsrv_fetch_assoc($table_exists) as $key => $val)
						{
							sqlsrv_query($connection, 'DROP TABLE ' . $val);
						}
						
						sqlsrv_query($connection, 'SET foreign_key_checks = 1');
					}
					
					sqlsrv_query($connection, $schema);
					
					if(sqlsrv_errors())
					{
						$error						= sqlsrv_errors();
						$error						= $error['message'];
					}
				}
				else
				{
					$error							= sqlsrv_errors();
					$error							= $error['message'];
				}
			}
			
			elseif('SQLite3' == $_SESSION['database']['driver'])
			{
				$connection							= new SQLite3($_SESSION['database']['hostname']);
				
				if($connection)
				{
					$table_exists					= $connection->query('SELECT name FROM sqlite_master WHERE type = "table"');
					
					if($table_exists)
					{
						$connection->query('SET foreign_key_checks = 0');
						
						foreach($table_exists as $key => $val)
						{
							$connection->query('DROP TABLE ' . $val);
						}
						
						$connection->query('SET foreign_key_checks = 1');
					}
					
					$connection->query($schema);
					
					if($connection->lastErrorMsg())
					{
						$error						= $connection->lastErrorMsg();
					}
				}
				else
				{
					$error							= $connection->lastErrorMsg();
				}
			}
			
			if(!$error)
			{
				$zip								= new ZipArchive();
				$unzip								= $zip->open('assets' . DIRECTORY_SEPARATOR . 'sample-module.zip');
				
				if($unzip === true)
				{
					$zip->extractTo(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'modules');
					$zip->close();
				}
			}
			else
			{
				if(file_exists($output))
				{
					@unlink($output);
				}
				
				$error								= $error;
			}
		}
		else
		{
			$error									= 'Please choose the correct database driver!';
		}
		
		if($error)
		{
			echo json_encode
			(
				array
				(
					'status'						=> 403,
					'message'						=> $error
				)
			);
			
			exit;
		}
	}
	
	$html											= '
		<h4>
			Congratulations!
		</h4>
		<p>
			<a href="//www.aksaracms.com" class="text-primary text-decoration-none" target="_blank"><b>Aksara</b></a> has been successfully installed on your system.
		</p>
		' . ((isset($_POST['request_config']) && 1 == $_POST['request_config']) || (isset($_GET['validate_config']) && 1 == $_GET['validate_config']) ? '
		<hr class="row" />
		<div class="alert alert-warning">
			<h4>
				Notice
			</h4>
			<p>
				Your configuration file or folder is not writable or there was a problem creating the configuration file. You will have to create the following code by hand in ' . dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . '<b>config.php</b> manually and click on revalidate config button.
			</p>
			<textarea rows="10" class="form-control" onclick="this.focus();this.select()">' . $source . '</textarea>
		</div>
		' : null) . '
		<hr class="row" />
		<p class="mb-0">
			You can login as superuser with following credential:
		</p>
		<div class="row">
			<div class="col-4 font-weight-bold">
				Username
			</div>
			<div class="col-8">
				' . $_SESSION['security']['username'] . '
			</div>
		</div>
		<div class="row form-group">
			<div class="col-4 font-weight-bold">
				Password
			</div>
			<div class="col-8">
				' . $_SESSION['security']['password'] . '
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-md-5">
				<img src="assets/like-a-boss.png" class="img-fluid" alt="Like a boss..." />
			</div>
			<div class="col-md-7">
				<p>
					If you find this useful, follow my updates to get my other works!
				</p>
				<p>
					Just to remind you, i also <b>collect donations</b> from people like you to <b>support my research</b>.
				</p>
				<p>
					Regardless of the amount, it will be very useful.
				</p>
				<p>
					Cheers,
					<br />
					<a href="//abydahana.github.io" class="text-primary text-decoration-none" target="_blank">
						<b>Aby Dahana</b>
					</a>
				</p>
			</div>
		</div>
		<hr class="row" />
		<div class="row">
			<div class="col-sm-6">
				&nbsp;
			</div>
			<div class="col-sm-6">
				' . (file_exists(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config.php') ? '<a href="' . (!$_SESSION['system']['mode'] && $unzip ? '../xhr/boot' : '../home/partial_error') . '" class="btn btn-warning btn-block font-weight-bold">Launch Your App</a>' : '<a href="install.php?validate_config=1" class="btn btn-warning btn-block font-weight-bold --xhr">Revalidate Config</a>') . '
			</div>
		</div>
	';
	
	echo json_encode
	(
		array
		(
			'status'								=> 200,
			'active'								=> '.final',
			'passed'								=> '.requirement, .database, .security, .system, .final',
			'html'									=> $html
		)
	);
	