<?php
declare(strict_types=1);

/*
 * 	<RopOpenMessage> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropOpenMessage {

   /**
     * 	Singleton instance of object
     * 	@var RopOpenMessage
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropOpenMessage {

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
		// [MS-OXROPS]		2.2.6.1.1 RopOpenMessage ROP Request Buffer
		// [MS-OXROPS]

		if ($mod == mapiHTTP::MKRESP) {

			$req->xpath('//RopId[text()="OpenFolder"]/..');
			while($req->getItem() !== null) {

				$p = $req->savePos();
				$req->restorePos($p);
				$id = $req->getVar('GlobalCounter', false);
				$resp->setVal($id);
				$req->restorePos($p);
			}
		}

		return true;
	}

}
