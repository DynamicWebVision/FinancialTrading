<?php

namespace Tests\Unit\Messgen;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\Messgen\MessgenUsers;
use App\Model\Messgen\MessgenCategory;
use App\Model\Messgen\MessgenMessage;
use App\Http\Controllers\Nursing\FlexcareController;
use App\Http\Controllers\Nursing\MedproController;
use App\Services\Utility;

class UsersTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public $categories = ['Sales', 'JobSearch', 'CustomerFeedback', 'TroubleShooting', 'OnlineDating', 'ColdSales','TechRecruiting','AcctRecruiting','RelationshipManagement', 'Test', 'Testaaa'];

    public $messages = [
        'Under the constraints of keeping things strictly professional, here’s what you could write without sounding too boring or jargon-y:

Dear XXX_NAME_XXX,

In my five-year career as a paralegal, I have honed my legal research and writing skills, and the attorneys I’ve worked with have complimented me on my command of case law and litigation support. Spiegel Law Firm’s 20 years in practice proves that the firm has strong values and excellent attorneys, which is why I want to be a part of the Spiegel Law Firm team.

I currently serve as a paralegal for Chandler LLC, where I work closely with the partners on a number of high-priority cases. During my time here, I implemented a new calendar system that ensures timely filing of court papers. This system has prevented missed deadlines and allowed for better organization of internal and client meetings.

Previously, as a paralegal for the Neuerburg Law Firm, I received praise for my overall support of the legal team and my positive attitude.

My further qualifications include a bachelor’s degree from Rutgers University, a paralegal certificate, and training in LexisNexis, Westlaw, and Microsoft Office Suite.

I would love the opportunity to discuss how I can contribute to your legal team. Thank you in advance for your consideration, and I look forward to hearing from you.',

        'XXX_TIME_XXX works great. See you then.',

        'Hello XXX_NAME_XXX',

        'I have a problem. See, my inbox currently (and embarrassingly) hosts 1,500 unread emails—including newsletters from at least 50 different brands.

But this problem only fuels my passion for creating emails that are worth opening. Because from my perspective, as someone who can barely get through their own stack of mail, that’s a true win.

I’ve been following Vitabe for years, and can proudly say that I open every single email you send to me. I’m a sucker for a good subject line—“Take a Vitamin-ute—We’ll A-B-C You Soon” being my favorite—and the way your email content feels both fun and expert-backed really speaks to me. This is why I’m thrilled to submit my application for a role as email marketing manager at your company.

I have over four years of experience working in the email marketing space. In my current role at Westside Bank, I was able to implement new email campaigns centered around reengaging churned clients. By analyzing data around the types of clients who churn and the engagement of our current email subscribers, as well as A/B testing headlines and newsletter layouts, we were able to increase email subscribers by 15% and convert 30% of those subscribers to purchase our product, a significant increase from the previous year. I also launched a “Your Credit Matters” newsletter focused on educating our clients on how they spend and manage their credit—which became our highest performing campaign in terms of open-rates and click-through to date.

Previously, as a member of the marketing team at Dream Diary Mattresses, I collaborated with the sales and product team to understand how I could best support them in hitting their quarterly goals. One specific project involving creating personalized emails for customers drew more people to come back to our site after 30 days than direct paid ad campaigns, leading to a 112% increase in revenue from the last quarter.

I take the content I write and the calendars I manage seriously, editing and refining to the point beyond being detail-oriented into scary territory, and I feel my experience and drive would greatly help Vitabe further develop their email program for success.

Thank you very much for your time and consideration. I look forward to hearing from you.',

        'I love game of thrones too.',
        'Hey XXX_NAME_XXX,
        
        I am really impressed with your background and I have some great opportunities for you. Please email me back to find out more.',

        'One of the best ways to do this is to ask a candidate for help or ask their opinion on something. This shows them that you see them as an expert.',

        'I received your contact information from XXX_CONNECTION_XXX.


We grabbed a coffee last week to talk about to talk about talented designers in the area that would thrive in a fast paced working environment ­ he couldn"t have recommended you more enthusiastically!


Would you be open to having a quick chat about [Company] and the role that I think might be a perfect fit?',

        'I am so sorry about what happened.',
        'We"re hiring for a new team lead and I thought it would be a nice fit. Do you have some time tomorrow afternoon to discuss the role in a bit more detail?',

        'Laura Buxton is a Customer Onboarding Manager at Braze (formerly Appboy) who works with customers during implementation and training. When she isn’t listening to the latest episodes of her favourite podcasts you’ll find her planning out her dream house, or googling images of Burmese kittens.',

        'Hello! I liked your profile – would you be interested in having lunch at [someplace safe like a local diner/bookstore/coffee shop]?'

    ];

    public function testGet() {
        $users = MessgenUsers::get();

        $utility = new Utility();

        $currentUnixTime = 1478908800;

        foreach ($users as $user) {
            $daysAddition = rand(0, 5);

            $secondsAddition = rand(3000, 86400);

            $secondsAddition = ($daysAddition*86400) + $secondsAddition;

            $currentUnixTime = $currentUnixTime + $secondsAddition;

            $updateUser = MessgenUsers::find($user->id);

            $updateUser->created_at = $utility->unixToMysqlDateNoTime($currentUnixTime);
            $updateUser->updated_at = $utility->unixToMysqlDateNoTime($currentUnixTime);

            $updateUser->save();
        }
    }

    public function testCreateCategories() {
        $categoryCount = rand(1,3);


        $users = MessgenUsers::get();

        foreach ($users as $user) {
            $currentCatCount = 1;
            while ($currentCatCount < $categoryCount) {
                $categoryIndex = rand(0,10);

                $newCategory = $this->categories[$categoryIndex];

                $categoryToBeSaved = new MessgenCategory();

                $categoryToBeSaved->category_name = $newCategory;
                $categoryToBeSaved->user_id = $user->id;

                $categoryToBeSaved->save();

                $messageCount = array_rand([1,1,1,2,2,3,4]);
                $currentMessageCount = 1;

                while ($currentMessageCount < $messageCount) {
                    $nextMessage = array_rand($this->messages);
                    $nextMessage = $this->messages[$nextMessage];

                    $newMessage = new MessgenMessage();

                    $newMessage->user_id = $user->id;
                    $newMessage->category_id = $categoryToBeSaved->id;

                    $endText = rand(8, 20);
                    $newMessage->message_name = substr(preg_replace("/[^A-Za-z0-9 ]/", '', $nextMessage), 0, $endText);

                    $newMessage->message_sample = substr($nextMessage, 0, 150);
                    $newMessage->message = $nextMessage;

                    $newMessage->save();

                    $currentMessageCount++;
                }


                $currentCatCount++;
            }
        }
    }

    public function testHandleTimeStampsdd() {
        $users = MessgenUsers::where('id','!=',1)->get();
        $utility = new Utility();

        foreach ($users as $user) {
            $currentUnix = strtotime($user->created_at);

            $userCategories = MessgenCategory::where('user_id','=', $user->id)->get();

            foreach ($userCategories as $category) {
                $daysAddition = rand(0, 5);

                $secondsAddition = rand(3000, 86400);

                $secondsAddition = ($daysAddition*86400) + $secondsAddition;

                $currentUnix = $currentUnix + $secondsAddition;

                $updateCategory = MessgenCategory::find($category->id);

                $updateCategory->updated_at = $utility->unixToMysqlDate($currentUnix);
                $updateCategory->created_at = $utility->unixToMysqlDate($currentUnix);;

                $updateCategory->save();
            }
        }
    }

    public function testHandleTimeStampddsdd() {
        $userCategories = MessgenCategory::get();
        $utility = new Utility();

        foreach ($userCategories as $category) {
            $currentUnix = strtotime($category->created_at);

            $userMessages = MessgenMessage::where('category_id','=', $category->id)->get();

            foreach ($userMessages as $message) {
                $daysAddition = rand(0, 5);

                $secondsAddition = rand(3000, 86400);

                $secondsAddition = ($daysAddition*86400) + $secondsAddition;

                $currentUnix = $currentUnix + $secondsAddition;

                $updateCategory = MessgenMessage::find($message->id);

                $updateCategory->updated_at = $utility->unixToMysqlDate($currentUnix);
                $updateCategory->created_at = $utility->unixToMysqlDate($currentUnix);;

                $updateCategory->save();
            }
        }
    }
}
