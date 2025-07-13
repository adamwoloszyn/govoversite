<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use App\Helpers\Unique_FileName;
use Illuminate\Support\Facades\Log;
use Exception;

use Illuminate\Support\Facades\Storage;

use App\Models\Videos;
use App\Models\VideoCategories;
use App\Models\VideoProcessingStates;
use App\Models\AgendaItemType;

use App\Http\Controllers\Helpers\ViewRouter;
use App\Models\Video_AgendaItemTimeStamps;



use Illuminate\Support\Str;
use Aws\S3\S3Client;

class VideoProcessingController extends Controller
{
    public function addVideo()
    {
        $vr = new ViewRouter();

        //info("hit");
        //\Debugbar::addException(new Exception("test"));

        //dd($vr->determinePageView("index"));

        $allVideoCategories = VideoCategories::GetAllEnabledForAdmin();


        // Info("home->index");

        return view( 'VideoProcessing.add' )->with( 'videoCategories', $allVideoCategories);
    }
    public function dropZone()
    {
        $vr = new ViewRouter();

        //info("hit");
        //\Debugbar::addException(new Exception("test"));

        //dd($vr->determinePageView("index"));

        $allVideoCategories = VideoCategories::GetAllEnabledForAdmin();


        // Info("home->index");

        return view( 'VideoProcessing.dropzone' )->with( 'videoCategories', $allVideoCategories);
    }

    public function upload(Request $request)
    {
        // Retrieve the uploaded file chunk
        // Retrieve the uploaded file chunk
        $file = $request->file('file');

        // Retrieve the chunk index and total number of chunks
        $chunkIndex = $request->input('dzchunkindex');
        $totalChunks = $request->input('dztotalchunkcount');

        // Generate a unique identifier for the upload session
        //$uploadSessionId = Str::uuid();
        $uploadSessionId = "adam";

        // Store the file chunk in temporary storage
        $chunkPath = storage_path('app/temp/' . $uploadSessionId . '/' . $file->getClientOriginalName() . '.' . $chunkIndex);
        $file->move(storage_path('app/temp/' . $uploadSessionId), $file->getClientOriginalName() . '.' . $chunkIndex);

        // Check if all chunks have been uploaded
        if ($this->allChunksUploaded($uploadSessionId, $file, $totalChunks)) {

            // Reassemble the chunks into a complete file
            $completeFilePath = $this->reassembleChunks($uploadSessionId, $file, $totalChunks);
    
            // Upload the complete file to AWS S3
            //$s3Path = $this->uploadToS3($completeFilePath);
    
            // Perform any additional processing or storage operations with the complete file
    
            
            // Delete the temporary file chunks
            $this->deleteChunks($uploadSessionId);

            return response()->json([
                'status' => 'success',
                'message' => 'File uploaded successfully.',
                'completed file' => $completeFilePath,
            ]);
        }else{
            return response()->json([
                'status' => 'still uploading: ' . $chunkIndex . " out of " . $totalChunks,
            ]);
        }
    }

    private function allChunksUploaded($uploadSessionId, $file, $totalChunks)
    {
        for ($chunkNumber = 0; $chunkNumber < $totalChunks; $chunkNumber++) {
            $chunkFilePath = storage_path('app/temp/' . $uploadSessionId . '/' . $file->getClientOriginalName() . '.' . $chunkNumber);
           
            if (!file_exists($chunkFilePath)) {
                //echo("file does not exist \n");
                return false;
            }else{
                //echo("file does  exist \n");
            }
        }
        return true;
    }

    private function reassembleChunks($uploadSessionId, $file, $totalChunks)
    {
        $completeFilePath = storage_path('app/temp/' . $uploadSessionId . '/' . $file->getClientOriginalName());

        $completeFile = fopen($completeFilePath, 'ab');

        for ($chunkNumber = 0; $chunkNumber < $totalChunks; $chunkNumber++) {
            $chunkFilePath = storage_path('app/temp/' . $uploadSessionId . '/' . $file->getClientOriginalName() . '.' . $chunkNumber);

            $chunkFile = fopen($chunkFilePath, 'rb');
            stream_copy_to_stream($chunkFile, $completeFile);
            fclose($chunkFile);
        }

        fclose($completeFile);

        return $completeFilePath;
    }

    private function uploadToS3($filePath)
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        // extract file name and extension
        $pathInfo = pathinfo($filePath);
        $fileName = $pathInfo['filename']; 
        $extension = $pathInfo['extension']; 
        $fileNameAndExtension = $fileName . "_DROPZONE." . $extension;

