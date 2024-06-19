function showToast(title, message, type) {

  document.querySelector('#overlay').style.visibility = 'visible';

  document.querySelector('#title').innerHTML = title;
  document.querySelector('#textMessage').innerHTML = message;

  let toast = document.querySelector('#toast');
  let progress = document.querySelector('.progress');

  toast.classList.add('active');
  progress.classList.add('active');

  toast.classList.add(type);

  setTimeout(() => {
      toast.classList.remove('active', type);
  }, 5000)

  setTimeout(() => {
      progress.classList.remove('active');
      document.querySelector('#overlay').style.visibility = 'hidden';
  }, 5300);

}


function closeToast() {

  let toast = document.querySelector('#toast');
  let progress = document.querySelector('.progress');

  toast.classList.remove('active', 'success', 'error');

  setTimeout(() => {
      progress.classList.remove('active');
  }, 300)

  document.querySelector('#overlay').style.visibility = 'hidden';

}