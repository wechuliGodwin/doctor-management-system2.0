<?php

namespace App\Http\Controllers;

use App\Models\ResearchDayRegistration;
use App\Models\ResearchPaper;
use App\Models\Mortality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PublicationsController extends Controller
{
/**
     * Display a listing of research papers.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ResearchPaper::query();

        // Apply search on title, authors, or journal
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('authors', 'like', "%{$search}%")
                  ->orWhere('journal', 'like', "%{$search}%");
            });
        }

        // Optional filter by journal
        if ($request->filled('journal')) {
            $query->where('journal', $request->input('journal'));
        }

        $papers = $query->orderBy('date', 'desc')->paginate(10);

        $journals = ResearchPaper::select('journal')
            ->distinct()
            ->pluck('journal')
            ->sort()
            ->all();

        return view('education.research_papers', compact('papers', 'journals'));
    }

public function showInformationPack()
    {
        return view('education.information-pack');
    }

public function showResearchDayGallery()
    {
        $posters = ResearchDayRegistration::whereNotNull('poster_path')->get();
        return view('education.research-day-images', compact('posters'));
    }
public function showResearchDayPosters()
{
    $registeredPosters = ResearchDayRegistration::all();

    // Extract unique categories from the registered posters
    $categories = [];
    foreach ($registeredPosters as $poster) {
        if (is_array($poster->categories)) {
            foreach ($poster->categories as $category) {
                if (!in_array($category, $categories)) {
                    $categories[] = $category;
                }
            }
        }
    }

    // Get top presenters based on the number of posters submitted
    $topPresenters = ResearchDayRegistration::select('names')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('names')
        ->orderBy('count', 'desc')
        ->limit(10)
        ->get();

    // Prepare the presenters data to include the name and count
    $topPresenters = $topPresenters->map(function ($presenter) {
        return [
            'name' => $presenter->names,
            'count' => $presenter->count,
        ];
    });

    return view('education.view-research-day-posters', compact('registeredPosters', 'categories', 'topPresenters'));
}
    public function showResearchDayImages()
    {
        // Gallery view for images
        return view('education.research-day-images');
    }
    /**
     * Display the Research Day page.
     *
     * @return \Illuminate\View\View
     */
    public function showResearchDay()
    {
        $registeredPosters = ResearchDayRegistration::all();
        return view('education.research_day', compact('registeredPosters'));
    }

    /**
     * Display the Research Day registration form.
     *
     * @return \Illuminate\View\View
     */
    public function registerResearchDay()
    {
        return view('education.register-research-day');
    }

    /**
     * Handle Research Day registration submission.
     *
     * Files are stored in the "public/posters" folder.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitResearchDayRegistration(Request $request)
    {
        $validated = $request->validate([
            'names'            => 'required|string|max:255',
            'phone_numbers'    => 'required|string|max:255',
            'emails'           => 'required|string|max:255',
            'co_investigators' => 'required|string|max:255',
            'categories'       => 'required|array',
            'resubmission'     => 'nullable|boolean',
            'poster'           => 'required|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:20480',
            'title'            => 'required|string|max:255',
        ]);

        try {
            $file = $request->file('poster');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('posters', $fileName, 'public');

            $registration = ResearchDayRegistration::create([
                'title'            => $validated['title'],
                'names'            => $validated['names'],
                'phone_numbers'    => $validated['phone_numbers'],
                'emails'           => $validated['emails'],
                'co_investigators' => $validated['co_investigators'],
                'categories'       => $validated['categories'],
                'resubmission'     => $request->boolean('resubmission'),
                'poster_path'      => $path,
            ]);

            // Send thank-you emails to the submitters
            $researcherName = explode(',', $registration->names)[0] ?? 'Researcher';
            $submitterEmails = explode(',', $validated['emails']);

            foreach ($submitterEmails as $email) {
                try {
                    Mail::send('emails.research_day_thankyou', [
                        'researcherName' => $researcherName,
                        'registration'   => $registration
                    ], function ($message) use ($email, $path) {
                        $message->to(trim($email))
                                ->subject('Research Day Registration - Thank You')
                                ->from('systemdev@kijabehospital.org')
                                ->attach(Storage::disk('public')->path($path));
                    });
                    Log::info('Thank you email sent to submitter', ['email' => $email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send thank you email', ['email' => $email, 'error' => $e->getMessage()]);
                }
            }

            // Notify research coordinators
            try {
                Mail::send('emails.research_day_registration', [
                    'registration' => $registration
                ], function ($message) use ($path) {
                    $message->to('researchcoord@kijabehospital.org')
                            ->cc('researchcoord@kijabehospital.org')
                            ->bcc('ictmgr@kijabehospital.org')
                            ->subject('New Research Day Registration')
                            ->from('systemdev@kijabehospital.org')
                            ->attach(Storage::disk('public')->path($path));
                });
                Log::info('Registration notification email sent');
            } catch (\Exception $e) {
                Log::error('Failed to send registration notification email', ['error' => $e->getMessage()]);
            }

            return redirect()->route('register-research-day')->with('success', 'Registration submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Registration submission error', ['message' => $e->getMessage()]);
            return redirect()->route('register-research-day')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * View all Research Day registrations (for admins).
     *
     * @return \Illuminate\View\View
     */
    public function viewResearchDayRegistrations()
    {
        $registrations = ResearchDayRegistration::all();
        return view('education.view-research-day-registrations', compact('registrations'));
    }

    /**
     * Display the Research Day posters page.
     *
     * @return \Illuminate\View\View
     */
 public function viewResearchDayPosters()
{
    $registeredPosters = ResearchDayRegistration::all();

    // Extract unique categories from the registered posters
    $categories = [];
    foreach ($registeredPosters as $poster) {
        if (is_array($poster->categories)) {
            foreach ($poster->categories as $category) {
                if (!in_array($category, $categories)) {
                    $categories[] = $category;
                }
            }
        }
    }

    // Get top presenters based on the number of posters submitted
    $topPresenters = ResearchDayRegistration::select('names')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('names')
        ->orderBy('count', 'desc')
        ->limit(10)
        ->get();

    // Prepare the presenters data to include the name and count
    $topPresenters = $topPresenters->map(function ($presenter) {
        return [
            'name' => $presenter->names,
            'count' => $presenter->count,
        ];
    });

    return view('education.view-research-day-posters', compact('registeredPosters', 'categories', 'topPresenters'));
}


    /**
     * View a specific poster.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewPoster($id)
    {
        $registration = ResearchDayRegistration::findOrFail($id);
        $filePath = Storage::disk('public')->path($registration->poster_path);

        if (!file_exists($filePath)) {
            abort(404, 'Poster file not found.');
        }

        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Download a specific poster.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPoster($id)
    {
        $registration = ResearchDayRegistration::findOrFail($id);
        $filePath = Storage::disk('public')->path($registration->poster_path);

        if (!file_exists($filePath)) {
            abort(404, 'Poster file not found.');
        }

        $mimeType = mime_content_type($filePath);

        return response()->download($filePath, basename($filePath), [
            'Content-Type' => $mimeType
        ]);
    }

    /**
     * Handle poster upload via standard form.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPoster(Request $request)
    {
        $request->validate([
            'poster' => 'required|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:20480',
        ]);

        $file = $request->file('poster');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('posters', $fileName, 'public');

        return response()->json(['success' => true, 'path' => $path]);
    }

    /**
     * Handle chunked file upload.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleChunkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'chunk' => 'required|integer',
            'chunks' => 'required|integer',
            'name' => 'required|string',
        ]);

        $file = $request->file('file');
        $chunk = $request->input('chunk');
        $chunks = $request->input('chunks');
        $fileName = $request->input('name');
        $tempPath = storage_path('app/temp/' . $fileName . '.part' . $chunk);

        $file->move(storage_path('app/temp'), $fileName . '.part' . $chunk);

        if ($chunk == $chunks - 1) {
            $finalPath = storage_path('app/public/posters/' . time() . '_' . $fileName);
            $out = fopen($finalPath, 'wb');
            for ($i = 0; $i < $chunks; $i++) {
                $partPath = storage_path('app/temp/' . $fileName . '.part' . $i);
                $in = fopen($partPath, 'rb');
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
                unlink($partPath);
            }
            fclose($out);

            $relativePath = 'posters/' . time() . '_' . $fileName;
            return response()->json(['success' => true, 'path' => $relativePath]);
        }

        return response()->json(['success' => true, 'message' => 'Chunk uploaded']);
    }

    /**
     * Handle Dropzone upload.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleDropzoneUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:20480',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('posters', $fileName, 'public');

        return response()->json(['success' => true, 'path' => $path]);
    }
}
