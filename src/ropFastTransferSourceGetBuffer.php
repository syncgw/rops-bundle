<?php
declare(strict_types=1);

/*
 * 	<RopFastTransferSourceGetBuffer> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;
use syncgw\ics\icsHandler;

class ropFastTransferSourceGetBuffer {

    /**
     * 	Singleton instance of object
     * 	@var ropFastTransferSourceGetBuffer
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropFastTransferSourceGetBuffer {

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
		// [MS-OXROPS]		2.2.12.4.1 RopFastTransferSourceGetBuffer ROP Request Buffer
		// [MS-OXCFOLD]		2.2.12.4.2 RopFastTransferSourceGetBuffer ROP Response Buffer
		// [MS-OXCDATA] 	2.10.1 PropertyTagArray Structure
		// [MS-AXCXICS]		2.2.3.1.1.5.1 RopFastTransferSourceGetBuffer ROP Request Buffer
		// [MS-AXCXICS]		2.2.3.1.1.5.2 RopFastTransferSourceGetBuffer ROP Response Buffer
		// [MS-AXCXICS]		2.2.4 FastTransfer Stream

		if ($mod != mapiHTTP::REQ) {

			$ics = icsHandler::getInstance();
			if (!$ics->Parse($req, $resp, $mod))
				return false;
			$len = $resp->getVar('TransferBufferSize');
			$resp->updVar('InProgressCount', $len);
			$resp->updVar('TotalStepCount', $len);
		}
		if ($mod == mapiHTTP::MKRESP) {

			$req->xpath('//RopId[text()="FastTransferSourceGetBuffer"]/..');
			$resp->xpath('//RopId[text()="FastTransferSourceGetBuffer"]/..');
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
