<?php

namespace LWS\ExportActions\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use LWS\ExportActions\Jobs\WritePdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exporter extends Controller
{
    public function download(Request $request)
    {
        if (DB::table('job_update')->where('id', $request->id)->exists()) {
            $file_download_progress = DB::table('job_update')->where('id', $request->id)->first();
            //dd($file_download_progress->file_name);
            return response()->download($file_download_progress->file_name);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function progress()
    {
        $data = DB::table('job_update')->get()->toJson();

        return response($data, 200);
    }

    public function export(Request $requestData)
    {
        $client = new Client();

        $request = new \GuzzleHttp\Psr7\Request('GET', $requestData->url);
        $promise = $client->sendAsync($request)->then(function ($responseData) {
            $formatter = Formatter::make($responseData->getBody(), Formatter::JSON);
            $csv = $formatter->toCsv();

            Storage::disk('local')->put('csvfile.csv', $csv);
        });

        $promise->wait();

        $file_name = storage_path().'/app/csvfile.csv';

        switch (strtolower($requestData->format)) {
            case 'csv': return $this->csv($file_name); break;
            case 'excel': return $this->excel($file_name); break;
            case 'pdf': return $this->pdf($file_name); break;
        } //switch
    }

    public function csv($file_name)
    {
        $headers = [
            'Content-Type' => 'text/csv',
         ];

        return response()->download($file_name, 'export.csv', $headers);
    }

    public function excel($file_name)
    {
        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

        /* Set CSV parsing options */
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);

        /* Load a CSV file and save as a XLS */
        $spreadsheet = $reader->load($file_name);
        $writer = new Xlsx($spreadsheet);

        $writer->save('export.xlsx');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

    public function pdf($file_name)
    {
        $csv = array_map('str_getcsv', file($file_name));

        $thead = array_shift($csv);

        $lastKey = array_search(end($thead), $thead);

        $html = '
        <html>
        </head>
        <style>
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
          }
          
          th {

            font-family : bold;

          }
        
        </style>
        </head>
        ';

        $html .= '<body><table><thead><tr>';

        for ($i = 0; $i < count($thead); $i++) {
            $html .= "<th bgcolor='#ccc'>$thead[$i]</th>";
        }

        if ($i >= count($thead)) {
            $html .= '</tr></thead>';
        }

        foreach ($csv as $tbody) {
            $html .= '<tr>';
            foreach ($tbody as $td) {
                $html .= "<td>$td</td>";
            }
            $html .= '</tr>';
        }

        $html .= '</table></body>';

        $id = DB::table('job_update')->insertGetId([
            'name' => 'PDF',
            'status' => 'processing',
            'file_name' => '0',
        ]);
        WritePdf::dispatch($html, $id);

        return response()->json(['message'=>'Preparing File For Download...'], 200);
    }
}
