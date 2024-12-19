<?php

namespace App\Services;

use ErrorException;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Hotels\Entities\HotelDetail;
use stdClass;

/**
 * Smith api implementation
 * https://api.mrandmrssmith.com/us/rest/documentation
 *
 * Error codes
 * 400
 * 401
 * 404
 * 500
 *
 * Success response code
 * 200
 */

class SmithService
{
    private $userName;
    private $privateKey;
    private $urlBase;

    public function __construct()
    {
        $this->userName = config('services.smith.user_name');
        $this->privateKey = config('services.smith.private_key');
        $this->urlBase = config('services.smith.api_base');
    }

    public function getHotels()
    {
        // Refer: https://api.mrandmrssmith.com/api/hotels/associatedhotels
        $uri = '/hotels/associatedhotels';
        $res = $this->request('GET', $uri);

        return $res->hotels;
    }

    public function getHotelInfoById($id)
    {
        // https://api.mrandmrssmith.com/api/hotels/byid
        $uri = '/hotels/byid';
        $res = $this->request('GET', $uri, ['hotel_id' => $id]);

        return $res;
    }

    public function getHotelInfoByPropertyId($propertyId, $locale = 'en_US')
    {
        // https://api.mrandmrssmith.com/api/properties/view
        $uri = '/properties/view';
        $res = $this->request('GET', $uri, ['property_id' => $propertyId, 'locale' =>  $locale, 'region_fallback' => false]);

        return $res;
    }

    public function rateAll($room_id)
    {
        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/rooms/availability';
        $res = $this->request('GET', $uri, ['room_id' => 31084,'hotel_id' => 7470,'from_date'=>'2023-03-16','to_date'=>'2023-03-24']);

        return $res;
    }

    public function getHotelRates($data)
    {

        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/nexus/search';
        $res = $this->request('POST', $uri, $data);

        return $res;
    }

    public function getHotelRatesType($propertyId)
    {
        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/rates/types';
        $res = $this->request('GET', $uri, ['property_id' => $propertyId]);

        return $res;
    }

    public function getHotelRoomDetails($hotelId)
    {
        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/hotels/rooms';
        $res = $this->request('GET', $uri, ['hotel_id' => $hotelId]);

        return $res;
    }

    public function getHotelRoomDetailsByPropertyId($property_id)
    {
        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/rooms/bypropertyid';
        $res = $this->request('GET', $uri, ['property_id' => $property_id]);

        return $res;
    }

    public function getDestinations()
    {
        // https://api.mrandmrssmith.com/api/destinations/destinations
        $uri = '/destinations';
        $res = $this->request('GET', $uri);

        return $res;
    }

    public function searchHotel($data = [])
    {
        // https://partners.apimmsmith.info/api/nexus/propertylisting
        $uri = '/nexus/propertylisting';
        $res = $this->request('POST', $uri, $data);

        return $res;
    }

    public function bookingInitiate($data)
    {
        // https://api.sandbox.mrandmrssmith.com/api/bookings/initiate
        $uri = '/bookings/initiate';
        $res = $this->request('POST', $uri, $data);

        return $res;
    }

    public function lineItemsByBookingReference($booking_reference)
    {
        // https://api.mrandmrssmith.com/api/nexus/search
        $uri = '/bookings/lineitemsbybookingreference';
        $res = $this->request('GET', $uri, ['booking_reference' => $booking_reference]);

        return $res;
    }

    public function byhotelandrateandroomanddates($room_id,$property_id,$rate_id,$from_date)
    {
        $uri = '/policies/byhotelandrateandroomanddates';
        $res = $this->request('GET', $uri, ['room_id' => $room_id,'property_id' => $property_id,'from_date'=>$from_date]);

        return $res;
    }

    public function allbyhotelanddate($property_id)
    {
        $uri = '/policies/allbyhotelanddate';
        $res = $this->request('GET', $uri, ['property_id' => $property_id]);

        return $res;
    }

    public function bookingConfirm($data)
    {
        // https://api.sandbox.mrandmrssmith.com/api/bookings/confirm
        $uri = '/bookings/confirm';
        $res = $this->request('POST', $uri, $data);

        return $res;
    }

    public function bookingCancel($data)
    {
        // https://api.sandbox.mrandmrssmith.com/api/bookings/cancel
        $uri = '/bookings/cancel';
        $res = $this->request('POST', $uri, $data);

        return $res;
    }

