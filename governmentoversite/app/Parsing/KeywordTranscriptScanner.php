<?php
    namespace App\Parsing;

    use Illuminate\Support\Facades\Log;

    use App\Models\Keywords;
    use App\Models\Videos;
    use App\Models\Video_Keywords;
    use App\Http\Controllers\CRONJobBaseController;
    use Aws\ElasticBeanstalk\Exception\ElasticBeanstalkException;
    use Exception;

    use function PHPUnit\Framework\returnValue;

    class KeywordTranscriptScanner {
        public function ProcessFile($videoID, $localFileToProcess)
        {
            $loggingController = new CRONJobBaseController();

            $loggingController->DebugOutput("Keyword Process $localFileToProcess");
            $returnValue = [];

            $keywordList = Keywords::GetAllEnabledKeywords(-1);
            $keywordListString = "Keywords: ";
            foreach ($keywordList as $keywordListEntry) {
                $keywordListString = $keywordListString . ( strlen($keywordListString) > 10 ? ", " : "" ) . $keywordListEntry->keyword;
            }
            $loggingController->DebugOutput($keywordListString);

            // Open the file in read-only mode
            $file = fopen($localFileToProcess, 'r');

            if ($file) {
                $lineNumber = 1;
                $validWordTerminators = "\"';:,. \n";

                $currentTimeOffset = '00:00:00';

                // Read and process each line until the end of the file
                while (($line = fgets($file)) !== false) {
                    // Process the line
                    $loggingController->DebugOutput($lineNumber . " " . $line );

                    $pattern = '/\[(\d{2}:\d{2}:\d{2})\]/';
                    if (preg_match($pattern, $line, $matches)) {
                        $currentTimeOffset = $matches[1];
                        //echo $timeValue;  // Outputs: 00:00:00
                    }

                    foreach ($keywordList as $keywordListEntry) {
                        $offset = 0;

                        while ( ( $position = strpos( strtolower($line), strtolower($keywordListEntry->keyword), $offset ) ) !== false) {
                            $addKeyword = true;

                            // ensure the found word isn't an embedded word
                            if ( ( $position + strlen($keywordListEntry->keyword ) ) < strlen($line) )
                            {
                                // ensure found subphrase is followed by  ' . , are okay
                                //Log::info( $line[$position + strlen($keywordListEntry->keyword)] );
                                $addKeyword = 
                                    // doesn't have a character following it
                                    str_contains( $validWordTerminators, $line[$position + strlen($keywordListEntry->keyword)] )
                                        &&
                                    // doesn't have a character in front of it
                                    str_contains( $validWordTerminators, $line[$position - 1] )
                                ;
                            }

                            if ($addKeyword)
                            {
                                $loggingController->DebugOutput( "Found '$keywordListEntry->keyword' at position $position in line $lineNumber." );
                                $newEntry = new Video_Keywords();
                                $newEntry->video_id = $videoID;
                                $newEntry->keyword_id = $keywordListEntry->id;
                                $newEntry->lineNumber = $lineNumber;
                                $newEntry->position = $position;
                                $newEntry->position_in_video = $currentTimeOffset;

                                // Add to return array
                                $returnValue[] = $newEntry;
                            }

                            $offset = $position + strlen($keywordListEntry->keyword);
                        }
                    }

                    $lineNumber++;
                    // You can perform additional operations on each line here
                }

                // Close the file
                fclose($file);
            } else {
                // Failed to open the file
                log::error( 'Cannot open ' . $localFileToProcess );
                throw new Exception( "Cannot open " . $localFileToProcess );
            }

            return $returnValue;
        }   // end of ProcessFile()
    }   // end of KeywordTranscriptScanner class
?>