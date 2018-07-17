<?php
class SpeedyEpsLib {
	private $error;
	private $speedy_options;

	protected $ePSFacade;
	protected $resultLogin;

	private static $instance;

	public static function getInstance() {
		if ( null == self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->initConnection();
	}

	protected function initConnection() {
		require_once(dirname(__FILE__) . '/speedy-eps-lib/util/Util.class.php');
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/EPSFacade.class.php');
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/soap/EPSSOAPInterfaceImpl.class.php');
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ResultSite.class.php');
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/AddrNomen.class.php');

		$this->speedy_options = get_option('woocommerce_speedy_shipping_method_settings');

		try {
			if (isset($_POST['speedy_server_address'])) {
				$server_address = $_POST['speedy_server_address'];
			} elseif (!empty($this->speedy_options['server_address'])) {
				$server_address = $this->speedy_options['server_address'];
			} else {
				$server_address = 'https://www.speedy.bg/eps/main01.wsdl';
			}

			if (isset($_POST['speedy_username'])) {
				$username = $_POST['speedy_username'];
			} elseif (!empty($this->speedy_options['username'])) {
				$username = $this->speedy_options['username'];
			} else {
				$username = '';
			}

			if (isset($_POST['speedy_password'])) {
				$password = $_POST['speedy_password'];
			} elseif (!empty($this->speedy_options['password'])) {
				$password = $this->speedy_options['password'];
			} else {
				$password = '';
			}

			$ePSSOAPInterfaceImpl = new EPSSOAPInterfaceImpl($server_address);
			if ($password || $username) {
				$this->ePSFacade = new EPSFacade($ePSSOAPInterfaceImpl, $username, $password);
				$this->resultLogin  = $this->ePSFacade->login();
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}

	public function getServices($lang = 'bg') {
		$this->error = '';
		$services = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if ($this->resultLogin) {
			try {
				$listServices = $this->ePSFacade->listServices(time(), strtoupper($lang));

				if ($listServices) {
					foreach ($listServices as $service) {
						if ($service->getTypeId() == 26 || $service->getTypeId() == 36) {
							continue;
						}

						// Remove pallet services
						if ($service->getCargoType() == 2) {
							continue;
						}

						$services[$service->getTypeId()] = $service->getName();
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $services;
	}

	public function getOffices($name = null, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$offices = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listOffices = $this->ePSFacade->listOfficesEx($name, $city_id, strtoupper($lang));

				if ($listOffices) {
					foreach ($listOffices as $office) {
						$offices[] = array(
							'id'    => $office->getId(),
							'label' => $office->getId() . ' ' . $office->getName() . ', ' . $office->getAddress()->getFullAddressString(),
							'value' => $office->getName()
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $offices;
	}

	public function getOfficeById($officeId, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$result = '';
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listOffices = $this->ePSFacade->listOfficesEx(null, $city_id, strtoupper($lang));
				if ($listOffices) {
					foreach ($listOffices as $office) {
						if($office->getId() == $officeId) {
							$result = $office;
							break;
						}
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				$this->log->write('Speedy :: getOfficeById :: ' . $e->getMessage());
			}
		}

		return $result;
	}

	public function getCities($name = null, $postcode = null, $country_id = null, $lang = 'bg') {
		$this->error = '';
		$cities = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ParamFilterSite.class.php');

				$paramFilterSite = new ParamFilterSite();

				if ($postcode) {
					$paramFilterSite->setName($name);
					$paramFilterSite->setPostCode($postcode);
				} else {
					$paramFilterSite->setSearchString($name);
				}

				if ($country_id) {
					$paramFilterSite->setCountryId($country_id);
				}

				$listSitesEx = $this->ePSFacade->listSitesEx($paramFilterSite, strtoupper($lang));
				$listSites = array();

				foreach ($listSitesEx as $result) {
					if ($result->isExactMatch()) {
						$listSites[] = $result->getSite();
					}
				}

				if ($listSites) {
					$texts['bg'] = array(
						'mun' => 'общ.',
						'area' => 'обл.',
					);
					$texts['en'] = array(
						'mun' => 'Mun.',
						'area' => 'Area',
					);

					foreach ($listSites as $city) {
						$label = $city->getType() . ' ' . $city->getName();
						$label .= $city->getPostCode() ? ' (' . $city->getPostCode() . ')' : '';
						$label .= ($city->getMunicipality() && $city->getMunicipality() != '-') ? ', ' . $texts[$lang]['mun'] . ' ' . $city->getMunicipality() : '';
						$label .= ($city->getRegion() && $city->getRegion() != '-') ? ', ' . $texts[$lang]['area'] . ' ' . $city->getRegion() : '';

						$cities[] = array(
							'id' => $city->getId(),
							'label' => $label,
							'value' => $label,
							'postcode' => $city->getPostCode(),
							'nomenclature' => $city->getAddrNomen()->getValue()
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				error_log('Speedy :: getCities :: ' . $e->getMessage());
			}
		}

		return $cities;
	}

	public function getQuarters($name = null, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$quarters = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listQuarters = $this->ePSFacade->listQuarters($name, $city_id, strtoupper($lang));

				if ($listQuarters) {
					foreach ($listQuarters as $quarter) {
						$quarters[] = array(
							'id'    => $quarter->getId(),
							'label' => ($quarter->getType() ? $quarter->getType() . ' ' : '') . $quarter->getName(),
							'value' => ($quarter->getType() ? $quarter->getType() . ' ' : '') . $quarter->getName()
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $quarters;
	}

	public function getStreets($name = null, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$streets = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listStreets = $this->ePSFacade->listStreets($name, $city_id, strtoupper($lang));

				if ($listStreets) {
					foreach ($listStreets as $street) {
						$streets[] = array(
							'id'    => $street->getId(),
							'label' => ($street->getType() ? $street->getType() . ' ' : '') . $street->getName(),
							'value' => ($street->getType() ? $street->getType() . ' ' : '') . $street->getName()
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $streets;
	}

	public function getBlocks($name = null, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$blocks = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listBlocks = $this->ePSFacade->listBlocks($name, $city_id, strtoupper($lang));

				if ($listBlocks) {
					foreach ($listBlocks as $block) {
						$blocks[] = array(
							'label' => $block,
							'value' => $block
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $blocks;
	}

	public function getObject($name = null, $city_id = null, $lang = 'bg') {
		$this->error = '';
		$objects = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listObjects = $this->ePSFacade->listCommonObjects($name, $city_id, strtoupper($lang));

				if ($listObjects) {
					foreach ($listObjects as $object) {
						$objects[] = array(
							'id'    => $object->getId(),
							'label' => ($object->getType() ? $object->getType() . ': ' : '') . $object->getName() . ($object->getAddress() ? ', ' . $object->getAddress() : ''),
							'value' => $object->getAddress()
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $objects;
	}

	public function getCountries($filter = null, $lang = 'bg') {
		$this->error = '';
		$countries = array();
		$nomenclature = array(
			0 => 'NO',
			1 => 'FULL',
			2 => 'PARTIAL',
		);

		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		$paramFilterCountry = new ParamFilterCountry();

		if ( ! is_array( $filter ) ) {
			$paramFilterCountry->setName( $filter );
		} else {
			if ( isset( $filter['country_id'] ) ) {
				$paramFilterCountry->setCountryId( $filter['country_id'] );
			}
			if ( isset( $filter['name'] ) ) {
				$paramFilterCountry->setName( $filter['name'] );
			}
			if ( isset( $filter['iso_code_2'] ) ) {
				$paramFilterCountry->setIsoAlpha2( $filter['iso_code_2'] );
			}
		}

		if (isset($this->resultLogin)) {
			try {
				$listCountries = $this->ePSFacade->listCountriesEx($paramFilterCountry, strtoupper($lang));

				if ($listCountries) {
					foreach ($listCountries as $country) {
						$countries[] = array(
							'id'                   => $country->getCountryId(),
							'name'                 => $country->getName(),
							'label'                => $country->getName(),
							'iso_code_2'           => $country->getIsoAlpha2(),
							'iso_code_3'           => $country->getIsoAlpha3(),
							'nomenclature'         => $nomenclature[$country->getSiteNomen()],
							'required_state'       => (int)$country->isRequireState(),
							'required_postcode'    => (int)$country->isRequirePostCode(),
							'active_currency_code' => $country->getActiveCurrencyCode(),
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				error_log('Speedy :: getCountries :: ' . $e->getMessage());
			}
		}

		return $countries;
	}

	public function getStates($country_id, $name = null, $lang = 'bg') {
		$this->error = '';
		$states = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$listStates = $this->ePSFacade->listStates($country_id, $name, strtoupper($lang));

				if ($listStates) {
					foreach ($listStates as $state) {
						$states[] = array(
							'id'               => $state->getStateId(),
							'name'             => $state->getName(),
							'label'            => $state->getName(),
							'code'             => $state->getStateAlpha(),
							'country_id'       => $state->getCountryId(),
						);
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				error_log('Speedy :: getStates :: ' . $e->getMessage());
			}
		}

		return $states;
	}

	public function getListContractClients() {
		$return = array();

		if (isset($this->resultLogin)) {
			$clients = $this->ePSFacade->listContractClients();

			foreach ($clients as $client) {
				$address = $client->getAddress();
				$address_string = $address->getSiteType()
					. $address->getSiteName() . ', '
					. $address->getRegionName() . ', '
					. $address->getStreetType()
					. $address->getStreetName() . ' '
					. $address->getPostCode();

				$name = array();

				if (!empty($client->getPartnerName())) {
					$name[] = $client->getPartnerName();
				}

				if (!empty($client->getObjectName())) {
					$name[] = $client->getObjectName();
				}

				$return[$client->getClientId()] = array(
					'clientId'   => $client->getClientId(),
					'name'       => implode(', ', $name),
					'address'    => $address_string
				);
			}
		}

		return $return;
	}

	public function calculate($data) {
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ParamCalculation.class.php');

		$this->error = '';
		$resultCalculation = array();

		if (isset($this->resultLogin)) {
			try {
				$speedy_shipping_method = new WC_Speedy_Shipping_Method();

				$paramCalculation = new ParamCalculation();
				$paramCalculation->setSenderId(doubleval($data['client_id']));
				$paramCalculation->setBroughtToOffice($speedy_shipping_method->from_office && $speedy_shipping_method->office_id);
				$paramCalculation->setToBeCalled($data['to_office'] && $data['office_id']);
				$paramCalculation->setParcelsCount($data['count']);
				$paramCalculation->setWeightDeclared($data['weight']);
				$paramCalculation->setDocuments($speedy_shipping_method->documents);
				$paramCalculation->setPalletized(false);

				if (!empty($data['parcels_size'])) {
					$parcel_sizes = array();
					$parcel_weight = 0;

					foreach ($data['parcels_size'] as $seqNo => $parcels_size) {
						$paramParcelInfo = new ParamParcelInfo();
						$paramParcelInfo->setSeqNo($seqNo);
						$paramParcelInfo->setParcelId(-1);

						if (!empty($parcels_size['depth']) || !empty($parcels_size['height']) || !empty($parcels_size['width'])) {
							$size = new Size();

							if ($parcels_size['depth']) {
								$size->setDepth($parcels_size['depth']);
							}

							if ($parcels_size['height']) {
								$size->setHeight($parcels_size['height']);
							}

							if ($parcels_size['width']) {
								$size->setWidth($parcels_size['width']);
							}

							$paramParcelInfo->setSize($size);
						} elseif(!empty($data['parcel_size'])) {
							$paramParcelInfo->setPredefinedSize($data['parcel_size']);
						}

						if (!empty($parcels_size['weight'])) {
							$paramParcelInfo->setWeight($parcels_size['weight']);

							$parcel_weight += $parcels_size['weight'];
						}

						$parcel_sizes[] = $paramParcelInfo;
					}

					if (count($parcel_sizes) == 1) {
						$parcel_sizes_get = $parcel_sizes[0];
						$parcel_sizes_get = $parcel_sizes_get->getWeight();

						if (empty($parcel_sizes_get)) {
							$parcel_sizes_set = $parcel_sizes[0];
							$parcel_sizes_set->setWeight($data['weight']);
							$parcel_sizes[0] = $parcel_sizes_set;
						}
					}

					if ($parcel_weight) {
						$paramCalculation->setWeightDeclared($parcel_weight);
					}

					$paramCalculation->setParcels($parcel_sizes);
				}

				if (!empty($data['fixed_time'])) {
					$paramCalculation->setFixedTimeDelivery($data['fixed_time']);
				} else {
					$paramCalculation->setFixedTimeDelivery(null);
				}

				if ( $this->speedy_options['pricing'] == 'free' || $this->speedy_options['pricing'] == 'fixed' || $this->speedy_options['pricing'] == 'table_rate' ) {
					$payer_type = ParamCalculation::PAYER_TYPE_SENDER;
				} elseif (isset($data['payer_type'])) {
					$payer_type = $data['payer_type'];
				} elseif ( $this->speedy_options['pricing'] == 'calculator' || $this->speedy_options['pricing'] == 'calculator_fixed' ) {
					if ( isset( $data['abroad'] ) && $data['abroad'] ) {
						$payer_type = ParamCalculation::PAYER_TYPE_SENDER;
					} else {
						$payer_type = ParamCalculation::PAYER_TYPE_RECEIVER;
					}
				} else {
					$payer_type = ParamCalculation::PAYER_TYPE_RECEIVER;
				}

				if (isset($data['loading'])) {
					if ($data['insurance']) {
						if ($data['fragile']) {
							$paramCalculation->setFragile(true);
						} else {
							$paramCalculation->setFragile(false);
						}

						$paramCalculation->setAmountInsuranceBase($data['totalNoShipping']);
						$paramCalculation->setPayerTypeInsurance($payer_type);
					} else {
						$paramCalculation->setFragile(false);
					}
				} elseif ($speedy_shipping_method->insurance) {
					if ($speedy_shipping_method->fragile) {
						$paramCalculation->setFragile(true);
					} else {
						$paramCalculation->setFragile(false);
					}

					$paramCalculation->setAmountInsuranceBase($data['totalNoShipping']);
					$paramCalculation->setPayerTypeInsurance($payer_type);
				} else {
					$paramCalculation->setFragile(false);
				}

				if (!($data['to_office'] && $data['office_id'])) {
					$paramCalculation->setReceiverSiteId($data['city_id']);
				}

				$paramCalculation->setPayerType($payer_type);

				if ( $data['abroad'] && $data['active_currency_code'] ) {
					$convert_currency = false;
					$convert_currency_rate = 1;
					foreach ( $speedy_shipping_method->currency_rate as $currency ) {
						if ( $currency['iso_code'] == $data['active_currency_code'] ) {
							$convert_currency_rate = $currency['rate'];
							$convert_currency = true;
							break;
						}
					}

					if ( $convert_currency ) {
						$data['total'] = $speedy_shipping_method->convertSpeedyPrice( $data['total'], $speedy_shipping_method->currency, $data['active_currency_code'] );
					}
				}

				if ( isset( $data['cod'] ) && $data['cod'] && ( ! $speedy_shipping_method->money_transfer || ( $speedy_shipping_method->money_transfer && $data['abroad'] ) ) ) {
					$paramCalculation->setAmountCodBase($data['total']);
				} else {
					$paramCalculation->setAmountCodBase(0);
				}

				$paramCalculation->setTakingDate($data['taking_date']);
				$paramCalculation->setAutoAdjustTakingDate(true);

				if ($speedy_shipping_method->from_office && $speedy_shipping_method->office_id) {
					$paramCalculation->setWillBringToOfficeId($speedy_shipping_method->office_id);
				}

				if ($data['to_office'] && $data['office_id']) {
					$paramCalculation->setOfficeToBeCalledId($data['office_id']);
				} else { 
					$paramCalculation->setOfficeToBeCalledId(null);
				}

				if (isset($data['country_id']) && $data['country_id'] && !($data['to_office'] && $data['office_id'])) {
					$paramCalculation->setReceiverCountryId($data['country_id']);
					$paramCalculation->setReceiverPostCode($data['postcode']);
				}

				if ( isset( $data['abroad'] ) && $data['abroad'] && $data['cod'] && ( $speedy_shipping_method->pricing == 'calculator' || $speedy_shipping_method->pricing == 'calculator_fixed' ) ) {
					$paramCalculation->setIncludeShippingPriceInCod(true);
				}

				$resultCalculation = $this->ePSFacade->calculateMultipleServices($paramCalculation, $speedy_shipping_method->allowed_methods);

				foreach ($resultCalculation as $key => $service) {
					if ($service->getErrorDescription()){
						unset($resultCalculation[$key]);
					}
				}

				$resultCalculation = array_values($resultCalculation);
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $resultCalculation;
	}

	public function getAllowedDaysForTaking($data) {
		$this->error = '';
		$firstAvailableDate = '';

		if (isset($this->resultLogin)) {
			try {
				if ($this->speedy_options['from_office'] && $this->speedy_options['office_id']) {
					$senderSiteId = null;
					$senderOfficeId = $this->speedy_options['office_id'];
				} else {
					$resultClientData = $this->ePSFacade->getClientById($this->resultLogin->getClientId());
					$senderSiteId = $resultClientData->getAddress()->getSiteId();
					$senderOfficeId = null;
				}

				$takingTime = $this->ePSFacade->getAllowedDaysForTaking($data['shipping_method_id'], $senderSiteId, $senderOfficeId, $data['taking_date']);

				if ($takingTime) {
					$firstAvailableDate = $takingTime[0];
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $firstAvailableDate;
	}

	public function createBillOfLading($data, $order) {
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ParamCalculation.class.php');

		$this->error = '';
		$bol = array();

		if (isset($this->resultLogin)) {
			try {
				$speedy_shipping_method = new WC_Speedy_Shipping_Method();

				$sender = new ParamClientData();
				$sender->setClientId(doubleval($data['client_id']));

				if ($this->speedy_options['telephone']) {
					$senderPhone = new ParamPhoneNumber();
					$senderPhone->setNumber($this->speedy_options['telephone']);
					$sender->setPhones(array(0 => $senderPhone));
				}

				if ($this->speedy_options['name']) {
					$sender->setContactName($this->speedy_options['name']);
				}

				$receiverAddress = new ParamAddress();
				if ($data['city_id']) {
					$receiverAddress->setSiteId($data['city_id']);
				} else {
					$receiverAddress->setSiteName($data['city']);
				}

				if ($data['quarter']) {
					$receiverAddress->setQuarterName($data['quarter']);
				}

				if ($data['quarter_id']) {
					$receiverAddress->setQuarterId($data['quarter_id']);
				}

				if ($data['street']) {
					$receiverAddress->setStreetName($data['street']);
				}

				if ($data['street_id']) {
					$receiverAddress->setStreetId($data['street_id']);
				}

				if ($data['street_no']) {
					$receiverAddress->setStreetNo($data['street_no']);
				}

				if ($data['block_no']) {
					$receiverAddress->setBlockNo($data['block_no']);
				}

				// TODO Трябва ли ни object id
				// if ($data['object_id']) {
					// $receiverAddress->setCommonObjectId($data['object_id']);
				// }

				if ($data['entrance_no']) {
					$receiverAddress->setEntranceNo($data['entrance_no']);
				}

				if ($data['floor_no']) {
					$receiverAddress->setFloorNo($data['floor_no']);
				}

				if ($data['apartment_no']) {
					$receiverAddress->setApartmentNo($data['apartment_no']);
				}

				if ($data['note']) {
					$receiverAddress->setAddressNote($data['note']);
				}

				if ($data['state_id']) {
					$receiverAddress->setStateId($data['state_id']);
				}

				if (isset($data['country_id'])) {
					$receiverAddress->setPostCode($data['postcode']);
					$receiverAddress->setFrnAddressLine1($data['address_1']);
					$receiverAddress->setFrnAddressLine2($data['address_2']);
					$receiverAddress->setCountryId($data['country_id']);
					$receiverAddress->setStateId($data['state_id']);
				}

				$receiver = new ParamClientData();
				$receiverPhone = new ParamPhoneNumber();
				$receiverPhone->setNumber($order['telephone']);
				$receiver->setPhones(array(0 => $receiverPhone));
				$receiver->setEmail($order['email']);

				if (!empty($order['company'])) {
					$receiver->setContactName($order['firstname'] . ' ' . $order['lastname']);
					$receiver->setPartnerName($order['company']);
				} else {
					$receiver->setPartnerName($order['firstname'] . ' ' . $order['lastname']);
				}

				$picking = new ParamPicking();
				$picking->setClientSystemId(1508057258); //WooCommerce System ID
				$picking->setRef1($order['order_id']);
				$picking->setParcelsCount($data['count']);
				$picking->setWeightDeclared($data['weight']);

				if (!empty($data['parcels_size'])) {
					$parcel_sizes = array();
					$parcel_weight = 0;

					foreach ($data['parcels_size'] as $seqNo => $parcels_size) {
						$paramParcelInfo = new ParamParcelInfo();
						$paramParcelInfo->setSeqNo($seqNo);
						$paramParcelInfo->setParcelId(-1);

						if (!empty($parcels_size['depth']) || !empty($parcels_size['height']) || !empty($parcels_size['width'])) {
							$size = new Size();

							if ($parcels_size['depth']) {
								$size->setDepth($parcels_size['depth']);
							}

							if ($parcels_size['height']) {
								$size->setHeight($parcels_size['height']);
							}

							if ($parcels_size['width']) {
								$size->setWidth($parcels_size['width']);
							}

							$paramParcelInfo->setSize($size);
						} elseif(!empty($data['parcel_size'])) {
							$paramParcelInfo->setPredefinedSize($data['parcel_size']);
						}

						if (!empty($parcels_size['weight'])) {
							$paramParcelInfo->setWeight($parcels_size['weight']);

							$parcel_weight += $parcels_size['weight'];
						}

						$parcel_sizes[] = $paramParcelInfo;
					}

					if (count($parcel_sizes) == 1) {
						$parcel_sizes_get = $parcel_sizes[0];
						$parcel_sizes_get = $parcel_sizes_get->getWeight();

						if (empty($parcel_sizes_get)) {
							$parcel_sizes_set = $parcel_sizes[0];
							$parcel_sizes_set->setWeight($data['weight']);
							$parcel_sizes[0] = $parcel_sizes_set;
						}
					}

					if ($parcel_weight) {
						$picking->setWeightDeclared($parcel_weight);
					}

					$picking->setParcels($parcel_sizes);
				}

				if (!empty($data['fixed_time'])) {
					$picking->setFixedTimeDelivery($data['fixed_time']);
				}

				$picking->setServiceTypeId($data['shipping_method_id']);

				if ($data['to_office'] && $data['office_id']) {
					$picking->setOfficeToBeCalledId($data['office_id']);
					$office = $this->getOfficeById($data['office_id']);
				} else {
					$receiver->setAddress($receiverAddress);
					$picking->setOfficeToBeCalledId(null);
					$office = array();
				}

				$service = $this->getServiceById($data['shipping_method_id']);

				if((empty($office) || $office->getOfficeType() != 3) && !empty($service)) {
					if($service->getAllowanceBackDocumentsRequest()->getValue() == 'ALLOWED') {
						$picking->setBackDocumentsRequest($this->speedy_options['back_documents']);
					}

					if($service->getAllowanceBackReceiptRequest()->getValue() == 'ALLOWED') {
						$picking->setBackReceiptRequest($this->speedy_options['back_receipt']);
					}
				}

				if ($this->speedy_options['from_office'] && $this->speedy_options['office_id']) {
					$picking->setWillBringToOffice(true);
					$picking->setWillBringToOfficeId($this->speedy_options['office_id']);
				} else {
					$picking->setWillBringToOffice(false);
				}

				$picking->setContents($data['contents']);
				$picking->setPacking($data['packing']);
				$picking->setPackId($data['packing']);
				$picking->setDocuments($this->speedy_options['documents']);
				$picking->setPalletized(false);

				if ( ($this->speedy_options['pricing'] == 'free' && !empty($speedy_shipping_method->free_shipping_total) && $speedy_shipping_method->free_shipping_total <= $data['total']) || $this->speedy_options['pricing'] == 'fixed' || $this->speedy_options['pricing'] == 'table_rate' ) {
					$payer_type = ParamCalculation::PAYER_TYPE_SENDER;
				} elseif (isset($data['payer_type'])) {
					$payer_type = $data['payer_type'];
				} else {
					$payer_type = ParamCalculation::PAYER_TYPE_RECEIVER;
				}

				if ($data['insurance']) {
					if ($data['fragile']) {
						$picking->setFragile(true);
					} else {
						$picking->setFragile(false);
					}

					$picking->setAmountInsuranceBase($data['totalNoShipping']);

					$picking->setPayerTypeInsurance($payer_type);
				} else {
					$picking->setFragile(false);
				}

				$picking->setSender($sender);
				$picking->setReceiver($receiver);

				$picking->setPayerType($payer_type);

				$picking->setTakingDate($data['taking_date']);

				if ($data['deffered_days']) {
					$picking->setDeferredDeliveryWorkDays($data['deffered_days']);
				}

				if ($data['client_note']) {
					$picking->setNoteClient($data['client_note']);
				}

				if ( $this->speedy_options['pricing'] == 'table_rate' ) {
					$data['total'] += $data['shipping_method_cost'];
				}

				if ( $data['abroad'] && $data['active_currency_code'] ) {
					$convert_currency = false;
					$convert_currency_rate = 1;
					foreach ( $this->speedy_options['currency_rate'] as $currency ) {
						if ( $currency['iso_code'] == $data['active_currency_code'] ) {
							$convert_currency_rate = $currency['rate'];
							$convert_currency = true;
							break;
						}
					}

					if ( $convert_currency ) {
						$data['total'] = $speedy_shipping_method->convertSpeedyPrice( $data['total'], $this->speedy_options['currency'], $data['active_currency_code'] );
					}
				}

				if ($data['cod']) {
					$picking->setAmountCodBase($data['total']);
				} else {
					$picking->setAmountCodBase(0);
				}

				if ( $data['cod'] && ( $this->speedy_options['money_transfer'] && !$data['abroad'] ) ) {
					$picking->setRetMoneyTransferReqAmount($data['total']);
					$picking->setAmountCodBase(0);
				}

				$optionBeforePayment = new ParamOptionsBeforePayment();
				if ( $data['cod'] && !$data['abroad'] && isset($data['option_before_payment']) && $data['option_before_payment'] != 'no_option' && (empty($office) || $office->getOfficeType() != 3)) {
					if ($data['option_before_payment'] == 'open') {
						$optionBeforePayment->setOpen(true);
					} elseif ($data['option_before_payment'] == 'test') {
						$optionBeforePayment->setTest(true);
					}

					$optionBeforePayment->setReturnPayerType($this->speedy_options['return_payer_type']);
					$optionBeforePayment->setReturnServiceTypeId($this->getReturnPackageServiceTypeId($picking));
				}
				$picking->setOptionsBeforePayment($optionBeforePayment);

				if ( isset( $data['abroad'] ) && $data['abroad'] && $data['cod'] && ( $speedy_shipping_method->pricing == 'calculator' || $speedy_shipping_method->pricing == 'calculator_fixed' ) ) {
					$picking->setIncludeShippingPriceInCod(true);
				}

				if ($speedy_shipping_method->return_voucher && (!isset($data['abroad']) || !$data['abroad'])) {
					$returnVoucher = new ParamReturnVoucher();
					$returnVoucher->setServiceTypeId($this->getReturnVoucherServiceTypeId($picking));
					$returnVoucher->setPayerType($speedy_shipping_method->return_voucher_payer_type);

					$picking->setReturnVoucher($returnVoucher);
				}

				$result = $this->ePSFacade->createBillOfLading($picking);
				$parcels = $result->getGeneratedParcels();
				$parcels = $parcels[0];
				$bol['bol_id'] = $parcels->getParcelId();
				$bol['total'] = $result->getAmounts()->getTotal();
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $bol;
	}

	public function createPDF($bol_id) {
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ParamPDF.class.php');

		$this->error = '';
		$pdf = '';

		if (isset($this->resultLogin)) {
			try {
				$paramPDF = new ParamPDF();

				if ($this->speedy_options['label_printer']) {
					$pickingParcels = $this->ePSFacade->getPickingParcels((float)$bol_id);

					$ids = array();

					foreach ($pickingParcels as $parcel) {
						$ids[] = $parcel->getParcelId();
					}

					$paramPDF->setIds(array_map('floatval', $ids));
					$paramPDF->setType(ParamPDF::PARAM_PDF_TYPE_LBL);
				} else {
					$paramPDF->setIds((float)$bol_id);
					$paramPDF->setType(ParamPDF::PARAM_PDF_TYPE_BOL);
				}

				$paramPDF->setIncludeAutoPrintJS(true);

				$pdf = $this->ePSFacade->createPDF($paramPDF);
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $pdf;
	}

	public function createReturnVoucher($bol_id) {
		require_once(dirname(__FILE__) . '/speedy-eps-lib/ver01/ParamPDF.class.php');

		$this->error = '';
		$pdf = '';

		if (isset($this->resultLogin)) {
			try {
				$paramPDF = new ParamPDF();

				if ($this->speedy_options['label_printer']) {
					$pickingParcels = $this->ePSFacade->getPickingParcels((float)$bol_id);

					$ids = array();

					foreach ($pickingParcels as $parcel) {
						$ids[] = $parcel->getParcelId();
					}

					$paramPDF->setIds(array_map('floatval', $ids));
				} else {
					$paramPDF->setIds((float)$bol_id);
				}

				$paramPDF->setType(30); // ParamPDF::PARAM_PDF_TYPE_VOUCHER

				$paramPDF->setIncludeAutoPrintJS(true);

				$pdf = $this->ePSFacade->createPDF($paramPDF);
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $pdf;
	}

	public function requestCourier($bol_ids) {
		$this->error = '';
		$result = array();

		if (isset($this->resultLogin)) {
			try {
				$paramOrder = new ParamOrder();
				$paramOrder->setBillOfLadingsList(array_map('floatval', $bol_ids));
				$paramOrder->setBillOfLadingsToIncludeType(ParamOrder::ORDER_BOL_INCLUDE_TYPE_EXPLICIT);

				if ($this->speedy_options['telephone']) {
					$paramPhoneNumber = new ParamPhoneNumber();
					$paramPhoneNumber->setNumber($this->speedy_options['telephone']);
					$paramOrder->setPhoneNumber($paramPhoneNumber);
				}

				$paramOrder->setWorkingEndTime($this->speedy_options['workingtime_end_hour'] . $this->speedy_options['workingtime_end_min'] );
				$paramOrder->setContactName($this->speedy_options['name']);

				$result = $this->ePSFacade->createOrder($paramOrder);
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $result;
	}

	public function cancelBol($bol_id) {
		$this->error = '';
		$cancelled = false;

		if (isset($this->resultLogin)) {
			try {
				$this->ePSFacade->invalidatePicking((float)$bol_id);
				$cancelled = true;
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $cancelled;
	}

	public function getError($type = null) {
		if ($type) {
			if (isset($this->error[$type])) {
				return $this->error[$type];
			} else {
				return false;
			}
		} else {
			return $this->error;
		}
	}

	public function isAvailableMoneyTransfer() {
		if (isset($this->resultLogin)) {
			try {
				return in_array('101', $this->ePSFacade->getAdditionalUserParams(time()));
			} catch (ClientException $ce) {
				return FALSE;
			} catch (ServerException $se) {
				return FALSE;
			}
		}
	}

	public function checkReturnVoucherRequested($bol_id) {
		$this->error = '';
		$voucherRequested = false;

		if (isset($this->resultLogin)) {
			try {
				$pickingExtendedInfo = $this->ePSFacade->getPickingExtendedInfo((float)$bol_id);

				if (!is_null($pickingExtendedInfo->getReturnVoucher()) && ($pickingExtendedInfo->getReturnVoucher() instanceof ResultReturnVoucher)) {
					$voucherRequested = true;
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		return $voucherRequested;
	}

	public function getServiceById($service_id, $lang = 'bg') {
		$services = array();
		if (strtolower($lang) != 'bg') {
			$lang = 'en';
		}

		if (isset($this->resultLogin)) {
			try {
				$servises = $this->ePSFacade->listServices(time(), strtoupper($lang));

				foreach($servises as $servise) {
					if($servise->getTypeId() == $service_id) {
						return $servise;
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				$this->log->write('Speedy :: getServiceById :: ' . $e->getMessage());
			}
		}
	}

	public function getReturnPackageServiceTypeId($picking) {
		$this->error = '';
		$services = array();
		$returnVoucherServiceTypeId = null;

		$sender = $picking->getSender();
		$receiver = $picking->getReceiver();

		try {
			$speedy_shipping_method = new WC_Speedy_Shipping_Method();
			if ($speedy_shipping_method->from_office && $speedy_shipping_method->office_id) {
				$senderSiteId = null;
				$senderOfficeId = $speedy_shipping_method->office_id;
			} else {
				$senderData = $this->ePSFacade->getClientById($sender->getClientId());
				$senderSiteId = $senderData->getAddress()->getSiteId();
				$senderOfficeId = null;
			}

			if ($receiver->getAddress()) {
				$receiverSiteId = $receiver->getAddress()->getSiteId();
				$receiverOfficeId = null;
			} else {
				$receiverSiteId = null;
				$receiverOfficeId = $picking->getOfficeToBeCalledId();
			}

			// Reverse sender and receiver data
			$listServices = $this->ePSFacade->listServicesForSites(time(), $receiverSiteId, $senderSiteId, null, null, null, null, null, null, null, $receiverOfficeId, $senderOfficeId);

			foreach($listServices as $listService) {
				$services[] = $listService->getTypeId();
			}

			if (in_array($speedy_shipping_method->return_package_city_service_id, $services)) {
				$returnVoucherServiceTypeId = $speedy_shipping_method->return_package_city_service_id;
			} elseif (in_array($speedy_shipping_method->return_package_intercity_service_id, $services)) {
				$returnVoucherServiceTypeId = $speedy_shipping_method->return_package_intercity_service_id;
			}

		} catch (Exception $e) {
			 $this->error = $e->getMessage();
		}

		return $returnVoucherServiceTypeId;
	}

	public function getReturnVoucherServiceTypeId($picking) {
		$this->error = '';
		$services = array();
		$returnVoucherServiceTypeId = null;

		$sender = $picking->getSender();
		$receiver = $picking->getReceiver();

		try {
			$speedy_shipping_method = new WC_Speedy_Shipping_Method();
			if ($speedy_shipping_method->from_office && $speedy_shipping_method->office_id) {
				$senderSiteId = null;
				$senderOfficeId = $speedy_shipping_method->office_id;
			} else {
				$senderData = $this->ePSFacade->getClientById($sender->getClientId());
				$senderSiteId = $senderData->getAddress()->getSiteId();
				$senderOfficeId = null;
			}

			if ($receiver->getAddress()) {
				$receiverSiteId = $receiver->getAddress()->getSiteId();
				$receiverOfficeId = null;
			} else {
				$receiverSiteId = null;
				$receiverOfficeId = $picking->getOfficeToBeCalledId();
			}

			// Reverse sender and receiver data
			$listServices = $this->ePSFacade->listServicesForSites(time(), $receiverSiteId, $senderSiteId, null, null, null, null, null, null, null, $receiverOfficeId, $senderOfficeId);

			foreach($listServices as $listService) {
				$services[] = $listService->getTypeId();
			}

			if (in_array($speedy_shipping_method->return_voucher_city_service_id, $services)) {
				$returnVoucherServiceTypeId = $speedy_shipping_method->return_voucher_city_service_id;
			} elseif (in_array($speedy_shipping_method->return_voucher_intercity_service_id, $services)) {
				$returnVoucherServiceTypeId = $speedy_shipping_method->return_voucher_intercity_service_id;
			}

		} catch (Exception $e) {
			 $this->error = $e->getMessage();
		}

		return $returnVoucherServiceTypeId;
	}

	public function getPayerType($order_id, $shippingCost, $is_bol_recalculated = false ) {
		global $wpdb;
		$this->speedy_options = get_option('woocommerce_speedy_shipping_method_settings');
		$payerType = null;

		$table_name = $wpdb->prefix . 'speedy_order';

		$query = "SELECT data FROM `" . $table_name . "` WHERE order_id = '" . intval( $order_id ) . "'";

		$data = maybe_unserialize( $wpdb->get_var( $query ) );

		if ( $data['price_gen_method'] && ! $is_bol_recalculated ) {
			if ( $data['price_gen_method'] == 'fixed' || $data['price_gen_method'] == 'free' ) {
				if ( $data['price_gen_method'] == 'free' ) {
					$delta = 0.0001;

					if ( abs( $data['shipping_method_cost'] - 0.0000 ) < $delta ) {
						$payerType = ParamCalculation::PAYER_TYPE_SENDER;
					} else {
						$payerType = ParamCalculation::PAYER_TYPE_RECEIVER;
					}
				} else {
					$payerType = ParamCalculation::PAYER_TYPE_SENDER;
				}
			} else {
				$payerType = ParamCalculation::PAYER_TYPE_RECEIVER;
			}
		} elseif ( $data['price_gen_method'] && $is_bol_recalculated ) {
			if ( $this->speedy_options['pricing'] == 'free' || $this->speedy_options['pricing'] == 'fixed' || $this->speedy_options['pricing'] == 'table_rate' ) {
				if ( $this->speedy_options['pricing'] == 'free' ) {
					$delta = 0.0001;

					if ( ( $shippingCost - 0.0000 ) < $delta ) {
						$payerType = ParamCalculation::PAYER_TYPE_SENDER;
					} else {
						$payerType = ParamCalculation::PAYER_TYPE_RECEIVER;
					}
				}else{
					$payerType = ParamCalculation::PAYER_TYPE_SENDER;
				}
			}else{
				$payerType = ParamCalculation::PAYER_TYPE_RECEIVER;
			}
		} elseif ( ! isset( $data['price_gen_method'] ) ) {
			if ( $this->speedy_options['pricing'] == 'free' || $this->speedy_options['pricing'] == 'fixed' || $this->speedy_options['pricing'] == 'table_rate' ) {
				$payerType = ParamCalculation::PAYER_TYPE_SENDER;
			} else {
				$payerType = ParamCalculation::PAYER_TYPE_RECEIVER;
			}
		}

		$allowed_pricings = array(
			'calculator',
			'free',
			'calculator_fixed'
		);

		if ( isset( $this->speedy_options['invoice_courrier_sevice_as_text'] ) && $this->speedy_options['invoice_courrier_sevice_as_text'] && in_array( $this->speedy_options['invoice_courrier_sevice_as_text'], $allowed_pricings ) && ( isset( $data['cod'] ) && !$data['cod'] ) ) {
			$payerType = ParamCalculation::PAYER_TYPE_SENDER;
		}

		// International Shipping
		if ( isset( $data['abroad'] ) && $data['abroad'] ) {
			$payerType = ParamCalculation::PAYER_TYPE_SENDER;
		}

		return $payerType;
	}

	public function validateAddress($address) {
		$paramAddress = new ParamAddress();

		$paramAddress->setSiteId( trim( $address['city_id'] ) );
		if (!isset($address['city_id']) || !$address['city_id']) {
			$paramAddress->setSiteName( trim( $address['city'] ) );
		}
		$paramAddress->setPostCode( trim( $address['postcode'] ) );
		$paramAddress->setFrnAddressLine1( trim( $address['address_1'] ) );
		$paramAddress->setFrnAddressLine2( trim( $address['address_2'] ) );
		$paramAddress->setCountryId( trim( $address['country_id'] ) );
		$paramAddress->setStateId( trim( $address['state_id'] ) );

		if (isset($this->resultLogin)) {
			try {
				$valid = $this->ePSFacade->validateAddress($paramAddress, 0);
			} catch (Exception $e) {
				$valid = $e->getMessage();
			}
		} else {
			$valid = false;
		}

		return $valid;
	}
}
?>