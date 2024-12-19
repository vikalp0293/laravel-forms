<?php
use Modules\User\Entities\User;
use App\SendNotification as SendNotification;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Modules\User\Entities\OrganizationStaff;
use Illuminate\Support\Facades\Mail;
use Modules\Hotels\Entities\BookingDetail;
use Modules\Hotels\Entities\BookingRoom;
use App\Services\StripeService;
use App\Services\SmithService;

class Helpers {

	public static function bookingDetails($booking_id) {

		$bookingDetail = BookingDetail::from('booking_details as bd')
		                ->select('bd.id','bd.smith_hotel_id','bd.smith_booking_id','bd.created_at','bd.total','bd.status','bd.id','bd.date_from','bd.date_to','bd.nights','u.name as customer','u.email','hd.name as hotel_name','hd.city','hd.address as hotel_address','u.phone_number','bd.deposit_amount','bd.subtotal','bd.processing_fee','bd.processing_fee','cancellation_days','bd.auto_charge_failed',
		                    DB::Raw('sum(case when (br.smith_booking_id!="") then 1 else 0 end) AS rooms')
		                )
		                ->join('users as u','u.id','=','bd.user_id')
		                ->join('booking_rooms as br','bd.smith_booking_id','=','br.smith_booking_id')
		                ->join('hotel_details as hd','bd.hotel_detail_id','=','hd.id')
		                ->where('bd.id',$booking_id)
		                ->first();
		if($bookingDetail){
	        $bookingRooms = BookingRoom::select('smith_booking_id','hotel_smith_room_id','adults','children','total_amount_inc_tax','total_customer_amount_inc_tax','total_customer_amount_ex_tax')
	                ->where('smith_booking_id',$bookingDetail->smith_booking_id)
	                ->get();

	        $service = new SmithService();
	        $roomDetails = $service->getHotelRoomDetails($bookingDetail->smith_hotel_id);
	        $rooms = $roomDetails->rooms;
	        $roomsBooked = array();

	        foreach ($bookingRooms as $key => $bookingRoom) {
	            $room_id                                = $bookingRoom->hotel_smith_room_id;
	            $rooms->$room_id->adults                = $bookingRoom->adults;
	            $rooms->$room_id->children              = $bookingRoom->children;
	            $rooms->$room_id->total_amount_inc_tax  = $bookingRoom->total_amount_inc_tax;
	            $rooms->$room_id->total_customer_amount_inc_tax  = $bookingRoom->total_customer_amount_inc_tax;
	            $rooms->$room_id->total_customer_amount_ex_tax  = $bookingRoom->total_customer_amount_ex_tax;
	            $roomsBooked[]                          = $rooms->$room_id;
	        }
	        $bookingDetail->rooms = $roomsBooked;
		}else{
			return $bookingDetail = array();
		}

		return $bookingDetail;
	}

	public static function bookingMail($booking_id){
		$bookingDetail = self::bookingDetails($booking_id);

		if(!empty($bookingDetail)){
            $to_name = $bookingDetail->customer;
            $to_email = $bookingDetail->email;
            $mailSubject = 'SevennUp Hotel Booking';
            $data = array('name'=>$to_name, "bookingDetail" => $bookingDetail,'mailSubject' => $mailSubject);

            \Mail::send('emails.booking_mail', $data, function ($message)  use ($to_name, $to_email,$mailSubject) {
                $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'),'sevennup');
            });
        }
	}

	public static function sucessfulPaymentMail($booking_id){
		$bookingDetail = self::bookingDetails($booking_id);

		if(!empty($bookingDetail)){
            $to_name = $bookingDetail->customer;
            $to_email = $bookingDetail->email;
            $mailSubject = 'SevennUp Booking - Auto Payment Success';
            $data = array('name'=>$to_name, "bookingDetail" => $bookingDetail,'mailSubject' => $mailSubject);

            \Mail::send('emails.auto_payment_successful', $data, function ($message)  use ($to_name, $to_email,$mailSubject) {
                $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'),'sevennup');
            });
        }
	}

	public static function failedPaymentMail($booking_id){
		$bookingDetail = self::bookingDetails($booking_id);

		if(!empty($bookingDetail)){
            $to_name = $bookingDetail->customer;
            $to_email = $bookingDetail->email;
            $mailSubject = 'SevennUp Booking - Auto Payment Failed';
            $data = array('name'=>$to_name, "bookingDetail" => $bookingDetail,'mailSubject' => $mailSubject);

            \Mail::send('emails.failed_payment_mail', $data, function ($message)  use ($to_name, $to_email,$mailSubject) {
                $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'),'sevennup');
            });
        }
	}

	public static function bookingCancelled($booking_id){
		$bookingDetail = self::bookingDetails($booking_id);

		if(!empty($bookingDetail)){
            $to_name = $bookingDetail->customer;
            $to_email = $bookingDetail->email;
            $mailSubject = 'SevennUp Booking - Cancelled';
            $data = array('name'=>$to_name, "bookingDetail" => $bookingDetail,'mailSubject' => $mailSubject);

            \Mail::send('emails.booking_cancelled_mail', $data, function ($message)  use ($to_name, $to_email,$mailSubject) {
                $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'),'sevennup');
            });
        }
	}

