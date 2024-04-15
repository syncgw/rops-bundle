<?php
declare(strict_types=1);

/*
 * 	<RopLongTermIdFromId> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;

class ropLongTermIdFromId {

    /**
     * 	Singleton instance of object
     * 	@var RopLongTermIdFromId
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): RopLongTermIdFromId {

	   	if (!self::$_obj)
            self::$_obj = new self();

		return self::$_obj;
	}

 	/**
	 * 	Parse Rop request / response
	 *
	 *	@param 	- XML request document or binary request body
	 *	@param 	- XML response document
	 *	@param	- mapiHTTP::REQ = Decode request; mapiHTTP::RESP = Decode response; mapiHTTP::MKRESP = Create response
	 *	@return - true = Ok; false = Error
	 */
	public function Parse(&$req, XML &$resp, int $mod): bool {

		// [MS-OXROPS]		2.2.1 ROP Input and Output Buffers
		// [MS-OXCRPC] 		2.2.2.1 RPC_HEADER_EXT Structure
		// [MS-OXROPS]		2.2.3.8.1 RopLongTermIdFromId ROP Request Buffer
		// [MS-OXROPS] 		2.2.3.8.2 RopLongTermIdFromId ROP Success Response Buffer
		// [MS-OXCDATA] 	2.2.1.3.1 LongTermID Structure
		// [MS-OXCDATA] 	2.2.1.3 Global Identifier Structure

		return true;
	}

}
