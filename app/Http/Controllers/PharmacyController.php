<?php

namespace App\Http\Controllers;

use App\Models\Telepharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'pharmacy-user') {
            return redirect($this->getRedirectForRole($user->role))->with('error', 'Only pharmacy users can access this dashboard.');
        }

        $records = Telepharmacy::where('user_id', Auth::id())->latest()->get();
        return view('services.telemedicine-pharmacy-patient', ['records' => $records]);
    }

    public function storeOrder(Request $request)
    {
        if (Auth::user()->role !== 'pharmacy-user') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacy users can place orders.');
        }

        $request->validate([
            'drug_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'prescription' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'delivery_address' => 'required|string|max:500',
        ]);

        $record = new Telepharmacy();
        $record->user_id = Auth::id();
        $record->type = 'order';
        $record->drug_name = $request->drug_name;
        $record->quantity = $request->quantity;
        $record->delivery_address = $request->delivery_address;
        $record->status = 'pending';

        if ($request->hasFile('prescription')) {
            $path = $request->file('prescription')->store('prescriptions', 'public');
            $record->prescription_path = $path;
        }

        $record->save();

        return redirect()->route('pharmacy.index')->with('success', 'Order placed successfully!');
    }

    public function storeConsultation(Request $request)
    {
        if (Auth::user()->role !== 'pharmacy-user') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacy users can book consultations.');
        }

        $request->validate([
            'consultation_date' => 'required|date|after:today',
            'consultation_type' => 'required|in:video,phone,chat',
            'notes' => 'nullable|string|max:1000',
        ]);

        $record = new Telepharmacy();
        $record->user_id = Auth::id();
        $record->type = 'consultation';
        $record->consultation_date = $request->consultation_date;
        $record->consultation_type = $request->consultation_type;
        $record->notes = $request->notes;
        $record->status = 'pending';

        $record->save();

        return redirect()->route('pharmacy.index')->with('success', 'Consultation booked successfully!');
    }

    public function storeRefill(Request $request)
    {
        if (Auth::user()->role !== 'pharmacy-user') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacy users can request refills.');
        }

        $request->validate([
            'order_id' => 'required|integer|min:1',
            'refill_quantity' => 'required|integer|min:1',
            'refill_address' => 'required|string|max:500',
        ]);

        $originalOrder = Telepharmacy::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('type', 'order')
            ->first();

        if (!$originalOrder) {
            throw ValidationException::withMessages([
                'order_id' => 'The provided Order ID does not exist or is not a valid order for your account.',
            ]);
        }

        $record = new Telepharmacy();
        $record->user_id = Auth::id();
        $record->type = 'refill';
        $record->original_order_id = $request->order_id;
        $record->quantity = $request->refill_quantity;
        $record->delivery_address = $request->refill_address;
        $record->status = 'pending';

        $record->save();

        return redirect()->route('pharmacy.index')->with('success', 'Refill request submitted successfully!');
    }

    public function pharmacistIndex()
    {
        $user = Auth::user();
        if ($user->role !== 'pharmacist') {
            return redirect($this->getRedirectForRole($user->role))->with('error', 'Only pharmacists can access this dashboard.');
        }

        $orders = Telepharmacy::where('type', 'order')->where('status', 'pending')->latest()->get();
        $consultations = Telepharmacy::where('type', 'consultation')->where('status', 'pending')->latest()->get();
        $refills = Telepharmacy::where('type', 'refill')->where('status', 'pending')->latest()->get();

        return view('services.telemedicine-pharmacy-doctor', [
            'orders' => $orders,
            'consultations' => $consultations,
            'refills' => $refills,
        ]);
    }

    public function approveOrder($id)
    {
        if (Auth::user()->role !== 'pharmacist') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacists can approve orders.');
        }

        $order = Telepharmacy::where('type', 'order')->findOrFail($id);
        $order->status = 'approved';
        $order->save();

        return redirect()->route('pharmacist.index')->with('success', 'Order approved successfully!');
    }

    public function rejectOrder($id)
    {
        if (Auth::user()->role !== 'pharmacist') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacists can reject orders.');
        }

        $order = Telepharmacy::where('type', 'order')->findOrFail($id);
        $order->status = 'rejected';
        $order->save();

        return redirect()->route('pharmacist.index')->with('success', 'Order rejected successfully!');
    }

    public function approveRefill($id)
    {
        if (Auth::user()->role !== 'pharmacist') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacists can approve refills.');
        }

        $refill = Telepharmacy::where('type', 'refill')->findOrFail($id);
        $refill->status = 'approved';
        $refill->save();

        return redirect()->route('pharmacist.index')->with('success', 'Refill request approved successfully!');
    }

    public function rejectRefill($id)
    {
        if (Auth::user()->role !== 'pharmacist') {
            return redirect($this->getRedirectForRole(Auth::user()->role))->with('error', 'Only pharmacists can reject refills.');
        }

        $refill = Telepharmacy::where('type', 'refill')->findOrFail($id);
        $refill->status = 'rejected';
        $refill->save();

        return redirect()->route('pharmacist.index')->with('success', 'Refill request rejected successfully!');
    }

    private function getRedirectForRole($role)
    {
        switch ($role) {
            case 'pharmacy-user':
                return '/services/pharmacy';
            case 'pharmacist':
                return '/pharmacist';
            default:
                return '/home';
        }
    }
}
