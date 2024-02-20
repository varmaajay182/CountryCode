<?php

namespace Countrycodevendor\Countrycode\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function store()
    {
        $filePath = 'D:\country.json';

        if (file_exists($filePath)) {

            $fileContent = file_get_contents($filePath);

            $data = json_decode($fileContent, true);
            // dd($data);

            if ($data !== null) {
                // Storage::put('public/countryData.json', json_encode($data));

                return response()->json([
                    'message' => 'Stored country data successfully',
                ]);

            } else {

                return response()->json([
                    'error' => 'Error decoding JSON.',
                ]);
            }
        } else {
            echo "File does not exist.";
            return response()->json([
                'error' => 'File does not exist.',
            ]);
        }

    }

    public function index()
    {
        $jsonFilePath = public_path('/storage/countryData.json');
        $jsonData = file_get_contents($jsonFilePath);

        $countriesData = json_decode($jsonData, true);
        $countryNames = array_column($countriesData, 'country');
        array_multisort($countryNames, SORT_ASC, $countriesData);

        // dd($countriesData);
        return view('countrycode::index', [
            'countryData' => $countriesData,
        ]);
    }

    public function checkValidation(Request $request)
    {
        try {
            $countryCode = $request['countrycode'];

            $jsonFilePath = public_path('/storage/countryData.json');
            $jsonData = file_get_contents($jsonFilePath);

            $countriesData = json_decode($jsonData, true);

            $matchingCountry = null;
            foreach ($countriesData as $country) {
                if ($country['countryCode'] == $countryCode) {
                    $matchingCountry = $country;
                    break;
                }
            }

            if ($matchingCountry) {
                $stringNumber = $request['number'];

                // dd($stringNumber);
                $integerNumber = (int) $stringNumber;
                $length = floor(log10($integerNumber) + 1);
                // dd($length);

                if ($length <= $matchingCountry['phoneNumberMaxLength'] && $length >= $matchingCountry['phoneNumberMinLength']) {
                    // return redirect('/country')->with('success', 'Submit successfully');
                    return response()->json(['success' => 'submit successfully']);
                } else {
                    // return redirect('/country')->with('error', 'invalid Number');
                    return response()->json(['error' => 'invalid Number']);
                }
            } else {

                return response()->json(['error' => 'Country code not found'], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
