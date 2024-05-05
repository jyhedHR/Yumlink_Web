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
function handleHrefClick(event, sortType) {
  event.preventDefault();
  let columnName = "title";
  sortArticles(sortType, columnName);
}

function sortArticles(sortType, columnName) {
  $.ajax({
    url: `/admin/sortArticles/${sortType}/${columnName}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      updateTable(response);
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
    },
  });
}

function updateTable(response) {
  let sortedArticles = response;
  let tableBody = "";
  sortedArticles.forEach((article, index) => {
    tableBody += `
      <tr>
        <th scope="row">${index + 1}</th>
        <td>${article.titleArticle}</td>
        <td>${article.descriptionArticle}</td>
        <td>${
          article.datePublished
            ? new Date(article.datePublished).toLocaleDateString()
            : ""
        }</td>
        <td>${article.imgArticle}</td>
        <td>
          <div class="btn-group-admin" role="group">
            <button type="button" class="btn btn-info">Update</button>
            <form id="deleteForm${
              article.idArticle
            }" method="post" action="{{ path('article_delete_admin', {'idArticle': article.idArticle}) }}">
              <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ article.idArticle) }}">
              <button type="button" onclick="confirmDelete('${
                article.idArticle
              }')" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    `;
  });
  // Replace the table body with the sorted articles
  $(".table tbody").html(tableBody);
}

$('.dropdown-item.hide-column').on('click', function(e) {
  e.preventDefault();
  $('.hideable-column').toggleClass('hidden');
});

function handleHide(event, columnName) {
  event.preventDefault();
  console.log(columnName);
  $("th[data-column-name='" + columnName + "']").hide();
  $("td[data-column-name='" + columnName + "']").hide();
}