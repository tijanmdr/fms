<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $booking;
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function create(Request $req)
    {
        $req = $req->only('name', 'number', 'date', 'hour', 'minute');
        $validate = Validator::make($req, [
            'name' => 'required|string',
            'number' => 'required|string',
            'date' => 'required|date',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'integer|min:0|max:59',
        ]);

        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $this->booking->create($req);
            return returnMessage(true, 'The booking has been created!');
        }
    }

    public function delete(Request $req)
    {
        $req = $req->only('id');
        $validate = Validator::make($req, [
            'id' => 'required|integer|exists:bookings'
        ]);

        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $this->booking->find($req['id'])->delete();
            return returnMessage(true, 'The booking has been deleted!');
        }
    }

    public function toggle(Request $req)
    {
        $req = $req->only('id');
        $validate = Validator::make($req, [
            'id' => 'required|integer|exists:bookings'
        ]);

        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $booking = $this->booking->find($req['id']);
            $status = ($booking->show === 1) ? 0 : 1;
            $booking->update(['show'=>$status]);
            return returnMessage(true, 'The booking has been updated!', $booking);
        }
    }

    public function bookingList($date = null)
    {
        if ($date === null) {
            $date = \Carbon\Carbon::now()->format("Y-m-d");
        } else {
            $date = \Carbon\Carbon::parse($date)->format('Y-m-d');
        }
        $bookings = $this->booking->where('date', $date)->get();

        return returnMessage(true, 'The booking for '.$date.' has been retrieved!', $bookings);
    }
}
