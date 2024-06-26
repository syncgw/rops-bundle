<?php
declare(strict_types=1);

/*
 * 	<RopSynchronizationUploadStateStreamBegin> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropSynchronizationUploadStateStreamBegin {

    /**
     * 	Singleton instance of object
     * 	@var ropSynchronizationUploadStateStreamBegin
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropSynchronizationUploadStateStreamBegin {

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
		// [MS-OXROPS]		2.2.13.9.1 RopSynchronizationUploadStateStreamBegin ROP Request Buffer
		// [MS-OXCFOLD]		2.2.13.9.2 RopSynchronizationUploadStateStreamBegin ROP Response Buffer
		// [MS-OXCDATA] 	2.10.1 PropertyTagArray Structure
		// {MS_OXCFXICS]	2.2.1.1.1 MetaTagIdsetGiven ICS State Property

		if ($mod == mapiHTTP::MKRESP) {

			$req->xpath('//RopId[text()="SynchronizationUploadStateStreamBegin"]/..');
			$resp->xpath('//RopId[text()="SynchronizationUploadStateStreamBegin"]/..');
			while($req->getItem() !== null) {

				$ip = $req->savePos();
				$id = $req->getVar('InputHandleIndex', false);
				$resp->getItem();
				$op = $resp->savePos();
				$resp->updVar('InputHandleIndex', $id, false);
				$req->restorePos($ip);
				$resp->restorePos($op);
			}
		}

		return true;
	}

}
