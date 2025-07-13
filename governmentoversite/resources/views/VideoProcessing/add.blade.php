@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Upload a New Video</div>

                    <div class="card-body">
                        <small>
                            <form id="my-dropzone" class="dropzone" action="{{ route('videoUpload') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                @if ($errors->has('error'))
                                    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
                                @endif

                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        <label for="Title">
                                            Title
                                        </label>
                                    </div>
                                    <div class="col-md-10">
                                        <input name="Title" class="form-control" type="text" placeholder="Title of Video" value="{{ old('Title') }}Title"/>
                                        @error('Title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        <label for="WhenWasVideoCreated">
                                            Video Creation Date/Time
                                        </label>
                                    </div>
                                    <div class="col-md-10">
                                        <input name="WhenWasVideoCreated" class="form-control" type="text" placeholder="When Created" value="{{ old('When_was_Video_Created') }}{{ date('Y-m-d H:i:s') }}"/>
                                        @error('WhenWasVideoCreated')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>                                

                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        <label for="Slug">
                                            Slug
                                        </label>
                                    </div>
                                    <div class="col-md-10">
                                        <input name="Slug" class="form-control" type="text" placeholder="Slug of Video" value="{{ old('Slug') }}slug-of-video"/>
                                        @error('Slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        Category
                                    </div>
                                    <div class="col-md-10">
                                        <div class="dropdown">
                                            <select name="Category" class="form-control" >
                                                @foreach ($videoCategories as $aVideoCategory)
                                                    <option value="{{$aVideoCategory->id}}" {{ old('Category') == $aVideoCategory->id ? 'selected' : '' }}>{{$aVideoCategory->short_description}}</option>
                                                @endforeach
                                            </select>
                                            @error('Category')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>         
                                {{-- <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        Agenda Summary
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" rows="5" name="AgendaSummary" placeholder="Agenda Summary" >{{ old('AgendaSummary') }}Agenda Summary</textarea>
                                        @error('AgendaSummary')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        Speakers
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" rows="5" name="Speakers" placeholder="Speakers Involved ( Separated by commas )" >{{ old('Speakers') }}speaker 1, speaker 2</textarea>
                                        @error('Speakers')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        Video File:
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" id="videoFile" name="videoFile" type="file" value="{{ old('videoFile') }}"/>
                                        <!-- {{ old('videoFile') }} -->
                                        @error('videoFile')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                                <div id="my-dropzone" class="dropzone">
                                    <div class="dz-message">
                                        <span>Drop files here or click to upload</span>
                                    </div>
                                    <div class="progress">
                                        <div id="my-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>   
                                <input type="text" id="videoFile" name="videoFile" />

                                <div class="row mt-4 justify-content-center_">
                                    <div class="col-md-2">
                                        No thumbnail selected yet. Please upload:
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" type="file" name="thumbnail" id="thumbnail" /><br /><br />
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <input class="btn btn-success form-control" type="submit" value="Upload Video"/>
                                    </div>
                                </div>
                                
                            </form>     
                                          
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
            var myDropzone = new Dropzone("#my-dropzone", {
                url: "{{ route('upload') }}", // Specify the route for handling file uploads
                chunking: true, // Enable chunked uploads
                chunkSize: 5 * 1024 * 1024, // Set the chunk size (e.g., 20 MB per chunk)
                parallelChunkUploads: false, // Upload multiple chunks simultaneously
                maxFilesize: 10240,
                // Other options and callbacks as needed
            });

            // Update the progress bar based on the upload progress
            myDropzone.on("uploadprogress", function(file, progress, bytesSent) {
                console.log(progress);
                var progressBar = document.querySelector("#my-progress-bar");
                progressBar.style.width = progress + "%";
                progressBar.setAttribute("aria-valuenow", progress);
            });

            myDropzone.on("addedfile", function(file) {
                console.log("addedfile");
                // Remove success or error marks when a new file is added
                var previewsContainer = document.querySelector("#my-dropzone .dz-preview");
                if (previewsContainer) {
                    previewsContainer.classList.remove("dz-success", "dz-error");
                }
            });

            myDropzone.on("success", function(file, response) {
                // Add success mark when file upload is successful
                console.log("success");
                console.log(response);
                $("#videoFile").val(response['completed file']);
                var previewElement = file.previewElement;
                if (previewElement) {
                    previewElement.classList.add("dz-success");
                }

                // Handle the success response from the server
                if (response.status === 'success') {
                    // Display success message or perform further actions
                    console.log('File uploaded successfully:', response.file);
                } else {
                    // Handle error response if needed
                    console.error('File upload failed:', response.message);
                }
            });

            myDropzone.on("error", function(file) {
                console.log("error");
                // Add error mark when file upload fails
                var previewElement = file.previewElement;
                if (previewElement) {
                    previewElement.classList.add("dz-error");
                }
            });
        });
    </script>
@endsection
