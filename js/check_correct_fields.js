function surligne(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "";
}

function confirmPswd(champ)
{
    console.log(document.getElementById('pswd1').value);
    console.log(champ.value);
    if (champ.value != document.getElementById('pswd1').value)
    {
         surligne(champ, true);
         return false;
     }
     else
     {
        surligne(champ, false);
     }
}

function verifLogin(champ)
{
   if(champ.value.length < 3 || champ.value.length > 25)
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}

function verifMail(champ)
{
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   if(!regex.test(champ.value))
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}

function verifPswd(champ)
{
    if(champ.value.length < 5 || champ.value.length > 16)
    {
       surligne(champ, true);
       return false;
    }
    else
    {
       surligne(champ, false);
       return true;
    }
}


