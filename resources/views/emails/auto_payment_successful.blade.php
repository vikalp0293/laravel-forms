<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>sevenupp</title>
        <style type="text/css">
            body{font-family: arial}
        </style>
    </head>
    <body>
        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="padding-bottom: 15px; padding-top: 10px;"><img src="https://admin.sevennup.com/images/logo-dark.png" alt="sevenupp"></td>
                </tr>                       
            </tbody>
        </table>
        <!-- Banner content start -->
        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="background: rgb(218,255,0); padding-left: 15px;padding-top: 10px; padding-bottom: 10px">
                        <table>
                            <tr>
                                <td style="font-family: arial; font-size: 24px; font-weight: 700; color: #000;">Booking - Auto Payment Success</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Content Area Start -->

        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="color:#FF8000; font-size: 14px; font-family: arial; padding-top: 20px; padding-bottom: 10px; font-weight: 600">Hello {{ $bookingDetail->customer }}</td>
                </tr>
                <tr>
                    <td style="color:#000; font-size: 14px; font-family: arial; padding-top: 10px; padding-bottom: 10px; font-weight: normal; line-height:20px;">Thank you for booking. We'd like to let you know that your an payment of <strong>USD {{ $bookingDetail->total-$bookingDetail->deposit_amount }}</strong> against your booking for <strong>{{ $bookingDetail->hotel_name }}</strong> is successful.</td>
                </tr>                       
            </tbody>
        </table>

        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="color:#FF8000; font-size: 16px; font-family: arial; padding-top: 10px; padding-bottom: 10px; font-weight: 600">Booking Details</td>
                </tr>
                <tr>
                    <td style="color:#000; font-size: 16px; font-family: arial; padding-top: 0px; padding-bottom: 10px; font-weight: normal; line-height:20px;font-weight: 600">Booking ID: {{ $bookingDetail->smith_booking_id }}</td>
                </tr>

                <tr>
                    <td style="color:#000; font-size: 16px; font-family: arial; padding-top: 0px; padding-bottom: 10px; font-weight: normal; line-height:20px;font-weight: 600">Booking Amount: USD {{ $bookingDetail->total }}</td>
                </tr>

                <tr>
                    <td style="color:#000; font-size: 16px; font-family: arial; padding-top: 0px; padding-bottom: 10px; font-weight: normal; line-height:20px;font-weight: 600">Duration: {{ $bookingDetail->nights }} Nights (From {{ date('M d,Y',strtotime($bookingDetail->date_from)) }} to {{ date('M d,Y',strtotime($bookingDetail->date_to)) }})</td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="color:#FF8000; font-size: 16px; font-family: arial; padding-top: 10px; padding-bottom: 10px; font-weight: 600">Room Details</td>
                </tr>
            </tbody>
        </table>

        <table class="price-table" cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <thead>
                <tr>
                    <th width="13%" style="text-align: left; background: #F1F1F1; padding: 9px 4px; font-size: 14px; border-bottom: #D6D4D4;">Room Id</th>
                    <th width="48%" style="text-align: left; background: #F1F1F1; padding: 9px 4px; font-size: 14px; border-bottom: #D6D4D4;">NAME</th>
                    <th width="13%" style="text-align: center; background: #F1F1F1; padding: 9px 4px; font-size: 14px; border-bottom: #D6D4D4;">Adults</th>
                    <th width="13%" style="text-align: center; background: #F1F1F1; padding: 9px 4px; font-size: 14px; border-bottom: #D6D4D4;">Total</th>
                </tr>
            </thead>
            <tbody>

                @forelse($bookingDetail->rooms as $key => $room)
                <tr>
                    <td width="13%" style="text-align: left; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4;">{{ $room->room_id }}</td>
                    <td width="48%" style="text-align: left; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4;">{{ $room->name }}</td>
                    <td width="13%" style="text-align: center; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4; white-space: nowrap;">{{ $room->adults }}</td>
                    <td width="13%" style="text-align: right; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4; padding-right: 10px; white-space: nowrap;">USD {{ $room->total_customer_amount_inc_tax }} </td>
                </tr>
                @empty
                @endforelse


               
                <tr>
                    <td width="13%" style="text-align: left; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4;">&nbsp;</td>
                    <td width="48%" style="text-align: left; padding: 9px 4px; border-bottom: 1px solid #D6D4D4; font-size: 18px; font-weight: 600;">Prccessing Fees</td>
                    <td width="13%" colspan="3" style="text-align: right; padding: 9px 4px; padding-right: 10px;font-size: 18px; font-weight: 600; border-bottom: 1px solid #D6D4D4;">USD {{ $bookingDetail->processing_fee }}</td>
                </tr>

                <tr>
                    <td width="13%" style="text-align: left; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4;">&nbsp;</td>
                    <td width="48%" style="text-align: left; padding: 9px 4px; border-bottom: 1px solid #D6D4D4; font-size: 18px; font-weight: 600;">Sub Total</td>
                    <td width="13%" colspan="3" style="text-align: right; padding: 9px 4px; padding-right: 10px;font-size: 18px; font-weight: 600; border-bottom: 1px solid #D6D4D4;">USD {{ $bookingDetail->subtotal }}</td>
                </tr>

                <tr>
                    <td width="13%" style="text-align: left; padding: 9px 4px; font-size: 14px; border-bottom: 1px solid #D6D4D4;">&nbsp;</td>
                    <td width="48%" style="text-align: left; padding: 9px 4px; border-bottom: 1px solid #D6D4D4; font-size: 18px; font-weight: 600;">TOTAL</td>
                    <td width="13%" colspan="3" style="text-align: right; padding: 9px 4px; padding-right: 10px;font-size: 18px; font-weight: 600; border-bottom: 1px solid #D6D4D4;">USD {{ $bookingDetail->total }}</td>
                </tr>
            </tbody>
        </table>
        <!-- content Area End -->

        <!-- footer start -->
        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="background: rgb(218,255,0); padding-left: 15px; padding-top: 10px; padding-bottom: 10px"><img src="https://admin.sevennup.com/images/logo-dark.png" alt="sevenupp"></td>
                    <td style="background: rgb(218,255,0); text-align: right; padding-right: 15px; padding-top: 10px; padding-bottom: 10px">
                        <a href="#" target="_blank" style="color: #000; font-size: 14px; text-decoration: none; font-family: arial">Help</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center" style="border-bottom: 1px solid #D6D4D4">
            <tbody>
                <tr>
                    <td style="padding-left:0px; padding-top: 10px; font-size: 12px; padding-bottom: 10px; font-family: arial;">©{{ date('Y') }} sevenupp. All Rights Reserved.</td>
                    <td style="text-align: right; padding-right:0px; padding-top: 10px; padding-bottom: 10px; font-family: arial;">
                        
                        <a href="#" target="_blank" style="color: #000; font-size: 12px; text-decoration: none; font-family: arial">Facebook</a> | 
                        <a href="#" target="_blank" style="color: #000; font-size: 12px; text-decoration: none; font-family: arial">Twitter</a> | 
                        <a href="#" target="_blank" style="color: #000; font-size: 12px; text-decoration: none; font-family: arial">LinkedIn</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" width="650" align="center">
            <tbody>
                <tr>
                    <td style="padding-left:0px; padding-top: 10px; font-size: 11px; padding-bottom: 10px; color: #7F7F7F; font-family: arial;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>