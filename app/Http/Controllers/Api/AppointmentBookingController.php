<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Customer;
use App\Notifications\sendConfirmationEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentBookingController 
{

    /**
     * Using the `registrationNumber` sent via the Vue lead capture component, send a request to the `Transport for London` API VRM lookup to pull vehicle make and model.
     * DVLA API is accessible, but only provides the vehicles make and not the accompanying model.
     * 
     * API call pulled from https://tfl.gov.uk/modes/driving/check-your-vehicle/. No API key required.
     * https://mobileapi.tfl.gov.uk/dvs/api/hgv/LC09GBX
     * 
     * @return array $details
     */
    public function postVehicleRegistration(): array
    {
        $registrationNumber = request('registrationNumber', '');
        $normalisedRegistration = strtoupper(preg_replace('/\s+/', '', $registrationNumber)); // Remove spaces and whitespace, return as uppercase | xx00 xxx => XX00XXX

        $guzzle = new \GuzzleHttp\Client;
        $request = $guzzle->post('https://mobileapim.tfl.gov.uk/Prod/unirucCapitaFacade/VRMLookup', [
            'body' => json_encode([
                'vrmLookupRequest' => [
                    'vRM' => $normalisedRegistration,
                    'country' => 'UK',
                    'date' => []
                ]
            ])
        ]);

        $details = [
            'make' => '',
            'model' => '',
        ];
        try {
            $response = json_decode($request->getBody()->getContents());
            $vehicleDetails = $response->vrmLookupResponse->vehicleDetails;
    
            if($vehicleDetails->make != '') { // Found matching vehicle
                $details['make'] = $vehicleDetails->make;
                $details['model'] = $vehicleDetails->model;
    
            }
        } catch (\Throwable $exception) {
            // Do nothing, we've already set a default value for $details.
        }
        
        return $details;
    }

    /**
     * Check to see if any dates have been fully booked.
     * Max number of bookings per day is 17 (09:00, 09:30, ... , 16:30, 17:00) 
     * If any dates have 17 bookings, add them to the `FullyBooked` array to block out on the Calendar and prevent any attempted bookings.
     * 
     * @return array $bookedDates
     */
    public function getBookedDates(): array
    {
        $fullyBookedDates = [];
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(30)->format('Y-m-d');

        $bookings = Booking::where('date', '>=', $startDate)->where('date', '<=', $endDate)
                    ->select(DB::raw('date, count(date) as timeslots'))
                    ->groupBy('date')
                    ->get();

        $fullyBooked = $bookings->filter(function($booking) { // Remove any dates that aren't fully booked.
            return $booking->timeslots === 17;
        });

        foreach($fullyBooked as $bookedDate) {
            array_push($fullyBookedDates, $bookedDate->date->format('Y-m-d'));
        }
       
        return $fullyBookedDates;
    }

    /**
     * Collate a list of available timeslots and return
     * 
     * @return array $timeslots
     */
    public function postTimeslots()
    {
        $date = request('date', '');
        $timeslots = config('timeslots.defaults');

        $bookings = Booking::where('date', '=', $date)->select('time')->get()->toArray();
        foreach($bookings as $booking) {
            $formatted = str_pad($booking['time'], 4, 0, STR_PAD_LEFT); // 900 => 0900
            $timeslots[$formatted] = true;
        }
        ksort($timeslots);

        $formattedTimeslots = [];
        foreach($timeslots as $key => $value) { // JS doesn't like leading zeros for array/object keys. Reformat pre-ordered chunks with the timeslot/bookingStatus within individual array wrappers.
            array_push($formattedTimeslots, ['timeslot' => $key, 'timeslotReadable' => date('g:i a', strtotime($key)), 'booked' => $value]);
        }

        return $formattedTimeslots;
    }

    /**
     * Store submission request and fire off confirmation emails
     * 
     * @return boolean
     */
    public function postSubmitBooking(): bool
    {
        $name = request('name', '');
        $emailAddress = request('email', '');
        $phoneNumber = request('phone', '');
        $vehicleRegistration = request('vehicleRegistration', '');
        $vehicleMake = request('vehicleMake', '');
        $vehicleModel = request('vehicleModel', '');
        $bookingDate = request('calendarDate', '');
        $bookingTime = request('calenderTime', '');

        try {
            $customer = Customer::updateOrCreate(
                ['email_address' => $emailAddress],
                ['name' => $name, 'phone_number' => $phoneNumber]
            );
            $vehicle = $customer->vehicle()->firstOrCreate(
                ['registration' => $vehicleRegistration],
                ['make' => $vehicleMake, 'model' => $vehicleModel] 
            );
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'date' => $bookingDate,
                'time' => $bookingTime
            ]);

            $customer->notify(new sendConfirmationEmail($booking));
            \App\Models\Settings::sendBookingConfirmation($booking);
        } catch (\Throwable $exception) {
            return false;
        }

        return true;
    }

    /**
     * Quickly populate test dates into `Bookings` DB.
     */
    // public function populateTestDates()
    // {
    //     $dateTest = [
    //         '2024-02-06' => ['0900', '0930', '1000', '1030', '1100', '1130', '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700'],
    //         '2024-02-07' => ['1100', '1130', '1200', '1230', '1300', '1330', '1530', '1600', '1630', '1700'],
    //         '2024-02-10' => ['0900', '0930', '1000', '1030', '1100', '1130', '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700'],
    //         '2024-02-12' => ['0900', '0930', '1000', '1030', '1100', '1130', '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530']
    //     ];
    //     foreach($dateTest as $date => $array) {
    //         foreach($array as $time) {
    //             Booking::create([
    //                 'date' => $date,
    //                 'time' => $time 
    //             ]);
    //         }
    //     }
    // }
}