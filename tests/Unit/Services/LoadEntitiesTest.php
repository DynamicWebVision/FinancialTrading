<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Yelp;
use Illuminate\Support\Facades\DB;
use App\Model\Ranker\Entity;
use App\Model\Ranker\EntityAttribute;
use App\Model\Ranker\EntityDataField;

class LoadEntitiesTest extends TestCase
{

    public function testLoadCityRestaurants() {
        $restaurants = DB::select("select distinct yelp_location.name, address1, city, state, zip, phone_no from yelp_location
                    join yelp_location_category on yelp_location.id = yelp_location_category.yelp_location_id
                    join yelp_categories on yelp_location_category.yelp_category_id = yelp_categories.id
                    where city_id = 5554 and yelp_categories.parent = 'restaurants';");

        foreach ($restaurants as $restaurant) {
            $entity = new Entity();

            $entity->name = $restaurant->name;

            $entity->save();

            $houstonCityAttribute = new EntityAttribute();

            $houstonCityAttribute->entity_id = $entity->id;
            $houstonCityAttribute->attribute_id = 51;

            $houstonCityAttribute->save();

            $stateAttribute = new EntityAttribute();

            $stateAttribute->entity_id = $entity->id;
            $stateAttribute->attribute_id = 69;

            $stateAttribute->save();
        }
    }


}
