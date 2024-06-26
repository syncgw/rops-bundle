<?php
declare(strict_types=1);

/*
 * 	<RopLogon> handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

class ropLogon {

    /**
     * 	Singleton instance of object
     * 	@var RopLogon
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): RopLogon {

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
		// [MS-OXROPS] 		2.2.3.1.1 RopLogon ROP Request Buffer
		// [MS-OXCSTOR]		2.2.1.1.1 RopLogon ROP Request Buffer
		// [MS-OXCSTOR]		2.2.1.1.3 RopLogon ROP Success Response Buffer for Private Mailbox
		// [MS-OXNSPI] 		2.2.1.2 Permitted Error Code Values
		// [MS-OXCDATA] 	2.4 Error Codes
		// [MS-OXCDATA]		2.2.1.1 Folder ID Structure

		if ($mod == mapiHTTP::MKRESP) {

			$p = $resp->savePos();
			$d = new \DateTime();
			$resp->getVar('LogonTime');
			$p1 = $resp->savePos();
			$resp->updVar('Seconds', $d->format('s'), false);
			$resp->restorePos($p1);
			$resp->updVar('Minutes', $d->format('i'), false);
			$resp->restorePos($p1);
			$resp->updVar('Hour', $d->format('G'), false);
			$resp->restorePos($p1);
			$resp->updVar('DayOfWeek', $d->format('N'), false);
			$resp->restorePos($p1);
			$resp->updVar('Day', $d->format('d'), false);
			$resp->restorePos($p1);
			$resp->updVar('Month', $d->format('m'), false);
			$resp->restorePos($p1);
			$resp->updVar('Year', $d->format('Y'), false);
			$resp->restorePos($p);
		}

		return true;
	}

}
