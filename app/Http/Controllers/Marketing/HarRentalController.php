<?php namespace App\Http\Controllers\Marketing;

use Request;
use View;
use DB;
use App\Model\Rentals\PossibleRental;
use App\Model\Rentals\VwPossibleRentalsDowntown;
use App\Model\Rentals\PossibleRentalAgent;
use App\Http\Controllers\ProcessScheduleController;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Redirect;
use Aws;
use Session;
use \Log;
use App\Services\Scraper;
use App\Services\Utility;
use App\Services\ProcessLogger;

class HarRentalController extends Controller {

    public $logger;

    public function __construct()
    {
        $this->utility = new Utility();
    }

    public function index() {
        return View::make('rentals');
    }

    public function load() {
        $post = Request::all();

        $rentals = json_decode($post['rentals']);

        foreach($rentals as $rental) {

            $newPossibleRental = PossibleRental::firstOrNew(['MLS_NUM' => $rental->MLSNUM]);

            if (isset($rental->AGENT_PHOTO_NUM)) {
                $newPossibleRental->AGENT_PHOTO_NUM = $rental->AGENT_PHOTO_NUM;
            }

            if (isset($rental->HASOPENHOUSE)) {
                $newPossibleRental->HASOPENHOUSE = $rental->HASOPENHOUSE;
            }

            if (isset($rental->MLSNUM)) {
                $newPossibleRental->MLSNUM = $rental->MLSNUM;
            }

            if (isset($rental->SHOWADDRESSONLINE)) {
                $newPossibleRental->SHOWADDRESSONLINE = $rental->SHOWADDRESSONLINE;
            }

            if (isset($rental->LISTINGID)) {
                $newPossibleRental->LISTINGID = $rental->LISTINGID;
            }

            if (isset($rental->LISTSTATUS)) {
                $newPossibleRental->LISTSTATUS = $rental->LISTSTATUS;
            }

            if (isset($rental->STREETNUM)) {
                $newPossibleRental->STREETNUM = $rental->STREETNUM;
            }

            if (isset($rental->STREETNAME)) {
                $newPossibleRental->STREETNAME = $rental->STREETNAME;
            }

            if (isset($rental->LISTPRICE)) {
                $newPossibleRental->LISTPRICE = $rental->LISTPRICE;
            }

            if (isset($rental->PROPTYPE)) {
                $newPossibleRental->PROPTYPE = $rental->PROPTYPE;
            }

            if (isset($rental->BEDROOM)) {
                $newPossibleRental->BEDROOM = $rental->BEDROOM;
            }

            if (isset($rental->BATHHALF)) {
                $newPossibleRental->BATHHALF = $rental->BATHHALF;
            }

            if (isset($rental->PHOTOPRIMARY)) {
                $newPossibleRental->PHOTOPRIMARY = $rental->PHOTOPRIMARY;
            }

            if (isset($rental->BATHFULL)) {
                $newPossibleRental->BATHFULL = $rental->BATHFULL;
            }

            if (isset($rental->ACRES)) {
                $newPossibleRental->ACRES = $rental->ACRES;
            }

            if (isset($rental->AGENTLISTNAME)) {
                $newPossibleRental->AGENTLISTNAME = $rental->AGENTLISTNAME;
            }

            if (isset($rental->AGENTLISTID)) {
                $newPossibleRental->AGENTLISTID = $rental->AGENTLISTID;
            }

            if (isset($rental->BLDGSQFT)) {
                $newPossibleRental->BLDGSQFT = $rental->BLDGSQFT;
            }

            if (isset($rental->BLDGSQFTSRC)) {
                $newPossibleRental->BLDGSQFTSRC = $rental->BLDGSQFTSRC;
            }

            if (isset($rental->KEYMAP)) {
                $newPossibleRental->KEYMAP = $rental->KEYMAP;
            }

            if (isset($rental->LOTSIZE)) {
                $newPossibleRental->LOTSIZE = $rental->LOTSIZE;
            }

            if (isset($rental->LOTSIZESRC)) {
                $newPossibleRental->LOTSIZESRC = $rental->LOTSIZESRC;
            }

            if (isset($rental->STORIES)) {
                $newPossibleRental->STORIES = $rental->STORIES;
            }

            if (isset($rental->SUBDIVISION)) {
                $newPossibleRental->SUBDIVISION = $rental->SUBDIVISION;
            }

            if (isset($rental->YEARBUILT)) {
                $newPossibleRental->YEARBUILT = $rental->YEARBUILT;
            }

            if (isset($rental->YEARBUILTSRC)) {
                $newPossibleRental->YEARBUILTSRC = $rental->YEARBUILTSRC;
            }

            if (isset($rental->CITY)) {
                $newPossibleRental->CITY = $rental->CITY;
            }

            if (isset($rental->ZIP)) {
                $newPossibleRental->ZIP = $rental->ZIP;
            }

            if (isset($rental->OFFICELISTNAME)) {
                $newPossibleRental->OFFICELISTNAME = $rental->OFFICELISTNAME;
            }

            if (isset($rental->OFFICELISTID)) {
                $newPossibleRental->OFFICELISTID = $rental->OFFICELISTID;
            }

            if (isset($rental->PROPSUBTYPE)) {
                $newPossibleRental->PROPSUBTYPE = $rental->PROPSUBTYPE;
            }

            if (isset($rental->LONGITUDE)) {
                $newPossibleRental->LONGITUDE = $rental->LONGITUDE;
            }

            if (isset($rental->LATITUDE)) {
                $newPossibleRental->LATITUDE = $rental->LATITUDE;
            }

            if (isset($rental->FULLSTREETADDRESS)) {
                $newPossibleRental->FULLSTREETADDRESS = $rental->FULLSTREETADDRESS;
            }

            if (isset($rental->LISTDATE)) {
                $newPossibleRental->LISTDATE = $rental->LISTDATE;
            }

            if (isset($rental->PROPERTY_CLASS_ID)) {
                $newPossibleRental->PROPERTY_CLASS_ID = $rental->PROPERTY_CLASS_ID;
            }

            if (isset($rental->LASTREDUCED)) {
                $newPossibleRental->LASTREDUCED = $rental->LASTREDUCED;
            }

            if (isset($rental->LISTPRICEORI)) {
                $newPossibleRental->LISTPRICEORI = $rental->LISTPRICEORI;
            }

            if (isset($rental->REGION_ID)) {
                $newPossibleRental->REGION_ID = $rental->REGION_ID;
            }

            if (isset($rental->LOTSIZEUNIT)) {
                $newPossibleRental->LOTSIZEUNIT = $rental->LOTSIZEUNIT;
            }

            if (isset($rental->IMPROVEMENT)) {
                $newPossibleRental->IMPROVEMENT = $rental->IMPROVEMENT;
            }

            if (isset($rental->RESTRICTION)) {
                $newPossibleRental->RESTRICTION = $rental->RESTRICTION;
            }

            if (isset($rental->LANDUSE)) {
                $newPossibleRental->LANDUSE = $rental->LANDUSE;
            }

            if (isset($rental->DOH)) {
                $newPossibleRental->DOH = $rental->DOH;
            }

            if (isset($rental->STATE)) {
                $newPossibleRental->STATE = $rental->STATE;
            }

            if (isset($rental->LISTSTATUS_CLASS)) {
                $newPossibleRental->LISTSTATUS_CLASS = $rental->LISTSTATUS_CLASS;
            }

            if (isset($rental->LISTSTATUS_TEXT)) {
                $newPossibleRental->LISTSTATUS_TEXT = $rental->LISTSTATUS_TEXT;
            }

            if (isset($rental->LISTPRICE_HTML)) {
                $newPossibleRental->LISTPRICE_HTML = $rental->LISTPRICE_HTML;
            }

            if (isset($rental->LOTSIZE_FORMAT)) {
                $newPossibleRental->LOTSIZE_FORMAT = $rental->LOTSIZE_FORMAT;
            }

//            if (isset($rental->SHOW_PRICEREDUCED)) {
//                $newPossibleRental->SHOW_PRICEREDUCED = $rental->SHOW_PRICEREDUCED;
//            }

            if (isset($rental->MLS_AREA)) {
                $newPossibleRental->MLS_AREA = $rental->MLS_AREA;
            }

            if (isset($rental->MLS_NUM)) {
                $newPossibleRental->MLS_NUM = $rental->MLS_NUM;
            }

            if (isset($rental->ADDRESSURL)) {
                $newPossibleRental->ADDRESSURL = $rental->ADDRESSURL;
            }
            $newPossibleRental->save();

            $processScheduleController = new ProcessScheduleController();

            $processScheduleController->createQueueRecordsWithVariableIds('rental_agent', [$newPossibleRental->id]);


        }
    }

