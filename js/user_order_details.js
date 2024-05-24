// file upload
function handleFileUpload(orderId) {
    var fileInput = document.getElementById('fileUpload' + orderId);
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('order_id', orderId);

    fetch('/api/upload_proof_of_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // or response.json() if your server returns JSON
        } else {
            throw new Error('An error occurred while uploading the file.');
        }
    })
    .then(data => {
        alert('File uploaded successfully!');
    })
    .catch(error => {
        alert(error.message);
    });
}