window.addEventListener('load', function () {

  document.getElementById('logout-btn').addEventListener('click', () => {
    document.getElementById('logout-form').submit();
  });

  if (document.body.contains(document.getElementById('close-message'))) {
    document.getElementById('close-message').addEventListener('click', (elem) => {
      console.log(elem);
      elem.target.parentElement.style.display = "none";
    });
  }

  Array.from(document.getElementsByClassName('alert')).forEach((elem) => {
    elem.addEventListener('click', (evt) => {
      evt.preventDefault();
      if (confirm("Are you sure you want to " + elem.getAttribute('data-alert-text') + "?")) {
        elem.parentElement.submit();
      }
    });
  });
});
