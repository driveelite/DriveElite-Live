<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; 

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
        $oldStatus = $booking->status;
        
        $booking->update(['status' => $request->status]);

        $emailStatusMsg = ""; 

        if ($oldStatus !== $request->status) {
            if (in_array($request->status, ['Approved', 'Completed', 'Cancelled'])) {
                try {
                    // 🔥 THE ULTIMATE VIVA HACK: Force Bypass .env and Cache
                    // Ye code zabardasti system ko Gmail par shift kar dega
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => 'smtp.gmail.com',
                        'mail.mailers.smtp.port' => 465,
                        'mail.mailers.smtp.encryption' => 'ssl',
                        'mail.mailers.smtp.username' => 'driveelite099@gmail.com',
                        'mail.mailers.smtp.password' => 'dljdaciftcopwhrv',
                        'mail.from.address' => 'driveelite099@gmail.com',
                        'mail.from.name' => 'Drive Elite Admin',
                    ]);

                    $userEmail = $booking->user->email ?? 'driveelite099@gmail.com';
                    
                    // Thora VIP design wali email
                    \Illuminate\Support\Facades\Mail::html(
                        "<div style='font-family: Arial, sans-serif; padding: 25px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #f97316; margin-bottom: 20px;'>Drive Elite Rentals</h2>
                            <p style='color: #334155; font-size: 16px;'>Dear Customer,</p>
                            <p style='color: #334155; font-size: 16px;'>Your reservation status has been successfully updated to: <strong style='color: #1e3a8a; font-size: 18px;'>" . ucfirst($request->status) . "</strong>.</p>
                            <p style='color: #64748b; font-size: 14px; margin-top: 30px; border-top: 1px solid #cbd5e1; padding-top: 15px;'>Thank you for choosing Drive Elite. Have a safe journey!</p>
                         </div>", 
                        function ($message) use ($userEmail) {
                            $message->to($userEmail)
                                    ->subject('Drive Elite - Booking Status Updated');
                        }
                    );

                    $emailStatusMsg = " & Email Sent Successfully to " . $userEmail;

                } catch (\Exception $e) {
                    $emailStatusMsg = " BUT Email Failed: " . $e->getMessage();
                    \Log::error("Mail sending failed: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Reservation status updated!' . $emailStatusMsg);
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