<?php

declare(strict_types=1);

namespace Cissee\Webtrees\Module\Gov4Webtrees;

use Cissee\WebtreesExt\Requests;
use DateTime;
use Fisharebest\Webtrees\FlashMessages;
use Fisharebest\Webtrees\Http\Controllers\AbstractEditController;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Tree;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Vesta\Model\PlaceStructure;
use function response;
use function view;
use Fig\Http\Message\StatusCodeInterface;

//cf EditRepositoryController
class EditGovMappingController extends AbstractEditController {

  protected $module;

  public function __construct($module) {
    $this->module = $module;
  }

  /**
   * Show a form to create or update a gov mapping.
   *
   * @return Response
   */
  public function editGovMapping(ServerRequestInterface $request, Tree $tree): ResponseInterface {
    $placeName = Requests::getString($request, 'place-name');

    $ps = PlaceStructure::create("2 PLAC " . $placeName, $tree);
    
    //do not use plac2gov here - we're only interested in actual direct mappings at this point!
    $govId = FunctionsPrintGov::getGovId($ps);
    
    //'cleanup' use case (multiple GOV ids mapped): handled silently now
    //should we address this explicitly? E.g. show warning icon next to edit control?
    if ($govId === null) {
      $title = I18N::translate('Set GOV id for %1$s', $placeName);
    } else {
      $title = I18N::translate('Reset GOV id for %1$s', $placeName);
    }
    
    return response(view($this->module->name() . '::modals/edit-gov-mapping', [
                'moduleName' => $this->module->name(),
                'placeName' => $placeName,
                'title' => $title,
                'govId' => $govId,
    ]));
  }

  /**
   * Process a form to create or update a gov mapping.
   *
   * @param Request $request
   * @param Tree    $tree
   *
   * @return JsonResponse
   */
  public function editGovMappingAction(ServerRequestInterface $request): ResponseInterface {
    $placeName = Requests::getString($request, 'place-name');
    $govId = Requests::getString($request, 'gov-id');

    if ($govId === '') {
      FunctionsGov::deleteGovId($placeName);
      FlashMessages::addMessage(I18N::translate('GOV id for %1$s has been removed.', $placeName));

      //no need to return data, we'll just close the modal from which this has been called
      return response();
    }
    
    //test whether id is valid
    $gov = FunctionsGov::loadGovObject($this->module, $govId);
    if ($gov == null) {
      $error = I18N::translate('Invalid Id! Valid GOV ids are e.g. \'EITTZE_W3091\', \'object_1086218\'.');
      
      //try again
      return response(['html' => $error], StatusCodeInterface::STATUS_CONFLICT);
    }
    
    //reset in order to reload hierarchy
    FunctionsGov::deleteGovObject($govId);
    
    FunctionsGov::setGovId($placeName, $govId);
    
    FlashMessages::addMessage(I18N::translate('GOV id for %1$s has been set to %2$s.', $placeName, $govId));
    
    //no need to return data, we'll just close the modal from which this has been called
    return response();
  }

}
