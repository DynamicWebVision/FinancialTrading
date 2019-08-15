<?php namespace App\Http\Controllers\Nursing;

use App\Services\Scraper;
use App\Http\Controllers\Controller;
use App\Model\NurseJobs;

class MedproController extends Controller {

    public $states = ['Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY'];

    public function __construct()
    {
        $this->scraper = new Scraper();
    }

    public function loadFromFile() {
        NurseJobs::where('agency', '=', 'Medpro')->delete();

        //Load medpro by going to search/post, then view source
        $text = file_get_contents(public_path().'/temp/medpro.html');

        while ($this->scraper->inString($text, 'Nursing: Emergency Room')) {
            $afterNursing = $this->scraper->getToEnd($text, 'Nursing: Emergency Room');
            $cityState = $this->scraper->getNextTextBetweenTagsThatHasString(',', $afterNursing);

            $city = trim($this->scraper->getTextBeforeString(',', $cityState));
            $state = trim($this->scraper->getTextAfterString(',', $cityState));

            $url = $this->scraper->getNextHref($text);

            if (isset($this->states[$state])) {
                $nurseJob = new NurseJobs();

                $nurseJob->agency = 'Medpro';
                $nurseJob->agency_url = $url;

                $nurseJob->city = $city;
                $nurseJob->state = $this->states[$state];

                $nurseJob->save();
            }

            $text = substr($text, strpos($text,'Emergency Room') + 12);
        }
    }
}