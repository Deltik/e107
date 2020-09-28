<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration\Storage;


use e107\Configuration\ConfigurationInterface;
use PhpParser\Error;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

/**
 * e107 v1/v2 e107_config.php adapter
 * @package e107\Configuration\Storage
 */
class V2SiteConfigurationStorage extends AbstractSiteConfigurationStorage
{
	protected const DATABASE_CREDENTIAL_DEFAULTS = [
		'driver'  => 'mysql',
		'prefix'  => '',
		'port'    => '3306',
		'charset' => 'utf8mb4',
	];
	protected const LEGACY_DATABASE_VARIABLES = [
		'prefix'   => 'mySQLprefix',
		'host'     => 'mySQLserver',
		'port'     => 'mySQLport',
		'name'     => 'mySQLdefaultdb',
		'username' => 'mySQLuser',
		'password' => 'mySQLpassword',
		'charset'  => 'mySQLcharset',
	];
	protected const LEGACY_PATH_VARIABLES = [
		'admin'     => 'ADMIN_DIRECTORY',
		'core'      => 'CORE_DIRECTORY',
		'docs'      => 'DOCS_DIRECTORY',
		'help'      => 'HELP_DIRECTORY',
		'files'     => 'FILES_DIRECTORY',
		'handlers'  => 'HANDLERS_DIRECTORY',
		'images'    => 'IMAGES_DIRECTORY',
		'languages' => 'LANGUAGES_DIRECTORY',
		'media'     => 'MEDIA_DIRECTORY',
		'plugins'   => 'PLUGINS_DIRECTORY',
		'system'    => 'SYSTEM_DIRECTORY',
		'themes'    => 'THEMES_DIRECTORY',
		'web'       => 'WEB_DIRECTORY',
	];

	/**
	 * @inheritDoc
	 */
	public function read()
	{
		include($this->filePath);

		$output = [
			'database' => self::DATABASE_CREDENTIAL_DEFAULTS,
			'paths'    => self::PATH_DEFAULTS,
		];

		foreach (self::LEGACY_DATABASE_VARIABLES as $databaseKey => $legacyDatabaseVariable)
		{
			if (isset($$legacyDatabaseVariable))
			{
				$output['database'][$databaseKey] = $$legacyDatabaseVariable;
			}
		}
		foreach (self::LEGACY_PATH_VARIABLES as $pathKey => $legacyPathVariable)
		{
			if (isset($$legacyPathVariable))
			{
				$output['paths'][$pathKey] = $$legacyPathVariable;
			}
		}

		return $output;
	}

	/**
	 * @inheritDoc
	 */
	public function write($config)
	{
		$lexer = new Lexer\Emulative([
			'usedAttributes' => [
				'comments', 'startLine', 'endLine', 'startTokenPos', 'endTokenPos',
			],
		]);
		$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP5, $lexer);

		$currentRawConfig = file_get_contents($this->filePath);
		try
		{
			$ast = $parser->parse($currentRawConfig);
		}
		catch (Error $ignored)
		{
			$ast = $parser->parse($this->getSiteConfigFileTemplate());
		}
		$tokens = $lexer->getTokens();

		$traverser = new NodeTraverser();
		$traverser->addVisitor(new CloningVisitor());