    public function processOneRentalAgent($newPossibleRental) {

        $scraper = new Scraper();

        sleep(rand(3, 8));

        $locationDetail = $scraper->getCurl('https://www.har.com/api/detail/'.$newPossibleRental['MLSNUM']);

        if (!$locationDetail)  {
            $this->logger->logMessage('Har respone invalid value');
        }

        try {

            $this->logger->logMessage('Har Response: '.substr($locationDetail, 0, 140));
            $agentInfo = json_decode($locationDetail)->AGENTINFO;

            $newPossibleRentalAgent = PossibleRentalAgent::where(['AGENTEMAIL' => strtoupper($agentInfo->AGENTEMAIL)])->first();

            if (is_null($newPossibleRentalAgent)) {
                $newPossibleRentalAgent = new PossibleRentalAgent();

                if (isset($agentInfo->ANSWERS)) {
                    $newPossibleRentalAgent->ANSWERS = $agentInfo->ANSWERS;
                }

                if (isset($agentInfo->AGENTKEY)) {
                    $newPossibleRentalAgent->AGENTKEY = $agentInfo->AGENTKEY;
                }

                if (isset($agentInfo->AGENTID)) {
                    $newPossibleRentalAgent->AGENTID = $agentInfo->AGENTID;
                }

                if (isset($agentInfo->MEMBER_NUMBER)) {
                    $newPossibleRentalAgent->MEMBER_NUMBER = $agentInfo->MEMBER_NUMBER;
                }

                if (isset($agentInfo->MLSORGID)) {
                    $newPossibleRentalAgent->MLSORGID = $agentInfo->MLSORGID;
                }

                if (isset($agentInfo->AGENTNAME)) {
                    $newPossibleRentalAgent->AGENTNAME = $agentInfo->AGENTNAME;
                }

                if (isset($agentInfo->FULLNAME)) {
                    $newPossibleRentalAgent->FULLNAME = $agentInfo->FULLNAME;
                }

                if (isset($agentInfo->AGENTEMAIL)) {
                    $newPossibleRentalAgent->AGENTEMAIL = strtoupper($agentInfo->AGENTEMAIL);
                }

                if (isset($agentInfo->AGENTPHONE)) {
                    $newPossibleRentalAgent->AGENTPHONE = $agentInfo->AGENTPHONE;
                }

                if (isset($agentInfo->AGENTWEBSITE)) {
                    $newPossibleRentalAgent->AGENTWEBSITE = $agentInfo->AGENTWEBSITE;
                }

                if (isset($agentInfo->TOTAL)) {
                    $newPossibleRentalAgent->TOTAL = $agentInfo->TOTAL;
                }

                if (isset($agentInfo->AGENTPHOTO)) {
                    $newPossibleRentalAgent->AGENTPHOTO = $agentInfo->AGENTPHOTO;
                }

                if (isset($agentInfo->LEADCALLPHONE)) {
                    $newPossibleRentalAgent->LEADCALLPHONE = $agentInfo->LEADCALLPHONE;
                }

                if (isset($agentInfo->ACCEPTPHONEFROM)) {
                    $newPossibleRentalAgent->ACCEPTPHONEFROM = $agentInfo->ACCEPTPHONEFROM;
                }

                if (isset($agentInfo->ACCEPTPHONETO)) {
                    $newPossibleRentalAgent->ACCEPTPHONETO = $agentInfo->ACCEPTPHONETO;
                }

                if (isset($agentInfo->HIDEMYLISTING)) {
                    $newPossibleRentalAgent->HIDEMYLISTING = $agentInfo->HIDEMYLISTING;
                }

                if (isset($agentInfo->BROKERCODE)) {
                    $newPossibleRentalAgent->BROKERCODE = $agentInfo->BROKERCODE;
                }

                if (isset($agentInfo->OFFICE_NUMBER)) {
                    $newPossibleRentalAgent->OFFICE_NUMBER = $agentInfo->OFFICE_NUMBER;
                }

                if (isset($agentInfo->OFFICE_NAME)) {
                    $newPossibleRentalAgent->OFFICE_NAME = $agentInfo->OFFICE_NAME;
                }

                if (isset($agentInfo->OFFICE_EMAIL)) {
                    $newPossibleRentalAgent->OFFICE_EMAIL = $agentInfo->OFFICE_EMAIL;
                }

                if (isset($agentInfo->OFFICE_PHONE)) {
                    $newPossibleRentalAgent->OFFICE_PHONE = $agentInfo->OFFICE_PHONE;
                }

                if (isset($agentInfo->OFFICE_WEBSITE)) {
                    $newPossibleRentalAgent->OFFICE_WEBSITE = $agentInfo->OFFICE_WEBSITE;
                }

                if (isset($agentInfo->OFFICE_PHOTO)) {
                    $newPossibleRentalAgent->OFFICE_PHOTO = $agentInfo->OFFICE_PHOTO;
                }

                if (isset($agentInfo->OFFICE_ADDRESS)) {
                    $newPossibleRentalAgent->OFFICE_ADDRESS = $agentInfo->OFFICE_ADDRESS;
                }

                if (isset($agentInfo->OFFICEADDRESS2)) {
                    $newPossibleRentalAgent->OFFICEADDRESS2 = $agentInfo->OFFICEADDRESS2;
                }

                if (isset($agentInfo->OFFICE_FULLADDRESS)) {
                    $newPossibleRentalAgent->OFFICE_FULLADDRESS = $agentInfo->OFFICE_FULLADDRESS;
                }

                if (isset($agentInfo->OFFICE_NAME_NOQUOTE)) {
                    $newPossibleRentalAgent->OFFICE_NAME_NOQUOTE = $agentInfo->OFFICE_NAME_NOQUOTE;
                }

                if (isset($agentInfo->OFFICE_CITY)) {
                    $newPossibleRentalAgent->OFFICE_CITY = $agentInfo->OFFICE_CITY;
                }

                if (isset($agentInfo->OFFICE_STATE)) {
                    $newPossibleRentalAgent->OFFICE_STATE = $agentInfo->OFFICE_STATE;
                }

                if (isset($agentInfo->OFFICE_ZIP)) {
                    $newPossibleRentalAgent->OFFICE_ZIP = $agentInfo->OFFICE_ZIP;
                }

                if (isset($agentInfo->REGIONID)) {
                    $newPossibleRentalAgent->REGIONID = $agentInfo->REGIONID;
                }

                if (isset($agentInfo->MLS_PREMIUM)) {
                    $newPossibleRentalAgent->MLS_PREMIUM = $agentInfo->MLS_PREMIUM;
                }

                $newPossibleRentalAgent->save();
            }

            $updateRental = PossibleRental::find($newPossibleRental['id']);

            $updateRental->AGENTID = $newPossibleRentalAgent->id;

            $updateRental->save();
        }
        catch (\Exception $e) {
            $this->logger->logMessage('Log Message Error :'.$e->getMessage());
        }

    }

