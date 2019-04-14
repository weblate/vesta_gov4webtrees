<?php

namespace Cissee\Webtrees\Module\Gov4Webtrees; //cannot use original AbstractModule because we override setPreference, setName


use Cissee\Webtrees\Hook\HookInterfaces\EmptyIndividualFactsTabExtender;
use Cissee\Webtrees\Hook\HookInterfaces\IndividualFactsTabExtenderInterface;
use Cissee\Webtrees\Module\Gov4Webtrees\FunctionsGov;
use Cissee\Webtrees\Module\Gov4Webtrees\FunctionsPrintGov;
use Cissee\WebtreesExt\AbstractModule;
use Cissee\WebtreesExt\FormatPlaceAdditions;
use Fisharebest\Webtrees\Carbon;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Module\ModuleConfigInterface;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cissee\WebtreesExt\Requests;
use Vesta\Hook\HookInterfaces\EmptyFunctionsPlace;
use Vesta\Hook\HookInterfaces\FunctionsPlaceInterface;
use Vesta\Model\GedcomDateInterval;
use Vesta\Model\GenericViewElement;
use Vesta\Model\PlaceStructure;
use Vesta\VestaModuleTrait;

class Gov4WebtreesModule extends AbstractModule implements ModuleCustomInterface, ModuleConfigInterface, IndividualFactsTabExtenderInterface, FunctionsPlaceInterface {

  use VestaModuleTrait;
  use Gov4WebtreesModuleTrait;
  use EmptyIndividualFactsTabExtender;
  use EmptyFunctionsPlace;

  protected function onBoot(): void {
    //cannot do this in constructor: module name not set yet!    
    //migrate (we need the module name here to store the setting)
    $this->updateSchema('\Cissee\Webtrees\Module\Gov4Webtrees\Schema', 'SCHEMA_VERSION', 1);
  }

  public function assetsViaViews(): array {
    return ['css/style.css' => 'css/style'];
  }
  
  public function customModuleAuthorName(): string {
    return 'Richard Cissée';
  }

  public function customModuleVersion(): string {
    return '2.0.0-alpha.5.1';
  }

  public function customModuleLatestVersionUrl(): string {
    return 'https://cissee.de';
  }

  public function customModuleSupportUrl(): string {
    return 'https://cissee.de';
  }

  public function description(): string {
    return $this->getShortDescription();
  }

  /**
   * Where does this module store its resources
   *
   * @return string
   */
  public function resourcesFolder(): string {
    return __DIR__ . '/resources/';
  }

  public function getHelpAction(ServerRequestInterface $request): ResponseInterface {
    $topic = Requests::getString($request, 'topic');
    return response(HelpTexts::helpText($topic));
  }
  
  //HookInterface: FunctionsPlaceInterface
  public function hPlacesGetLatLon(PlaceStructure $place) {
    $govId = FunctionsPrintGov::getGovId($place);
    if ($govId === null) {
      return null;
    }

    $gov = FunctionsGov::retrieveGovObject($this, $govId);
    if ($gov === null) {
      return null;
    }
    return array($gov->getLat(), $gov->getLon());
  }

