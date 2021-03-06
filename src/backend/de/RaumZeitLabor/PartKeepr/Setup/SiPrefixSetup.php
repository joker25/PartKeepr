<?php
namespace de\RaumZeitLabor\PartKeepr\Setup;

use	de\RaumZeitLabor\PartKeepr\PartKeepr,
	de\RaumZeitLabor\PartKeepr\SiPrefix\SiPrefix,
	de\RaumZeitLabor\PartKeepr\SiPrefix\SiPrefixManager;

class SiPrefixSetup extends AbstractSetup {
	
	const SIPREFIX_DATA_FILE = "../setup-data/siprefixes.yaml";
	
	/**
	 * Stores the migrated si prefixes
	 * @var array
	 */
	private static $siPrefixes = array();
	
	public function run () {
		$this->setupSiPrefixes();
	}
	
	/**
	 * Sets up the SI prefixes
	 */
	public function setupSiPrefixes () {
		$count = 0;
		$skipped = 0;
		
		$data = Setup::loadYAML(self::SIPREFIX_DATA_FILE);
		
		foreach ($data as $prefixName => $data) {
			if (!SiPrefixManager::getInstance()->siPrefixExists($prefixName)) {
				$prefix = new SiPrefix();
				$prefix->setPrefix($prefixName);
				$prefix->setPower($data["power"]);
				$prefix->setSymbol($data["symbol"]);
				$this->entityManager->persist($prefix);
				$count++;
			} else {
				$skipped++;
			}
		}
		
		$this->entityManager->flush();
		$this->logMessage(sprintf("Imported %d Si Prefixes, skipped %d", $count, $skipped));
	}
}
