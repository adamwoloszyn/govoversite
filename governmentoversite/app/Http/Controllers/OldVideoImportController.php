<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

use App\Models\Videos;
use App\Models\VideoCategories;
use App\Models\VideoProcessingStates;
use App\Models\AgendaItemType;
use App\Models\Video_AgendaItemTimeStamps;

use \DateTime;

class OldVideoImportController extends Controller
{
	public function query()
    {
		try {
			
	        DB::connection('secondary')->getPdo();
	        echo "Connection successful.";
	        
	        $meetingInformation = DB::connection('secondary')->select('SELECT * FROM "public"."Meeting" where "channelId" = 17 and "id" = 2874 order by date desc');

			$meetingId = "";
			$meetingCreatedAt = "";
			$meetingUpdatedAt = "";
			$meetingChannelId = "";
			$meetingPublishDate = "";
			$meetingState = "";
			$meetingNotes = "";
			
			
		    if (!empty($meetingInformation)) {
		        foreach ($meetingInformation as $meeting) {
					$meetingId = $meeting->id;
					$meetingCreatedAt = $meeting->createdAt;
					$meetingUpdatedAt = $meeting->updatedAt;
					$meetingChannelId = $meeting->channelId;
					
					$dateTime = new DateTime($meeting->date);
					$dateTime->setTime(0, 0, 0);
					$meetingPublishDate = $dateTime->format("Y-m-d H:i:s");

					$meetingState = $meeting->state;
					$meetingNotes = $meeting->notes;
				}
			}
			echo($meetingId . "<br />");
			echo($meetingCreatedAt . "<br />");
			echo($meetingUpdatedAt . "<br />");
			echo($meetingChannelId . "<br />");
			echo($meetingPublishDate . "<br />");
			echo($meetingState . "<br />");
			echo($meetingNotes . "<br />");
			echo("-----------");
		    
		    $meetingHightlights = DB::connection('secondary')->select('SELECT * FROM "public"."Highlight" where "meetingId" = 2874 order by time asc');
		    
		    $meetingHighlightsToStore = array();
		    if (!empty($meetingHightlights)) {
		        foreach ($meetingHightlights as $row) {
		            $meetingHighlightsToStore[] = array(
			            'id' => $row->id,
			            'meetingId' => $row->meetingId,
			            'part' => $row->part,
			            'time' => $row->time,
			            'description' => $row->description,
		            );
		        }
		    } else {
		        echo "No results found.";
		    }
		    
	    } catch (\Exception $e) {
	        echo "Connection failed: " . $e->getMessage();
	    }
    }
    
