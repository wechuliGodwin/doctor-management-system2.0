<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class PrescriptionController extends Controller
{
    public function savePrescription(Request $request)
    {
	    // Debugging: Log or dump the incoming data
	         \Log::info($request->all());


        // Validate the data
        $validated = $request->validate([
            'appointment_id' => 'required|integer',
            'prescription' => 'required|string',
            'dosage' => 'required|string',
            'quantity' => 'required|string',
            'duration' => 'required|string',
	]);



        // Find the appointment and save the prescription
        $appointment = Appointment::findOrFail($validated['appointment_id']);
        $appointment->prescribe = $validated['prescription']; // Fixed typo: "prescribe" instead of "prescibe"
        $appointment->dosage = $validated['dosage'];
        $appointment->quantity = $validated['quantity'];
        $appointment->duration = $validated['duration'];
        $appointment->save();


        // Return a success message or redirect back
        return redirect()->back()
            ->with('feedbackMessage', 'Prescription saved successfully.')
	    ->with('feedbackType', 'success');

    }


    public function downloadPrescription($appointment_id)
    {
        // Is the user authenticated?
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // Find the appointment and check authorization
        $appointment = Appointment::findOrFail($appointment_id);

        // Is the authenticated user the patient of the appointment. Fixes BOLA
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this prescription.');
        }
        $phpWord = new PhpWord();
        $password = bin2hex(random_bytes(8));

        $phpWord->getSettings()->getDocumentProtection()->setEditing(\PhpOffice\PhpWord\SimpleType\DocProtect::READ_ONLY, $password);
        $section = $phpWord->addSection();

        // Add image to fit 100% of the page width
        $letterheadPath = public_path('images/logo_2.png');

        // Use 100% width of page with margin adjustments
        $section->addImage(
            $letterheadPath,
            [
                'width' => 150,
                'height' => null,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,  // Center image
            ]
        );
        $section->addTextBreak();
        $section->addText("Kijabe", ['size' => 12]);
        $section->addText("Off Nairobi-Nakuru Highway", ['name' => 'Times New Roman','size' => 12, 'lineHeight' => 1]);
        $section->addText("P.O Box 20 - 00220 Kijabe Kenya", ['name' => 'Times New Roman', 'size' => 12, 'lineHeight' => 1]);
        $section->addText("Tel: (254) 0709728200, 0787145122", ['name' => 'Times New Roman', 'size' => 12, 'lineHeight' => 1]);
        $section->addText("info@kijabehospital.org/ feedback@kijabehospital.org", ['name' => 'Times New Roman', 'size' => 12, 'lineHeight' => 1]);
        $section->addText(" _________________________________", ['size' => 24, 'name' => 'Arial', 'lineHeight' => 1, 'color' => '159ED5'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START]);

        // Title styling
        $section->addText("Prescription Medication", ['name' => 'Arial', 'size' => 16, 'bold' => true, 'color' => '159ED5'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak();
        $section->addTextBreak();

        // Add a table for patient details
        $patientTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'alignment' => 'left']);

        $patientTable->addRow();
        $patientTable->addCell(2000)->addText(" Patient No", ['bold' => true, 'size' => 12]);
        $patientTable->addCell(6000)->addText("{$appointment->hmis_patient_number}", ['size' => 12]);

        $patientTable->addRow();
        $patientTable->addCell(2000)->addText(" Patient Name", ['bold' => true, 'size' => 12]);
        $patientTable->addCell(6000)->addText("{$appointment->patient_name}", ['size' => 12]);

        $section->addTextBreak();

        // Diagnosis...edit I'm commenting this out for now...damn copilot autocompletes everything
        //$section->addText("Diagnosis: {$appointment->diagnosis}", ['bold' => true, 'size' => 12]);
        //$section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();

        // Add a table for prescription details
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'alignment' => 'left']);

        // Table header with background color
        $table->addRow();  // Row height
        $table->addCell(2000, ['bgColor' => 'D9EAD3'])->addText("Description", ['bold' => true, 'size' => 12]);
        $table->addCell(2000, ['bgColor' => 'D9EAD3'])->addText("Dosage", ['bold' => true, 'size' => 12]);
        $table->addCell(2000, ['bgColor' => 'D9EAD3'])->addText("Frequency", ['bold' => true, 'size' => 12]);
        $table->addCell(2000, ['bgColor' => 'D9EAD3'])->addText("Duration", ['bold' => true, 'size' => 12]);

        // Add a row for the prescribed medication
        $table->addRow();
        $table->addCell(2000)->addText(" {$appointment->prescribe}", ['size' => 12]);
        $table->addCell(2000)->addText(" {$appointment->dosage}", ['size' => 12]);
        $table->addCell(2000)->addText(" {$appointment->quantity}", ['size' => 12]);
        $table->addCell(2000)->addText(" {$appointment->duration}", ['size' => 12]);

        // Add special instructions
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addTextBreak();

        // Refills and doctor's signature
        $section->addText("Doctor's Signature: ____________________________", ['size' => 12]);
        $section->addText("Date: {$appointment->appointment_date}", ['size' => 12]);

        // Add an icon image (e.g., for medication, a simple checkmark or pill icon)
        //$iconPath = public_path('images/logo1.jpeg'); // Adjust path to your icon image
        //if (file_exists($iconPath)) {
        //    $section->addImage(
        //        $iconPath,
        //        [
        //            'width' => 400,
        //            'height' => null,
        //            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        //        ]
        //    );
        //}

        // Save the document as a Word file
        $filename = "Prescription_{$appointment_id}.docx";
        $filePath = storage_path($filename);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);

        // Return the Word file as a download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

}


