<?php
declare(strict_types=1);

/*
 * 	<RopSynchronizationUploadStateStreamContinue> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropSynchronizationUploadStateStreamContinue {

    /**
     * 	Singleton instance of object
     * 	@var ropSynchronizationUploadStateStreamContinue
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropSynchronizationUploadStateStreamContinue {

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
		// [MS-OXCROPS]		2.2.13.10 RopSynchronizationUploadStateStreamContinue ROP
		// [MS-OXCROPS]		2.2.13.10.1 RopSynchronizationUploadStateStreamContinue ROP Request Buffer
		// [MS-OXCROPS]		2.2.13.10.2 RopSynchronizationUploadStateStreamContinue ROP Response Buffer
		// [MS-OXCDATA] 	2.10.1 PropertyTagArray Structure
		// {MS_OXCFXICS]	2.2.1.1.1 MetaTagIdsetGiven ICS State Property

		if ($mod == mapiHTTP::MKRESP) {

			$req->xpath('//RopId[text()="SynchronizationUploadStateStreamContinue"]/..');
			$resp->xpath('//RopId[text()="SynchronizationUploadStateStreamContinue"]/..');
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