    public function oneRentalAgent($rentalId) {
        $this->logger = new ProcessLogger('rental_agent');

        $newPossibleRental = PossibleRental::find($rentalId)->toArray();

        $this->logger->logMessage('Checking Agent Info for '.$newPossibleRental['id']);

        $this->processOneRentalAgent($newPossibleRental);
    }

    public function autoLoadAgents() {
        $noAgentCount = PossibleRental::where('AGENTID', '=', 0)->count();

        while ($noAgentCount > 0) {
            $this->oneRentalAgent();
            $randomSeconds = rand(20, 144);
            sleep($randomSeconds);
            $noAgentCount = PossibleRental::where('AGENTID', '=', 0)->count();
        }
    }

    public function createAgentTable($agentData) {

        $columns = [];

        foreach ($agentData as $index=>$column) {

            $columnLength = strlen($column) + 20;

            echo $index.' varchar('.$columnLength.'),<BR>';
        }

        echo '<BR><BR><BR><BR><BR><BR><BR>';

        foreach ($agentData as $index=>$column) {

            echo 'if (isset($agentInfo->'.$index.')) {<BR>';
            echo '$newPossibleRentalAgent->'.$index. ' = $agentInfo->'.$index.';<BR>';
            echo '}<BR><BR>';
        }
    }

