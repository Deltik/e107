<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 */

namespace e107\SessionHandlers;

use e107;
use e_db;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Non-blocking session handler that saves sessions in a database table
 * @package e107\SessionHandlers
 */
class DatabaseSessionHandler extends BaseSessionHandler
{
	protected e_db $_db;

	/**
	 * Table name
	 */
	protected string $_table = 'session';

	protected ?int $_lifetime = null;

	public function __construct()
	{
		$this->_db = e107::getDb('session');
	}

	/**
	 * @return string
	 */
	protected function getTable(): string
	{
		return $this->_table;
	}

	public function getLifetime(): int
	{
		if (null === $this->_lifetime)
		{
			$this->setLifetime(ini_get('session.gc_maxlifetime'));
		}
		return (integer)$this->_lifetime;
	}

	/**
	 * @param integer|null $seconds
	 * @return static
	 */
	protected function setLifetime(?int $seconds = null): static
	{
		$this->_lifetime = $seconds;
		return $this;
	}

	/**
	 * Open session, parameters are ignored (see e_session handler)
	 * @param string $save_path
	 * @param string $sess_name
	 * @return boolean
	 */
	public function open($save_path, $sess_name): bool
	{
		return true;
	}

	/**
	 * Close session
	 * @return boolean
	 */
	public function close(): bool
	{
		$this->gc($this->getLifetime());
		return true;
	}

	/**
	 * Get session data
	 * @param string $session_id
	 * @return string
	 */
	public function read($session_id): string
	{
		$check = $this->_db->select($this->getTable(), 'session_data', "session_id='" . $this->_sanitize($session_id) . "' AND session_expires>" . time());
		if ($check)
		{
			$tmp = $this->_db->fetch();
			return $tmp['session_data'];
		}
		elseif (false !== $check)
		{
			return '';
		}
		throw new \RuntimeException(
			"SQL error while trying to read session ID $session_id data: " . $this->_db->getLastErrorText()
		);
	}

	/**
	 * Write session data
	 * @param string $session_id
	 * @param string $session_data
	 * @return boolean
	 */
	public function write($session_id, $session_data): bool
	{
		if (!($session_id = $this->_sanitize($session_id))) return false;

		$data = $this->generateDbWriteData($session_data);
		$update_instead = $this->_db->select($this->getTable(), 'session_id', "`session_id`='{$session_id}'");

		if ($update_instead)
		{
			$data['WHERE'] = "`session_id`='{$session_id}'";
			$success = false !== $this->_db->update($this->getTable(), $data);
		}
		else
		{
			$data['data']['session_id'] = $session_id;
			$success = $this->_db->insert($this->getTable(), $data);
		}

		if ($success) return true;
		throw new \RuntimeException(
			"SQL error while trying to write data to session ID $session_id: " . $this->_db->getLastErrorText()
		);
	}

	/**
	 * Destroy session
	 * @param string $session_id
	 * @return boolean
	 */
	public function destroy($session_id): bool
	{
		$session_id = $this->_sanitize($session_id);
		return 0 < $this->_db->delete($this->getTable(), "`session_id`='{$session_id}'");
	}

	/**
	 * Garbage collection
	 * @param int $session_maxlf ignored - see write()
	 * @return int|false Number of deleted sessions
	 */
	public function gc($session_maxlf): int|false
	{
		$this->_db->delete($this->getTable(), '`session_expires`<' . time());
		return $this->_db->foundRows();
	}

	/**
	 * Allow only well formed session id string
	 * @param string $session_id
	 * @return string
	 */
	protected function _sanitize(string $session_id): string
	{
		return preg_replace('#[^0-9a-zA-Z,-]#', '', $session_id);
	}

	/**
	 * Generate the data payload to put into the {@see e_db}
	 * @param $session_data
	 * @return array
	 */
	#[ArrayShape(['data' => "array", '_FIELD_TYPES' => "string[]", '_DEFAULT' => "string"])]
	private function generateDbWriteData($session_data): array
	{
		return array(
			'data'         => array(
				'session_expires' => time() + $this->getLifetime(),
				'session_user'    => e107::getUser()->getId(),
				'session_data'    => $session_data,
			),
			'_FIELD_TYPES' => array(
				'session_id'      => 'str',
				'session_expires' => 'int',
				'session_data'    => 'str',
			),
			'_DEFAULT'     => 'str',
		);
	}
}
