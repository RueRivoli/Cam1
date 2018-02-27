
function  getDataURL() {
  var dataURL = canvas.toDataURL();
}

 var streaming = false,
          video        = document.querySelector('#video'),
          cover        = document.querySelector('#cover'),
          canvas       = document.querySelector('#canvas'),
          photo        = document.querySelector('#photo'),
          startbutton  = document.querySelector('#startbutton'),
          width = 400,
          height = 400;
    
          var constraints = {
            audio: false,
            video: true
        };

    navigator.mediaDevices.getUserMedia(constraints)
    .then(function (mediaStream) {
        var video = document.querySelector('video');
        video.srcObject = mediaStream;
        video.onloadedmetadata = function (e) {
            video.play();
        };
    })
    .catch(function (err) {
        console.log(err.name + ": " + err.message);
    });


      video.addEventListener('canplay', function(ev){
        if (!streaming) {
          height = video.videoHeight / (video.videoWidth/width);
          video.setAttribute('width', width);
          video.setAttribute('height', height);
          canvas.setAttribute('width', width);
          canvas.setAttribute('height', height);
          streaming = true;
        }
      }, false);
     
      function takepicture() {
          canvas.width = width;
          canvas.height = height;
          
          
          canvas.getContext('2d').drawImage(video, 0, 0, width, height);
          data = canvas.toDataURL('image/png');
          
          var myImg = new Image();
          // myImg.src = 'img/lion.png';
          myImg.addEventListener('load', function() {
            canvas.getContext('2d').drawImage(myImg, 0, 0, 200, 100);
            dataR = canvas.toDataURL('image/png');
           
          }, false);

          /*if (document.getElementById('filter1').checked)
            filter = "dolphin";*/
            
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
              alert(xhr.responseText);
          }
        }
        xhr.open("POST", "save.php", false);
        
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('data='+ canvas.toDataURL('image/png'));

        /*Ajout de l'image dans la rubrique*/
        // var pv = document.getElementById('pic_view');
        // var newCadrage = document.createElement('div');
        // newCadrage.className = 'cadrage';
        // newCadrage.id = xhr.responseText;
        // pv.insertBefore(newCadrage, pv.firstChild);
        // var newImg = document.createElement('img');
        // newImg.className = 'moovable_image';
        // newImg.src  = xhr.responseText;
        // //newImg.src  = canvas.toDataURL('image/png');
        // //newImg.src = data;
        // newImg.title = xhr.responseText;
        // document.getElementById(xhr.responseText).appendChild(newImg);



        var pv = document.getElementById('pic_view');

        // /*Creation of the contour of the image*/
        var newCadrage = document.createElement('div');
        newCadrage.className = 'cadrage';
        newCadrage.id = xhr.responseText;
        pv.insertBefore(newCadrage, pv.firstChild);

        // /*Creation of image captured by the cam*/
        var newImg = document.createElement('img');
        newImg.className = 'moovable_image';
        newImg.src  = xhr.responseText;
        newImg.title = xhr.responseText;


        /*Creation of the contour of the image deleting*/
        var newDelete = document.createElement('div');
        newDelete.className = 'delete';
        newDelete.id = 'delete';

        /*Creation of image X (cross)*/
        var ImgDel = document.createElement('img');
        ImgDel.id= "cross";
        ImgDel.src  = 'img/x2.png';

        // /*Creation of the link on the cross*/
        var newLink = document.createElement('a');
        newLink.id = 'delpost_link';
        newLink.href = 'script/delete_post.php?post_id='+ xhr.responseText + '&b=1';
        newLink.title = xhr.responseText;

        newCadrage.appendChild(newImg);
        newCadrage.appendChild(newDelete);
        newDelete.appendChild(newLink);
        newLink.appendChild(ImgDel);
        };


      startbutton.addEventListener('click', function(ev){
          takepicture();
        ev.preventDefault();
        getDataURL();
      }, false);

      function upload(){
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            alert(xhr.responseText);
        }
      }
      xhr.open("POST", "upload.php", false);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send('data='+ canvas.toDataURL('image/png'));
      }

