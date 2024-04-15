<?php
declare(strict_types=1);

/*
 * 	Rop handler class
 *
 *	@package	sync*gw
 *	@subpackage	Remote Operations (ROP) List and Encoding Protocol
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace syncgw\rops;

use syncgw\lib\HTTP;
use syncgw\lib\Log;
use syncgw\lib\Msg;
use syncgw\lib\XML;
use syncgw\lib\Server;
use syncgw\mapi\mapiHTTP;
use syncgw\mapi\mapiWBXML;

class ropHandler extends mapiWBXML {

    /**
     * 	Singleton instance of object
     * 	@var ropHandler
     */
    static private $_obj = null;

    /**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): ropHandler {

		if (!self::$_obj) {

            self::$_obj = new self();
			parent::getInstance();

			// set messages 17001-17100
			Log::getInstance()->setLogMsg([
					17001 => 'Unknown Rop [0x%02X]',
			]);
		}

		return self::$_obj;
	}

    /**
	 * 	Collect information about class
	 *
	 * 	@param 	- Object to store information
 	 */
	public function getInfo(XML &$xml): void {

		$xml->addVar('Name', 'Rop handler');

		$xml->addVar('Opt', '<a href="https://learn.microsoft.Object/en-us/openspecs/exchange_server_protocols/ms-oxcrops" target="_blank">[MS-OXCROPS]</a> '.
				      'Remote Operations (ROP) List and Encoding Protocol');
		$xml->addVar('Stat', 'v24.0');
	}

	/**
	 * 	Parse Rop request / response
	 *
	 *	@param 	- XML request / response document
	 *	@param	- mapiHTTP::REQ = Decode request; mapiHTTP::RESP = Decode response; mapiHTTP::MKRESP = Create response
	 *	@return - true = Ok; false = Error
	 */
	public function Parse(XML &$xml, int $mod): bool {

		// get decoded request body
		$http = HTTP::getInstance();
		$req  = $http->getHTTPVar(HTTP::RCV_BODY);

		$xml->getVar('RopsList');

		if ($mod == mapiHTTP::REQ || $mod == mapiHTTP::RESP) {

			// get length of Rop buffers
			$end = $xml->getVar('RopSize') + self::$_pos - 2;

			$xml->getVar('RopsList');
			$rids = array_flip(ropDefs::ROP_ID);

			// process <RopRec>
			while (self::$_pos < $end) {

				$rop = parent::_getInt(1);
				self::$_pos--;

				// undefined Rop -> RopRelease() response -> skip
				if (!$rop)
					break;

				if (!isset($rids[$rop])) {

					Log::getInstance()->logMsg(Log::ERR, 17001, $rop);
					break;
				}

				$rop = $rids[$rop];

				Msg::InfoMsg('--- ROP: ['.$rop.'] at 0x'.sprintf('%X', self::$_pos).' '.self::$_pos);

				// load skeleton
				if (!($sub  = parent::_loadSkel('../../rops-bundle/assets/'.$rop, 'RopRec', $mod, false)))
					return false;

				// swap <RopRec>
				$sub->getVar('RopRec');
				$xml->append($sub, false);

				// get last added <RopRec>
				$p = $xml->savePos();
				$xml->xpath('//RopRec');
				while($xml->getItem() !== null)
					;

				// process <RopRec>
				parent::Decode($xml, null);

				$class = 'syncgw\\rops\\rop'.$rop;
				$class = $class::getInstance();
				if (!$class->Parse($req, $xml, $mod))
					return false;

				$xml->restorePos($p);
			}

			// build server object handle table
			$n = $xml->getVar('ActualSize') - $xml->getVar('RopSize');
			$xml->getVar('Object');
			// compute number of handles
			$xml->dupVar(($n / 4 ) - 1);

		} else {

			// mapiHTTP::MKRESP

			// get all <RopRec> to process
			$rops = [];
			$req->xpath('//RopId');
			while ($rop = $req->getItem())
				$rops[] = $rop;

			// create skeleton
			foreach ($rops as $rop) {

				// load skeleton
				if (!($sub  = parent::_loadSkel('../../rops-bundle/assets/'.$rop, 'RopRec', $mod, false)))
					return false;

				$sub->getVar('RopRec');
				$xml->append($sub, false, false);
			}

			// fill data
			foreach ($rops as $rop) {

				$class = 'syncgw\\rops\\rop'.$rop;
				$class = $class::getInstance();
				// get <ServerObjectHandleTable><Object>
				if (!$class->Parse($req, $xml, $mod))
					return false;
			}

			$rcnt = $req->xpath('//Object');

			// check <ServerObjectHandleTable><Object>
			$xml->getVar('Object');
			$xml->dupVar($rcnt - 1);

			$xml->xpath('//Object');
			$n = 0;
			while ($xml->getItem() !== null) {

				if ($n == 1 && in_array('FastTransferSourceGetBuffer', $rops))
					$n++;
				$xml->setVal(strval($n++));
			}
		}

		return true;
	}

}