    public function getRentals() {
        return PossibleRental::where('AGENTID', '!=', 0)->orderBy('id')->get();
    }

    public function getRentalEmail() {

        $agentInfo = PossibleRentalAgent::where('email_sent', '=', 0)->first();

        $body = $this->buildBody($agentInfo);

        $agentInfo->email_sent = 1;

        DB::table('possible_rental_agents')
            ->where('AGENTEMAIL','=', $agentInfo->AGENTEMAIL)
            ->update(array('email_sent' => 1));

        return ['body'=>$body, 'subject'=>'Powerful Templating Tool for Realtors','email'=>$agentInfo->AGENTEMAIL];
    }

    public function buildBody($agentInfo) {
        $scraper = new Scraper();
        $firstName = ucfirst(strtolower($scraper->getTextBeforeString(' ', $agentInfo->FULLNAME)));

        $bodyText = 'Hello '.$firstName.',%0A%0A';

        $bodyText = $bodyText.'I am a software engineer that has created a beta version of a powerful templating application called Messgen (located at messgen.com). It allows you to formulate robust, audience tailored messages in seconds. At this point I only have 20-30 friends using the application, but a couple of them who are finding the tool especially useful are real estate agents, which is why I’m contacting you after getting your information off of har.com.';

        $bodyText = $bodyText.'%0A%0A';

        $bodyText = $bodyText.'As I’m in a beta stage, the application will be completely free to use as my primary goal at this stage is to refine the product through feedback. Although I obviously hope to monetize the application at a later date, I promise I will not charge you anything for at least a year from the time you register as appreciation for trying out the new product.';

        $bodyText = $bodyText.'%0A%0A';

        $bodyText = $bodyText.'Please do not hesitate if you have any questions for how to use the application. I would love your general feedback on the tool as well as any thoughts on how it can help you even more.';

        $bodyText = $bodyText.'%0A%0A';

        $bodyText = $bodyText.'Best Regards,%0A';
        $bodyText = $bodyText.'Brian';

        return $bodyText;
    }
}