<?php
declare(strict_types=1);

/*
 * 	<RopGetPropertyIdsFromNames> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;

class ropGetPropertyIdsFromNames {

    /**
     * 	Singleton instance of object
     * 	@var RopGetPropertyIdsFromNames
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): RopGetPropertyIdsFromNames {

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
		// [MS-OXROPS] 		2.2.8.1.1 RopGetPropertyIdsFromNames ROP Request Buffer
		// [MS-OXCPRPT]		2.2.12.1 RopGetPropertyIdsFromNames ROP Request Buffer
		// [MS-OXCDATA] 	2.6 Property Name Structures
		// [MS-OXROPS] 		2.2.8.1.2 RopGetPropertyIdsFromNames ROP Success Response Buffer

		return true;
	}

}