    public function create()
    {
        return view('videoimport.import');
    }
	public function createSlug($title)
	{
	    // Remove special characters and replace spaces with hyphens
	    $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
	    
	    // Trim leading and trailing hyphens
	    $slug = trim($slug, '-');
	    
	    return $slug;
	}
	public function formatTime($timeInSeconds)
	{
	    $hours = floor($timeInSeconds / 3600);
	    $minutes = floor(($timeInSeconds % 3600) / 60);
	    $seconds = $timeInSeconds % 60;
	
	    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
	}
    public function store(Request $request)
    {
	    $s3Bucket = env('AWS_BUCKET');
        
        $awsRegion = env('AWS_DEFAULT_REGION');
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // Assuming the first row contains headers

        foreach ($csv as $row) {
            // Access CSV data using header names (MeetingId, Meeting Name, VideoFilePath, WillTranscribe)
			
            $meetingId = $row['MeetingID'];
			$meetingDate = $row['Date'];
            $meetingName = $row['MeetingName'];
			$meetingCategoryParent = $row['CategoryParent'];
			$meetingCategoryChild = $row['CategoryChild'];
            $videoFilePath = $row['VideoFilePath'];
            $willTranscribe = $row['WillTranscribe'];

            if($willTranscribe == "Yes"){
                echo "Meeting ID: $meetingId | Meeting Name: $meetingName | Video File Path: $videoFilePath | Will Transcribe: $willTranscribe <br>";
                
                $meetingInformation = DB::connection('secondary')->select('SELECT * FROM "public"."Meeting" where "id" = '.$meetingId.' order by date desc');

				$meetingId = "";
				$meetingCreatedAt = "";
				$meetingUpdatedAt = "";
				$meetingChannelId = "";
				$meetingPublishDate = "";
				$meetingState = "";
				$meetingNotes = "";
				
			    if (!empty($meetingInformation)) {
			        foreach ($meetingInformation as $meeting) {
						$meetingId = $meeting->id;
						$meetingCreatedAt = $meeting->createdAt;
						$meetingUpdatedAt = $meeting->updatedAt;
						$meetingChannelId = $meeting->channelId;
						
						$dateTime = new DateTime($meeting->date);
						$dateTime->setTime(0, 0, 0);
						$meetingPublishDate = $dateTime->format("Y-m-d H:i:s");
	
						$meetingState = $meeting->state;
						$meetingNotes = $meeting->notes;
												
						$title = $meetingName;
		                $videoCreationDateTime = $meetingCreatedAt;
		                $slug = $this->createSlug($meetingName);
		                $category = "";
		                $agendaSummary = $meetingNotes;
		                
		                $speakers = "";
		                $videoS3Path = $videoFilePath;
		
		                $s3Path = $videoFilePath;
		
		                //need to download the file onto server, then let the process run.
		                //once we have videos that are in final status will have their videos deleted.                
		                $videoFilePathParts = explode('/', $videoFilePath);
		                $folder = $videoFilePathParts[0]; // "OldImports"
		                $filenameWithExtension = $videoFilePathParts[1]; // "OSM 5-8-23 .mp4"
		
		                $localFilePath = "/var/www/laravel/storage/app/temp/adam/" . $filenameWithExtension;
		                //$localFilePath = "/Volumes/Development/LDG/GovernmentOversite/governmentoversite/storage/app/temp/adam/" . $filenameWithExtension;
						
						$result = $this->processVideo($localFilePath, $s3Bucket, $s3Path, $awsRegion);		                
		                //$result = true;
		                if ($result) {
		                    echo("Success");
							$categoryId = 0;
							$parentCategory = VideoCategories::where('long_description', $meetingCategoryParent)->first();
							if ($parentCategory) {
								$desiredCategory = VideoCategories::where('long_description', $meetingCategoryChild)
									->where('parent_id', $parentCategory->id)
									->firstOrFail();
									$categoryId = $desiredCategory->id;
							}

		                    $desiredCategory = VideoCategories::findOrFail($categoryId);
		                    $desiredVideoProcessingState = VideoProcessingStates::GetAdminUploadedVideo();
		
		                    //if uploaded successfully, create the video entry. It will go through the process.
		                    $newVideoEntry = new Videos;
		                    $newVideoEntry->slug = $slug;
		                    $newVideoEntry->title = $title;
		                    $newVideoEntry->when_was_video_created = $videoCreationDateTime;
		                    $newVideoEntry->agendaSummary = nl2br($agendaSummary);
		                    $newVideoEntry->speakers = $speakers;
		                    $newVideoEntry->videofilelocalpath = $localFilePath;
		                    $newVideoEntry->VideoCategory()->associate($desiredCategory);
		                    $newVideoEntry->VideoProcessingState()->associate($desiredVideoProcessingState);
		                    $newVideoEntry->audit_log = "Video row created";
		
		                    $newVideoEntry->save();
		                    $newVideoEntry->UpdateAuditLog("Admin Uploaded Video locally");
		                    
		                    $meetingHightlights = DB::connection('secondary')->select('SELECT * FROM "public"."Highlight" where "meetingId" = '.$meetingId.' order by time asc');
		    
						    $meetingHighlightsToStore = array();
						    if (!empty($meetingHightlights)) {
						        foreach ($meetingHightlights as $row) {
						            $meetingHighlightsToStore[] = array(
							            'id' => $row->id,
							            'meetingId' => $row->meetingId,
							            'part' => $row->part,
							            'time' => $row->time,
							            'description' => $row->description,
						            );
						            
						            $newVideoAgendaEntry = new Video_AgendaItemTimeStamps();
		            
				                    $newVideoAgendaEntry->video_jump_point = $this->formatTime($row->time);
				                    $newVideoAgendaEntry->comment = nl2br($row->description);
									//$newVideoAgendaEntry->comment = $this->filterUnusedAgendaItems(nl2br($row->description));
				
				                    $newVideoAgendaEntry->video_id = $newVideoEntry->id;
				                    $newVideoAgendaEntry->save();
				                    
						        }
						    } else {
						        echo "No results found.";
						    }
		
							
		                    $VideoHasBeenPublished = VideoProcessingStates::GetAdminUploadedVideo();
		                    $newVideoEntry->VideoProcessingState()->associate($VideoHasBeenPublished);
		                    $newVideoEntry->save();

							//delete temp file
							// $command = "rm -v " . $localFilePath;
							// echo($command);
							// exec($command, $output, $return_var);

						} else {
							// Error
							//Log::Info("failureeee: ");
		                    echo("failureeee: " . $result);

						}   
						
			        }
			    } else {
			        echo "No results found.";
			    }
                             
            }
            
        }

        //return redirect()->route('old-video-import.create')->with('success', 'CSV file imported successfully.');
    }
	public function processVideo($localFilePath, $s3Bucket, $s3Path, $awsRegion)
	{
		$command = "aws s3 cp s3://{$s3Bucket}/{$s3Path} {$localFilePath} --region {$awsRegion}";
		echo($command);
		exec($command, $output, $return_var);
		if($return_var === 0){
			return true;
		}else{
			return false;
		}
	}
	public function filterUnusedAgendaItems($items){
        // Split the content into lines
        $lines = explode("<br />", $items);

                        
        // Filter out lines containing "SYNTAX: xx"
        $filteredLines = array_filter($lines, function ($line) {
            $patterns = [
			    "COMMISSIONER: xx VOTE: xx",
			    "REPRESENTATIVE: xx VOTE: xx",
			    "SESSION #6: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx",
			    "REPORTER: xx",
			    "MOTION DETAILS: (EXIT) Non public session:",
			    "DISCUSSION: xx",
			    "SESSION #2: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx",
			    "SYNTAX: xx",
			    "MAP: xx",
                "**NOTE:** xx",
			    "REPORT:",
			    "SECOND: xx",
			    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx",
			    "AMOUNT: xx",
			    "ACCOUNTS PAYABLE: xx",
			    "MOTION:",
			    "DATE: xx",
			    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx",
			    "TIME OUT: xx:xx",
			    "TIME IN: xx::xx",
			    "YES: xx NO: xx",
			    "MOTION DETAILS: Seal",
			    "REPORT DETAILS: No vote to seal",
			    "REPORT DETAILS: No vote to seal",
			    "TIME IN: xx:xx",
			    "TIME OUT: xx:xx",
			    "SELECTMEN: xx VOTE: xx",
			    "DATA LINK: xx",
			    "CEMETARY: xx",
			    "SELECTMEN: xx VOTE: xx",
			    "MOTIONER: xx",
			    "AGENDA ITEM: xx",
			    "TAX STATUS: xx",
			    "SESSION #5: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx",
			    "REQUEST# 202x DEPARTMENT: xx AMOUNT: xx",
			    "SESSION #1: xx/xx/xx RSA 91-A:3 II (xx) STATUS: xx",
			    "REPRESENTATIVE: xx VOTE: xx",
			    "LOT: xx",
			    "REPRESENTATIVE: xx VOTE: xx",
			    "AMENDED: xx",
			    "REPRESENTATIVE: xx VOTE: xx",
			    "COMMISSIONER: xx VOTE: xx",
			    "REPRESENTATIVE: xx VOTE: xx",
			    "AGENDA DETAILS: xx",
			    "AGENDA ITEM: xx",
			    "MOTION DETAILS: (ENTER INTO) Non public session:",
			    "SELECTMEN: xx VOTE: xx",
			    "SYNOPSIS: xx",
			    "REPORT DETAILS: No vote to seal",
			    "COMMISSIONER: xx VOTE: xx",
			    "DISCUSSION: xx",
			    "YES: xx NO: xx",
                "____________________",
				"**NOTICE TYPE:** xx",
				"**ACCOUNTS PAYABLE MANIFEST:",
				"** #1: **DATE:** xx \$xx",
				"**ACCOUNTS PAYABLE MANIFEST:** #2: **DATE:** xx \$xx",
				"**ACCOUNTS PAYABLE MANIFEST:** #3: **DATE:** xx \$xx",
				"**PAYROLL MANIFEST:** Date: xx \$xx",
				"**PAYROLL MANIFEST:** Date: xx \$xx",
				"**PAYROLL MANIFEST:** Date: xx \$xx",
				"**CASH USED TO DATE:** \$xx",
				"**NAME:** xx",
				"**ADDRESS:** xx",
				"**LAND OWNER:** xx",
				"**MAP:** xx",
				"**LOT:** xx",
				"**SUB:** xx",
				"**ACCESS:** xx",
				"**CEMETERY:** xx",
				"**ACREAGE/CUT:** xx",
				"**TOTAL ACREAGE:** xx",
				"**TAX STATUS:** xx",
				"**DETAIL:** xx",
				"**PERMIT TYPE:** xx",
				"**DATE:** xx",
				"**ADDRESS:** xx",
				"**MAP:** xx",
				"**LOT:** xx",
				"**SUB:** xx",
				"**ACCESS ROAD:** xx",
				"**ACREAGE:** xx",
				"**TAXES:** xx",
				"**CEMETERY:** xx",
				"**AMOUNT:** \$xx",
				"**DISCUSSION:** xx",
				"**SYNTAX:** xx",
				"
				____________________"
			];

            foreach ($patterns as $pattern) {
                if (strpos($line, $pattern) !== false) {
                    return false;
                }
            }

            return true;
        });
        // Join the filtered lines back into a single string
        $result = implode("<br>", $filteredLines);
        return $result;
    }
}