		$traverser->addVisitor(new class(
			$config,
			self::LEGACY_DATABASE_VARIABLES,
			self::DATABASE_CREDENTIAL_DEFAULTS,
			self::LEGACY_PATH_VARIABLES,
			self::PATH_DEFAULTS,
		) extends NodeVisitorAbstract
		{
			private ConfigurationInterface $config;
			private $legacyDatabaseVariables;
			private $databaseCredentialDefaults;
			private $legacyPathVariables;
			private $pathDefaults;

			public function __construct(
				$config,
				$legacyDatabaseVariables,
				$databaseCredentialDefaults,
				$legacyPathVariables,
				$pathDefaults
			)
			{
				$this->config = $config;
				$this->legacyDatabaseVariables = $legacyDatabaseVariables;
				$this->databaseCredentialDefaults = $databaseCredentialDefaults;
				$this->legacyPathVariables = $legacyPathVariables;
				$this->pathDefaults = $pathDefaults;
			}

			public function leaveNode(Node $node)
			{
				if ($node instanceof Node\Expr\Assign)
				{
					if (in_array($node->var->name, $this->legacyDatabaseVariables))
					{
						$key = array_search($node->var->name, $this->legacyDatabaseVariables);
						$value = $this->config->get("database/$key");
						if (empty($value)) $value = $this->databaseCredentialDefaults[$key] ?? '';
						$node->expr->value = $value;
						unset($this->legacyDatabaseVariables[$key]);
						return $node;
					}
					elseif (in_array($node->var->name, $this->legacyPathVariables))
					{
						$key = array_search($node->var->name, $this->legacyPathVariables);
						$value = $this->config->get("paths/$key");
						if (empty($value)) $value = $this->pathDefaults[$key] ?? '';
						$node->expr->value = $value;
						unset($this->legacyPathVariables[$key]);
						return $node;
					}
				}
				return null;
			}

			public function afterTraverse(array $nodes)
			{
				foreach ($this->legacyDatabaseVariables as $key => $legacyDatabaseVariable)
				{
					$nodes[] = new Node\Stmt\Expression(
						new Node\Expr\Assign(
							new Node\Expr\Variable($legacyDatabaseVariable),
							new Node\Scalar\String_($this->config->get("database/$key"))
						)
					);
				}
				foreach ($this->legacyPathVariables as $key => $legacyPathVariable)
				{
					$nodes[] = new Node\Stmt\Expression(
						new Node\Expr\Assign(
							new Node\Expr\Variable($legacyPathVariable),
							new Node\Scalar\String_($this->config->get("paths/$key"))
						)
					);
				}
				return $nodes;
			}
		});

		$modifiedAst = $traverser->traverse($ast);

		$printer = new Standard();
		$replacedConfig = $printer->printFormatPreserving($modifiedAst, $ast, $tokens);
		file_put_contents($this->filePath, $replacedConfig);
	}

	/**
	 * @inheritDoc
	 */
	protected function getSiteConfigFileTemplate()
	{
		$copyrightYear = date('Y');
		$rfc2822Date = date('r');
		return <<<EOD
<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-{$copyrightYear} e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 configuration file
 *
 * This file has been generated by the installation script on {$rfc2822Date}.
 */

\$mySQLserver    = '{{ mySQLserver }}';
\$mySQLuser      = '{{ mySQLuser }}';
\$mySQLpassword  = '{{ mySQLpassword }}';
\$mySQLdefaultdb = '{{ mySQLdefaultdb }}';
\$mySQLport      = '{{ mySQLport }}';
\$mySQLprefix    = '{{ mySQLprefix }}';
\$mySQLcharset   = 'utf8';

\$ADMIN_DIRECTORY     = 'e107_admin/';
\$FILES_DIRECTORY     = 'e107_files/';
\$IMAGES_DIRECTORY    = 'e107_images/';
\$THEMES_DIRECTORY    = 'e107_themes/';
\$PLUGINS_DIRECTORY   = 'e107_plugins/';
\$HANDLERS_DIRECTORY  = 'e107_handlers/';
\$LANGUAGES_DIRECTORY = 'e107_languages/';
\$HELP_DIRECTORY      = 'e107_docs/help/';
\$MEDIA_DIRECTORY	  = 'e107_media/';
\$SYSTEM_DIRECTORY    = 'e107_system/';


// -- Optional --
// define('e_DEBUG', true); // Enable debug mode to allow displaying of errors
// define('e_HTTP_STATIC', 'https://static.mydomain.com/');  // Use a static subdomain for js/css/images etc. 
// define('e_MOD_REWRITE_STATIC', true); // Rewrite static image urls. 
// define('e_LOG_CRITICAL', true); // log critical errors but do not display them to user. 
// define('e_GIT', 'path-to-git');  // Path to GIT for developers
// define('X-FRAME-SAMEORIGIN', false); // Option to override X-Frame-Options 
// define('e_PDO, true); // Enable PDO mode (used in PHP > 7 and when mysql_* methods are not available)
EOD;
	}
}