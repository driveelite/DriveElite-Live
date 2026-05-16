<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; 

class BookingController extends Controller
{
    /**
     * Display a listing of all reservations.
     */
    public function index()
    {
        // 'user' aur 'car' relationship load karna performance ke liye behtar hai
        $bookings = Booking::with(['car', 'user'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Update reservation status and notify customer via email.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|string']);
        $oldStatus = $booking->status;
        
        $booking->update(['status' => $request->status]);

        $emailStatusMsg = ""; // Default message

        // 🌟 VIP Dynamic Email Logic (Smart Error Catcher)
        if ($oldStatus !== $request->status) {
            // Sirf tab email bhejni hai jab status Pending se Approved, Completed, ya Cancelled par aaye
            if (in_array($request->status, ['Approved', 'Completed', 'Cancelled'])) {
                try {
                    // 🔥 THE ULTIMATE VIVA HACK: Force Bypass .env and Cache
                    // Ye code zabardasti system ko Gmail (Port 587, TLS) par shift kar dega
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => 'smtp.gmail.com',
                        'mail.mailers.smtp.port' => 587,
                        'mail.mailers.smtp.encryption' => 'tls',
                        'mail.mailers.smtp.username' => 'driveelite099@gmail.com',
                        'mail.mailers.smtp.password' => 'dljdaciftcopwhrv',
                        'mail.from.address' => 'driveelite099@gmail.com',
                        'mail.from.name' => 'Drive Elite Admin',
                    ]);

                    // Paka Tareeqa: Agar booking wale ki email na miley, toh admin ko bhej de
                    $userEmail = $booking->user->email ?? 'driveelite099@gmail.com';
                    
                    \Illuminate\Support\Facades\Mail::html(
                        "<h2>Drive Elite Rentals</h2>
                         <p>Dear Customer,</p>
                         <p>Your reservation status has been successfully updated to: <strong>" . ucfirst($request->status) . "</strong>.</p>
                         <p>Thank you for choosing Drive Elite. Have a safe journey!</p>", 
                        function ($message) use ($userEmail) {
                            $message->to($userEmail)
                                    ->subject('Booking Status Updated - Drive Elite');
                        }
                    );

                    // Agar email chali gayi toh success message
                    $emailStatusMsg = " & Email Sent Successfully to " . $userEmail;

                } catch (\Exception $e) {
                    // Agar fail hua toh screen par batayega kyun fail hua, par crash nahi hoga!
                    $emailStatusMsg = " BUT Email Failed: " . $e->getMessage();
                    \Log::error("Mail sending failed: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Reservation status updated!' . $emailStatusMsg);
    }

    /**
     * Generate and download the PDF invoice for a booking.
     */
    public function invoice(Booking $booking)
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        // PDF view ko data pass kiya
        $pdf = Pdf::loadView('admin.bookings.invoice', compact('booking', 'settings'));
        
        return $pdf->download('DriveElite-Invoice-00' . $booking->id . '.pdf');
    }

    /**
     * Delete a financial/reservation record securely.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Record deleted securely!');
    }

    /**
     * 🤖 Financial Dashboard Logic: Calculates revenue and pending amounts.
     */
    public function payments()
    {
        // 1. Total Revenue: Sirf 'Completed' bookings ka sum
        $totalRevenue = Booking::where('status', 'Completed')->sum('total_price');

        // 2. Pending Clearance: 'Pending' aur 'Approved' dono shamil hain
        $pendingClearance = Booking::whereIn('status', ['Pending', 'Approved'])->sum('total_price');

        // 3. Completed Transactions Count
        $completedTransactions = Booking::where('status', 'Completed')->count();

        // 4. All Transactions: With actual user and car data from frontend
        $recentTransactions = Booking::with(['car', 'user'])->latest()->get();

        return view('admin.payments', compact(
            'totalRevenue', 
            'pendingClearance', 
            'completedTransactions', 
            'recentTransactions'
        ));
    }

    /**
     * 🚀 NEW: Export to CSV (Excel Compatible Ledger).
     */
    public function exportLeads()
    {
        $bookings = Booking::with('user')->get();
        $filename = "DriveElite_Financial_Ledger_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // CSV Headers: Ref ID, Customer Name, Email, Amount, Status, Date
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