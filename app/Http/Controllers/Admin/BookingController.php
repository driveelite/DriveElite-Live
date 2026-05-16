<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['car', 'user'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|string']);
        
        // 🚀 VIVA SAFE MODE: Sirf database update hoga, koi email network call nahi hogi taake app crash na ho!
        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Reservation status updated successfully!');
    }

    public function invoice(Booking $booking)
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('admin.bookings.invoice', compact('booking', 'settings'));
        return $pdf->download('DriveElite-Invoice-00' . $booking->id . '.pdf');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Record deleted securely!');
    }

    public function payments()
    {
        $totalRevenue = Booking::where('status', 'Completed')->sum('total_price');
        $pendingClearance = Booking::whereIn('status', ['Pending', 'Approved'])->sum('total_price');
        $completedTransactions = Booking::where('status', 'Completed')->count();
        $recentTransactions = Booking::with(['car', 'user'])->latest()->get();

        return view('admin.payments', compact(
            'totalRevenue', 'pendingClearance', 'completedTransactions', 'recentTransactions'
        ));
    }

    public function exportLeads()
    {
        $bookings = Booking::with('user')->get();
        $filename = "DriveElite_Financial_Ledger_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        fputcsv($handle, ['Ref ID', 'Customer Name', 'Email', 'Amount (PKR)', 'Status', 'Date']);

        foreach ($bookings as $row) {
            fputcsv($handle, [
                'BKG-' . $row->id,
                $row->user->name ?? 'Guest',
                $row->user->email ?? 'N/A',
                $row->total_price,
                $row->status,
                $row->created_at->format('Y-m-d')
            ]);
        }

        fclose($handle);
        exit;
    }
}