async function disLike() {
  const data = { user: 37 };
  const queryString = window.location.pathname;
  const response = await fetch(`/articles${queryString}/dislike`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });
  if (response.ok) {
    const likeIcon = document.getElementById("likeIcon");
    likeIcon.innerHTML = `<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>`;
  } else {
    console.error("Failed to perform like action.");
  }
}

async function like() {
  const data = { user: 37 };
  const queryString = window.location.pathname;
  const response = await fetch(`/articles${queryString}/like`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });
  if (response) {
    const dislikeIcon = document.getElementById("dislikeIcon");
    dislikeIcon.innerHTML = `<path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>`;
  } else {
    console.error("Failed to perform like action.");
  }
}

// function handleTranslateText(event, description) {
//   event.preventDefault(); 
//   console.log('Translate function called');
//   $.ajax({
//     url: `/articles/translate/article/${description}`,
//     method: "GET",
//     dataType: "json",
//     success: function (response) {
//       console.log(response);
//     },
//     error: function (xhr, status, error) {
//       console.error("Error:", error);
//     },
//   });
// }