	/**
	 * function for calculate percentage
	 * @param $current
	 * @param $total
	 * @return float
	 */
	public static function calculatePercentage($current, $total){
		$percentage = ($current / $total) * 100;
		return round($percentage, 2);
	}


	/**
	 * function for conver array keys
	 * @param $array
	 * @return array
	 */
	public static function convertArrayKeys($array){
		$keys = array_keys($array);
		//Map keys to format function
		$keys = array_map( @[ self, 'map' ],$keys );
		
		//Use array_combine to map formatted keys to array values
		$array = array_combine($keys,$array);
		
		//Replace nulls and .00 from array
		return self::replaceNulls($array);
	}


	public static function map($key){
		// return str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
		return lcfirst(implode('', array_map('ucfirst', explode('_', $key))));
	}

	public static function replaceNulls($array){
		array_walk_recursive($array, @[self, 'array_replacing']);
		return $array;
	}

	public static function array_replacing(&$item, $key)
	{
		if($item == null || $item == NULL){
			$item = "";
		} elseif($item == ".00"){
			$item = 0;
		}
		// else{
		//     $item = trim($item);
		// }

	}

	public static function clean($string)
	{
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
	}

	public static function getAcronym($words) {
		$words = preg_replace('/\s+/', ' ', $words);
		$words = explode(" ", $words);

		$acronym = "";
		foreach ($words as $w) {
			if(strlen($acronym) < 2 && trim($w) != "")
			{
				$acronym .= $w[0];
			} 
		}
		return strtoupper($acronym);

	}


	public static function getFeaturePermission($feature) {
		$permission =   OrganizationPermission::select('read_own','read_all','edit_own','edit_all','delete_own','delete_all')
                        ->where('role_id',Auth::user()->role)
                        ->where('feature_id',$feature)
                        ->first();
        if($permission){
            return $permission->toArray();
        }else{
            return array();
        }
	}

	public static function checkDiscount() {


		// $organization_id = \Auth::user()->organization_id;

		$settings = \DB::select("select value from settings where code = 'ecommerce_discount'");

		if(!empty($settings)){
			if ($settings[0]->value == 'true') {
				return true;
			} else {
				return false;
			}
		}else{
			return false;
		}
	}



	public static function getUserDetails($user_id =0) {

		if($user_id != 0){
			$user = User::findorfail($user_id);
			if($user){
				return $user;
			}
		}
		return false;
	}


	public static function sendNotifications($receiver = array(), $bodies = array(),$channels = array(),$mailSubject = '',$details = array()) {

		if(!empty($channels)){

			foreach ($channels as $key => $channel) {
				if(!empty($bodies) && isset($bodies[$channel])){
					// Push,In-app/WA/Email
					if($channel == 'wa'){
						$notifyNumbers = '91'.$receiver->phone_number;
		                if(!empty($notifyNumbers)){
		                    $broadcast = self::sendWaNotification($bodies[$channel],$notifyNumbers);
		                }
					}
					
					if($channel == 'database'){

						if(!empty($details) && $details['fcm_token'] != ""){
							$sendNotification = SendNotification::sendNotification($details);
						}

						if (\Auth::user()){
							$organization_id = \Auth::user()->organization_id;
				        }else{
				            $organization_id = $details['organization_id'];
				        }

						$factory = (new Factory)->withServiceAccount(__DIR__.'/seven-firebase.json')->withDatabaseUri(\Config::get('constants.FIREBASE.DATABASEURI'));
						$database = $factory->createDatabase();
						$details['date'] = date('Y-m-d H:i:s');
						$newPost = $database
						// ->getReference(\Config::get('constants.FIREBASE.REFERENCE').'/user_id_'.$receiver->id)
						->getReference(\Config::get('constants.FIREBASE.REFERENCE').'/org_'.$organization_id.'_user_id_'.$receiver->id)
						->push($details);
					}

					if($channel == 'mail'){

						$to_name = $receiver->name;
						$to_email = $receiver->email;
						$data = array('name'=>$receiver->name, "body" => $bodies[$channel],'mailSubject' => $mailSubject);
						$mailBody = $bodies[$channel];

						Mail::send('emails.email_template', $data, function ($message)  use ($to_name, $to_email,$mailBody,$mailSubject) {
							// $message->to($to_email, $to_name)
							$message->to($to_email, $to_name)
							->subject($mailSubject)
							->from(env('MAIL_FROM'),'sevennup');
							// ->setBody($mailBody, 'text/html');
						});


						// Mail::send(‘emails.mail’, $data, function($message) use ($to_name, $to_email) {
						// $message->to($to_email, $to_name)
						// ->subject(Laravel Test Mail’);
						// $message->from(‘SENDER_EMAIL_ADDRESS’,’Test Mail’);
						// });

					}

				}
			}
		}
	}

	

	public static function pagination($paginated)
    {
        return [
            'pagination' => [
                'total' => $paginated->total(),
                'count' =>$paginated->count(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last' => $paginated->lastPage(),
                'total_pages' => $paginated->lastPage(),
                'links' =>  array(
                                'next' => $paginated->nextPageUrl(),
                                'prev' => $paginated->previousPageUrl(),
                            )
            ]
        ];
    }
}
