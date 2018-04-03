
// function  getDataURL() {
//   var dataURL = canvas.toDataURL();
// }

//  var streaming = false,
//           video        = document.querySelector('#video'),
//           cover        = document.querySelector('#cover'),
//           canvas       = document.querySelector('#canvas'),
//           photo        = document.querySelector('#photo'),
//           startbutton  = document.querySelector('#startbutton'),
//           width = 400,
//           height = 400;
    
//           var constraints = {
//             audio: false,
//             video: true
//         };

//     navigator.mediaDevices.getUserMedia(constraints)
//     .then(function (mediaStream) {
//         var video = document.querySelector('video');
//         video.srcObject = mediaStream;
//         video.onloadedmetadata = function (e) {
//             video.play();
//         };
//     })
//     .catch(function (err) {
//         console.log(err.name + ": " + err.message);
//     });

//       function takepicture() {
//           canvas.width = width;
//           canvas.height = height;
          
//           canvas.getContext('2d').drawImage(video, 0, 0, width, height);
//           data = canvas.toDataURL('image/png');
          
//           var myImg = new Image();
//           myImg.addEventListener('load', function() {
//             canvas.getContext('2d').drawImage(myImg, 0, 0, 200, 100);
//             dataR = canvas.toDataURL('image/png');
           
//           }, false);

//           var xhr = new XMLHttpRequest();
//           xhr.onreadystatechange = function() {
//           if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
//           {
//             // if (xhr.readyState == 1 && (xhr.status == 200 || xhr.status == 0)) {
//             // {
//                //alert(xhr.responseText);
//             }
//           }
//         }
//         xhr.open("POST", "save.php", false);
//         xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//         xhr.send('data='+ canvas.toDataURL('image/png'));

//         var pv = document.getElementById('pic_view');

//         // /*Creation of the contour of the image*/
//         var newCadrage = document.createElement('div');
//         newCadrage.className = 'cadrage';
//         newCadrage.id = xhr.responseText;
//         pv.insertBefore(newCadrage, pv.firstChild);

//         // /*Creation of image captured by the cam*/
//         var newImg = document.createElement('img');
//         newImg.className = 'moovable_image';
//         newImg.src  = xhr.responseText;
//         newImg.title = xhr.responseText;


//         /*Creation of the contour of the image deleting*/
//         var newDelete = document.createElement('div');
//         newDelete.className = 'delete';
//         newDelete.id = 'delete';

//         /*Creation of image X (cross)*/
//         var ImgDel = document.createElement('img');
//         ImgDel.id= "cross";
//         ImgDel.src  = 'img/x2.png';

//         // /*Creation of the link on the cross*/
//         var newLink = document.createElement('a');
//         newLink.id = 'delpost_link';
//         newLink.href = 'script/delete_post.php?post_url='+ xhr.responseText + '&b=1';
//         newLink.title = xhr.responseText;

//         newCadrage.appendChild(newImg);
//         newCadrage.appendChild(newDelete);
//         newDelete.appendChild(newLink);
//         newLink.appendChild(ImgDel);
//         };


//       startbutton.addEventListener('click', function(ev){
//           takepicture();
//         ev.preventDefault();
//         getDataURL();
//       }, false);

//       function upload(){
//         var xhr = new XMLHttpRequest();
//         xhr.onreadystatechange = function() {
//         if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
//             alert(xhr.responseText);
//         }
//       }
//       xhr.open("POST", "upload.php", false);
//       xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//       xhr.send('data='+ canvas.toDataURL('image/png'));
//       }


      function saveComment(idp){

        var login;
        
        var form_com = document.getElementById("form_com");
        var text = document.getElementById("valuecom").value;
        
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
        {
          //if (xhr.readyState == 1 && (xhr.status == 200 || xhr.status == 0)) {
           //{
             //alert(idp);
             //alert(xhr.responseText);
             login = xhr.responseText;
        }
        }
      }
      xhr.open("POST", "comment.php", false);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("text="+text+"&idp="+idp);


      var texts = document.getElementById('texts');

      var newCom = document.createElement('div');
      newCom.className = 'one_com';

      var author = document.createElement('div');
      author.className = 'author';

      var nameLog = document.createElement('div');
      nameLog.className = 'namelog';
      nameLog.innerHTML = login;

      var mess = document.createElement('div');
      mess.className = 'mess';

      var writing = document.createElement('div');
      writing.id = 'writing';
      writing.innerHTML = text;

      mess.appendChild(writing);
      author.appendChild(nameLog);
      newCom.appendChild(author);
      newCom.appendChild(mess);
      texts.appendChild(newCom);
};

