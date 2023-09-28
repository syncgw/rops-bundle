<?php
declare(strict_types=1);

/*
 * 	<RopSynchronizationConfigure> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropSynchronizationConfigure {

    /**
     * 	Singleton instance of object
     * 	@var RopSynchronizationConfigure
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): RopSynchronizationConfigure {

	   	if (!self::$_obj)
            self::$_obj = new self();

		return self::$_obj;
	}

    /**
	 * 	Collect information about class
	 *
	 * 	@param 	- Object to store information
     *	@param 	- true = Provide status information only (if available)
	 */
	public function getInfo(XML &$xml, bool $status): void {

		$xml->addVar('Opt', sprintf('&lt;%s&gt; response handler', 'RopSynchronizationConfigure'));
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
		// [MS-OXROPS]		2.2.13.1.1 RopSynchronizationConfigure ROP Request Buffer
		// [MS-OXCFOLD]		2.2.13.1.2 RopSynchronizationConfigure ROP Response Buffer
		// [MS-OXCFXICS] 	2.2.3.2.1.1.1 RopSynchronizationConfigure ROP Request Buffer
		// [MS-OXCFXICS] 	2.2.3.1.1.1.1 RopFastTransferSourceCopyTo ROP Request Buffer
		// [MS-OXCDATA] 	2.10.1 PropertyTagArray Structure

		if ($mod == mapiHTTP::MKRESP) {

			$req->xpath('//RopId[text()="SynchronizationConfigure"]/..');
			$resp->xpath('//RopId[text()="SynchronizationConfigure"]/..');
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
