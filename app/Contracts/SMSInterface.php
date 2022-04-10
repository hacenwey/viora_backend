<?php

/**
 * SMS Interface
 *
 * @package     iProd
 * @subpackage  Contracts
 * @category    SMS Interface
 * @author      iProd Technologies Team
 * @version     1.0.0
 * @link        https://iprod.mr
*/

namespace App\Contracts;

interface SMSInterface
{
	function initialize();
	function send($to,$text);
}