  public function hPlacesGetParentPlaces(PlaceStructure $place, $typesOfLocation, $recursively = false) {
    $useMedianDate = boolval($this->getSetting('USE_MEDIAN_DATE', '0'));
    $allowSettlements = boolval($this->getSetting('ALLOW_SETTLEMENTS', '1'));

    //$date = $place->getEventDateInterval();

    if ($useMedianDate) {
      $julianDay = $place->getEventDateInterval()->getMedian();
    } else {
      $julianDay = $place->getEventDateInterval()->getMin();
    }

    if ($julianDay === null) {
      return array();
    }

    $govId = FunctionsPrintGov::getGovId($place);
    if ($govId === null) {
      return array();
    }

    $placeStructures = array();

    $gov = FunctionsGov::retrieveGovObject($this, $govId);

    //load hierarchy (one per type of location)
    foreach ($typesOfLocation as $typeOfLocation) {
      $types = array();
      $typesFallback = array();

      if ("POLI" === $typeOfLocation) {
        $types = FunctionsGov::$TYPES_ADMINISTRATIVE;
        if ($allowSettlements) {
          $typesFallback = FunctionsGov::$TYPES_SETTLEMENT;
        }
      } else if ("RELI" === $typeOfLocation) {
        $types = FunctionsGov::$TYPES_RELIGIOUS;
      }
      //"GEOG", "CULT" not supported yet!

      $currentGov = $gov;
      $currentGovId = $govId;
      //TODO: use from/to rather than single julianDay?

      while ($currentGov !== null) {
        //next hierarchy level (if any)
        $nextGovId = null;
        if (sizeOf($types) > 0) {
          $nextGovId = FunctionsGov::findGovParentOfType($this, $currentGovId, $currentGov, $julianDay, $types, $gov->getVersion());
        }
        if (($govId === null) && (sizeOf($typesFallback) > 0)) {
          $nextGovId = FunctionsGov::findGovParentOfType($this, $currentGovId, $currentGov, $julianDay, $typesFallback, $gov->getVersion());
        }
        if ($nextGovId === null) {
          break;
        }
        $currentGovId = $nextGovId;
        $currentGov = FunctionsGov::retrieveGovObject($this, $currentGovId);

        if ($currentGov !== null) {
          //if we have a GOV-Id mapping, use that instead
          //(e.g. place name in GOV is "Verden St.Andreas", but we use "Verden (St. Andreas)")
          //TODO: this does not take into account GOV mappings elsewhere (e.g. via _GOV in shared place)!						
          $mappedName = FunctionsGov::getNameMappedToGovId($currentGovId);
          if ($mappedName !== null) {
            $placeStructures[] = new PlaceStructure("2 PLAC " . $mappedName, $place->getTree(), null, GedcomDateInterval::createEmpty());
          } else {
            //which label to use as place name here? we cannot decide = use all of them!
            foreach ($currentGov->getLabels() as $placeName) {
              $placeStructures[] = new PlaceStructure("2 PLAC " . $placeName->getProp(), $place->getTree(), null, GedcomDateInterval::createEmpty());
            }
          }
        }

        if (!$recursively) {
          break;
        }
      }
    }

    return $placeStructures;
  }

  public function hFactsTabGetOutputBeforeTab(Individual $person) {
    //load script once
    //
    //TODO: not great because timestamp is added, preventing caching
    //(timestamp because this is loaded via jquery via ajax)
    //not sure this still applies for 2.x
    $pre = '<script src="' . $this->assetUrl('js/jquery-ui.js') . '"></script>';
    $pre .= '<script src="' . $this->assetUrl('js/widgets.js') . '"></script>';
    
    //note: content actually served via style.phtml!
    $html = '<link href="' . $this->assetUrl('css/style.css') . '" type="text/css" rel="stylesheet" />';

    return new GenericViewElement($html, $pre);
  }

  public function hFactsTabGetOutputAfterTab(Individual $person) {
    //refresh (= initially show) all widgets (these are created via FunctionsPrintGov)!
    //(further optimization: could restrict to visible facts here, and refresh others on toggle of 'Events of close relatives')
    $post = '<script>' .
            '$(".govWidget").each(function() {' .
            '	$(this).gov("instance")._refresh();' .
            '});' .
            '</script>';

    return new GenericViewElement('', $post);
  }

  public function hFactsTabGetFormatPlaceAdditions(PlaceStructure $place) {
    $fpg = new FunctionsPrintGov($this);
    $gve = $fpg->getGovForFactPlace($place);
    $html = $gve->getMain();
    $script = $gve->getScript();
    $ll = $this->hPlacesGetLatLon($place);
    $tooltip = null;
    if ($ll) {
      $tooltip = 'via GOV';
    }

    return new FormatPlaceAdditions('', $ll, $tooltip, $html, '', $script);
  }

  public function getExpandAction(ServerRequestInterface $request): ResponseInterface {
    $switchToSlowAjax = Requests::getBool($request, 'switchToSlowAjax');
    if ($switchToSlowAjax) {
      //auto-adjust
      $this->setPreference('FAST_AJAX', '0');
    }
    
    //we can cache here (response is immutable for given version)!
    //$response->headers->set('Cache-Control', 'public,max-age=31536000,immutable');
    $expiry_date = Carbon::now()->addYears(10)->toDateTimeString();
    
    $response = response(AjaxRequests::expand($this, $request), 200, [
      'Expires' => $expiry_date,
    ]);
    
    return $response;
  }

  public function setPreference(string $setting_name, string $setting_value): void {
    if ('RESET' === $setting_name) {
      if ('1' === $setting_value) {
        FunctionsGov::clear();
      }
    }

    parent::setPreference($setting_name, $setting_value);
  }

  //used in FunctionsGov - refactor?
  public function getSetting($setting_name, $default = '') {
    return $this->getPreference($setting_name, $default);
  }

}
