<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Core\Exception;

/**
 * Exception to be thrown on database connection errors
 */
class DatabaseConnectionException extends \OxidEsales\Eshop\Core\Exception\DatabaseException
{
    /**
     * DatabaseConnectionException constructor.
     *
     * Use this exception to catch and rethrow exceptions of the underlying DBAL, that occur while connection to the database.
     * Provide the caught exception as the third parameter of the constructor to enable exception chaining.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous Previous exception thrown by the underlying DBAL
     */
    public function __construct($message, $code, \Exception $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
