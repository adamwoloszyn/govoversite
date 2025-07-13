@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upload Files</h2>
        <form id="my-dropzone" class="dropzone">
            @csrf
            <div class="fallback">
                <input name="file" type="file" multiple />
            </div>
        </form>
        <div id="my-dropzone" class="dropzone">
            <div class="dz-message">
                <span>Drop files here or click to upload</span>
            </div>
            <div class="progress">
                <div id="my-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
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
                chunkSize: 1 * 1024 * 1024, // Set the chunk size (e.g., 10 MB per chunk)
                parallelChunkUploads: false, // Upload multiple chunks simultaneously
                maxFilesize: 5000,
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
