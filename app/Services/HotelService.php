<?php

namespace App\Services;

use Modules\Hotels\Entities\BookingDetail;

class HotelService
{
    public function saveBookingDetails($bookingData, $userId = 0)
    {
        $bookingDetail = new BookingDetail();
        $bookingDetail->hotel_detail_id = $bookingData['hotel_detail_id'];
        $bookingDetail->booking_reference = $bookingData['booking_reference'];
        $bookingDetail->hotel_smith_property_id = $bookingData['hotels[0][property_id]'];
        $bookingDetail->hotel_smith_room_id = $bookingData['hotels[0][rooms][0][room_id]'];
        $bookingDetail->smith_booking_id = $bookingData['smith_booking_id'];
        $bookingDetail->hotel_rate_type_id = $bookingData['hotels[0][rooms][0][type_id]'];
        $bookingDetail->user_id = $userId;
        $bookingDetail->total_amount_inc_tax = $bookingData['hotels[0][rooms][0][sale][inc_tax]'];
        $bookingDetail->total_amount_ex_tax = $bookingData['hotels[0][rooms][0][sale][ex_tax]'];
        $bookingDetail->hotel_detail_id = $bookingData['hotel_detail_id'];
        $bookingDetail->currency_code = $bookingData['sale_currency'];
        $bookingDetail->date_from = $bookingData['date_from'];
        $bookingDetail->nights = $bookingData['nights'];
        $bookingDetail->children = $bookingData['children'];
        $bookingDetail->save();

        $bookingDetail->refresh();

        return $bookingDetail;
    }
}
