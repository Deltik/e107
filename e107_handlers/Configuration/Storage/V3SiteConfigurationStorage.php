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
use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Error;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

/**
 * e107 v3 e107_config.php parser and writer
 *
 * @package e107\Configuration\Storage
 */
class V3SiteConfigurationStorage extends AbstractSiteConfigurationStorage
{
	public const    SIGNATURE         = 'e107_config.php format revision: 3';
	protected const DATABASE_VARIABLE = 'database';
	protected const PATHS_VARIABLE    = 'paths';

	/**
	 * @inheritDoc
	 */
	public function read()
	{
		include($this->filePath);

		$databaseVariable = self::DATABASE_VARIABLE;
		$pathsVariable = self::PATHS_VARIABLE;
		$output = [
			$databaseVariable => $$databaseVariable,
			$pathsVariable    => array_merge(self::PATH_DEFAULTS, $$pathsVariable),
		];

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
		$firstLine = current(explode("\n", $currentRawConfig));
		if (strpos($firstLine, self::SIGNATURE) === false || substr($firstLine, 0, 2) !== "<?")
		{
			$currentRawConfig = $this->getSiteConfigFileTemplate();
		}
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
			self::PATH_DEFAULTS,
		) extends NodeVisitorAbstract {
			private ConfigurationInterface $config;
			private                        $pathDefaults;

			public function __construct(
				$config,
				$pathDefaults
			)
			{
				$this->config = $config;
				$this->pathDefaults = $pathDefaults;
				$this->currentCategory = null;
				$this->unseenCategories = array_keys($this->config->get());
				$this->factory = new BuilderFactory;
			}

			public function leaveNode(Node $node)
			{
				if ($node instanceof Node\Expr\Variable)
				{
					$this->currentCategory = $node->name;
					if (($key = array_search($this->currentCategory, $this->unseenCategories)) !== false)
					{
						unset($this->unseenCategories[$key]);
					}

					return null;
				}
				if ($node instanceof Node\Expr\Assign)
				{
					$category = $node->var->name;
					$expression = $node->expr;
					if (!($expression instanceof Node\Expr\Array_)) return null;

					$newMap = $this->config->get($this->currentCategory);
					if (empty($newMap)) return null;

					$removedKeyIndexes = [];
					/** @var Node\Expr\ArrayItem $arrayItem */
					foreach ($expression->items as $index => $arrayItem)
					{
						$key = $arrayItem->key->value;
						if (!array_key_exists($key, $newMap))
						{
							$removedKeyIndexes[] = $index;
							continue;
						}

						$newValue = $newMap[$key];
						unset($newMap[$key]);

						$arrayItem->value = $this->factory->val($newValue);
					}

					foreach ($removedKeyIndexes as $removedKeyIndex)
					{
						array_splice($expression->items, $removedKeyIndex, 1, []);
					}

					foreach ($newMap as $remainingKey => $newValue)
					{
						$expression->items[] = new Node\Expr\ArrayItem(
							$this->factory->val($newValue),
							$this->factory->val($remainingKey)
						);
					}

					return $node;
				}

				return null;
			}

			public function afterTraverse(array $nodes)
			{
				foreach ($this->unseenCategories as $category)
				{
					$nodes[] = new Node\Stmt\Expression(
						new Node\Expr\Assign(
							new Node\Expr\Variable($category),
							$this->factory->val($this->config->get($category))
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

		return <<<EOD
<?php // e107_config.php format revision: 3 (do not change or remove this line)
/**
 * e107 website system
 *
 * Copyright (C) 2008-{$copyrightYear} e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Site configuration file
 */

/**
 * Database Connection Settings
 *
 * The \$database array contains the information that e107 needs to access the
 * database associated with this site.
 *
 * @var array \$database = [
 *     // Common options
 *     'driver' => 'string', // Case-insensitive driver name ({@see \e107\Database\Drivers})
 *     'prefix' => 'string', // The prefix to apply to every table in the database
 *
 *     // Options for mysql, pgsql, mssql, oracle
 *     'username' => 'string', // Login username (leave blank for none)
 *     'password' => 'string', // Login password (leave blank for none)
 *     'host' => 'string', // Hostname of the database server (domain or IP). 'localhost' is the most common value.
 *     'port' => integer,  // Port number of the database server (remove or set to null for default)
 *     'name' => 'string', // Name of the database or schema to use on the server
 *
 *     // Options for sqlite
 *     'path' => 'string', // Absolute path to the SQLite database
 *
 *     // Options for mysql, pgsql, oracle
 *     'charset' => 'string', // The character set used when connecting to the database (leave blank for default)
 *
 *     // Options for mysql
 *     'socket' => 'string', // Instead of a username and password, the path of a UNIX socket used to connect to MySQL
 * ]
 */
\$database = [
    // Required options
    'driver'   => 'mysql',
    'prefix'   => '{{ mySQLprefix }}',
    // Typical options for database servers
    'host'     => '{{ mySQLserver }}',
    'port'     => 3306,
    'name'     => '{{ mySQLdefaultdb }}',
    'username' => '{{ mySQLuser }}',
    'password' => '{{ mySQLpassword }}',
    // Typical options for database files
    'path'     => null,
    // Advanced options
    'charset'  => 'utf8mb4',
    'socket'   => null,
];

/**
 * Base Paths
 *
 * Relative paths to the directories of each kind of e107 resource
 *
 * e107 will append site-specific paths to these base paths.
 *
 * @var array \$paths = [
 *     // Current paths
 *     'admin'     => 'e107_admin/',      // Admin area files
 *     'core'      => 'e107_core/',       // System assets
 *     'docs'      => 'e107_docs/',       // Admin documentation
 *     'handlers'  => 'e107_handlers/',   // System backend framework
 *     'images'    => 'e107_images/',     // System images
 *     'languages' => 'e107_languages/',  // Locale files
 *     'media'     => 'e107_media/',      // Uploaded files
 *     'plugins'   => 'e107_plugins/',    // System plugins
 *     'system'    => 'e107_system/',     // Runtime-generated site files
 *     'themes'    => 'e107_themes/',     // System themes
 *     'web'       => 'e107_web/',        // Frontend web libraries
 *
 *     // Legacy paths
 *     'files'     => 'e107_files/',      // e107 v1/v2 supplementary files
 * ]
 */
\$paths = [
    'admin'     => 'e107_admin/',
    'core'      => 'e107_core/',
    'docs'      => 'e107_docs/',
    'handlers'  => 'e107_handlers/',
    'images'    => 'e107_images/',
    'languages' => 'e107_languages/',
    'media'     => 'e107_media/',
    'plugins'   => 'e107_plugins/',
    'system'    => 'e107_system/',
    'themes'    => 'e107_themes/',
    'web'       => 'e107_web/',
];
EOD;
	}
}