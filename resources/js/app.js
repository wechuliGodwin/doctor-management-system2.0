import './bootstrap';
import Alpine from 'alpinejs';
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';

window.Alpine = Alpine;
window.Dropzone = Dropzone;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  Dropzone.autoDiscover = true;

  const myDropzone = new Dropzone("#dropzone-area", {
    url: "{{ route('handle-dropzone-upload') }}",
    paramName: "file",
    maxFilesize: 300, // in MB
    acceptedFiles: ".ppt,.pptx,.doc,.docx,.pdf,.jpg,.jpeg,.png,.gif,.bmp,.tiff",
    addRemoveLinks: true,
    autoProcessQueue: false, // Changed back to false as per original intent
    uploadMultiple: false,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    dictDefaultMessage: 'Drop your research poster here or click to upload',
    dictInvalidFileType: 'You can\'t upload files of this type',
    dictFileTooBig: 'File is too big. Max filesize: 300MiB.',
    init: function() {
      var myDropzone = this;

      // Disable submit button initially
      document.getElementById('submitBtn').disabled = true;

      // Event handler for when a file is added to Dropzone
      this.on("addedfile", function(file) {
        let progressBar = document.createElement("div");
        progressBar.className = "dz-progress";
        progressBar.innerHTML = `
          <span class="dz-upload" data-dz-uploadprogress></span>
          <span class="dz-success-mark">
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <circle cx="27" cy="27" r="25" stroke-width="2" stroke="#659f13" fill="none"></circle>
              <path d="M15.774,29.274 L23.274,36.774 L38.274,21.774" stroke-width="2" stroke="#659f13" fill="none"></path>
            </svg>
          </span>
        `;
        file.previewElement.appendChild(progressBar);

        // Enable submit button if file is added
        document.getElementById('submitBtn').disabled = false;
      });

      // Event handler for file upload progress
      this.on("uploadprogress", function(file, progress) {
        if (file.previewElement) {
          let progressBar = file.previewElement.querySelector('[data-dz-uploadprogress]');
          if (progressBar) {
            progressBar.style.width = progress + "%";
            progressBar.textContent = Math.round(progress) + "%";
          }
        }
      });

      // Event handler for when upload is complete
      this.on("complete", function(file) {
        if (file.status === "success") {
          let successMark = file.previewElement.querySelector('.dz-success-mark');
          if (successMark) successMark.style.visibility = 'visible';
        }
      });

      // Event handler for successful upload
      this.on("success", function(file, response) {
        document.getElementById('poster_path').value = response.path;
        console.log('File uploaded successfully:', response.path);
        document.getElementById('loader').classList.add('hidden');
        document.getElementById('submitBtn').disabled = false; // Enable submit button
      });

      // Event handler for upload errors
      this.on("error", function(file, errorMessage) {
        console.error('Error uploading file:', errorMessage);
        let message = errorMessage;
        if (typeof errorMessage === 'object') {
          message = errorMessage.message || 'An unexpected error occurred.';
          if (errorMessage.errors && errorMessage.errors.file) { // Laravel validation errors
            message = errorMessage.errors.file.join('<br>'); // Display multiple errors
          }
        }
        alert('An error occurred while uploading the file: ' + message);
        document.getElementById('loader').classList.add('hidden');
        document.getElementById('submitBtn').disabled = false; // Re-enable submit button in case of error
        this.removeFile(file); // Remove the errored file from dropzone
      });
    }
  });

  // Event handler for form submission
  document.getElementById('submitBtn').addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('loader').classList.remove('hidden');
    if (myDropzone.files.length > 0) {
      myDropzone.processQueue();
    } else {
      alert("Please select a file to upload.");
      document.getElementById('loader').classList.add('hidden');
      return;
    }
  });

  // Trigger file selection when clicking the dropzone area
  document.getElementById('dropzone-area').addEventListener('click', function(e) {
    if (!e.target.closest('.dz-remove')) { // Use closest for better handling of nested elements
      e.preventDefault();
      e.stopPropagation();
      myDropzone.hiddenFileInput.click();
    }
  });
});