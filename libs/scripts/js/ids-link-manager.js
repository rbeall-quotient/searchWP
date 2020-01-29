function mediaNext()
{
  console.log("Media Next...");
  var total = document.getElementById("visualMediaCount").value;
  var index = document.getElementById("visualMediaIndex").value;

  if(index < total)
  {
    index++;
  }

  for(var i = 1; i <= total; i++)
  {
    var media = document.getElementById("displayMedia" + i);
    if(i != index)
    {
      media.style.display = "none";
    }
    else
    {
      media.style.display = "block";
    }
  }

  if(index > 1)
  {
    document.getElementById("mediaPrev").style.display = "block";
  }
  else
  {
    document.getElementById("mediaPrev").style.display = "none";
  }

  if(index < total)
  {
    document.getElementById("mediaNext").style.display = "block";
  }
  else
  {
    document.getElementById("mediaNext").style.display = "none";
  }

  document.getElementById("visualMediaIndex").value = index;
  document.getElementById("mediaIndex").innerHTML = index;
}

function mediaPrevious()
{
  console.log("Media Previous...");
  var total = document.getElementById("visualMediaCount").value;
  var index = document.getElementById("visualMediaIndex").value;

  if(index > 1)
  {
    index--;
  }

  for(var i = 1; i <= total; i++)
  {
    var media = document.getElementById("displayMedia" + i);
    if(i != index)
    {
      media.style.display = "none";
    }
    else
    {
      media.style.display = "block";
    }
  }

  if(index > 1)
  {
    document.getElementById("mediaPrev").style.display = "block";
  }
  else
  {
    document.getElementById("mediaPrev").style.display = "none";
  }

  if(index < total)
  {
    document.getElementById("mediaNext").style.display = "block";
  }
  else
  {
    document.getElementById("mediaNext").style.display = "none";
  }

  document.getElementById("visualMediaIndex").value = index; 
}
