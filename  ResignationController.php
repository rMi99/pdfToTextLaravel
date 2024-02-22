<?php
// source
// https://www.mywebtuts.com/blog/laravel-add-text-to-pdf-file-example#:~:text=Sometimes%2C%20we%20need%20to%20edit,and%20setasign%2Ffpdi%20composer%20packages.
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi; //package

class ResignationController extends Controller
{
    public function index()
    {
        return view('resignation.index');
    }

    /**
     * Download the modified PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        $templatePath = public_path("pdf/LeavingCertificate.pdf");
        $outputFilePath = public_path("pdf/filled_template.pdf");

        $variable1 = "22";
        $variable2 = "ddfvdfdf";
        $variable3 = "sadsadsad";

        $texts = [
            "Mywebtuts.com",
            "Variable 1: " . $variable1,
            "Variable 2: " . $variable2,
            "Variable 3: " . $variable3
        ];

        $this->fillPDFFile($templatePath, $outputFilePath, $texts);

        return response()->file($outputFilePath);
    }

    /**
     * Fill the PDF file with dynamic text.
     *
     * @param  string  $file
     * @param  string  $outputFilePath
     * @param  array   $texts
     * @return void
     */
    private function fillPDFFile($file, $outputFilePath, $texts)
    {
        $fpdi = new Fpdi;

        $count = $fpdi->setSourceFile($file);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(1, 106, 254);

            $left = 10;
            $top = 10;

            // Loop through $texts array and add each text with corresponding variable
            foreach ($texts as $text) {
                $fpdi->Text($left, $top, $text);
                $top += 30;

            }
        }

        $fpdi->Output($outputFilePath, 'F');
    }
}
