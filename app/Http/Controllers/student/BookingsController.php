<?php

namespace App\Http\Controllers\student;
use App\Http\Controllers\Controller;
use App\Mail\BookedTime;
use App\Mail\BookingDeleted;
use App\Mail\CounsellorNotification;
use App\Models\BookingDetails;
use App\Models\counsellor;
use App\Models\TimeSlots;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\CssSelector\Node\Specificity;
use \Illuminate\Support\Facades\Mail;

use function PHPSTORM_META\type;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($counsellor_id, Request $request)
    {

        $counsellor = counsellor::where('counsellor_id', $counsellor_id)->first();

        $timeslot_id = $request->query('timeslot_id');

        $timeslots = TimeSlots::all();

        $specificTimeSlot = $timeslots->firstWhere('timeslot_id', $timeslot_id);


        return view('counsellors.bookings.create',[
            'counsellor' => $counsellor,
            'timeslot' => $specificTimeSlot,

        ]);
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $counsellor_id)
    {
        // Retrieve the counsellor and timeslot from the database
        $counsellor = Counsellor::where('counsellor_id', $counsellor_id)->first();
        $timeslot_id = $request->query('timeslot');
        $specificTimeSlot = TimeSlots::where('timeslot_id', $timeslot_id)->first();

        // Validate request inputs with custom error messages
        $validatedData = $request->validate([
            'mobile-no' => 'required|digits_between:10,15',
            'email' => 'required|email',
            'NIC'=>'nullable',
            'name' => 'nullable|string|max:255',
            'gender'=>'required',
            'message' => 'nullable|string|max:1000',
            'category'=>'required',
            'year'=>'nullable ',
            'faculty'=>'nullable',
        ], [
            'mobile-no.required' => 'Please provide your mobile number.',
            'mobile-no.digits_between' => 'Mobile number must be between 10 and 15 digits.',
            'email.required' => 'Your email is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Prepare form details for email or future use
        $formDetails = [
            'mobile_no' => $validatedData['mobile-no'],
            'email' => $validatedData['email'],
            'faculty' => $validatedData['faculty'] ?? null,
            'name' => $validatedData['name'] ?? null,
            'gender'=>$validatedData['gender'] ??null,
            'registration_no' => $validatedData['registration_no'] ?? null,
            'message' => $validatedData['message'] ?? null,
            'NIC'=>$validatedData['NIC'] ?? null,
            'category'=>$validatedData['category'] ?? null,
            'year'=>$validatedData['year'] ?? null,
        ];

        // Create a new booking record
        $bookingRecord = new BookingDetails();
        $bookingRecord->counsellor_id = $counsellor->counsellor_id;
        $bookingRecord->timeslot_id = $timeslot_id;
        $bookingRecord->mobile_no = $formDetails['mobile_no'];
        $bookingRecord->email = $formDetails['email'];
        $bookingRecord->faculty = $formDetails['faculty'];
        $bookingRecord->name = $formDetails['name'];
        $bookingRecord->gender=$formDetails['gender'];
        $bookingRecord->registration_no = $formDetails['registration_no'];
        $bookingRecord->message = $formDetails['message'];
        $bookingRecord->NIC=$formDetails['NIC'];
        $bookingRecord->category=$formDetails['category'];
        $bookingRecord->year=$formDetails['year'];

        $bookingRecord->save();

        // // Generate PDF from view
        // $pdf = Pdf::loadView('pdf.booking_confirmation', [
        //     'formDetails' => $formDetails,
        //     'counsellor' => $counsellor,
        //     'timeslot' => $specificTimeSlot,
        //     'bookingDate' => Carbon::now()->format('F d, Y'), // Current date
        //     'bookingTime' => Carbon::now()->format('h:i A'),
        //     'bookingID' => $bookingRecord->booking_id,
        //     'location' => 'Sitharana Counseling Center, SUSL'
        // ]);
        // $pdfPath = storage_path('app/public/booking_confirmation.pdf');
        // $pdf->save($pdfPath);

        // // Send email confirmation to the client with PDF attachment
        Mail::to($formDetails['email'])->send(new BookedTime($formDetails, $counsellor, $specificTimeSlot));

        // Send email notification to the counsellor with PDF attachment
        Mail::to($counsellor->email)->send(new CounsellorNotification($formDetails, $counsellor, $specificTimeSlot,$bookingRecord));



        // Save the PDF file temporarily


        // Pass the download link to the view
        return view('emails.invoice', [
            'counsellor' => $counsellor,
            'timeslot' => $specificTimeSlot,
            'bookingDetail' => $bookingRecord,
            'pdfPath' => asset('storage/booking_confirmation.pdf')
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function destroy($id)
{
    // Find the booking by ID
    $booking = BookingDetails::findOrFail($id);

    // Capture the email and other necessary info before deleting the booking
    $userEmail = $booking->email; // Adjust based on your relations

    // Delete the booking
    $booking->delete();

    $counsellor = Counsellor::where('counsellor_id', $booking->counsellor_id)->first();

    $TimeSlot = TimeSlots::where('timeslot_id',$booking->timeslot_id)->first();

    // Send the email notification
    Mail::to($userEmail)->send(new BookingDeleted($booking,$counsellor  ,  $TimeSlot));



    // Redirect back with a success message
    return redirect()->back()->with('success', 'Booking deleted successfully, and email notification sent.');
}
}
