function setupImagePreview(inputId, previewContainerId) {
  const fileInput = document.getElementById(inputId);
  const previewContainer = document.getElementById(previewContainerId);
  let filesArray = [];

  fileInput.addEventListener("change", (event) => {
    filesArray = Array.from(event.target.files);
    updatePreview();
  });

  function updatePreview() {
    previewContainer.innerHTML = ""; // Xóa preview cũ
    filesArray.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const col = document.createElement("div");
        col.classList.add("col-md-5", "mb-3", "position-relative");
        col.style.height = "200px";
        col.style.width = "200px";

        const img = document.createElement("img");
        img.src = e.target.result;
        img.classList.add("img-fluid");
        img.style.objectFit = "cover";
        img.style.height = "100%";

        const removeButton = document.createElement("button");
        removeButton.classList.add(
          "btn",
          "btn-danger",
          "btn-sm",
          "position-absolute",
          "top-0",
          "right-0",
          "m-2",
        );
        removeButton.textContent = "X";
        removeButton.onclick = () => {
          filesArray.splice(index, 1);
          updateFileInput();
          updatePreview();
        };

        col.appendChild(img);
        col.appendChild(removeButton);
        previewContainer.appendChild(col);
      };
      reader.readAsDataURL(file);
    });
  }

  function updateFileInput() {
    const dataTransfer = new DataTransfer();
    filesArray.forEach((file) => {
      dataTransfer.items.add(file);
    });
    fileInput.files = dataTransfer.files;
  }
}
