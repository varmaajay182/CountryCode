<?php

namespace Countrycodevendor\Countrycode\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $language = ['Urdu', 'English', 'Russian', 'Gujarati', 'Bengali', 'Chinese', 'Hindi'];

            $languageMatched = false;

            for ($i = 0; $i < count($language); $i++) {
                if ($request->language == $language[$i]) {
                    App::setLocale($request->language);
                    $languageMatched = true;
                    break;
                }
            }

            if ($languageMatched) {
                return response()->json([
                    'country' => __('countrycode::lang.country'),
                    'countryName' => __('countrycode::lang.countryName'),
                    'input' => __('countrycode::lang.input'),
                    'language' => __('countrycode::lang.language'),
                ]);
            } else {
                return response()->json(['error' => 'This Language is not included']);
            }

        }
        // App::setLocale('Urdu');

        // echo trans('countrycode::lang.greeting');
        $jsonFilePath = public_path('vendor/countrycode/data/countryData.json');
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

            $jsonFilePath = public_path('vendor/countrycode/data/countryData.json');
            $jsonData = file_get_contents($jsonFilePath);

            $countriesData = json_decode($jsonData, true);

            $matchingCountry = null;
            foreach ($countriesData as $country) {
                if ($country['countryCode'] == $countryCode) {
                    $matchingCountry = $country;
                    break;
                }
            }
            App::setLocale($request->language);
            if ($matchingCountry) {
                $stringNumber = $request['number'];

                // dd($stringNumber);
                $integerNumber = (int) $stringNumber;
                $length = floor(log10($integerNumber) + 1);
                // dd($length);

                if ($length <= $matchingCountry['phoneNumberMaxLength'] && $length >= $matchingCountry['phoneNumberMinLength']) {
                    // return redirect('/country')->with('success', 'Submit successfully');
                    return response()->json(['success' => __('countrycode::lang.success')]);
                } else {
                    // return redirect('/country')->with('error', 'invalid Number');
                    return response()->json(['error' => __('countrycode::lang.error')]);
                }
            } else {

                return response()->json(['error' => 'Country code not found'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function selectlaguage(Request $request)
    {
        if ($request->ajax()) {

            //countries retrive data
            $countryCode = $request['countrycode'];
            $jsonFilePath = public_path('vendor/countrycode/data/countryData.json');
            $jsonData = file_get_contents($jsonFilePath);
            $countriesData = json_decode($jsonData, true);
            // Log::info('Received language data: ' . $countriesData);
            $matchingCountry = null;
            foreach ($countriesData as $country) {
                if ($country['countryCode'] == $countryCode) {
                    $matchingCountry = $country;
                    break;
                }
            }

            //laguage retrive data
            $jsonFilePathForLanguage = public_path('vendor/countrycode/data/language.json');
            if (file_exists($jsonFilePathForLanguage)) {
                $jsonDataForLanguage = file_get_contents($jsonFilePathForLanguage);
                $languagesData = json_decode($jsonDataForLanguage, true);
                // Log::info('Received language data: ' . json_encode($languagesData));
                // $languageDataencode = json_encode($languagesData);
                $matchingLanguage = null;
                foreach ($languagesData as $language) {
                    if ($language['country'] == $matchingCountry['country']) {
                        $matchingLanguage = $language;
                        break;
                    }
                }
                App::setLocale('en');
                // Log::info('Received country code: ' . $matchingLanguage);
                return response()->json([
                    'data' => $matchingLanguage,
                    'country' => __('countrycode::lang.country'),
                    'countryName' => __('countrycode::lang.countryName'),
                    'input' => __('countrycode::lang.input'),
                    'language' => __('countrycode::lang.language'),
                ]);
            } else {
                Log::error('Language file not found.');
                return response()->json(['error' => 'Language file not found.'], 500);
            }

        }
    }

}
