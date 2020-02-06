var interval = setInterval(setExpanders, 10);
setTimeout(setImages, 100);
setTimeout(setImages, 500);

/*** EDAN Search Section ***/

/**
 * upon click submit, build a url around user entered search term and redirect
 *
 * @param  {string} term user entered search term
 * @return {boolean}     false to prevent normal form operation
 */
function edan_search_redirect(term)
{
  var search = document.getElementById('edan-search-bar').value;
  search = search.trim();
  search = search.replace(/\s/g, '+');

  let url = new URL(window.location.href);
  let params = new URLSearchParams(url.search.slice(1));

  params = new URLSearchParams();
  params.set('edan_q', search);
  console.log(encodeURI(url.toString().split('?')[0] + '?' + params.toString()))

  window.location.replace(url.toString().split('?')[0] + '?' + params.toString());

  return false;
}

/*** EDAN Search Facets ***/
/**
 * Toggle display of facet filters based on link click
 * @param  {[string]} key name of facet
 */
function toggle_facet_view(key)
{
  //get facet filter list
  var element = document.getElementById('facet-' + key);
  var link = document.getElementById(key + '-link');
  console.log(link.textContent);

  //if display is "none", set to "block". Otherwise, set to "none".
  if(element.style.display === 'none')
  {
    element.style.display = 'block';
    link.textContent = link.textContent.replace('►', '▼');
  }
  else
  {
    element.style.display = 'none';
    link.textContent = link.textContent.replace('▼', '►');
  }
}

/**
 * upon click submit, build a url around user entered search term and redirect
 *
 * @param  {string} term user entered search term
 * @return {boolean}     false to prevent normal form operation
 */
function edan_search_redirect(term)
{
  var search = document.getElementById('edan-search-bar').value;
  search = search.trim();
  search = search.replace(/\s/g, '+');

  let url = new URL(window.location.href);
  let params = new URLSearchParams(url.search.slice(1));

  params = new URLSearchParams();
  params.set('edan_q', search);
  console.log(encodeURI(url.toString().split('?')[0] + '?' + params.toString()))

  window.location.replace(url.toString().split('?')[0] + '?' + params.toString());

  return false;
}

/*** EDAN Search Minimizer Code ***/

function setExpanders()
{
    //display or minimize all minimizable fields
    var fields = document.getElementsByClassName('edan-search-object-fields');

    for(i = 0; i < fields.length; i++)
    {
      if(fields[i].dataset.minimized == "yes")
      {
        fields[i].style.display = "none";
      }
    }

    if(fields.length > 0)
    {
      clearInterval(interval);
    }
}

/**
 * Hide or reveal non-mini elements.
 * @param  {[string]}  classname class name for non-mini classes
 */
function toggle_non_minis(classname)
{
  //get expander element for object
  var expander = document.getElementById(classname + '-expander');
  //get object element
  var li = document.getElementById(classname + '-container');

  var doExpand = (expander.textContent == 'Expand');

  //toggle text for expander between 'Expand' and 'Collapse'
  if(doExpand)
  {
    expander.textContent = "Collapse";

    //highlight object if expanded
    li.classList.add('edan-search-object-highlight');
  }
  else
  {
    expander.textContent = 'Expand';

    //remove highlight if collapsed
    li.classList.remove('edan-search-object-highlight');
  }

  //get fields elements
  var elements = li.getElementsByClassName('edan-search-object-fields');

  //iterate through elements and display if hidden. Hide if currently displayed.
  for(var i = 0; i < elements.length; i++)
  {
    if (doExpand)
    {
      elements[i].style.display = "block";
    }
    else
    {
      elements[i].style.display = "none";
    }
  }
}

/**
 * Toggle all minimizable fields on clicking "Expand All" link
 */
function toggle_all()
{
  //get expandall link element
  var expandall  = document.getElementById('edan-search-expandall');
  //get all object containers
  var containers = document.getElementsByClassName('edan-search-object-container');
  //get all expander links
  var expanders   = document.getElementsByClassName('edan-search-expander');
  //get all minimizable fields
  //
  var fields     = document.getElementsByClassName('edan-search-object-fields');

  //test whether or not to expand or collapse
  var doExpand = (expandall.textContent == 'Expand All');

  //if doExpand, change text to "Collapse All"
  if(doExpand)
  {
    expandall.textContent = 'Collapse All';
  }
  else
  {
    expandall.textContent = 'Expand All';
  }

  //highlight or remove highlighting from all containers
  for(var i = 0; i < containers.length; i++)
  {
    if(doExpand)
    {
      containers[i].classList.add('edan-search-object-highlight');
    }
    else
    {
      containers[i].classList.remove('edan-search-object-highlight');
    }
  }

  //set all expand links to collapse or vice-versa
  for(i = 0; i < expanders.length; i++)
  {
    if(doExpand)
    {
      expanders[i].textContent = 'Collapse';
    }
    else
    {
      expanders[i].textContent = 'Expand';
    }
  }

  //display or minimize all minimizable fields
  for(i = 0; i < fields.length; i++)
  {
    if(doExpand)
    {
      fields[i].style.display = "block";
    }
    else
    {
      fields[i].style.display = "none";
    }
  }
}

/*** EDAN Search Image Resource Manager Code ***/

function setImages()
{
  var images = document.getElementsByClassName('edan-search-object-view-image-media');

  for(i = 0; i < images.length; i++)
  {
    if(images[i].dataset.hidden == "yes")
    {
      images[i].style.display = "none";
    }
    else
    {
      images[i].style.display = "block";
    }

    var frame = document.getElementById("displayMediaIframe" + (i+1));
    var url = new URL(frame.src);
    var src = "https://ids.si.edu/ids/dynamic?id=";
    if(frame.src.includes("ids.si.edu"))
    {
      let params = new URLSearchParams(url.search.slice(1));
      src = src + params.get('id');
    }
    else
    {
      src = src + frame.src;
    }

    frame.src = src;
  }
}

function mediaNext()
{
  var images = document.getElementsByClassName('edan-search-object-view-image-media');
  var total = images.length;
  var index = document.getElementById("mediaIndex").innerHTML.split("/")[0];

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
  document.getElementById("mediaIndex").innerHTML = index + "/" + total;
}

function mediaPrevious()
{
  var images = document.getElementsByClassName('edan-search-object-view-image-media');
  var total = images.length;
  var index = document.getElementById("mediaIndex").innerHTML.split("/")[0];

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
  document.getElementById("mediaIndex").innerHTML = index + "/" + total;
}
