<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DiseaseController extends Controller
{
    // ... (keep index method the same)

    /**
     * Display a listing of the diseases with search, letter filter, and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $letter = $request->input('letter');

        // Initialize the query builder
        $diseasesQuery = Disease::orderBy('name');

        // Apply letter filter if present
        if ($letter) {
            $diseasesQuery->where('name', 'LIKE', "{$letter}%");
        }

        // Apply search filter if present
        if ($query) {
            $query = trim($query);
            $diseasesQuery->where('name', 'LIKE', "%{$query}%");
        }

        // Paginate the results (15 diseases per page)
        $diseases = $diseasesQuery->paginate(15)->withQueryString();

        // Generate an array of alphabet letters (A-Z)
        $alphabet = range('A', 'Z');

        return view('diseases.index', compact('diseases', 'query', 'letter', 'alphabet'));
    }


    /**
     * Display the specified disease along with related diseases based on symptoms and causes.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $disease = Disease::findOrFail($id);

        // Process text fields with enhanced formatting
        $disease->description = $this->formatParagraphs($disease->description ?? '', 'description');
        $disease->causes = $this->formatParagraphs($disease->causes ?? '', 'causes');
        $disease->cure = $this->formatParagraphs($disease->cure ?? '', 'cure');
        $disease->treatment = $this->formatParagraphs($disease->treatment ?? '', 'treatment');
        $disease->when_to_see_doctor = $this->formatParagraphs($disease->when_to_see_doctor ?? '', 'when_to_see_doctor');

        // ... (keep related diseases logic the same)

        // Initialize the related diseases query
        $relatedDiseasesQuery = Disease::where('id', '!=', $disease->id);

        // Apply conditions for overlapping symptoms
        if (!empty($currentSymptoms)) {
            $relatedDiseasesQuery->where(function ($query) use ($currentSymptoms) {
                foreach ($currentSymptoms as $symptom) {
                    $query->orWhere('symptoms', 'LIKE', "%{$symptom}%");
                }
            });
        }

        // Apply conditions for overlapping causes
        if (!empty($currentCauses)) {
            $relatedDiseasesQuery->where(function ($query) use ($currentCauses) {
                foreach ($currentCauses as $cause) {
                    $query->orWhere('causes', 'LIKE', "%{$cause}%");
                }
            });
        }

        // Fetch related diseases with a limit of 6
        $relatedDiseases = $relatedDiseasesQuery->inRandomOrder()->take(6)->get();

        return view('diseases.show', compact('disease', 'relatedDiseases'));
    }

    /**
     * Enhanced formatting method with medical content patterns
     *
     * @param string $text
     * @param string $field
     * @return string
     */
    protected function formatParagraphs(string $text = '', string $field = ''): string
    {
        $text = trim($text);
        if (empty($text)) return '';

        $paragraphs = preg_split('/\n\n+/', $text);
        $formattedText = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            
            // Field-specific formatting
            switch($field) {
                case 'causes':
                    if (preg_match('/^(The exact cause of|While the precise cause of|Primary causes? of) (.+?) (is|are)/i', $paragraph)) {
                        $formattedText .= "<h3 class='section-subtitle'>Cause</h3>";
                        $paragraph = preg_replace('/^(The exact cause of|While the precise cause of|Primary causes? of) (.+?) (is|are)/i', '', $paragraph);
                    }
                    break;

                case 'treatment':
                    if (preg_match('/^(Common treatments? include|Standard treatment options are)/i', $paragraph)) {
                        $formattedText .= "<h3 class='section-subtitle'>Treatment Options</h3>";
                    }
                    break;
            }

            // General medical content patterns
            if (preg_match('/^(Risk factors include|Potential risks? are)/i', $paragraph, $matches)) {
                $formattedText .= "<h3 class='section-subtitle'>Risk Factors</h3>";
                $paragraph = preg_replace('/^(Risk factors include|Potential risks? are)/i', '', $paragraph);
            }

            if (preg_match('/^(Common symptoms include|Typical manifestations are)/i', $paragraph)) {
                $formattedText .= "<h3 class='section-subtitle'>Symptoms</h3>";
                $paragraph = preg_replace('/^(Common symptoms include|Typical manifestations are)/i', '', $paragraph);
            }

            // Existing subtitle formatting
            if (preg_match('/^###\s+(.*)/', $paragraph, $matches)) {
                $formattedText .= "<h3 class='section-subtitle'>" . htmlspecialchars($matches[1]) . "</h3>";
                $paragraph = str_replace($matches[0], '', $paragraph);
            }

            // Add paragraph if content remains
            if (!empty(trim($paragraph))) {
                $formattedText .= "<p class='content-paragraph'>" . nl2br(htmlspecialchars(trim($paragraph))) . "</p>";
            }
        }

        return $formattedText;
    }
}
