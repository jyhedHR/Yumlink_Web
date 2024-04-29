function confirmDelete(idArticle) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Submit the form if confirmed
      document.getElementById("deleteForm" + idArticle).submit();
    }
  });
}

$(document).ready(function () {
  $(".sort-option").on("click", function (e) {
    e.preventDefault(); // Prevent default link behavior

    let sortType = $(this).data("sort-type");
    sortArticles(sortType);
  });

  function sortArticles(sortType) {
    $.ajax({
      url: "{{path('sort_articles')}}", // Symfony route for sorting
      method: "POST",
      data: { sortType: sortType },
      dataType: "json", // Specify that we expect JSON response
      success: function (response) {
        // Handle the sorted articles JSON response
        updateTable(response);
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  function updateTable(response) {
    let sortedArticles = JSON.parse(response);
    console.log(sortedArticles);
  }
});