    public function saveSmithHotelDetails($property)
    {
        $hotelDetail = new HotelDetail();
        $hotelDetail->hotel_id = 0;
        $hotelDetail->smith_hotel_id = $property->hotel_id ?? '';
        $hotelDetail->smith_property_id = $property->id ?? '';
        $hotelDetail->address = $property->address ?? '';
        $hotelDetail->city = $property->city ?? '';
        $hotelDetail->county = $property->county ?? '';
        $hotelDetail->postcode = $property->postcode ?? '';
        $hotelDetail->country = $property->country ?? '';
        $hotelDetail->country_name = $property->country_name ?? '';
        $hotelDetail->location = $property->location ?? '';
        $hotelDetail->map_lat = $property->map_lat;
        $hotelDetail->map_long = $property->map_long;
        $hotelDetail->doubles_from = $property->doubles_from ?? '';
        $hotelDetail->tax_rate = $property->tax_rate;
        $hotelDetail->all_inclusive = $property->all_inclusive;
        $hotelDetail->doubles_from_tax_amount = $property->doubles_from_tax_amount;
        $hotelDetail->doubles_from_ex_tax = $property->doubles_from_ex_tax;
        $hotelDetail->doubles_from_gbp_ex_tax = $property->doubles_from_gbp_ex_tax;
        $hotelDetail->doubles_from_gbp_inc_tax = $property->doubles_from_gbp_inc_tax;
        $hotelDetail->doubles_from_inc_tax = $property->doubles_from_inc_tax;

        $hotelDetail->max_child_age = $property->max_child_age;
        $hotelDetail->min_child_age = $property->min_child_age;
        $hotelDetail->max_infant_age = $property->max_infant_age;

        $hotelDetail->check_in_time = $property->check_in_time;
        $hotelDetail->check_out_time = $property->check_out_time;

        $hotelDetail->name = $property->name;
        $hotelDetail->status = $property->status;
        $hotelDetail->record_updated = $property->record_updated;
        $hotelDetail->short_description = $property->short_description;
        $hotelDetail->description = $property->description;
        $hotelDetail->style = $property->style;
        $hotelDetail->setting = $property->setting;
        $hotelDetail->smith_tip = $property->smith_tip;
        $hotelDetail->smith_offer = $property->smith_offer;

        $hotelDetail->listing_image = $property->listing_image;
        $hotelDetail->listing_portrait_image = $property->listing_portrait_image;
        $hotelDetail->listing_snf_image = $property->listing_snf_image;

        $hotelDetail->number_of_rooms = $property->number_of_rooms ?? 0;
        $hotelDetail->sleeps = $property->sleeps;
        $hotelDetail->badges = $property->badges;
        $hotelDetail->adult_only = $property->adult_only;
        $hotelDetail->restaurant = $property->restaurant ?? "";
        $hotelDetail->dress_code = $property->dress_code ?? "";
        $hotelDetail->top_table = $property->top_table ?? "";
        $hotelDetail->last_orders = $property->last_orders ?? "";
        $hotelDetail->room_service = $property->room_service ?? "";

        $hotelDetail->hotel_bar = $property->hotel_bar ?? "";
        $hotelDetail->rooms_description = $property->rooms_description ?? "";
        $hotelDetail->rates_description = $property->rates_description ?? "";
        $hotelDetail->facilities = $property->facilities ?? "";
        $hotelDetail->checkout = $property->checkout ?? "";
        $hotelDetail->more_kids = $property->more_kids ?? "";
        $hotelDetail->also_need_to_know = $property->also_need_to_know ?? "";
        $hotelDetail->favourite_rooms = $property->favourite_rooms ?? "";
        $hotelDetail->packing_tips = $property->packing_tips ?? "";
        $hotelDetail->in_the_know_also = $property->in_the_know_also ?? "";
        $hotelDetail->wgobf = $property->wgobf ?? "";

        $hotelDetail->image = $property->image ?? "";
        $hotelDetail->gallery_images = $property->gallery_images ?? "";
        $hotelDetail->header_images = $property->header_images ?? [];
        $hotelDetail->mms_header = $property->mms_header ?? [];
        $hotelDetail->hotel_currency = $property->hotel_currency;
        $hotelDetail->health_and_fitness = $property->health_and_fitness ?? "";
        $hotelDetail->wifi = $property->wifi ?? "";
        $hotelDetail->swimming_pool = $property->swimming_pool ?? "";

        $hotelDetail->getting_there = $property->getting_there ?? [];
        $hotelDetail->website = $property->website ?? "";
        $hotelDetail->useful_information = $property->useful_information ?? "";
        $hotelDetail->events = $property->events ?? "";
        $hotelDetail->cards_accepted = $property->cards_accepted ?? "";
        $hotelDetail->is_villa = $property->is_villa ?? "";
        $hotelDetail->star_rating = $property->star_rating ?? "";

        $hotelDetail->save();

        return $hotelDetail;
    }

    private function checkResponse($response)
    {

        $statusCode = $response->getStatusCode();

        // Seems 200 are success status
        if ($statusCode == 200) {
            $body = json_decode($response->body());
            if (!empty($body->api->error)) {
                if ($body->api->error !== 'None') {
                    // throw new Exception($body->api->error);

                    $response = [
                        'error' => $body->api->error,
                    ];

                    return response($response, 401);
                }
            }

            return $body->api->data;
        }

        $method = debug_backtrace()[1]['function'];;
        Log::error('SmithService->' . $method . ' returned status: ' . $statusCode, [
            'response' => $response->body(),
        ]);

        $response = [
                        'error' => "SmithService returned error status with code",
                        'statusCode' => $statusCode
                    ];

        return (Object) $response;            


        throw new Exception('SmithService returned error status with code: ' . $statusCode);
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @return stdClass
     * @throws ErrorException
     * @throws Exception
     */
    private function request($method, $url, $data = array())
    {
        $data['username'] = $this->userName;
        $data['version'] = 2;
        $queryString = http_build_query($data);
        $hash = $this->getHash($queryString);

        $data['hash'] = $hash;

        if ($method == 'GET') {

            if (count($data)) {
                $url = $url . '?' . $queryString . '&hash=' . $hash;
            }

            $response = Http::withoutVerifying()->get($this->urlBase . $url);
            
        } elseif ($method == 'POST') {
            $response = Http::withoutVerifying()->asForm()->post($this->urlBase . $url, $data);
        } elseif ($method == 'PUT') {

            $response = Http::withoutVerifying()->put($url, $data);
        } else {
            throw new Exception('Unknown method: ' . $method);
        }

        return  $this->checkResponse($response);
    }

    /**
     * @param string $part
     * @return string
     */
    private function getHash($queryString)
    {
        // Making string to uppercase
        $queryString = strtoupper($queryString);

        // Concatenate private key
        $queryString = $queryString . $this->privateKey;

        // MD5 hash for query string
        $queryString = md5($queryString);

        return $queryString;
    }
}
