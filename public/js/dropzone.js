Dropzone.autoDiscover = false;  // Disable auto initialization

// Initialize Dropzone on the form with the ID 'new-postForm'
Dropzone.options.newPostForm = {
    paramName: "file",  // Name of the file parameter that will be sent to the server
    maxFilesize: 5,     // Maximum file size in megabytes
    addRemoveLinks: true, // Show remove links on uploaded files
    dictDefaultMessage: "Drop files here to upload", // Default message shown by Dropzone
    // Add more options or event listeners as needed
};