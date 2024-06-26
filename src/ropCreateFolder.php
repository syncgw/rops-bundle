<?php
declare(strict_types=1);

/*
 * 	<RopCreateFolder> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropCreateFolder {

   /**
     * 	Singleton instance of object
     * 	@var ropCreateFolder
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropCreateFolder {

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
		// [MS-OXCROPS]		2.2.4.2.1 RopCreateFolder ROP Request Buffer
		// [MS-OXCFOLD]		2.2.1.2.1 RopCreateFolder ROP Request Buffer
		// [MS-OXCROPS]		2.2.4.2.2 RopCreateFolder ROP Success Response Buffer
		// [MS-OXCROPS]		2.2.4.2.3 RopCreateFolder ROP Failure Response Buffer

		if ($mod == mapiHTTP::MKRESP) {

			// $db = DB::getInstance();
			// $resp->updVar('FolderId', $xml->getVar('GUID'));

			$req->xpath('//RopId[text()="CreateFolder"]/..');
			$resp->xpath('//RopId[text()="CreateFolder"]/..');
			while($req->getItem() !== null) {

				$ip = $req->savePos();
				$id = $req->getVar('OutputHandleIndex', false);
				$resp->getItem();
				$op = $resp->savePos();
				$resp->updVar('OutputHandleIndex', $id, false);
				$req->restorePos($ip);
				$resp->restorePos($op);
			}
		}

		return true;
	}

}
