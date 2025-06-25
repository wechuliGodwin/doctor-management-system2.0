namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PosterInquiryController extends Controller
{
    public function sendInquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'poster_id' => 'required|integer|exists:research_day_registrations,id',
            'message' => 'required|string|max:1000',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'poster_id' => $request->poster_id,
            'message' => $request->message,
            'title' => \App\Models\ResearchDayRegistration::find($request->poster_id)->title ?? 'Unknown Poster',
        ];

        // Send email
        Mail::raw(
            "Inquiry from: {$data['name']} ({$data['email']})\n" .
            "Poster ID: {$data['poster_id']}\n" .
            "Poster Title: {$data['title']}\n" .
            "Message: {$data['message']}",
            function ($message) use ($data) {
                $message->to('ictmgr@kijabehospital.org')
                        ->cc('research@kijabehospital.org') // Second email (placeholder)
                        ->subject('Research Poster Inquiry');
                $message->from($data['email'], $data['name']);
            }
        );

        return redirect()->back()->with('success', 'Your inquiry has been sent successfully!');
    }
}