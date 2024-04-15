<?php
declare(strict_types=1);

/*
 * 	<RopGetReceiveFolder> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropGetReceiveFolder {

    /**
     * 	Singleton instance of object
     * 	@var RopGetReceiveFolder
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): RopGetReceiveFolder {

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

		// [MS-OXCROPS]		2.2.1 ROP Input and Output Buffers
		// [MS-OXCRPC] 		2.2.2.1 RPC_HEADER_EXT Structure
		// [MS-OXCROPS] 	2.2.3.2.1 RopGetReceiveFolder ROP Request Buffer
		// [MS-OXCROPS] 	2.2.3.2.2 RopGetReceiveFolder ROP Success Response Buffer

		if ($mod == mapiHTTP::MKRESP) {

			// get decoded request body
			// set <ServerObjectHandleTable><Object>
			$req->xpath('//RopId[text()="GetReceiveFolder"]/..');
			$req->getItem();

			$handle = $req->getVar('InputHandleIndex', false);

			$resp->xpath('//RopId[text()="GetReceiveFolder"]/..');
			$resp->getItem();

			$resp->updVar('InputHandleIndex', $handle);
		}

		return true;
	}

}
