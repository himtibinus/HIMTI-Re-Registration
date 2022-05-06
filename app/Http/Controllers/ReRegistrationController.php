<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ReRegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Section =
            DB::connection('mysql2')->select("select * FROM quartilinformation WHERE IsActive = '1'");
        if ($Section != null) {
            $QuartilActive = $Section[0];
        } else {
            $QuartilActive = [];
        }
        return view('home', ['QuartilActive' => $QuartilActive]);
    }
    public function CheckSection($Year, $Quartil)
    {
        $Section =
            DB::connection('mysql2')->select("select Distinct SectionID, SectionName, SectionDescription, b.`Order`, NeedValidation  from questionsection AS a JOIN sectioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1' ORDER BY b.`Order`");
        return $Section;
    }
    public function reregistGenerate($Year, $Quartil)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $CheckSection = $this->CheckSection($Year, $Quartil);
        // $information = array();
        $information['Year'] = $Year;
        $information['Quartil'] = $Quartil;
        $information['Page'] = $CheckSection[0]->SectionID;
        $PrevButton = 0;
        if ($Quartil > 1) {

            $PrevQuartil = $Quartil - 1;

            $History =
                DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE Year = '" . $Year . "' AND Quartil = '" . $PrevQuartil . "' AND QuestionId = '34'");
            if ($History != null) {
                $PrevButton = 1;
            }
        }
        $user = Auth::user();
        $Section =
            DB::connection('mysql2')->select("select Distinct SectionID, SectionName, SectionDescription, NeedValidation, CanGetPrev  from questionsection AS a JOIN sectioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1' AND a.SectionID = '1'");
        $QustionInformation =
            DB::connection('mysql2')->select("select *, a.QuestionID AS QuestionID from questionsection AS a JOIN questioninformation AS b ON a.QuestionID = b.ID 
            LEFT JOIN datainformation AS c ON c.Year = a.Year AND c.Quartil = a.Quartil AND UserID = $user->id AND c.QuestionID = a.QuestionID
            WHERE a.Year = '" . $Year . "' AND a.Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1'AND a.SectionID = '1' ORDER BY `Order`");
        $CustomChoice =
            DB::connection('mysql2')->select("select * from customchoise WHERE IsActive = '1' ORDER BY `Order`");
        foreach ($QustionInformation as $QuestionInformationEach) {
            $CustomName = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $QuestionInformationEach->QuestionText);
            $QuestionInformationEach->CustomName = preg_replace('/\s+/', '_', $CustomName);
        }
        if ($Section[0]->NeedValidation == 2) {
            $information['LastPage'] = 1;
        } else {
            $information['LastPage'] = 0;
        }
        return view('CollectingData/Registration', ['Section' => $Section[0], 'QuestionInformation' => $QustionInformation, 'QuestionChoice' => $CustomChoice, 'Information' => $information, 'PrevButton' => $PrevButton]);
    }
    public function SubmitAnswer(Request $request, $Year, $Quartil)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $CheckSection = $this->CheckSection($Year, $Quartil);
        $Counting = 0;
        foreach ($CheckSection as $CheckSectionEach) {
            if ($CheckSectionEach->SectionID == $request['formid']) {
                break;
            }
            $Counting++;
        }
        $user = Auth::user();
        $QuestionInformation =
            DB::connection('mysql2')->select("select *, a.QuestionID AS QuestionID from questionsection AS a JOIN questioninformation AS b ON a.QuestionID = b.ID 
            LEFT JOIN datainformation AS c ON c.Year = a.Year AND c.Quartil = a.Quartil AND UserID = $user->id AND c.QuestionID = a.QuestionID
            WHERE a.Year = '" . $Year . "' AND a.Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1'AND a.SectionID = '" . $request['formid'] . "' ORDER BY `Order`");
        if ($request['TypeForm'] == "GetHistory") {
            $Page = $CheckSection[$Counting]->SectionID;
            $information['Year'] = $Year;
            $information['Quartil'] = $Quartil;
            $information['Page'] = $Page;
            $PrevQuartil = $Quartil - 1;
            $PrevButton = 0;
            $user = Auth::user();
            $Section =
                DB::connection('mysql2')->select("select Distinct SectionID, SectionName, SectionDescription, NeedValidation, CanGetPrev  from questionsection AS a JOIN sectioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $PrevQuartil . "' AND a.IsActive = '1' AND b.IsActive = '1' AND a.SectionID = '" . $Page . "'");
            $QustionInformation =
                DB::connection('mysql2')->select("select *, a.QuestionID AS QuestionID from questionsection AS a JOIN questioninformation AS b ON a.QuestionID = b.ID 
            LEFT JOIN datainformation AS c ON c.Year = a.Year AND c.Quartil = a.Quartil AND UserID = $user->id AND c.QuestionID = a.QuestionID
            WHERE a.Year = '" . $Year . "' AND a.Quartil = '" . $PrevQuartil . "' AND a.IsActive = '1' AND b.IsActive = '1'AND a.SectionID = '" . $Page . "' ORDER BY `Order`");
            $CustomChoice =
                DB::connection('mysql2')->select("select * from customchoise WHERE IsActive = '1' ORDER BY `Order`");
            foreach ($QustionInformation as $QuestionInformationEach) {
                $CustomName = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $QuestionInformationEach->QuestionText);
                $QuestionInformationEach->CustomName = preg_replace('/\s+/', '_', $CustomName);
            }
            if ($Section[0]->NeedValidation == 2) {
                $information['LastPage'] = 1;
            } else {
                $information['LastPage'] = 0;
            }
            return view('CollectingData/Registration', ['Section' => $Section[0], 'QuestionInformation' => $QustionInformation, 'QuestionChoice' => $CustomChoice, 'Information' => $information, 'PrevButton' => $PrevButton]);
        }
        if ($request['TypeForm'] == "Submit") {
            $CheckInformation =
                DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '34'");
            if ($CheckInformation == null) {
                DB::connection('mysql2')->insert("INSERT INTO `datainformation`(`UserID`, `Year`, `Quartil`, `QuestionId`, `AnswerText`) VALUES ('" . $user->id . "','" . $Year . "','" . $Quartil . "','34','Complete')");
            } else {
                DB::connection('mysql2')->update("UPDATE `datainformation` SET AnswerText = 'Complete' WHERE UserID ='" . $user->id . "' AND Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'AND QuestionId = '34'AND ID = '" . $CheckInformation[0]->ID . "'");
            }
            return redirect('./home');
        }
        foreach ($QuestionInformation as $QuestionInformationEach) {
            $CheckInformation =
                DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '" . $QuestionInformationEach->QuestionID . "'");
            $CustomName = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $QuestionInformationEach->QuestionText);
            $Name = preg_replace('/\s+/', '_', $CustomName);
            if (($request[$Name] != null && $request[$Name] != "") || $QuestionInformationEach->QuestionType == 5) {
                if ($CheckInformation == null) {

                    if ($QuestionInformationEach->QuestionType == 4) {
                        DB::connection('mysql2')->insert("INSERT INTO `datainformation`(`UserID`, `Year`, `Quartil`, `QuestionId`, `AnswerDate`) VALUES ('" . $user->id . "','" . $Year . "','" . $Quartil . "','" . $QuestionInformationEach->QuestionID . "','" . $request[$Name] . "')");
                    } else if ($QuestionInformationEach->QuestionType == 5) {
                        if ($request->hasFile($Name)) {
                            $filenameWithExt = $request->file($Name)->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension = $request->file($Name)->getClientOriginalExtension();
                            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                            $path = $request->file($Name)->storeAs('public/files', $fileNameToStore);
                            DB::connection('mysql2')->insert("INSERT INTO `datainformation`(`UserID`, `Year`, `Quartil`, `QuestionId`, `AnswerFileName`) VALUES ('" . $user->id . "','" . $Year . "','" . $Quartil . "','" . $QuestionInformationEach->QuestionID . "','" . $fileNameToStore . "')");
                        } else {
                            DB::connection('mysql2')->insert("INSERT INTO `datainformation`(`UserID`, `Year`, `Quartil`, `QuestionId`, `AnswerFileName`) VALUES ('" . $user->id . "','" . $Year . "','" . $Quartil . "','" . $QuestionInformationEach->QuestionID . "','" . $request[$Name] . "')");
                        }
                    } else if ($QuestionInformationEach->QuestionType != 6 && $QuestionInformationEach->QuestionType != 7) {
                        DB::connection('mysql2')->insert("INSERT INTO `datainformation`(`UserID`, `Year`, `Quartil`, `QuestionId`, `AnswerText`) VALUES ('" . $user->id . "','" . $Year . "','" . $Quartil . "','" . $QuestionInformationEach->QuestionID . "','" . $request[$Name] . "')");
                    }
                } else {
                    if ($QuestionInformationEach->QuestionType == 4) {
                        DB::connection('mysql2')->update("UPDATE `datainformation` SET AnswerDate = '" . $request[$Name] . "' WHERE UserID ='" . $user->id . "' AND Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'AND QuestionId = '" . $QuestionInformationEach->QuestionID . "'AND ID = '" . $CheckInformation[0]->ID . "'");
                    } else if ($QuestionInformationEach->QuestionType == 5) {
                        if ($request->hasFile($Name)) {
                            $filenameWithExt = $request->file($Name)->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension = $request->file($Name)->getClientOriginalExtension();
                            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                            $path = $request->file($Name)->storeAs('public/files', $fileNameToStore);
                            DB::connection('mysql2')->update("UPDATE `datainformation` SET AnswerFileName = '" . $fileNameToStore . "' WHERE UserID ='" . $user->id . "' AND Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'AND QuestionId = '" . $QuestionInformationEach->QuestionID . "' AND ID = '" . $CheckInformation[0]->ID . "'");
                        } else {
                            DB::connection('mysql2')->update("UPDATE `datainformation` SET AnswerFileName = '" . $request[$Name] . "' WHERE UserID ='" . $user->id . "' AND Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'AND QuestionId = '" . $QuestionInformationEach->QuestionID . "'AND ID = '" . $CheckInformation[0]->ID . "'");
                        }
                    } else if ($QuestionInformationEach->QuestionType != 6 && $QuestionInformationEach->QuestionType != 7) {
                        DB::connection('mysql2')->update("UPDATE `datainformation` SET AnswerText = '" . $request[$Name] . "' WHERE UserID ='" . $user->id . "' AND Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'AND QuestionId = '" . $QuestionInformationEach->QuestionID . "' AND ID = '" . $CheckInformation[0]->ID . "'");
                    }
                }
            }
        }
        if ($request['TypeForm'] == "Next") {
            while (true) {
                if ($CheckSection[$Counting + 1]->NeedValidation == 1) {
                    if ($CheckSection[$Counting + 1]->SectionID == 3) {
                        $Section3 = DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '12'");
                        if ($Section3[0]->AnswerText == "Dewan Pengurus Inti" || $Section3[0]->AnswerText == "Pengurus") {
                            $Page = $CheckSection[$Counting + 1]->SectionID;
                            break;
                        } else {
                            $Counting++;
                        }
                    } else if ($CheckSection[$Counting + 1]->SectionID == 6) {
                        $Section3 = DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '20'");
                        if ($Section3[0]->AnswerText == "Pernah (Yes)") {
                            $Page = $CheckSection[$Counting + 1]->SectionID;
                            break;
                        } else {
                            $Counting++;
                        }
                    }
                } else {
                    $Page = $CheckSection[$Counting + 1]->SectionID;
                    break;
                }
            }
        } else if ($request['TypeForm'] == "Prev") {
            while (true) {
                if ($CheckSection[$Counting - 1]->NeedValidation == 1) {
                    if ($CheckSection[$Counting - 1]->SectionID == 3) {
                        $Section3 = DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '12'");
                        if ($Section3[0]->AnswerText == "Dewan Pengurus Inti" || $Section3[0]->AnswerText == "Pengurus") {
                            $Page = $CheckSection[$Counting - 1]->SectionID;
                            break;
                        } else {
                            $Counting--;
                        }
                    } else if ($CheckSection[$Counting - 1]->SectionID == 6) {
                        $Section3 = DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE UserID = '" . $user->id . "'AND Year = '" . $Year . "'AND Quartil ='" . $Quartil . "'AND QuestionId = '20'");
                        if ($Section3[0]->AnswerText == "Pernah (Yes)") {
                            $Page = $CheckSection[$Counting - 1]->SectionID;
                            break;
                        } else {
                            $Counting--;
                        }
                    }
                } else {
                    $Page = $CheckSection[$Counting - 1]->SectionID;
                    break;
                }
            }
            $Page = $CheckSection[$Counting - 1]->SectionID;
        } else {
            return back();
        }
        $PrevButton = 0;
        if ($Quartil > 1) {
            $PrevQuartil = $Quartil - 1;
            $History =
                DB::connection('mysql2')->select("SELECT * FROM datainformation WHERE Year = '" . $Year . "' AND Quartil = '" . $PrevQuartil . "' AND QuestionId = '34'");
            if ($History != null) {
                $PrevButton = 1;
            }
        }
        // $information = array();
        $information['Year'] = $Year;
        $information['Quartil'] = $Quartil;
        $information['Page'] = $Page;
        $user = Auth::user();
        $Section =
            DB::connection('mysql2')->select("select Distinct SectionID, SectionName, SectionDescription, NeedValidation, CanGetPrev  from questionsection AS a JOIN sectioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1' AND a.SectionID = '" . $Page . "'");
        $QustionInformation =
            DB::connection('mysql2')->select("select *, a.QuestionID AS QuestionID from questionsection AS a JOIN questioninformation AS b ON a.QuestionID = b.ID 
            LEFT JOIN datainformation AS c ON c.Year = a.Year AND c.Quartil = a.Quartil AND UserID = $user->id AND c.QuestionID = a.QuestionID
            WHERE a.Year = '" . $Year . "' AND a.Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1'AND a.SectionID = '" . $Page . "' ORDER BY `Order`");
        $CustomChoice =
            DB::connection('mysql2')->select("select * from customchoise WHERE IsActive = '1' ORDER BY `Order`");
        foreach ($QustionInformation as $QuestionInformationEach) {
            $CustomName = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $QuestionInformationEach->QuestionText);
            $QuestionInformationEach->CustomName = preg_replace('/\s+/', '_', $CustomName);
        }
        if ($Section[0]->NeedValidation  == 2) {
            $information['LastPage'] = 1;
        } else {
            $information['LastPage'] = 0;
        }
        return view('CollectingData/Registration', ['Section' => $Section[0], 'QuestionInformation' => $QustionInformation, 'QuestionChoice' => $CustomChoice, 'Information' => $information, 'PrevButton' => $PrevButton]);
    }
    function getFile($FilePath)
    {
        $fullPath = "public/files/" . $FilePath;
        return Storage::download($fullPath);
    }
    public function Admin($Year)
    {
        $Section =
            DB::connection('mysql2')->select("select * from quartilinformation WHERE Year = '" . $Year . "'");
        return view('Admin/Admin', ['Section' => $Section]);
    }
    public function EditData($Year, $Quartil)
    {
        $QuartilInformation =
            DB::connection('mysql2')->select("select * from quartilinformation WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "'");
        $SectionInformation =
            DB::connection('mysql2')->select("select SectionName, SectionDescription, NeedValidation, COUNT(*) AS TotalQuestion, SectionID from questionsection AS a JOIN sectioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "' AND a.IsActive = '1' AND b.IsActive = '1' GROUP BY SectionID, SectionName, SectionDescription, NeedValidation ORDER BY b.`Order`");
        return view('Admin/EditQuartal', ['QuartilInformation' => $QuartilInformation[0], 'SectionInformation' => $SectionInformation]);
    }
    public function EditQuartilInformation(Request $request, $Year, $Quartil)
    {
        DB::connection('mysql2')->update("UPDATE `quartilinformation` SET QuartilTitle = '" . $request['QuartilTitle'] . "', QuartilDescription ='" . $request['QuartilDescription'] . "' WHERE Year = '" . $Year . "'AND Quartil = '" . $Quartil . "'");
        return redirect('/Admin/' . $Year . '/' . $Quartil . '/Edit');
    }
    public function EditSection($Year, $Quartil, $Section)
    {
        $SectionInformation =
            DB::connection('mysql2')->select("select * from sectioninformation WHERE ID = '" . $Section . "'");
        $QuestionInformation =
            DB::connection('mysql2')->select("select * from questionsection AS a JOIN questioninformation AS b ON a.SectionID = b.ID WHERE Year = '" . $Year . "' AND Quartil = '" . $Quartil . "' AND b.ID = '" . $Section . "' AND a.IsActive = '1' AND b.IsActive = '1' ORDER BY a.`Order`");
        return view('Admin/EditSection');
    }
    public function GetPrevData($Year, $Quartil, $Page)
    {
    }
    /**
     * Get a new CSRF token and ensure that the session is active for more than 2 hours
     */
    public function refreshToken(Request $request)
    {
    }
}