        // upload file to AWS
        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),                              
            'Key' => $fileNameAndExtension,                                 // what to name file in S3
            //'Body' => Storage::get( $currentVideo->videofilelocalpath ),    // local file
            'SourceFile' => $filePath,              // local file
            //'ACL' => 'public-read'
        ]);

        return $result['ObjectURL'];
    }

    private function deleteChunks($uploadSessionId)
    {
        $tempPath = storage_path('app/temp/' . $uploadSessionId);
        Storage::deleteDirectory($tempPath);
    }
    

    public function uploadNewVideo(Request $request)
    {
        //store()
               
        //asStore()
        //storePublicly()
        //move()
        //getClientOriginalName()
        //getClientMimeType()
        //guessClientExtension()
        //getSize() in Kbs
        //getError();
        //isValid()

        //dd($request);

        $request->validate(
            [
                'Title' => 'required',
                'Category' => 'required',
                'Speakers' => 'required',
                //'videoFile' => 'required|mimes:mp4|max:10050480',
                'WhenWasVideoCreated' => 'required|date_format:Y-m-d H:i:s',
            ]
        );

        try
        { 
            //$extension = $request->file('videoFile')->guessExtension();
            //$mimeType  = $request->file('videoFile')->getMimeType();
            //$clientVideoFileName  = $request->file('videoFile')->getClientOriginalName();

            //$newImageName = Unique_FileName::CreateUniqueFileName( "video", $request->file('videoFile')->guessExtension() );

            //$moveResults = $request->videoFile->move( public_path(('working/videos')), $newImageName);

            

            //throw new Exception("Sorry, you must be at least 18 years old to access this page.");

            //dd( $moveResults );

            $desiredCategory = VideoCategories::findOrFail($request->Category);
            $desiredVideoProcessingState = VideoProcessingStates::GetAdminUploadedVideo();

            $newVideoEntry = new Videos;
            $newVideoEntry->slug = $request->Slug;
            $newVideoEntry->title = $request->Title;
            $newVideoEntry->when_was_video_created = $request->WhenWasVideoCreated;
            //$newVideoEntry->description = $request->Description;
            //$newVideoEntry->agendaSummary = $request->AgendaSummary;
            $newVideoEntry->speakers = $request->Speakers;
            //$newVideoEntry->sound_cloud_url = $request->SoundCloudURL;
            $newVideoEntry->videofilelocalpath = $request->videoFile;//$moveResults->getRealPath();
            $newVideoEntry->VideoCategory()->associate($desiredCategory);
            $newVideoEntry->VideoProcessingState()->associate($desiredVideoProcessingState);
            $newVideoEntry->audit_log = "Video row created";

            if ($request->hasFile('Thumbnail')) {
                $image = $request->file('Thumbnail');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('images/' . $imageName);
        
                // Store the image file
                $image = Image::make($image)->resize(640, 360); // Resize the image if needed
                $image->save($imagePath);
                $currentVideo->thumbnail = 'images/' . $imageName;
            }

            $newVideoEntry->save();

            $newVideoEntry->UpdateAuditLog("Admin Uploaded Video locally");

            $test = Videos::findOrFail($newVideoEntry->id);
            //dd($test);
        }
        catch( Exception $e)
        {
            info($e);
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage() . " Please try again.  If problem continues, please consult system administrator."]);
        }

        //return redirect()->route('success');
        return redirect('Video');
    }   // end of uploadNewVideo()

    public function EditVideo(int $id)
    {
        $currentVideo = Videos::find($id);
        $transcript = "Not able to load transcript.";
        $fileSpec = 'working\\transcripts\\' . basename( $currentVideo->transcriptfilelocalpath );

        try{
            // TODO Need to pull down from AWS
            $filePath = public_path( $fileSpec );

            $transcript = file_get_contents($filePath);
        }
        catch(Exception $e)
        {
            Log::error("Not able to load transcript file named {$fileSpec}");
        }

        $allVideoCategories = VideoCategories::GetAllEnabledForAdmin();

        $videoCategories = VideoCategories::with('parent')->whereNotNull('parent_id')->get();

        //dd($currentVideo->AssocatedKeywords());

        //dd($currentVideo->AgendaItemTimeStamps());

        return view('VideoProcessing.edit')
            ->with('Video', $currentVideo)
            ->with('Transcript', $transcript)
            ->with('videoCategories', $allVideoCategories)
            ->with('AssociatedKeyWords', $currentVideo->AssocatedKeywords())
            ->with('AgendaTypes', AgendaItemType::GetAgendaList())
            ->with('VideoAgendaItems', $currentVideo->AgendaItemTimeStamps());
    }   // end of EditVideo()

    public function EditVideoUpdate(Request $request)
    {
        // update video 
        $videoID = $request->input('VideoID');
        $currentVideo = Videos::find($videoID);
        $currentVideo->Title = $request->input('Title');
        $currentVideo->slug = $request->input('Slug');
        $currentVideo->video_category_id = $request->input('Category');
        //$currentVideo->Description = $request->input('Description');
        //$currentVideo->agendaSummary = $request->input('AgendaSummary');
        $currentVideo->speakers = $request->input('Speakers');
        //$currentVideo->sound_cloud_url = $request->input('SoundCloudURL');
        $currentVideo->when_was_video_created = $request->input('WhenWasVideoCreated');

        if ($request->hasFile('Thumbnail')) {
            $image = $request->file('Thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('images/' . $imageName);
    
            // Store the image file
            $image = Image::make($image)->resize(640, 360); // Resize the image if needed
            $image->save($imagePath);
            $currentVideo->thumbnail = 'images/' . $imageName;
        }

        $currentVideo->save();

        // update associated keywords
        if ( array_key_exists( 'keywords', $_POST ))
        {
            $associatedKeywordIDs = $_POST['keywords'];
            $associateVideo_Keywords = $currentVideo->Video_Keywords();
            foreach( $associateVideo_Keywords as $associateVideo_Keyword)
            {
                if (!in_array( $associateVideo_Keyword->keyword_id, $associatedKeywordIDs))
                {
                    $associateVideo_Keyword->is_enabled = 0;
                    $associateVideo_Keyword->save();
                }
            }
        }

        // process agenda text items
        // time entry and a text entry
        $prevAgendaItemTimeStamps = $currentVideo->AgendaItemTimeStamps();
        foreach( $prevAgendaItemTimeStamps as $prevAgendaItemTimeStamp)
        {
            $prevAgendaItemTimeStamp->is_enabled = 0;
            $prevAgendaItemTimeStamp->save();
        }

        $i = 1;
        $baseTimeStampKey = 'AgendaItemTimeStamp_';
        $baseTextKey = 'AgendaItemText_';
        $workingTimeStampKey = $baseTimeStampKey . $i;
        $workingTextKey = $baseTextKey . $i;
        $timeStringPattern = '/^[0-9][0-9]:[0-5][0-9]:[0-5][0-9]$/i';

        while ( array_key_exists( $workingTimeStampKey, $request->input() ))
        {
            // Log::info( $i );
            // Log::info( $request->input($workingTimeStampKey) );
            // Log::info( $request->input($workingTextKey) );
            $newVideoAgendaEntry = new Video_AgendaItemTimeStamps();
            $newVideoAgendaEntry->video_jump_point = "00:00:00";

            try
            {
                $newVideoAgendaEntry->video_jump_point = $request->input($workingTimeStampKey);
                if (!preg_match($timeStringPattern, $newVideoAgendaEntry->video_jump_point))
                {
                    $newVideoAgendaEntry->video_jump_point = "00:00:00";
                }               
                $content = $request->input($workingTextKey);
                $newVideoAgendaEntry->comment = $this->filterUnusedAgendaItems($content);

                $newVideoAgendaEntry->video_id = $currentVideo->id;
                $newVideoAgendaEntry->save();
            }
            catch( Exception $e)
            {
                Log::error( $request->input($workingTimeStampKey) );
                Log::error( $request->input($workingTextKey) );
                Log::error($e);
            }

            // create next key
            $i = $i + 1;
            $workingTimeStampKey = $baseTimeStampKey . $i;
            $workingTextKey = $baseTextKey . $i;
        }
        // dd($request->input('AgendaItemTimeStamp_1'));       
        //dd($request->input());

        $VideoHasBeenPublished = VideoProcessingStates::GetPublished();
        $currentVideo->VideoProcessingState()->associate($VideoHasBeenPublished);
        $currentVideo->save();

        return redirect('Video');
    }   // end of EditVideoUpdate()

    public function filterUnusedAgendaItems($items){
        // Split the content into lines
        $lines = explode("<br>", $items);

                        
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
                "____________________"
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
}   // end of VideoProcessingController class

